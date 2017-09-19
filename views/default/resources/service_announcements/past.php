<?php
/**
 * List all past announcements
 */

// title button
elgg_register_title_button(null, 'add', 'object', ServiceAnnouncement::SUBTYPE);

// build page elements
$title = elgg_echo('service_announcements:service_announcements:past');

$body = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'metadata_name_value_pairs' => [
		[
			'name' => 'enddate',
			'value' => time(),
			'operand' => '<',
		],
		[
			'name' => 'enddate',
			'value' => 0,
			'operand' => '>',
		],
	],
	'order_by_metadata' => [
		'name' => 'enddate',
		'direction' => 'DESC',
		'as' => 'integer',
	],
	'no_results' => elgg_echo('notfound'),
]);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'past',
]);

// draw page
echo elgg_view_page($title, $page);
