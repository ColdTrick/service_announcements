<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('service_announcements:announcement_type'),
	'name' => 'params[announcement_type]',
	'value' => $widget->announcement_type,
	'options_values' => [
		'' => elgg_echo('all'),
		'maintenance' => elgg_echo('service_announcements:announcement_type:maintenance'),
		'incident' => elgg_echo('service_announcements:announcement_type:incident'),
	],
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:service_announcements:edit:period'),
	'name' => 'params[period]',
	'value' => $widget->period,
	'options_values' => [
		'current' => elgg_echo('widgets:service_announcements:edit:period:current'),
		'upcomming' => elgg_echo('widgets:service_announcements:edit:period:upcomming'),
		'past' => elgg_echo('widgets:service_announcements:edit:period:past'),
	],
]);

if (elgg_view_exists('input/objectpicker')) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'params[services]',
	]);
	echo elgg_view_field([
		'#type' => 'objectpicker',
		'#label' => elgg_echo('widgets:service_announcements:edit:service'),
		'#help' => elgg_echo('widgets:service_announcements:edit:service:help'),
		'name' => 'params[services]',
		'values' => $widget->services,
		'subtype' => Service::SUBTYPE,
	]);
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widget:numbertodisplay'),
	'name' => 'params[num_display]',
	'value' => $num_display,
	'min' => 0,
]);
