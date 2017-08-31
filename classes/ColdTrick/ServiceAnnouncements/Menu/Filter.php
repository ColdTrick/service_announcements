<?php

namespace ColdTrick\ServiceAnnouncements\Menu;

class Filter {
	
	/**
	 * Change the filter menu items on the service announcements page
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function serviceAnnouncements($hook, $type, $return_value, $params) {
		
		if (!elgg_in_context('service_announcements')) {
			return;
		}
		
		// remove some items
		$remove_items = [
			'mine',
			'friend',
		];
		foreach ($return_value as $index => $menu_item) {
			if (!in_array($menu_item->getName(), $remove_items)) {
				continue;
			}
			
			unset($return_value[$index]);
		}
		
		// add new item
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'scheduled',
			'text' => elgg_echo('service_announcements:menu:filter:scheduled'),
			'href' => 'service_announcements/scheduled',
			'priority' => 500,
		]);
		
		return $return_value;
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
		
		if (!elgg_in_context('service_announcements') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		// add staff item
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'staff',
			'text' => elgg_echo('service_announcements:menu:filter:staff'),
			'href' => 'service_announcements/staff',
			'priority' => 600,
		]);
		
		return $return_value;
	}
}
