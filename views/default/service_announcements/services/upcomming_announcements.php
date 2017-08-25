<?php
/**
 * List upcomming announcements for the given Service
 *
 * @uses $vars['entity'] the Service to check for
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Service)) {
	return;
}

$options = [
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'limit' => false,
	'relationship' => ServiceAnnouncement::AFFECTED_SERVICES,
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'metadata_name_value_pairs' => [
		[
			'name' => 'startdate',
			'value' => time(),
			'operand' => '>',
		],
	],
	'order_by_metadata' => [
		'name' => 'startdate',
		'direction' => 'ASC',
		'as' => 'integer',
	],
];

$options = array_merge($options, (array) elgg_extract('options', $vars, []));

echo elgg_list_entities_from_relationship($options);