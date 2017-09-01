<?php

elgg_admin_gatekeeper();

// build page elements
$title = elgg_echo('service_announcements:service_announcements:staff');

$body = elgg_list_entities_from_relationship([
	'type' => 'user',
	'relationship' => SERVICE_ANNOUNCEMENT_STAFF,
	'relationship_guid' => elgg_get_site_entity()->guid,
	'inverse_relationship' => true,
	'no_results' => elgg_echo('notfound'),
]);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'staff',
]);

// draw page
echo elgg_view_page($title, $page);
