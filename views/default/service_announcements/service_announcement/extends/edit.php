<?php
/**
 * Extends the service announcement edit form with a linked notification to site announcements
 *
 * @uses $vars['entity'] the service announcement being edited
 */

if (!elgg_is_active_plugin('site_announcements')) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ServiceAnnouncement)) {
	// create
	return;
}

$site_announcement = \ColdTrick\ServiceAnnouncements\SiteAnnouncements::getLinkedSiteAnnouncement($entity);
if (empty($site_announcement) || !$site_announcement->canEdit()) {
	// not linked
	return;
}

$link = elgg_view('output/url', [
	'text' => elgg_echo('item:object:site_announcement'),
	'href' => "announcements/edit/{$site_announcement->guid}",
	'is_trusted' => true,
]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('service_announcements:service_announcements:edit:site_announcement', [$link]),
]);
