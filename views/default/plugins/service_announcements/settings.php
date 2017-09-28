<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

// site announcements support
$site_announcements = elgg_echo('service_announcements:settings:site_announcements:not_enabled');
if (elgg_is_active_plugin('site_announcements')) {
	$site_announcements = '';
	
	$site_announcements .= elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('service_announcements:settings:site_announcements:critical_incident'),
		'#help' => elgg_echo('service_announcements:settings:site_announcements:critical_incident:help'),
		'name' => 'params[site_announcements_critical_incident]',
		'value' => $plugin->site_announcements_critical_incident,
		'options_values' => [
			'no' => elgg_echo('option:no'),
			'yes' => elgg_echo('option:yes'),
		],
	]);
}

echo elgg_view_module('inline', elgg_echo('service_announcements:settings:site_announcements'), $site_announcements);
