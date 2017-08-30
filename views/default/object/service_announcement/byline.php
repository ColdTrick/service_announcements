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
