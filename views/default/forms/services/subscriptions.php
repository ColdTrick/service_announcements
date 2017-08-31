<?php
/**
 * Manage the user' subscriptions to a Service
 *
 * @uses $vars['entity'] the Service to manage
 */

$entity = elgg_extract('entity', $vars);
$notification_methods = elgg_get_notification_methods();
if (!($entity instanceof Service) || empty($notification_methods)) {
	return;
}

$announcement_types = [
	'maintenance',
	'incident',
];

$subscriptions = $entity->getUserSubscriptions();

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

$subsciptions_options = [];
foreach ($notification_methods as $method) {
	$subsciptions_options[elgg_echo("notification:method:{$method}")] = $method;
}

foreach ($announcement_types as $type) {
	echo elgg_view_field([
		'#type' => 'checkboxes',
		'#label' => elgg_echo("service_announcements:announcement_type:{$type}"),
		'name' => "subscriptions[{$type}]",
		'value' => elgg_extract($type, $subscriptions),
		'options' => $subsciptions_options,
	]);
}

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
