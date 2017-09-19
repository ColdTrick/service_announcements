<?php
/**
 * Create a byline for ServiceAnnoucements
 *
 * @uses $vars['entity'] the ServiceAnnoucement
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ServiceAnnouncement)) {
	return;
}

$byline = [];

if (!empty($entity->announcement_type)) {
	$type = $entity->announcement_type;
	if (elgg_language_key_exists("service_announcements:announcement_type:{$type}")) {
		$type = elgg_echo("service_announcements:announcement_type:{$type}");
	}
	
	$byline[] = elgg_format_element('span', [
		'class' => [
			'service-announcements-announcement-type',
			"service-announcements-announcement-type-{$entity->announcement_type}",
		],
	], $type);
}

if (!empty($entity->startdate)) {
	$start = elgg_echo('service_announcements:service_announcements:startdate');
	$start .= ': ' . date('d/m/Y', $entity->startdate);
	
	$byline[] = elgg_format_element('span', [], $start);
}

if (!empty($entity->enddate)) {
	$end = elgg_echo('service_announcements:service_announcements:enddate');
	$end .= ': ' . date('d/m/Y', $entity->enddate);
	
	$byline[] = elgg_format_element('span', [], $end);
}

echo implode(' ', $byline);
