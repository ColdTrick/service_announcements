<?php
/**
 * Helper input for Services selection on ServiceAnnouncement add/edit page
 *
 * @see input/select
 */

$options_values = [];

if (!(bool) elgg_extract('multiple', $vars)) {
	$options_values[''] = elgg_echo('service_announcements:input:services:select');
}

$services = elgg_get_entities([
	'type' => 'object',
	'subtype' => Service::SUBTYPE,
	'limit' => false,
	'batch' => true,
]);
/* @var $service Service */
foreach ($services as $service) {
	$options_values[$service->guid] = $service->getDisplayName();
}

$vars['class'] = elgg_extract_class($vars, ['elgg-input-services']);
$vars['options_values'] = $options_values;

echo elgg_view('input/select', $vars);
