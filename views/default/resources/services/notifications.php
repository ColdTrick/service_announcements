<?php

elgg_gatekeeper();

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);
if (empty($user) || !$user->canEdit()) {
	register_error(elgg_echo('limited_access'));
	forward(REFERER);
}

// set page owner
elgg_set_page_owner_guid($user->guid);

// set context
elgg_set_context('settings');

// make breadcrumb
elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/{$user->username}");
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:services:notifications'));

// build page elements
$title = elgg_echo('service_announcements:services:notifications');

$content = elgg_view('service_announcements/services/notifications', [
	'entity' => $user,
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
