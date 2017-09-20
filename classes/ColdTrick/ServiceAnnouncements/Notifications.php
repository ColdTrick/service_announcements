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
	
	/**
	 * Cron job to send out notifications about upcomming maintenace
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param mixed  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void
	 */
	public static function scheduledMaintenanceNotifications($hook, $type, $return_value, $params) {
		
		echo 'Starting Service Announcements scheduled maintenance' . PHP_EOL;
		elgg_log('Starting Service Announcements scheduled maintenance', 'NOTICE');
		
		$time = (int) elgg_extract('time', $params, time());
		$ia = elgg_set_ignore_access(true);
		
		/* @var $announcement_batch \ElggBatch */
		$announcement_batch = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => \ServiceAnnouncement::SUBTYPE,
			'limit' => false,
			'batch' => true,
			'metadata_name_value_pairs' => [
				[
					'name' => 'startdate',
					'value' => $time,
					'operand' => '>=',
				],
				[
					'name' => 'startdate',
					'value' => strtotime('+1 week', $time),
					'operand' => '<',
				],
			],
			'order_by_metadata' => [
				'name' => 'startdate',
				'direction' => 'ASC',
				'as' => 'integer',
			],
		]);
		
		$email_summary = [];
		$email_recipients = [];
		/* @var $announcement \ServiceAnnouncement */
		foreach ($announcement_batch as $announcement) {
			$subscriptions = $announcement->getSubscriptions();
			if (empty($subscriptions)) {
				continue;
			}
			
			$notify_params = [
				'object' => $announcement,
				'action' => 'scheduled',
			];
			
			foreach ($subscriptions as $user_guid => $methods) {
				if (empty($methods)) {
					// shouldn't happen
					continue;
				}
				
				foreach ($methods as $method) {
					switch ($method) {
						case 'email':
							// store for later summary e-mail
							if (!isset($email_summary[$announcement->guid])) {
								$email_summary[$announcement->guid] = $announcement;
							}
							
							if (!isset($email_recipients[$user_guid])) {
								$email_recipients[$user_guid] = [];
							}
							
							$email_recipients[$user_guid][] = $announcement->guid;
							
							break;
						case 'site':
							$recipient = get_user($user_guid);
							if (empty($recipient)) {
								continue;
							}
							
							$services = $announcement->getServices([
								'limit' => false,
								'batch' => true,
							]);
							$affected_services = [];
							/* @var $service \Service */
							foreach ($services as $service) {
								$affected_services[] = elgg_view('output/url', [
									'text' => $service->getDisplayName(),
									'href' => $service->getURL(),
								]);
							}
							
							// direct send site notification
							$subject = elgg_echo('service_announcements:notification:service_announcement:maintenace:scheduled:site:subject', [$announcement->getDisplayName()]);
							$notify_params['summary'] = elgg_echo('service_announcements:notification:service_announcement:maintenace:scheduled:site:summary', [$announcement->getDisplayName()]);
							$body = elgg_echo('service_announcements:notification:service_announcement:maintenace:scheduled:site:body', [
								$recipient->getDisplayName(),
								$announcement->getDisplayName(),
								$announcement->description,
								implode(PHP_EOL, $affected_services),
								$announcement->getURL(),
							]);
							
							notify_user($user_guid, $announcement->owner_guid, $subject, $body, $notify_params, ['site']);
							
							// small cleanup
							unset($services);
							unset($affected_services);
							break;
					}
				}
			}
		}
		
		if (empty($email_recipients)) {
			// nobody to notify
			echo 'Done with Service Announcements scheduled maintenance' . PHP_EOL;
			elgg_log('Done with Service Announcements scheduled maintenance', 'NOTICE');
			
			elgg_set_ignore_access($ia);
			
			return;
		}
		
		$site = elgg_get_site_entity();
		
		// make email summaries for each user
		foreach ($email_recipients as $user_guid => $announcement_guids) {
			$recipient = get_user($user_guid);
			if (empty($recipient)) {
				continue;
			}
			$summary = [];
			
			foreach ($announcement_guids as $guid) {
				/* @var $announcement \ServiceAnnouncement */
				$announcement = $email_summary[$guid];
				
				// make a service announcement info block
				$a_summary = $announcement->getDisplayName() . PHP_EOL;
				$a_summary .= elgg_echo('service_announcements:service_announcements:startdate') . ': ' . $announcement->getStartDate('j-n-Y G:i') . PHP_EOL;
				if (!empty($announcement->enddate)) {
					$a_summary .= elgg_echo('service_announcements:service_announcements:enddate') . ': ' . $announcement->getEndDate('j-n-Y G:i') . PHP_EOL;
				}
				
				$services = $announcement->getServices([
					'limit' => false,
					'batch' => true,
				]);
				$affected_services = [];
				/* @var $service \Service */
				foreach ($services as $service) {
					$affected_services[] = elgg_view('output/url', [
						'text' => $service->getDisplayName(),
						'href' => $service->getURL(),
					]);
				}
				$a_summary .= elgg_echo('service_announcements:service_announcements:edit:services') . ': ';
				$a_summary .= implode(', ', $affected_services) . PHP_EOL;
				$a_summary .= $announcement->getURL();
				
				// add summary to list
				$summary[] = $a_summary;
			}
			
			$subject = elgg_echo('service_announcements:notification:service_announcement:maintenace:scheduled:email:subject');
			$body = elgg_echo('service_announcements:notification:service_announcement:maintenace:scheduled:email:body', [
				$recipient->getDisplayName(),
				implode(PHP_EOL . PHP_EOL, $summary),
			]);
			
			notify_user($user_guid, $site->guid, $subject, $body, [], ['email']);
		}
		
		echo 'Done with Service Announcements scheduled maintenance' . PHP_EOL;
		elgg_log('Done with Service Announcements scheduled maintenance', 'NOTICE');
		
		elgg_set_ignore_access($ia);
	}
}
