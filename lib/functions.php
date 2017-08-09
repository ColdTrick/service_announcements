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
	
	$cache = [];
	
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
