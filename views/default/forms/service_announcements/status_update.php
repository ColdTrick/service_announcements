<?php
/**
 * Add a status update to a service announcement
 *
 * @uses $vars['entity'] the service announcement
 * @uses $vars['type']   the type of the status update
 */

$entity = elgg_extract('entity', $vars);
$type = elgg_extract('type', $vars);

if (!($entity instanceof ServiceAnnouncement)) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'type',
	'value' => $type,
]);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('service_announcements:status_update:text'),
	'name' => 'text',
	'required' => ($type !== 'close'),
	'rows' => 5,
]);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
