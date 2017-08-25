<?php
/**
 * ServiceAnnoucement river view.
 */

/* @var ElggRiverItem $item */
$item = elgg_extract('item', $vars);

$object = $item->getObjectEntity();

$vars['message'] = elgg_get_excerpt($object->description);

$affected = elgg_echo('service_announcements:service_announcements:edit:services') . ': <br />';
foreach ($object->getServices(['limit' => false, 'batch' => true]) as $service) {
	$affected .= elgg_view('output/url', [
		'text' => $service->getDisplayName(),
		'href' => $service->getURL(),
		'is_trusted' => true,
	]);
	$affected .= '<br />';
}

$vars['attachments'] = $affected;

echo elgg_view('river/elements/layout', $vars);
