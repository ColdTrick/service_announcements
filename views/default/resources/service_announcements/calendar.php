<?php

elgg_load_css('fullcalendar');
elgg_require_js('service_announcements/calendar');

// build page elements
$title = elgg_echo('service_announcements:service_announcements:calendar');

$body = elgg_format_element('div', [
	'id' => 'service-announcements-calendar',
]);

// build page
$page = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'calendar',
]);

// draw page
echo elgg_view_page($title, $page);
