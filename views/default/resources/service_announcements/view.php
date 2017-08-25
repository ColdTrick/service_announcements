<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', ServiceAnnouncement::SUBTYPE);

/* @var $entity ServiceAnnouncement */
$entity = get_entity($guid);

// breadcrumb
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:service_announcements:all'), 'service_announcements/all');
elgg_push_breadcrumb($entity->getDisplayName());

// build page elements
$title = $entity->getDisplayName();

$body = elgg_view_entity($entity);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page);
