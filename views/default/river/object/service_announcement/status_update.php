<?php
/**
 * ServiceAnnoucement river view.
 */

/* @var ElggRiverItem $item */
$item = elgg_extract('item', $vars);

$object = $item->getObjectEntity();
$annotation = $item->getAnnotation();

$vars['message'] = elgg_get_excerpt($annotation->value);

echo elgg_view('river/elements/layout', $vars);
