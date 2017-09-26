<?php

namespace ColdTrick\ServiceAnnouncements\Menu;

class Page {
	
	/**
	 * Add a menu item to the page menu
	 *
	 * @param string          $hook        the name of the hook
	 * @param string          $type        the type of the hook
	 * @param \ElggMenuItem[] $returnvalue current return value
	 * @param array           $params      supplied params
	 *
	 * @return \ElggMenuItem[]
	 */
	public static function registerServices($hook, $type, $returnvalue, $params) {
		
		$returnvalue[] = \ElggMenuItem::factory([
			'name' => 'services',
			'text' => elgg_echo('service_announcements:menu:page:services'),
			'href' => 'services/all',
			'contexts' => ['service_announcements', 'services'],
		]);
		
		return $returnvalue;
	}
	
	/**
	 * Add a menu item to the page menu
	 *
	 * @param string          $hook        the name of the hook
	 * @param string          $type        the type of the hook
	 * @param \ElggMenuItem[] $returnvalue current return value
	 * @param array           $params      supplied params
	 *
	 * @return \ElggMenuItem[]
	 */
	public static function registerServiceAnnouncements($hook, $type, $returnvalue, $params) {
		
		$returnvalue[] = \ElggMenuItem::factory([
			'name' => 'service_announcements',
			'text' => elgg_echo('service_announcements:menu:page:service_announcements'),
			'href' => 'service_announcements/all',
			'contexts' => ['services', 'service_announcements'],
		]);
		
		return $returnvalue;
	}
	
	/**
	 * Add a menu item to the page menu
	 *
	 * @param string          $hook        the name of the hook
	 * @param string          $type        the type of the hook
	 * @param \ElggMenuItem[] $returnvalue current return value
	 * @param array           $params      supplied params
	 *
	 * @return \ElggMenuItem[]
	 */
	public static function registerServiceNotifications($hook, $type, $returnvalue, $params) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		if (!elgg_in_context('settings')) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if (!($page_owner instanceof \ElggUser) || !$page_owner->canEdit()) {
			return;
		}
		
		$returnvalue[] = \ElggMenuItem::factory([
			'name' => 'services_notifications',
			'text' => elgg_echo('service_announcements:menu:page:services:notifications'),
			'href' => "services/notifications/{$page_owner->username}",
			'contexts' => ['settings'],
			'section' => 'notifications',
		]);
		
		return $returnvalue;
	}
	
	/**
	 * Add staff item to  filter menu on the service announcements page
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function serviceAnnouncementsStaff($hook, $type, $return_value, $params) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		// add staff item
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'staff',
			'text' => elgg_echo('service_announcements:menu:filter:staff'),
			'href' => 'service_announcements/staff',
			'contexts' => ['service_announcements', 'services']
		]);
		
		return $return_value;
	}
}
