<?php

namespace ColdTrick\ServiceAnnouncements;

class Notifications {
	
	/**
	 * Prepare the content of a status update notification
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareStatusUpdateMessage($hook, $type, $return_value, $params) {
		
		if (!($return_value instanceof \Elgg\Notifications\Notification)) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!($event instanceof \Elgg\Notifications\Event) || !($recipient instanceof \ElggUser)) {
			return;
		}
		
		$annotation = $event->getObject();
		if (!($annotation instanceof \ElggAnnotation)) {
			return;
		}
		
		$announcement = $annotation->getEntity();
		if (!($announcement instanceof \ServiceAnnouncement)) {
			return;
		}
		
		switch ($annotation->name) {
			case 'status_update_update':
				$return_value->subject = elgg_echo('service_announcements:notification:status_update:update:subject', [
					$announcement->getDisplayName(),
				], $language);
				$return_value->summary = elgg_echo('service_announcements:notification:status_update:update:summary', [
					$announcement->getDisplayName(),
				], $language);
				$return_value->body = elgg_echo('service_announcements:notification:status_update:update:body', [
					$recipient->getDisplayName(),
					$announcement->getDisplayName(),
					$annotation->value,
					$annotation->getURL(),
				], $language);
				break;
			case 'status_update_close':
				
				break;
			default:
				// not supported
				return;
		}
		
		return $return_value;
	}
	
	/**
	 * Get the subscribers for a status update
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function getStatusUpdateSubscriptions($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!($event instanceof \Elgg\Notifications\Event)) {
			return;
		}
		
		$annotation = $event->getObject();
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
		
		$subscribers = $entity->getSubscriptions();
		if (empty($subscribers)) {
			return;
		}
		
		$return_value += $subscribers;
		
		return $return_value;
	}
}
