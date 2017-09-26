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
];

$announcement_type = $widget->announcement_type;
if (!empty($announcement_type)) {
	$options['metadata_name_value_pairs'] = [
		'announcement_type' => $announcement_type,
	];
}

echo elgg_list_entities_from_metadata($options);
