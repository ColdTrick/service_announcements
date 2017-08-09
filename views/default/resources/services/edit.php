<?php

service_announcements_gatekeeper();

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Service::SUBTYPE);

/* @var $entity Service */
$entity = get_entity($guid);

if (!$entity->canEdit()) {
	register_error(elgg_echo('limited_access'));
	forward(REFERER);
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:services:all'), 'services/all');
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

// build page elements
$title = elgg_echo('service_announcements:services:edit', [$entity->getDisplayName()]);

$form_vars = [
	'enctype' => 'multipart/form-data',
];
$body_vars = service_announcements_prepare_service_vars($entity);

$body = elgg_view_form('services/edit', $form_vars, $body_vars);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page);
