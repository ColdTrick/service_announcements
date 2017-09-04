<?php

namespace ColdTrick\ServiceAnnouncements;

class Annotations {
	
	/**
	 * Get the correct URL for a status update
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|string
	 */
	public static function statusUpdateURL($hook, $type, $return_value, $params) {
		
		$annotation = elgg_extract('extender', $params);
		if (!($annotation instanceof \ElggAnnotation)) {
			return;
		}
		
		$entity = $annotation->getEntity();
		if (!($entity instanceof \ServiceAnnouncement)) {
			return;
		}
		
		$supported_names = [
			'status_update_update',
			'status_update_close',
		];
		if (!in_array($annotation->name, $supported_names)) {
			return;
		}
		
		$parts = parse_url($entity->getURL());
		$parts['fragment'] = "item-annotation-{$annotation->id}";
		return elgg_http_build_url($parts);
	}
}
