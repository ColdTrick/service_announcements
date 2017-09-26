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
	$start .= ': ' . $entity->getStartDate('j-n-Y G:i');
	
	$general_info .= elgg_format_element('div', [], $start);
}

if (!empty($entity->enddate)) {
	$end = elgg_echo('service_announcements:service_announcements:enddate');
	$end .= ': ' . $entity->getEndDate('j-n-Y G:i');
	
	$general_info .= elgg_format_element('div', [], $end);
}

if (!empty($entity->contact_user)) {
	$user_guids = (array) $entity->contact_user;
	
	$users = [];
	foreach ($user_guids as $user_guid) {
		$user = get_user($user_guid);
		if (empty($user)) {
			continue;
		}
		
		$users[] = elgg_view('output/url', [
			'text' => $user->getDisplayName(),
			'href' => $user->getURL(),
			'is_trusted' => true,
		]);
	}
	
	if (!empty($users)) {
		$contact = elgg_echo('service_announcements:contact_user');
		$contact .= ': ' . implode(', ', $users);
		
		$general_info .= elgg_format_element('div', [], $contact);
	}
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
	$services = $entity->getServices([
		'limit' => false,
		'batch' => true,
	]);
	$list = [];
	/* @var $service \Service */
	foreach ($services as $service) {
		$list[] = elgg_view('output/url', [
			'text' => $service->getDisplayName(),
			'href' => $service->getURL(),
			'is_trusted' => true,
		]);
	}
	
	$body .= elgg_view_module('info', elgg_echo('service_announcements:service_announcements:edit:services'), implode(', ', $list));
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
