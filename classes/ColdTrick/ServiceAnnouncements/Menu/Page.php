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
			'contexts' => ['service_announcements'],
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
			'contexts' => ['services'],
		]);
		
		return $returnvalue;
	}
}
