<?php

namespace ColdTrick\ServiceAnnouncements;

class SiteAnnouncements {
	
	const RELATIONSHIP = 'sa_related';
	
	/**
	 * Listen to the Service Announcement update to check integration with Site Announcements
	 *
	 * @param string      $event  the name of the event
	 * @param string      $type   the type of the event
	 * @param \ElggObject $entity supplied entity
	 *
	 * @return void
	 */
	public static function afterServiceAnnouncementUpdate($event, $type, $entity) {
		
		if (!($entity instanceof \ServiceAnnouncement) || !elgg_is_active_plugin('site_announcements')) {
			return;
		}
		
		if (elgg_get_plugin_setting('site_announcements_critical_incident', 'service_announcements') !== 'yes') {
			// no integration
			return;
		}
		
		if ($entity->announcement_type !== 'incident') {
			// only for incidents
			return;
		}
		
		$site_announcement = self::getLinkedSiteAnnouncement($entity);
		if (empty($site_announcement)) {
			// no yet linked
			if ($entity->priority !== 'critical') {
				// no need to link
				return;
			}
			
			// create new Site announcement
			$site_announcement = self::createSiteAnnouncement($entity);
		}
		
		if (empty($site_announcement)) {
			// something went wrong
			return;
		}
		
		self::updateSiteAnnouncement($entity, $site_announcement);
	}
	
	/**
	 * Get the linked Site announcement for the given announcement
	 *
	 * @param \ServiceAnnouncement $entity the announcement to check for
	 *
	 * @return false|\ElggObject
	 */
	protected static function getLinkedSiteAnnouncement(\ServiceAnnouncement $entity) {
		
		$ia = elgg_set_ignore_access(true);
		
		$site_announcements = $entity->getEntitiesFromRelationship([
			'type' => 'object',
			'subtype' => SITE_ANNOUNCEMENT_SUBTYPE,
			'limit' => 1,
			'relationship' => self::RELATIONSHIP,
		]);
		
		elgg_set_ignore_access($ia);
		
		if (empty($site_announcements)) {
			return false;
		}
		
		return $site_announcements[0];
	}
	
	/**
	 * Create a linked Site announcement for the given announcement
	 *
	 * @param \ServiceAnnouncement $entity the announcement to make for
	 *
	 * @return false|\ElggObject
	 */
	protected static function createSiteAnnouncement(\ServiceAnnouncement $entity) {
		
		$site = elgg_get_site_entity();
		
		$site_announcement = new \ElggObject();
		$site_announcement->subtype = SITE_ANNOUNCEMENT_SUBTYPE;
		$site_announcement->owner_guid = $site->guid;
		$site_announcement->container_guid = $site->guid;
		$site_announcement->access_id = $entity->access_id;
		
		$description = $entity->getDisplayName();
		$description .= ' ' . elgg_view('output/url', [
			'text' => elgg_echo('more_info'),
			'href' => $entity->getURL(),
			'is_trusted' => true,
		]);
		
		$site_announcement->description = $description;
		
		$site_announcement->startdate = $entity->startdate;
		$site_announcement->enddate = $entity->enddate ?: strtotime('+1day', $entity->startdate);
		$site_announcement->announcement_type = 'warning';
		
		$ia = elgg_set_ignore_access(true);
		
		if (!$site_announcement->save()) {
			elgg_set_ignore_access($ia);
			
			return false;
		}
		
		$entity->addRelationship($site_announcement->guid, self::RELATIONSHIP);
		
		elgg_set_ignore_access($ia);
		
		return $site_announcement;
	}
	
	/**
	 * Update the linked Site announcement for the given announcement
	 *
	 * @param \ServiceAnnouncement $entity            the announcement to update from
	 * @param \ElggObject          $site_announcement the site announcement to update
	 *
	 * @return bool
	 */
	protected static function updateSiteAnnouncement(\ServiceAnnouncement $entity, \ElggObject $site_announcement) {
		
		$ia = elgg_set_ignore_access(true);
		
		$site_announcement->access_id = $entity->access_id;
		
		$description = $entity->getDisplayName();
		$description .= ' ' . elgg_view('output/url', [
			'text' => elgg_echo('more_info'),
			'href' => $entity->getURL(),
			'is_trusted' => true,
		]);
		
		$site_announcement->description = $description;
		
		$site_announcement->startdate = $entity->startdate;
		
		if ($entity->priority === 'critical') {
			$site_announcement->enddate = $entity->enddate ?: strtotime('+1day', $entity->startdate);
		} else {
			$site_announcement->enddate = time();
		}
		
		if (!$site_announcement->save()) {
			elgg_set_ignore_access($ia);
			
			return false;
		}
		
		elgg_set_ignore_access($ia);
		
		return true;
	}
}
