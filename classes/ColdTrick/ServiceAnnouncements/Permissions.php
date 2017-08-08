<?php

namespace ColdTrick\ServiceAnnouncements;

class Permissions {
	
	/**
	 * Set container permissions for Services
	 *
	 * @param string $hook        the name of the hook
	 * @param string $type        the type of the hook
	 * @param bool   $returnvalue current return value
	 * @param array  $params      supplied params
	 *
	 * @retrun void|bool
	 */
	public static function serviceContainerPermissions($hook, $type, $returnvalue, $params) {
		
		$subtype = elgg_extract('subtype', $params);
		if ($subtype !== \Service::SUBTYPE) {
			return;
		}
		
		$user = elgg_extract('user', $params);
		if (!($user instanceof \ElggUser)) {
			return false;
		}
		
		if ($user->isAdmin()) {
			return true;
		}
		
		// @todo allow support staff to create Services
		
		return false;
	}
	
	/**
	 * Set container permissions for Service Announcements
	 *
	 * @param string $hook        the name of the hook
	 * @param string $type        the type of the hook
	 * @param bool   $returnvalue current return value
	 * @param array  $params      supplied params
	 *
	 * @retrun void|bool
	 */
	public static function serviceAnnouncementContainerPermissions($hook, $type, $returnvalue, $params) {
		
		$subtype = elgg_extract('subtype', $params);
		if ($subtype !== \ServiceAnnouncement::SUBTYPE) {
			return;
		}
		
		$user = elgg_extract('user', $params);
		if (!($user instanceof \ElggUser)) {
			return false;
		}
		
		if ($user->isAdmin()) {
			return true;
		}
		
		// @todo allow support staff to create Services
		
		return false;
	}
}
