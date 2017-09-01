<?php

namespace ColdTrick\ServiceAnnouncements;

class Router {
	
	/**
	 * Handle /services URLs
	 *
	 * @param array $page URL segments
	 *
	 * @return bool
	 */
	public static function services($page) {
		
		$vars = [];
		
		switch (elgg_extract(0, $page)) {
			case 'all':
				
				echo elgg_view_resource('services/all');
				return true;
				
				break;
			case 'add':
				
				echo elgg_view_resource('services/add');
				return true;
				
				break;
			case 'view':
				
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('services/view', $vars);
				return true;
				
				break;
			case 'edit':
				
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('services/edit', $vars);
				return true;
				
				break;
			default:
				
				forward('services/all');
				break;
		}
		
		return false;
	}
	
	/**
	 * Handle /service_announcements URLs
	 *
	 * @param array $page URL segments
	 *
	 * @return bool
	 */
	public static function serviceAnnouncements($page) {
		
		$vars = [];
		
		switch (elgg_extract(0, $page)) {
			case 'all':
				
				echo elgg_view_resource('service_announcements/all');
				return true;
				
				break;
			case 'calendar':
				
				echo elgg_view_resource('service_announcements/calendar');
				return true;
				
				break;
			case 'add':
				
				echo elgg_view_resource('service_announcements/add');
				return true;
				
				break;
			case 'view':
				
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('service_announcements/view', $vars);
				return true;
				
				break;
			case 'edit':
				
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('service_announcements/edit', $vars);
				return true;
				
				break;
			case 'scheduled':
				
				echo elgg_view_resource('service_announcements/scheduled');
				return true;
				
				break;
			case 'staff':
				
				echo elgg_view_resource('service_announcements/staff');
				return true;
				
				break;
			default:
				forward('service_announcements/all');
				break;
		}
		
		return false;
	}
}
