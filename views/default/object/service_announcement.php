<?php
/**
 * View a service announcement
 *
 * @uses $vars['entity'] the service announcement
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ServiceAnnouncement)) {
	return;
}

if (elgg_extract('full_view', $vars, false)) {
	echo elgg_view('object/service_announcement/full', $vars);
} else {
	echo elgg_view('object/service_announcement/list', $vars);
}
