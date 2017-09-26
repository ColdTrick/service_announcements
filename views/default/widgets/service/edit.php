<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

if (elgg_view_exists('input/objectpicker')) {
	echo elgg_view_field([
		'#type' => 'objectpicker',
		'#label' => elgg_echo('widgets:service:edit:objectpicker'),
		'name' => 'params[service_guid]',
		'values' => $widget->service_guid,
		'subtype' => Service::SUBTYPE,
		'limit' => 1,
	]);
} else {
	echo elgg_view_field([
		'#type' => 'number',
		'#label' => elgg_echo('widgets:service:edit:service_guid'),
		'name' => 'params[service_guid]',
		'value' => $widget->service_guid,
	]);
}

echo elgg_view_field([
	'#type' => 'checkboxes',
	'#label' => elgg_echo('widgets:service:edit:sections'),
	'name' => 'params[sections]',
	'options' => [
		elgg_echo('service_announcements:service:announcements:current') => 'current',
		elgg_echo('service_announcements:service:announcements:upcomming') => 'upcomming',
		elgg_echo('service_announcements:service:announcements:past') => 'past',
	],
	'value' => $widget->sections,
]);
