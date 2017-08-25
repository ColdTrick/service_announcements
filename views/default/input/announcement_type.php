<?php
/**
 * Helper input for announcement_type selection on ServiceAnnouncement add/edit page
 *
 * @see input/select
 */

$options_values = [
	'maintenance' => elgg_echo('service_announcements:announcement_type:maintenance'),
	'incident' => elgg_echo('service_announcements:announcement_type:incident'),
];

$vars['class'] = elgg_extract_class($vars, ['elgg-input-announcement-type']);
$vars['options_values'] = $options_values;

echo elgg_view('input/select', $vars);
