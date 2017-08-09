<?php
/**
 * Entity view for a Service
 *
 * @uses $vars['entity'] the Service to show
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Service)) {
	return;
}

if (elgg_extract('full_view', $vars, false)) {
	echo elgg_view('object/service_announcements_service/full', $vars);
} else {
	echo elgg_view('object/service_announcements_service/list', $vars);
}
