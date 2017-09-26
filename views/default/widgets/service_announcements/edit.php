<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widget:numbertodisplay'),
	'name' => 'params[num_display]',
	'value' => $num_display,
	'min' => 0,
]);

echo elgg_view_field([
	'#type' => 'radio',
	'#label' => elgg_echo('service_announcements:announcement_type'),
	'name' => 'params[announcement_type]',
	'value' => $widget->announcement_type,
	'options' => [
		elgg_echo('all') => '',
		elgg_echo('service_announcements:announcement_type:maintenance') => 'maintenance',
		elgg_echo('service_announcements:announcement_type:incident') => 'incident',
	],
]);
