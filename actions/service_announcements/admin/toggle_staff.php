<?php

$user_guid = (int) get_input('user_guid');
$user = get_user($user_guid);
if (empty($user)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if ($user->guid === elgg_get_logged_in_user_guid() || $user->isAdmin()) {
	return elgg_error_response(elgg_echo('service_announcments:action:service_announcements:admin:toggle_staff:error:invalid_user'));
}

$site = elgg_get_site_entity();

if (service_announcements_is_staff($user->guid)) {
	// remove staff role
	if (remove_entity_relationship($user->guid, SERVICE_ANNOUNCEMENT_STAFF, $site->guid)) {
		return elgg_ok_response('', elgg_echo('service_announcments:action:service_announcements:admin:toggle_staff:success:unassign', [$user->getDisplayName()]), REFERER);
	}
	
	return elgg_error_response(elgg_echo('service_announcments:action:service_announcements:admin:toggle_staff:error:unassign', [$user->getDisplayName()]));
}

// assign staff role
if (add_entity_relationship($user->guid, SERVICE_ANNOUNCEMENT_STAFF, $site->guid)) {
	return elgg_ok_response('', elgg_echo('service_announcments:action:service_announcements:admin:toggle_staff:success:assign', [$user->getDisplayName()]), REFERER);
}

return elgg_error_response(elgg_echo('service_announcments:action:service_announcements:admin:toggle_staff:error:assign', [$user->getDisplayName()]));
