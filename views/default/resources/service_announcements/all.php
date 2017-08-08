<?php

// breadcrumb
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:service_announcements:all'));

// title button
elgg_register_title_button(null, 'add', 'object', ServiceAnnouncement::SUBTYPE);

// build page elements
$title = elgg_echo('service_announcements:service_announcements:all');

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'no_results' => elgg_echo('notfound'),
]);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false, // for now
]);

// draw page
echo elgg_view_page($title, $page);
