<?php
/**
 * Entity full view for a ServiceAnnouncement
 *
 * @uses $vars['entity'] the ServiceAnnouncement to show
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ServiceAnnouncement)) {
	return;
}

// $icon = elgg_view_entity_icon($entity, 'tiny');
$icon = '';

// prepare summary
$entity_menu = '';
if (!elgg_in_context('widgets')) {
	$entity_menu = elgg_view_menu('entity', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
		'entity' => $entity,
		'handler' => 'service_announcements',
	]);
}

$params = [
	'metadata' => $entity_menu,
	'title' => false,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

// prepare body
$body = '';

$general_info = '';
if (!empty($entity->announcement_type)) {
	$type = $entity->announcement_type;
	if (elgg_language_key_exists("service_announcements:announcement_type:{$type}")) {
		$type = elgg_echo("service_announcements:announcement_type:{$type}");
	}
	
	$type = elgg_format_element('span', [
		'class' => [
			'service-announcements-announcement-type',
			"service-announcements-announcement-type-{$entity->announcement_type}",
		],
	], $type);
	$general_info .= elgg_format_element('div', [], $type);
}

if (!empty($entity->priority)) {
	$priority = $entity->priority;
	if (elgg_language_key_exists("service_announcements:priority:{$priority}")) {
		$priority = elgg_echo("service_announcements:priority:{$priority}");
	}
	
	$general_info .= elgg_format_element('div', [], elgg_echo('service_announcements:priority') . ": {$priority}");
}

if (!empty($entity->startdate)) {
	$start = elgg_echo('service_announcements:service_announcements:startdate');
	$start .= ': ' . date('d/m/Y', $entity->startdate);
	
	$general_info .= elgg_format_element('div', [], $start);
}

if (!empty($entity->enddate)) {
	$end = elgg_echo('service_announcements:service_announcements:enddate');
	$end .= ': ' . date('d/m/Y', $entity->enddate);
	
	$general_info .= elgg_format_element('div', [], $end);
}

if (!empty($entity->description)) {
	$general_info .= elgg_view('output/longtext', [
		'value' => $entity->description,
	]);
}

if (!empty($general_info)) {
	$body .= elgg_view_module('info', '', $general_info);
}

// affected services
$service_count = $entity->getServices([
	'count' => true,
]);
if (!empty($service_count)) {
	$list = elgg_view_entity_list($entity->getServices(['limit' => false]), ['full_view' => false]);
	
	$body .= elgg_view_module('info', elgg_echo('service_announcements:service_announcements:edit:services'), $list);
}

// status updates
$list = elgg_list_annotations([
	'guid' => $entity->guid,
	'annotation_names' => [
		'status_update_update',
		'status_update_close',
	],
	'limit' => false,
]);
if (!empty($list)) {
	$body .= elgg_view_module('info', elgg_echo('service_announcements:service_announcements:status_update'), $list);
}

// show full view
echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'icon' => $icon,
	'summary' => $summary,
	'body' => $body,
]);
