<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Service::SUBTYPE);

/* @var $entity Service */
$entity = get_entity($guid);

// breadcrumb
elgg_push_breadcrumb(elgg_echo('service_announcements:breadcrumb:services:all'), 'services/all');
elgg_push_breadcrumb($entity->getDisplayName());

if (service_announcements_is_staff()) {
	elgg_register_menu_item('title', [
		'name' => 'service_announcement',
		'text' => elgg_echo('service_announcements:add'),
		'href' => elgg_http_add_url_query_elements('service_announcements/add', [
			'services' => [$entity->guid],
		]),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

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
