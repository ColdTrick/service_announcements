<?php

namespace ColdTrick\ServiceAnnouncements;

class ContentSubscriptions {
	
	/**
	 * Add service announcements to content subscriptions
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function registerServiceAnnouncements($hook, $type, $return_value, $params) {
		
		if (!is_array($return_value)) {
			// something is wrong
			return;
		}
		
		$return_value['object'][] = \ServiceAnnouncement::SUBTYPE;
		
		return $return_value;
	}
}
