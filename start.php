<?php
/**
 * Main plugin file
 */

define('SERVICE_ANNOUNCEMENT_STAFF', 'service_announcement_staff');

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
	
	elgg_register_css('fullcalendar', elgg_get_simplecache_url('css/service_announcements/fullcalendar'));
	
	// ajax
	elgg_register_ajax_view('service_announcements/service_announcement/status_update');
	elgg_register_ajax_view('service_announcements/calendar');
	
	// register page handlers
	elgg_register_page_handler('services', '\ColdTrick\ServiceAnnouncements\Router::services');
	elgg_register_page_handler('service_announcements', '\ColdTrick\ServiceAnnouncements\Router::serviceAnnouncements');
	
	// search
	elgg_register_entity_type('object', Service::SUBTYPE);
	elgg_register_entity_type('object', ServiceAnnouncement::SUBTYPE);
	
	// events
	elgg_register_event_handler('update:after', 'object', '\ColdTrick\ServiceAnnouncements\Access::updateAnnotationAccess');
	
	// plugin hooks
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceContainerPermissions');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceAnnouncementContainerPermissions');
	elgg_register_plugin_hook_handler('permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::servicePermissions');
	elgg_register_plugin_hook_handler('permissions_check', 'object', '\ColdTrick\ServiceAnnouncements\Permissions::serviceAnnouncementPermissions');
	
	elgg_register_plugin_hook_handler('register', 'menu:site', '\ColdTrick\ServiceAnnouncements\Menu\Site::registerServiceAnnouncements');
	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\ServiceAnnouncements\Menu\Page::registerServices');
	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\ServiceAnnouncements\Menu\Page::registerServiceAnnouncements');
	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\ServiceAnnouncements\Menu\Page::registerServiceNotifications');
	elgg_register_plugin_hook_handler('register', 'menu:annotation', '\ColdTrick\ServiceAnnouncements\Menu\Annotation::registerDelete');
	elgg_register_plugin_hook_handler('register', 'menu:filter', '\ColdTrick\ServiceAnnouncements\Menu\Filter::serviceAnnouncements');
	elgg_register_plugin_hook_handler('register', 'menu:filter', '\ColdTrick\ServiceAnnouncements\Menu\Filter::serviceAnnouncementsStaff');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '\ColdTrick\ServiceAnnouncements\Menu\UserHover::registerStaff');
	
	elgg_register_plugin_hook_handler('entity_types', 'content_subscriptions', '\ColdTrick\ServiceAnnouncements\ContentSubscriptions::registerServiceAnnouncements');
	
	elgg_register_plugin_hook_handler('extender:url', 'annotation', '\ColdTrick\ServiceAnnouncements\Annotations::statusUpdateURL');
	
	// notifications
	elgg_register_notification_event('annotation', 'status_update_update');
	elgg_register_notification_event('annotation', 'status_update_close');
	elgg_register_notification_event('object', ServiceAnnouncement::SUBTYPE);
	
	elgg_register_plugin_hook_handler('send:before', 'notifications', '\ColdTrick\ServiceAnnouncements\Notifications::preventMaintenanceServiceAnnouncements');
	
	elgg_register_plugin_hook_handler('prepare', 'notification:create:annotation:status_update_update', '\ColdTrick\ServiceAnnouncements\Notifications::prepareStatusUpdateMessage');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:annotation:status_update_close', '\ColdTrick\ServiceAnnouncements\Notifications::prepareStatusUpdateMessage');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:' . ServiceAnnouncement::SUBTYPE, '\ColdTrick\ServiceAnnouncements\Notifications::prepareServiceAnnouncementMessage');
	
	elgg_register_plugin_hook_handler('get', 'subscriptions', '\ColdTrick\ServiceAnnouncements\Notifications::getStatusUpdateSubscriptions');
	elgg_register_plugin_hook_handler('get', 'subscriptions', '\ColdTrick\ServiceAnnouncements\Notifications::getServiceAnnouncementSubscriptions');
	
	elgg_register_plugin_hook_handler('cron', 'weekly', '\ColdTrick\ServiceAnnouncements\Notifications::scheduledMaintenanceNotifications');
	
	// extend views
	elgg_extend_view('service_announcements/services/sidebar', 'service_announcements/services/sidebar/subscriptions');
	
	// register actions
	elgg_register_action('services/edit', dirname(__FILE__) . '/actions/services/edit.php');
	elgg_register_action('services/delete', dirname(__FILE__) . '/actions/services/delete.php');
	elgg_register_action('services/subscriptions', dirname(__FILE__) . '/actions/services/subscriptions.php');
	elgg_register_action('services/notifications', dirname(__FILE__) . '/actions/services/notifications.php');
	
	elgg_register_action('service_announcements/edit', dirname(__FILE__) . '/actions/service_announcements/edit.php');
	elgg_register_action('service_announcements/delete', dirname(__FILE__) . '/actions/service_announcements/delete.php');
	elgg_register_action('service_announcements/status_update', dirname(__FILE__) . '/actions/service_announcements/status_update/edit.php');
	elgg_register_action('service_announcements/status_update/delete', dirname(__FILE__) . '/actions/service_announcements/status_update/delete.php');
	
	elgg_register_action('service_announcements/admin/toggle_staff', dirname(__FILE__) . '/actions/service_announcements/admin/toggle_staff.php', 'admin');
}
