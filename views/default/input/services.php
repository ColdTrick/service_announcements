<?php
/**
 * Helper input for Services selection on ServiceAnnouncement add/edit page
 *
 * @see input/checkboxes
 */

$options = [];

$services = elgg_get_entities([
	'type' => 'object',
	'subtype' => Service::SUBTYPE,
	'limit' => false,
	'batch' => true,
]);
/* @var $service Service */
foreach ($services as $service) {
	$options[$service->getDisplayName()] = $service->guid;
}

uksort($options, 'strcasecmp');

$vars['class'] = elgg_extract_class($vars, ['elgg-input-services']);
$vars['options'] = $options;

echo elgg_view('input/checkboxes', $vars);
