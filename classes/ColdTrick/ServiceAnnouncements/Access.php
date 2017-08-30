<?php

namespace ColdTrick\ServiceAnnouncements;

class Access {
	
	/**
	 * Update all annotatations access_id to match the entity access
	 *
	 * @param string      $event  the name of the event
	 * @param string      $type   the type of the event
	 * @param \ElggObject $entity supplied entity
	 *
	 * @return void
	 */
	public static function updateAnnotationAccess($event, $type, $entity) {
		
		if (!($entity instanceof \Service) && !($entity instanceof \ServiceAnnouncement)) {
			return;
		}
		
		$old_attributes = $entity->getOriginalAttributes();
		$old_access_id = elgg_extract('access_id', $old_attributes);
		if (!isset($old_access_id) || ($old_access_id === $entity->access_id)) {
			// nothing changed
			return;
		}
		
		/* @var $annotations_batch \ElggBatch */
		$annotations_batch = $entity->getAnnotations([
			'limit' => false,
			'batch' => true,
		]);
		/* @var $annotation \ElggAnnotation */
		foreach ($annotations_batch as $annotation) {
			if ($annotation->access_id === $entity->access_id) {
				continue;
			}
			
			$annotation->access_id = $entity->access_id;
			$annotation->save();
		}
	}
}
