<?php

service_announcements_gatekeeper();

$id = (int) get_input('id');

$annotation = elgg_get_annotation_from_id($id);
if (!($annotation instanceof ElggAnnotation)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = $annotation->getEntity();
if (!($entity instanceof ServiceAnnouncement)) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if (!$annotation->delete()) {
	return elgg_error_response(elgg_echo('service_announcements:action:service_announcement:status_update:delete:error'));
}

return elgg_ok_response('', elgg_echo('service_announcements:action:service_announcement:status_update:delete:success'), REFERER);
