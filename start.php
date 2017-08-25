<?php
/**
 * Main plugin file
 */

require_once(dirname(__FILE__) . '/lib/functions.php');

// register default elgg events
elgg_register_event_handler('init', 'system', 'service_announcements_init');

/**
 * Init function for this plugin
 *
 * @return void
 */
function service_announcements_init() {
	
	// css/js
	elgg_extend_view('elgg.css', 'css/service_announcements/site.css');
	
	// register page handlers
	elgg_register_page_handler('services', '\ColdTrick\ServiceAnnouncements\Router::services');
	elgg_register_page_handler('service_announcements', '\ColdTrick\ServiceAnnouncements\Router::serviceAnnouncements');
	
	// plugin hooks
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceContainerPermissions');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceAnnouncementContainerPermissions');
	
	elgg_register_plugin_hook_handler('register', 'menu:site', '\ColdTrick\ServiceAnnouncements\SiteMenu::registerServiceAnnouncements');
	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\ServiceAnnouncements\PageMenu::registerServices');
	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\ServiceAnnouncements\PageMenu::registerServiceAnnouncements');
	
	// register actions
	elgg_register_action('services/edit', dirname(__FILE__) . '/actions/services/edit.php');
	elgg_register_action('services/delete', dirname(__FILE__) . '/actions/services/delete.php');
	
	elgg_register_action('service_announcements/edit', dirname(__FILE__) . '/actions/service_announcements/edit.php');
	elgg_register_action('service_announcements/delete', dirname(__FILE__) . '/actions/service_announcements/delete.php');
}
