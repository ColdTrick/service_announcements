<?php

namespace ColdTrick\ServiceAnnouncements\Menu;

class Annotation {
	
	/**
	 * Add delete menu item to annotation menu for status updates
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerDelete($hook, $type, $return_value, $params) {
		
		if (!service_announcements_is_staff()) {
			return;
		}
		
		$annotation = elgg_extract('annotation', $params);
		if (!($annotation instanceof \ElggAnnotation)) {
			return;
		}
		
		$supported_types = [
			'status_update_update',
			'status_update_close',
		];
		if (!in_array($annotation->name, $supported_types)) {
			return;
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'href' => elgg_http_add_url_query_elements('action/service_announcements/status_update_delete', [
				'id' => $annotation->id,
			]),
			'confirm' => elgg_echo('deleteconfirm'),
		]);
		
		return $return_value;
	}
}
