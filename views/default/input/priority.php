<?php
/**
 * Helper input for priority selection on ServiceAnnouncement add/edit page
 *
 * @see input/select
 */

$options_values = [
	'low' => elgg_echo('service_announcements:priority:low'),
	'medium' => elgg_echo('service_announcements:priority:medium'),
	'high' => elgg_echo('service_announcements:priority:high'),
	'critical' => elgg_echo('service_announcements:priority:critical'),
];

$vars['class'] = elgg_extract_class($vars, ['elgg-input-priority']);
$vars['options_values'] = $options_values;

echo elgg_view('input/select', $vars);
