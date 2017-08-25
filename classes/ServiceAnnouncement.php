<?php

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
}
