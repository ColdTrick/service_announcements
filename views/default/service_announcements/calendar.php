<?php

$start = strtotime(get_input('start')) ?: time();
$end = strtotime(get_input('end')) ?: time();

$dbprefix = elgg_get_config('dbprefix');
$startdate_name_id = elgg_get_metastring_id('startdate');
$enddate_name_id = elgg_get_metastring_id('enddate');

$options = [
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'limit' => false,
	'joins' => [
		"JOIN {$dbprefix}metadata mdstart ON e.guid = mdstart.entity_guid",
		"JOIN {$dbprefix}metastrings msvstart ON mdstart.value_id = msvstart.id",
		"JOIN {$dbprefix}metadata mdend ON e.guid = mdend.entity_guid",
		"JOIN {$dbprefix}metastrings msvend ON mdend.value_id = msvend.id",
	],
	'wheres' => [
		"(
			mdstart.name_id = {$startdate_name_id}
			AND
			mdend.name_id = {$enddate_name_id}
			AND (
				(
					CAST(msvend.string AS SIGNED) > {$start}
					AND
					CAST(msvend.string AS SIGNED) < {$end}
				) OR (
					CAST(msvstart.string AS SIGNED) > {$start}
					AND
					CAST(msvstart.string AS SIGNED) < {$end}
				) OR (
					CAST(msvstart.string AS SIGNED) < {$start}
					AND
					(
						CAST(msvend.string AS SIGNED) = 0
						OR
						CAST(msvend.string AS SIGNED) > {$end}
					)
				)
			)
		)",
	],
];

$entities = elgg_get_entities_from_metadata($options);

$result = [];

foreach ($entities as $entity) {
	$classes = [
		'service-announcements-announcement-type',
		"service-announcements-announcement-type-{$entity->announcement_type}",
	];
	if ($entity->isFinished()) {
		$classes[] = 'service-announcements-announcement-finished';
	}
	
	$multiday = $entity->isMultiDay();
	$endts = $entity->getEndTimestamp();
	if (empty($endts)) {
		$endts = time();
	}
	
	if ($multiday) {
		$endts = strtotime('midnight +1day', $endts);
	}
	
	$result[] = [
		'title' => $entity->getDisplayName(),
		'start' => $entity->getStartDate(),
		'end' => date('c', $endts),
		'allDay' => $multiday,
		'url' => $entity->getURL(),
		'className' => implode(' ', $classes),
	];
}

header('Content-type: application/json');

echo json_encode($result);
