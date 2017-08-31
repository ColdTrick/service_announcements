<?php

namespace ColdTrick\ServiceAnnouncements\Menu;

class Site {
	
	/**
	 * Add a menu item to the site menu
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
			'text' => elgg_echo('service_announcements:menu:site:service_announcements'),
			'href' => 'service_announcements/all',
		]);
		
		return $returnvalue;
	}
}
