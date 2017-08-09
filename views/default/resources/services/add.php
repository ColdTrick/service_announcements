<?php

service_announcements_gatekeeper();

// breadcrumb
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:services:all'), 'services/all');
elgg_push_breadcrumb(elgg_echo('add'));

// build page elements
$title = elgg_echo('service_announcements:services:add');

$form_vars = [
	'enctype' => 'multipart/form-data',
];
$body_vars = service_announcements_prepare_service_vars();

$body = elgg_view_form('services/edit', $form_vars, $body_vars);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page);
