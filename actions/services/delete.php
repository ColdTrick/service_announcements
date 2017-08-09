<?php

service_announcements_gatekeeper();

$guid = (int) get_input('guid');
$entity = get_entity($guid);
if (!$entity instanceof Service) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$entity->canDelete()) {
	return elgg_error_response(elgg_echo('entity:delete:permission_denied'));
}

$title = $entity->getDisplayName();

if ($entity->delete()) {
	return elgg_ok_response('', elgg_echo('entity:delete:success', [$title]), 'services/all');
}

return elgg_error_response(elgg_echo('entity:delete:fail', [$title]));
