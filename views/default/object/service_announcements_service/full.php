<?php
/**
 * Entity full view for a Service
 *
 * @uses $vars['entity'] the Service to show
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Service)) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'tiny');

// prepare summary
$entity_menu = '';
if (!elgg_in_context('widgets')) {
	$entity_menu = elgg_view_menu('entity', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
		'entity' => $entity,
		'handler' => 'services',
	]);
}

$params = [
	'metadata' => $entity_menu,
	'title' => false,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

// prepare body
$body = elgg_view('output/longtext', [
	'value' => $entity->description,
]);

// current announcements (incidents/maintenance)

// past incidents

// upcomming maintenace

// show full view
echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'icon' => $icon,
	'summary' => $summary,
	'body' => $body,
]);
