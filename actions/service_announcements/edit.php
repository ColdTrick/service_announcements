<?php

elgg_make_sticky_form('service_announcements/edit');

$title = get_input('title');
$services = (array) get_input('services');
$startdate = (int) get_input('startdate');

if (empty($title) || empty($services) || empty($startdate)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$enddate = get_input('enddate');
if (!empty($enddate)) {
	$enddate = (int) $enddate;
	if ($enddate < $startdate) {
		return elgg_error_response(elgg_echo('service_announcments:action:service_announcements:edit:error:enddate'));
	}
}

if (!empty($startdate)) {
	$tempdate = new DateTime(gmdate('d-m-Y H:i', $startdate));
	$startdate = $tempdate->getTimestamp();
}

if (!empty($enddate)) {
	$tempdate = new DateTime(gmdate('d-m-Y H:i', $enddate));
	$enddate = $tempdate->getTimestamp();
}

$guid = (int) get_input('guid');
$new = false;
if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!($entity instanceof ServiceAnnouncement) || !$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
} else {
	$new = true;
	$entity = new ServiceAnnouncement();
	
	if (!$entity->save()) {
		return elgg_error_response(elgg_echo('save:fail'));
	}
}

$entity->title = $title;
$entity->description = get_input('description');
$entity->access_id = (int) get_input('access_id');

$entity->tags = get_input('tags') ? string_to_tag_array(get_input('tags')) : null;

$entity->startdate = $startdate;
if (!$new && (empty($entity->enddate) || $entity->enddate > time()) && !empty($enddate) && ($enddate < time())) {
	// closing the announcement using the edit form
	$entity->statusUpdate('close');
}
$entity->enddate = !empty($enddate) ? $enddate : 0;

$entity->setServices($services);

$entity->contact_user = (array) get_input('contact_user');
$entity->priority = get_input('priority') ?: null;
$entity->announcement_type = get_input('announcement_type') ?: null;

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

elgg_clear_sticky_form('service_announcements/edit');

if ($new) {
	elgg_create_river_item([
		'view' => 'river/object/service_announcement/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
	]);
}

return elgg_ok_response('', elgg_echo('save:success'), $entity->getURL());
