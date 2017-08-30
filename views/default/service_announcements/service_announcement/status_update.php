<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ServiceAnnouncement) || !service_announcements_is_staff()) {
	return;
}

$type = elgg_extract('type', $vars);

$form = elgg_view_form('service_announcements/status_update', [], [
	'entity' => $entity,
	'type' => $type,
]);

echo elgg_view_module('info', elgg_echo('service_announcements:service_announcements:status_update', [$entity->getDisplayName()]), $form);
