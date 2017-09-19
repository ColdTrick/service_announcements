<?php
/**
 * All helper functions are bundled here
 */

/**
 * Guard pages for only service staff (and admins)
 *
 * @return void
 */
function service_announcements_gatekeeper() {
	
	elgg_gatekeeper();
	
	if (service_announcements_is_staff()) {
		return;
	}
	
	register_error(elgg_echo('limited_access'));
	forward(REFERER);
}

/**
 * Check if a user is service staff
 *
 * @param int $user_guid the user to check (default: current user)
 *
 * @return bool
 */
function service_announcements_is_staff($user_guid = 0) {
	static $cache;
	
	$user_guid = (int) $user_guid;
	if ($user_guid < 1) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	if ($user_guid < 1) {
		return false;
	}
	
	$user = get_user($user_guid);
	if (empty($user)) {
		return false;
	}
	
	if ($user->isAdmin()) {
		// admins are always staff
		return true;
	}
	
	if (isset($cache)) {
		return in_array($user_guid, $cache);
	}
	
	// fill the cache
	$cache = elgg_get_entities_from_relationship([
		'type' => 'user',
		'limit' => false,
		'callback' => function($row) {
			return (int) $row->guid;
		},
		'relationship' => SERVICE_ANNOUNCEMENT_STAFF,
		'relationship_guid' => elgg_get_site_entity()->guid,
		'inverse_relationship' => true,
	]);
	
	return in_array($user_guid, $cache);
}

/**
 * Prepare the form vars for add/edit a Service
 *
 * @param Service $entity (optional) the entity to edit
 *
 * @return array
 */
function service_announcements_prepare_service_vars(Service $entity = null) {
	
	// defaults
	$result = [
		'title' => '',
		'description' => '',
		'tags' => [],
		'access_id' => get_default_access(null, [
			'entity_type' => 'object',
			'entity_subtype' => Service::SUBTYPE,
			'container_guid' => elgg_get_site_entity()->guid,
		]),
	];
	
	if ($entity instanceof Service) {
		// edit
		foreach ($result as $name => $value) {
			$result[$name] = $entity->$name;
		}
		
		$result['entity'] = $entity;
	}
	
	$sticky_vars = elgg_get_sticky_values('services/edit');
	if (!empty($sticky_vars)) {
		foreach ($sticky_vars as $name => $value) {
			$result[$name] = $value;
		}
		
		elgg_clear_sticky_form('services/edit');
	}
	
	return $result;
}

/**
 * Prepare the form vars for add/edit a ServiceAnnouncement
 *
 * @param ServiceAnnouncement $entity (optional) the entity to edit
 *
 * @return array
 */
function service_announcements_prepare_service_announcement_vars(ServiceAnnouncement $entity = null) {
	
	// defaults
	$result = [
		'title' => '',
		'description' => '',
		'access_id' => get_default_access(null, [
			'entity_type' => 'object',
			'entity_subtype' => ServiceAnnouncement::SUBTYPE,
			'container_guid' => elgg_get_site_entity()->guid,
		]),
		'tags' => [],
		'startdate' => null,
		'enddate' => null,
		'announcement_type' => '',
		'priority' => '',
		'services' => (array) get_input('services', []),
	];
	
	// edit
	if ($entity instanceof ServiceAnnouncement) {
		foreach ($result as $name => $value) {
			
			switch ($name) {
				case 'services':
					$result[$name] = $entity->getServices([
						'limit' => false,
						'callback' => function($row) {
							return (int) $row->guid;
						},
					]);
					
					break;
				case 'enddate':
					$result[$name] = !empty($entity->$name) ? $entity->name : null;
					break;
				default:
					$result[$name] = $entity->$name;
					break;
			}
		}
		
		$result['entity'] = $entity;
	}
	
	// sticky form vars
	$sticky_values = elgg_get_sticky_values('service_announcements/edit');
	if (!empty($sticky_values)) {
		foreach ($sticky_values as $name => $value) {
			$result[$name] = $value;
		}
		
		elgg_clear_sticky_form('service_announcements/edit');
	}
	
	return $result;
}

/**
 * Register title menu items for a ServiceAnnouncement
 *
 * @param ServiceAnnouncement $entity the announcement
 *
 * @return void
 */
function service_announcements_register_announcement_title_menu_item(ServiceAnnouncement $entity) {
	
	if (!service_announcements_is_staff()) {
		return;
	}
	
	// add status update
	elgg_register_menu_item('title', [
		'name' => 'status_update:update',
		'text' => elgg_echo('service_announcements:menu:title:service_announcement:status:update'),
		'href' => elgg_http_add_url_query_elements('ajax/view/service_announcements/service_announcement/status_update', [
			'type' => 'update',
			'guid' => $entity->guid,
		]),
		'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
		'data-colorbox-opts' => json_encode([
			'maxWidth' => '600px',
		]),
	]);
	
	// close
	if (empty($entity->enddate)) {
		elgg_register_menu_item('title', [
			'name' => 'status_update:close',
			'text' => elgg_echo('close'),
			'href' => elgg_http_add_url_query_elements('ajax/view/service_announcements/service_announcement/status_update', [
				'type' => 'close',
				'guid' => $entity->guid,
			]),
			'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
			'data-colorbox-opts' => json_encode([
				'maxWidth' => '600px',
			]),
		]);
	}
}
