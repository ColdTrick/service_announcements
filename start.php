<?php

// register default elgg events
elgg_register_event_handler('init', 'system', 'service_announcements_init');

/**
 * Init function for this plugin
 *
 * @return void
 */
function service_announcements_init() {
	
	// register page handlers
	elgg_register_page_handler('services', '\ColdTrick\ServiceAnnouncements\Router::services');
	elgg_register_page_handler('service_announcements', '\ColdTrick\ServiceAnnouncements\Router::serviceAnnouncements');
	
	// plugin hooks
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceContainerPermissions');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceAnnouncementContainerPermissions');
}
