<?php

class Service extends ElggObject {
	
	const SUBTYPE = 'service_announcements_service';
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$site = elgg_get_site_entity();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['owner_guid'] = $site->guid;
		$this->attributes['container_guid'] = $site->guid;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::canComment()
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		$friendly_title = elgg_get_friendly_title($this->getDisplayName());
		
		return elgg_normalize_url("services/view/{$this->guid}/{$friendly_title}");
	}
	
	/**
	 * Get the subscription settings for a user
	 *
	 * @param int $user_guid the user to check (default: current user)
	 *
	 * @return false|array the array is scructured as [announcement_type => [notification methods]]
	 * eg. [
	 * 		'maintenace' => ['email', 'site'],
	 * 		'incident' => ['site'],
	 * ]
	 */
	public function getUserSubscriptions($user_guid = 0) {
		
		$user_guid = (int) $user_guid;
		if ($user_guid < 1) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		if ($user_guid < 1) {
			return false;
		}
		
		$announcement_types = [
			'maintenance',
			'incident',
		];
		
		$result = array_combine($announcement_types, [[], []]);
		
		$notification_methods = elgg_get_notification_methods();
		if (empty($notification_methods)) {
			return $result;
		}
		
		$relationships = [];
		foreach ($announcement_types as $type) {
			foreach ($notification_methods as $method) {
				$relationships[] = "notify_{$type}_{$method}";
			}
		}
		
		$dbprefix = elgg_get_config('dbprefix');
		$query = "SELECT r.*
			FROM {$dbprefix}entity_relationships r
			WHERE r.guid_one = :guid_one
			AND r.guid_two = :guid_two
			AND r.relationship IN ('" . implode("', '", $relationships) . "')
		";
		$query_params = [
			':guid_one' => $user_guid,
			':guid_two' => $this->guid,
		];
		
		$relationships = get_data($query, 'row_to_elggrelationship', $query_params);
		if (empty($relationships)) {
			return $result;
		}
		
		/* @var $relationship \ElggRelationship */
		foreach ($relationships as $relationship) {
			list($dummy, $type, $method) = explode('_', $relationship->relationship);
			
			if (!isset($result[$type])) {
				$result[$type] = [];
			}
			
			$result[$type][] = $method;
		}
		
		return $result;
	}
	
	/**
	 * Add a subscription for a user
	 *
	 * @param string $announcement_type the type of the announcement
	 * @param string $method            the method of the notification
	 * @param int    $user_guid         the user to subscribe (default: current user)
	 *
	 * @return bool
	 */
	public function addUserSubscription($announcement_type, $method, $user_guid = 0) {
		
		if (empty($announcement_type) || empty($method)) {
			return false;
		}
		
		$user_guid = (int) $user_guid;
		if ($user_guid < 1) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		if ($user_guid < 1) {
			return false;
		}
		
		return (bool) add_entity_relationship($user_guid, "notify_{$announcement_type}_{$method}", $this->guid);
	}
	
	/**
	 * Add a subscription for a user
	 *
	 * @param string $announcement_type the type of the announcement
	 * @param string $method            the method of the notification
	 * @param int    $user_guid         the user to subscribe (default: current user)
	 *
	 * @return bool
	 */
	public function removeUserSubscription($announcement_type, $method, $user_guid = 0) {
		
		if (empty($announcement_type) || empty($method)) {
			return false;
		}
		
		$user_guid = (int) $user_guid;
		if ($user_guid < 1) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		if ($user_guid < 1) {
			return false;
		}
		
		return (bool) remove_entity_relationship($user_guid, "notify_{$announcement_type}_{$method}", $this->guid);
	}
	
	/**
	 * Get all subscription records for a given announcement type
	 *
	 * @param string $announcement_type the type of the announcement
	 *
	 * @return array in the form [
	 *     user_guid => ['email', 'sms', 'ajax'],
	 * ];
	 */
	public function getSubscriptions($announcement_type) {
		
		$dbprefix = elgg_get_config('dbprefix');
		$query = "SELECT r.*
			FROM {$dbprefix}entity_relationships r
			WHERE r.guid_two = {$this->guid}
			AND r.relationship LIKE 'notify_{$announcement_type}_%'
		";
		
		$relationships = get_data($query, 'row_to_elggrelationship');
		if (empty($relationships)) {
			return [];
		}
		
		$result = [];
		/* @var $relationship ElggRelationship */
		foreach ($relationships as $relationship) {
			$user_guid = $relationship->guid_one;
			list($dummy, $type, $method) = explode('_', $relationship->relationship);
			
			if (!isset($result[$user_guid])) {
				$result[$user_guid] = [];
			}
			
			$result[$user_guid][] = $method;
		}
		
		return $result;
	}
}
