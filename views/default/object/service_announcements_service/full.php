<?php
/**
 * Entity full view for a Service
 *
 * @uses $vars['entity'] the Service to show
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Service)) {
	return;
}

// $icon = elgg_view_entity_icon($entity, 'small');
$icon = '';

// prepare summary
$entity_menu = '';
if (!elgg_in_context('widgets')) {
	$entity_menu = elgg_view_menu('entity', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
		'entity' => $entity,
		'handler' => 'services',
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

// contact
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

// icon
if ($entity->hasIcon('medium')) {
	$general_info .= elgg_view('output/img', [
		'src' => $entity->getIconURL(['size' => 'medium']),
		'class' => ['service-announcements-announcement-full-icon'],
		'data-highres-url' => $entity->getIconURL(['size' => 'master']),
	]);
}

// description
if (!empty($entity->description)) {
	$general_info .= elgg_view('output/longtext', [
		'value' => $entity->description,
	]);
}

if (!empty($general_info)) {
	$body .= elgg_view_module('info', '', $general_info);
}

// current announcements (incidents/maintenance)
$current = elgg_view('service_announcements/services/current_announcements', $vars);
if (!empty($current)) {
	$body .= elgg_view_module('info', elgg_echo('service_announcements:service:announcements:current'), $current);
}

// upcomming maintenace
$upcomming = elgg_view('service_announcements/services/upcomming_announcements', [
	'entity' => $entity,
	'options' => [
		'offset_key' => 'upcomming',
		'offset' => (int) get_input('upcomming'),
	],
]);
if (!empty($upcomming)) {
	$body .= elgg_view_module('info', elgg_echo('service_announcements:service:announcements:upcomming'), $upcomming);
}

// past incidents
$past = elgg_view('service_announcements/services/past_announcements', [
	'entity' => $entity,
	'options' => [
		'offset_key' => 'past',
		'offset' => (int) get_input('past'),
	],
]);
if (!empty($past)) {
	$body .= elgg_view_module('info', elgg_echo('service_announcements:service:announcements:past'), $past);
}

// show full view
echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'icon' => $icon,
	'summary' => $summary,
	'body' => $body,
]);
