<?php

$events_options = [
	'limit' => false,
	'past_events' => true,
];

$start = get_input('start');
$end = get_input('end');

$options = [
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'metadata_name_value_pairs' => [],
];

if ($start) {
	$options['metadata_name_value_pairs'][] = [
		'name' => 'startdate',
		'value' => strtotime($start),
		'operand' => '>=',
	];
}
if ($end) {
	$options['metadata_name_value_pairs'][] = [
		'name' => 'enddate',
		'value' => strtotime($end),
		'operand' => '<=',
	];
}

$entities = elgg_get_entities_from_metadata($options);
	
// 	'' => [
// 		'name' => 'startdate',
// 		'value' => time(),
// 		'operand' => '<',
// 	],

$result = [];

foreach ($entities as $entity) {
	$result[] = [
		'title' => $entity->getDisplayName(),
		'start' => gmdate('c', $entity->startdate),
		'end' => gmdate('c', $entity->enddate),
		//'allDay' => $event->isMultiDayEvent(),
		'url' => $entity->getURL(),
		'className' => "service-announcements-announcement-type service-announcements-announcement-type-{$entity->announcement_type}",
	];
}

header('Content-type: application/json');

echo json_encode($result);
