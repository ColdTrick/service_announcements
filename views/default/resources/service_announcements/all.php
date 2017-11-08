<?php
/**
 * List all current announcements
 */

// title button
elgg_register_title_button(null, 'add', 'object', ServiceAnnouncement::SUBTYPE);

// build page elements
$title = elgg_echo('service_announcements:service_announcements:all');

$dbprefix = elgg_get_config('dbprefix');
$enddate_name_id = elgg_get_metastring_id('enddate');
$time = time();

$body = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'joins' => [
		"JOIN {$dbprefix}metadata mde ON e.guid = mde.entity_guid",
		"JOIN {$dbprefix}metastrings msve ON mde.value_id = msve.id",
	],
	'metadata_name_value_pairs' => [
		[
			'name' => 'startdate',
			'value' => $time,
			'operand' => '<=',
		],
	],
	'wheres' => [
		"(mde.name_id = {$enddate_name_id}
			AND (
				CAST(msve.string AS SIGNED) = 0
				OR
				CAST(msve.string AS SIGNED) > {$time}
				)
		)",
	],
	'order_by_metadata' => [
		'name' => 'startdate',
		'direction' => 'DESC',
		'as' => 'integer',
	],
	'no_results' => elgg_echo('notfound'),
]);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
]);

// draw page
echo elgg_view_page($title, $page);
