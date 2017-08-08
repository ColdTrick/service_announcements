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
		
		switch (elgg_extract(0, $page)) {
			case 'all':
				
				echo elgg_view_resource('services/all');
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
		
		switch (elgg_extract(0, $page)) {
			case 'all':
				
				echo elgg_view_resource('service_announcements/all');
				return true;
				
				break;
			default:
				forward('service_announcements/all');
				break;
		}
		
		return false;
	}
}
