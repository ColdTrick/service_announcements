<?php
/**
 * Entity list view for a ServiceAnnouncement
 *
 * @uses $vars['entity']        the ServiceAnnouncement to show
 * @uses $vars['show_services'] (bool) show the affected services (default: true)
 * @uses $vars['show_excerpt']  (bool) show an excerpt of the description of the announcement (default: true)
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ServiceAnnouncement)) {
	return;
}

$icon = '';

$entity_menu = '';
if (!elgg_in_context('widgets')) {
	$entity_menu = elgg_view_menu('entity', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
		'entity' => $entity,
		'handler' => 'service_announcements',
	]);
}

$subtitle = elgg_view('object/service_announcement/byline', $vars);

$body = '';
if (elgg_extract('show_services', $vars, true)) {
	/* @var $service_batch \ElggBatch */
	$service_batch = $entity->getServices([
		'limit' => false,
		'batch' => true,
	]);
	$services = [];
	/* @var $service Service */
	foreach ($service_batch as $service) {
		$services[] = elgg_view('output/url', [
			'text' => $service->getDisplayName(),
			'href' => $service->getURL(),
			'is_trusted' => true,
		]);
	}
	
	if (!empty($services)) {
		$service_output = elgg_echo('service_announcements:service_announcements:edit:services');
		$service_output .= ': ' . implode(', ', $services);
		
		$body .= elgg_format_element('div', [], $service_output);
	}
}

if (elgg_extract('show_excerpt', $vars, true)) {
	$body .= elgg_get_excerpt($entity->description, 150);
}

$params = [
	'metadata' => $entity_menu,
	'subtitle' => $subtitle,
	'content' => $body,
	'icon' => $icon,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
