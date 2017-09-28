<?php

namespace ColdTrick\ServiceAnnouncements;

class Wizard {
	
	/**
	 * Apply the replacement for services
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param string $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void|string
	 */
	public static function applyServicesReplacement($hook, $type, $return_value, $params) {
		
		if (!stristr($return_value, '{{service_announcements_services}}')) {
			return;
		}
		
		$new_output = elgg_view('service_announcements/wizard/services');
		
		return str_ireplace('{{service_announcements_services}}', $new_output, $return_value);
	}
	
	/**
	 * Make subscriptions for selected services
	 *
	 * @param string $event  the name of the event
	 * @param string $type   the type of the event
	 * @param mixed  $entity supplied entity
	 *
	 * @return void
	 */
	public static function saveServicesSubscriptions($event, $type, $entity) {
		
		$services = (array) get_input('wizard_services');
		if (empty($services)) {
			return;
		}
		
		$batch = elgg_get_entities([
			'type' => 'object',
			'subtype' => \Service::SUBTYPE,
			'guids' => $services,
			'limit' => false,
			'batch' => true,
		]);
		/* @var $service \Service */
		foreach ($batch as $service) {
			
			$service->addUserSubscription('incident', 'email');
			$service->addUserSubscription('maintenance', 'email');
		}
	}
}
