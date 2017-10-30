<?php

elgg_load_js('jquery.timepicker');
elgg_load_js('jquery.slider');
elgg_load_css('jquery.timepicker');
elgg_load_css('jquery.slider');

$vars['class'] = elgg_extract_class($vars, ['elgg-input-sa-datetime']);

$defaults = [
	'#type' => 'text',
	'value' => '',
	'disabled' => false,
];

$vars = array_merge($defaults, $vars);

$timestamp = elgg_extract('timestamp', $vars, false);
unset($vars['timestamp']);

if ($timestamp) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => elgg_extract('name', $vars),
	]);

	$vars['class'][] = 'elgg-input-timestamp';
	$vars['id'] = elgg_extract('name', $vars);
	unset($vars['name']);
	unset($vars['internalname']);
	
	$value = elgg_extract('value', $vars);
	
	echo elgg_format_element('script', [], 'require(["input/sa_datetimepicker"], function(DateTimePicker){ DateTimePicker.init("#' . $vars['id'] . '", "' . $value . '"); });');
}

// convert timestamps to text for display
if (is_numeric($vars['value'])) {
	$vars['value'] = date('Y-m-d H:i', elgg_extract('value', $vars));
}

echo elgg_view_field($vars);
