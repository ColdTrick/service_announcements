<?php
/**
 * Extends the Wizard replacement helper view, to explain how to add service announcement support
 */

echo elgg_view('output/longtext', [
	'value' => elgg_echo('service_announcements:wizard:replacements'),
	'class' => 'elgg-subtext',
]);

$templates = [];
$templates[] = elgg_format_element('div', [
	'class' => ['elgg-col', 'elgg-col-1of2', 'wizard-replacement-helper'],
	'title' => elgg_echo('service_announcements:wizard:replacements:services'),
], "{{service_announcements_services}}");

echo elgg_format_element('div', [
	'class' => ['elgg-subtext', 'clearfix', 'mbm'],
], implode('', $templates));
