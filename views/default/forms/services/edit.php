<?php
/**
 * Add/edit form for a Service
 *
 * @uses $vars['entity'] the Service to edit
 */

/* @var $entity Service */
$entity = elgg_extract('entity', $vars);

if ($entity instanceof Service) {
	// edit
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	]);
}

// title
echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'name' => 'title',
	'value' => elgg_extract('title', $vars),
	'required' => true,
]);

// icon
// Get post_max_size and upload_max_filesize
$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

// Determine the correct value
$upload_limit = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;
$upload_limit = elgg_format_bytes($upload_limit);

echo elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('service_announcements:edit:icon'),
	'#help' => elgg_echo('service_announcements:edit:icon:limit', [$upload_limit]),
	'name' => 'icon',
]);

// remove icon
if ($entity instanceof Service && $entity->hasIcon('master')) {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('service_announcements:edit:remove_icon'),
		'#help' => elgg_echo('service_announcements:edit:remove_icon:help'),
		'name' => 'remove_icon',
		'value' => 1,
	]);
}

// description
echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('description'),
	'name' => 'description',
	'value' => elgg_extract('description', $vars),
]);

// tags
echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('tags'),
	'name' => 'tags',
	'value' => elgg_extract('tags', $vars),
]);

// contact
echo elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo('service_announcements:contact_user'),
	'#help' => elgg_echo('service_announcements:contact_user:help'),
	'name' => 'contact_user',
	'values' => elgg_extract('contact_user', $vars),
]);

// access
echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'access_id',
	'value' => (int) elgg_extract('access_id', $vars),
	'entity_type' => 'object',
	'entity_subtype' => Service::SUBTYPE,
	'container_guid' => elgg_get_site_entity()->guid,
	'entity' => $entity,
]);

// footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
