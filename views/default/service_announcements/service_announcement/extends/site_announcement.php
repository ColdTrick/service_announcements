<?php
/**
 * Extends the site announcement edit form with a linked notification to service announcements
 *
 * @uses $vars['entity'] the site announcement being edited
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggObject) || ($entity->getSubtype() !== SITE_ANNOUNCEMENT_SUBTYPE)) {
	// create
	return;
}

$service_announcements = $entity->getEntitiesFromRelationship([
	'type' => 'object',
	'subtype' => ServiceAnnouncement::SUBTYPE,
	'limit' => 1,
	'relationship' => \ColdTrick\ServiceAnnouncements\SiteAnnouncements::RELATIONSHIP,
	'inverse_relationship' => true,
]);
if (empty($service_announcements)) {
	return;
}

/* @var $service_announcement ServiceAnnouncement */
$service_announcement = $service_announcements[0];
if (!$service_announcement->canEdit()) {
	return;
}

$link = elgg_view('output/url', [
	'text' => $service_announcement->getDisplayName(),
	'href' => $service_announcement->getURL(),
	'is_trusted' => true,
]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('service_announcements:site_announcements:edit:service_announcement', [$link]),
]);