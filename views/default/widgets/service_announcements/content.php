<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

$options = [
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'limit' => $num_display,
	'pagination' => false,
	'order_by_metadata' => [
		'name' => 'startdate',
		'direction' => 'DESC',
		'as' => 'integer',
	],
	'metadata_name_value_pairs' => [],
	'joins' => [],
	'wheres' => [],
	'no_results' => elgg_echo('widgets:service_announcements:notfound'),
	'show_services' => false,
	'show_excerpt' => false,
];

// announcement type
$announcement_type = $widget->announcement_type;
if (!empty($announcement_type)) {
	$options['metadata_name_value_pairs'][] = [
		'announcement_type' => $announcement_type,
	];
}

// period
switch ($widget->period) {
	case 'past':
		$options['metadata_name_value_pairs'][] = [
			'name' => 'enddate',
			'value' => time(),
			'operand' => '<',
		];
		$options['metadata_name_value_pairs'][] = [
			'name' => 'enddate',
			'value' => 0,
			'operand' => '>',
		];
		break;
	case 'upcomming':
		$options['metadata_name_value_pairs'][] = [
			'name' => 'startdate',
			'value' => time(),
			'operand' => '>',
		];
		$options['order_by_metadata']['direction'] = 'ASC';
		break;
	default:
		// current
		$dbprefix = elgg_get_config('dbprefix');
		$enddate_name_id = elgg_get_metastring_id('enddate');
		$time = time();
		
		$options['joins'][] = "JOIN {$dbprefix}metadata mde ON e.guid = mde.entity_guid";
		$options['joins'][] = "JOIN {$dbprefix}metastrings msve ON mde.value_id = msve.id";
		
		$options['metadata_name_value_pairs'][] = [
			'name' => 'startdate',
			'value' => $time,
			'operand' => '<=',
		];
		$options['wheres'][] = "(mde.name_id = {$enddate_name_id}
			AND (
				CAST(msve.string AS SIGNED) = 0
				OR
				CAST(msve.string AS SIGNED) > {$time}
				)
		)";
		
		$options['order_by_metadata']['direction'] = 'ASC';
		break;
}

// services
$services = $widget->services;
if (!empty($services)) {
	$options['relationship'] = ServiceAnnouncement::AFFECTED_SERVICES;
	$options['inverse_relationship'] = true;
	
	$options['wheres'][] = '(r.guid_two IN (' . implode(',', $services) . '))';
}

echo elgg_list_entities_from_relationship($options);
