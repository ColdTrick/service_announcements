<?php

/**
 * A service announcement related to services
 *
 * @property string $priority          the priority of the announcement
 * @property string $announcement_type the type of the announcement
 * @property int    $startdate         startdate of the announcement
 * @property int    $enddate           enddate of the announcement
 * @property int[]  $contact_user      an array of user_guids which are the contact person for this announcement
 */
class ServiceAnnouncement extends ElggObject {
	
	const SUBTYPE = 'service_announcement';
	const AFFECTED_SERVICES = 'affected';
	
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
		
		return elgg_normalize_url("service_announcements/view/{$this->guid}/{$friendly_title}");
	}
	
	/**
	 * Set the services affected by this announcement
	 *
	 * @param int[] $services
	 *
	 * return bool
	 */
	public function setServices($services) {
		
		if (!is_array($services)) {
			return false;
		}
		
		$existing_services = $this->getServices([
			'limit' => false,
			'callback' => function($row) {
				return (int) $row->guid;
			},
		]);
		
		$result = true;
		
		// remove services
		$remove_services = array_diff($existing_services, $services);
		foreach ($remove_services as $service_guid) {
			$result &= $this->removeRelationship($service_guid, self::AFFECTED_SERVICES);
		}
		
		// add new services
		$add_services = array_diff($services, $existing_services);
		foreach ($add_services as $service_guid) {
			$result &= $this->addRelationship($service_guid, self::AFFECTED_SERVICES);
		}
		
		return $result;
	}
	
	/**
	 * Return affected services
	 *
	 * @param array $options additional options for elgg_get_entities_from_relationship
	 *
	 * @see elgg_get_entities_from_relationship()
	 *
	 * @return bool|int|Service[]
	 */
	public function getServices($options = []) {
		
		$defaults = [
			'type' => 'object',
			'subtype' => Service::SUBTYPE,
			'relationship' => self::AFFECTED_SERVICES,
		];
		
		$options = array_merge($options, $defaults);
		
		return $this->getEntitiesFromRelationship($options);
	}
	
	/**
	 * Get the subscription records for this service announcement
	 *
	 * @return array in the form [
	 *     user_guid => ['email', 'sms', 'ajax'],
	 * ];
	 */
	public function getSubscriptions() {
		$result = elgg_get_subscriptions_for_container($this->guid);
		
		$ia = elgg_set_ignore_access(true);
		
		$batch = $this->getServices([
			'limit' => false,
			'batch' => true,
		]);
		/* @var $service \Service */
		foreach ($batch as $service) {
			$subs = $service->getSubscriptions($this->announcement_type);
			if (empty($subs)) {
				continue;
			}
			
			$result += $subs;
		}
		
		elgg_set_ignore_access($ia);
		
		return $result;
	}

	/**
	 * Returns the timestamp for the start of the announcement
	 *
	 * @return int the timestamp
	 */
	public function getStartTimestamp() {
		return $this->startdate;
	}
	
	/**
	 * Returns the timestamp for the end of the announcement
	 *
	 * @return int the timestamp
	 */
	public function getEndTimestamp() {
		return $this->enddate;
	}
	
	/**
	 * Returns the startdate and time for the announcement formatted as ISO-8601
	 *
	 * @param string $format provide a format for the date
	 *
	 * @see https://en.wikipedia.org/wiki/ISO_8601
	 *
	 * @return string a formatted date string
	 */
	public function getStartDate($format = 'c') {
		return gmdate($format, $this->getStartTimestamp());
	}
	
	/**
	 * Returns the startdate and time for the announcement formatted as ISO-8601
	 *
	 * @param string $format provide a format for the date
	 *
	 * @see https://en.wikipedia.org/wiki/ISO_8601
	 *
	 * @return string a formatted date string
	 */
	public function getEndDate($format = 'c') {
		return gmdate($format, $this->getEndTimestamp());
	}
	
	/**
	 * Returns if the announcement is spanning multiple days
	 *
	 * @return bool is it a multiday announcement
	 */
	public function isMultiDay() {
		$start = $this->getStartTimestamp();
		$end = $this->getEndTimestamp();
		
		$diff = $end - $start;
		
		if ($diff > (60 * 60 * 24)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns if the announcement is finished before the current time
	 *
	 * @return bool is the announcement is finished before now
	 */
	public function isFinished() {
		$end = $this->getEndTimestamp();
		if (empty($end)) {
			return false;
		}
		
		return $end < time();
	}
}
