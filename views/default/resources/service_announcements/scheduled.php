<?php

// title button
elgg_register_title_button(null, 'add', 'object', ServiceAnnouncement::SUBTYPE);

// build page elements
$title = elgg_echo('service_announcements:service_announcements:scheduled');

$body = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'metadata_name_value_pairs' => [
		'name' => 'startdate',
		'value' => time(),
		'operand' => '>',
	],
	'order_by_metadata' => [
		'name' => 'startdate',
		'direction' => 'ASC',
		'as' => 'integer',
	],
	'no_results' => elgg_echo('notfound'),
]);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'scheduled',
]);

// draw page
echo elgg_view_page($title, $page);
