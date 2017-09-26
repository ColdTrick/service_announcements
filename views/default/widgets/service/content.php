<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$service_guid = $widget->service_guid;
if (is_array($service_guid)) {
	$service_guid = $service_guid[0];
}

$service = get_entity($service_guid);
if (!($service instanceof Service)) {
	echo elgg_echo('widgets:service:no_service');
	return;
}

echo elgg_view_entity($service, ['full_view' => false]);

$sections = $widget->sections;
if (empty($sections)) {
	return;
}

// show sections
if (in_array('current', $sections)) {
	$current = elgg_view('service_announcements/services/current_announcements', [
		'entity' => $service,
		'options' => [
			'limit' => 5,
			'pagination' => false,
		],
	]);
	
	if (!empty($current)) {
		echo elgg_view_module('info', elgg_echo('service_announcements:service:announcements:current'), $current);
	}
}

if (in_array('upcomming', $sections)) {
	$upcomming = elgg_view('service_announcements/services/upcomming_announcements', [
		'entity' => $service,
		'options' => [
			'limit' => 5,
			'pagination' => false,
		],
	]);
	
	if (!empty($upcomming)) {
		echo elgg_view_module('info', elgg_echo('service_announcements:service:announcements:upcomming'), $upcomming);
	}
}

if (in_array('past', $sections)) {
	$past = elgg_view('service_announcements/services/past_announcements', [
		'entity' => $service,
		'options' => [
			'limit' => 5,
			'pagination' => false,
		],
	]);
	
	if (!empty($past)) {
		echo elgg_view_module('info', elgg_echo('service_announcements:service:announcements:past'), $past);
	}
}
