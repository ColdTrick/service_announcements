<?php

service_announcements_gatekeeper();

elgg_make_sticky_form('services/edit');

$title = get_input('title');
if (empty($title)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$guid = (int) get_input('guid');
if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!($entity instanceof Service) || !$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
} else {
	$entity = new Service();
	
	if (!$entity->save()) {
		return elgg_error_response(elgg_echo('save:fail'));
	}
}

$entity->title = $title;
$entity->description = get_input('description');
$entity->access_id = (int) get_input('access_id');

$tags = string_to_tag_array(get_input('tags'));
if (!empty($tags)) {
	$entity->tags = $tags;
} else {
	unset($entity->tags);
}

if ((bool) get_input('remove_icon', false)) {
	$entity->deleteIcon();
} else {
	$entity->saveIconFromUploadedFile('icon');
}

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

elgg_clear_sticky_form('services/edit');

return elgg_ok_response('', elgg_echo('save:success'), $entity->getURL());
