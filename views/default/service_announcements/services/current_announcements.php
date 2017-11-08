<?php
/**
 * List all current announcements for the given Service
 *
 * @uses $vars['entity'] the Service to check for
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Service)) {
	return;
}

$dbprefix = elgg_get_config('dbprefix');
$enddate_name_id = elgg_get_metastring_id('enddate');
$time = time();

$options = [
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'limit' => false,
	'relationship' => ServiceAnnouncement::AFFECTED_SERVICES,
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'joins' => [
		"JOIN {$dbprefix}metadata mde ON e.guid = mde.entity_guid",
		"JOIN {$dbprefix}metastrings msve ON mde.value_id = msve.id",
	],
	'metadata_name_value_pairs' => [
		[
			'name' => 'startdate',
			'value' => $time,
			'operand' => '<=',
		],
	],
	'wheres' => [
		"(mde.name_id = {$enddate_name_id}
			AND (
				CAST(msve.string AS SIGNED) = 0
				OR
				CAST(msve.string AS SIGNED) > {$time}
				)
		)",
	],
	'order_by_metadata' => [
		'name' => 'startdate',
		'direction' => 'DESC',
		'as' => 'integer',
	],
];
$options = array_merge($options, (array) elgg_extract('options', $vars, []));

echo elgg_list_entities_from_relationship($options);
