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
				$return_value->subject = elgg_echo('service_announcements:notification:status_update:close:subject', [
					$announcement->getDisplayName(),
				], $language);
				$return_value->summary = elgg_echo('service_announcements:notification:status_update:close:summary', [
					$announcement->getDisplayName(),
				], $language);
				
				if (!empty($annotation->value)) {
					$return_value->body = elgg_echo('service_announcements:notification:status_update:close:body', [
						$recipient->getDisplayName(),
						$announcement->getDisplayName(),
						$annotation->value,
						$annotation->getURL(),
					], $language);
				} else {
					$return_value->body = elgg_echo('service_announcements:notification:status_update:close:body:no_value', [
						$recipient->getDisplayName(),
						$announcement->getDisplayName(),
						$annotation->getURL(),
					], $language);
				}
				
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
	
	/**
	 * Prevent notifications for ServiceAnnouncements of the 'maintenance' type
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|false
	 */
	public static function preventMaintenanceServiceAnnouncements($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!($event instanceof \Elgg\Notifications\Event)) {
			return;
		}
		
		$entity = $event->getObject();
		if (!($entity instanceof \ServiceAnnouncement)) {
			return;
		}
		
		if ($entity->announcement_type !== 'maintenance') {
			return;
		}
		
		return false;
	}
	
	/**
	 * Get the subscribers for an ServiceAnnouncement
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function getServiceAnnouncementSubscriptions($hook, $type, $return_value, $params) {
		
		$event = elgg_extract('event', $params);
		if (!($event instanceof \Elgg\Notifications\Event)) {
			return;
		}
		
		$entity = $event->getObject();
		if (!($entity instanceof \ServiceAnnouncement)) {
			return;
		}
		
		$subscribers = $entity->getSubscriptions();
		if (empty($subscribers)) {
			return;
		}
		
		$return_value += $subscribers;
		
		return $return_value;
	}
	
	/**
	 * Prepare the content of an ServiceAnnouncement
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareServiceAnnouncementMessage($hook, $type, $return_value, $params) {
		
		if (!($return_value instanceof \Elgg\Notifications\Notification)) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!($event instanceof \Elgg\Notifications\Event) || !($recipient instanceof \ElggUser)) {
			return;
		}
		
		$announcement = $event->getObject();
		if (!($announcement instanceof \ServiceAnnouncement)) {
			return;
		}
		
		switch ($announcement->announcement_type) {
			case 'incident':
				
				$affected_services = $announcement->getServices([
					'limit' => false,
					'batch' => true,
				]);
				$services = [];
				/* @var $affected_services \Service */
				foreach ($affected_services as $service) {
					$services[] = elgg_view('output/url', [
						'text' => $service->getDisplayName(),
						'href' => $service->getURL(),
					]);
				}
				
				$return_value->subject = elgg_echo('service_announcements:notification:service_announcement:incident:subject', [
					$announcement->getDisplayName(),
				], $language);
				$return_value->summary = elgg_echo('service_announcements:notification:service_announcement:incident:summary', [
					$announcement->getDisplayName(),
				], $language);
				$return_value->body = elgg_echo('service_announcements:notification:service_announcement:incident:body', [
					$recipient->getDisplayName(),
					$announcement->getDisplayName(),
					$announcement->description,
					implode(PHP_EOL, $services),
					$announcement->getURL(),
				], $language);
				break;
			default:
				// not supported (yet)
				return;
		}
		
		return $return_value;
	}
}
