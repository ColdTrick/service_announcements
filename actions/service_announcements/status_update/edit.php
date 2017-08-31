<?php

service_announcements_gatekeeper();

$guid = (int) get_input('guid');
$type = get_input('type');
$text = get_input('text');

if (empty($guid) || empty($type)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof ServiceAnnouncement)) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$result = false;
$allow_empty = false;
switch ($type) {
	case 'close':
		$entity->enddate = time();
		$allow_empty = true;
	default:
		
		if (empty($text) && !$allow_empty) {
			return elgg_error_response(elgg_echo('error:missing_data'));
		}
		
		if (!empty($text)) {
			$result = $entity->annotate("status_update_{$type}", $text, $entity->access_id);
		} else {
			$result = true;
		}
		break;
}

if (empty($result)) {
	return elgg_error_response(elgg_echo('save:fail'));
}

if (is_numeric($result)) {
	// only create river item if there is text
	elgg_create_river_item([
		'view' => 'river/object/service_announcement/status_update',
		'action_type' => 'status_update',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
		'annotation_id' => $result,
	]);
}

return elgg_ok_response('', elgg_echo('save:success'), REFERER);
