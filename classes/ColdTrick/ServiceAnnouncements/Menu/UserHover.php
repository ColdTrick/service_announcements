<?php

namespace ColdTrick\ServiceAnnouncements\Menu;

class UserHover {
	
	/**
	 * Register staff toggle menu item
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplies params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerStaff($hook, $type, $return_value, $params) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$user = elgg_extract('entity', $params);
		if (!($user instanceof \ElggUser) || $user->isAdmin()) {
			return;
		}
		
		$text = elgg_echo('service_announcements:menu:user_hover:staff:assign');
		if (service_announcements_is_staff($user->guid)) {
			$text = elgg_echo('service_announcements:menu:user_hover:staff:unassign');
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'service_announcements_staff',
			'text' => $text,
			'href' => elgg_http_add_url_query_elements('action/service_announcements/admin/toggle_staff', [
				'user_guid' => $user->guid,
			]),
			'section' => 'admin',
			'is_action' => true,
		]);
		
		return $return_value;
	}
}
