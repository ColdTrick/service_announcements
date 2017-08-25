<?php

service_announcements_gatekeeper();

$count_services = elgg_get_entities([
	'type' => 'object',
	'subtype' => Service::SUBTYPE,
	'count' => true,
]);
if (empty($count_services)) {
	register_error(elgg_echo('service_announcements:service_announcements:error:no_services'));
	forward('services/add');
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:service_announcements:all'), 'service_announcements/all');
elgg_push_breadcrumb(elgg_echo('add'));

// build page elements
$title = elgg_echo('service_announcements:service_announcements:add');

$body_vars = service_announcements_prepare_service_announcement_vars();

$body = elgg_view_form('service_announcements/edit', [], $body_vars);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page);
