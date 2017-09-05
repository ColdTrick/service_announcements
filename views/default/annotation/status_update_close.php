<?php
/**
 * The closing annotation for a ServiceAnnouncement
 *
 * @uses $vars['annotation'] the annotation
 */

$annotation = elgg_extract('annotation', $vars);
if (!($annotation instanceof ElggAnnotation)) {
	return;
}

$owner = $annotation->getOwnerEntity();
if (!$owner) {
	return;
}

$icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', [
	'text' => $owner->getDisplayName(),
	'href' => $owner->getURL(),
	'is_trusted' => true,
]);

$menu = elgg_view_menu('annotation', [
	'annotation' => $annotation,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz float-alt',
]);

$value = $annotation->value;
if (empty($value)) {
	$value = elgg_echo('service_announcements:status_update:close:default');
}

$text = elgg_view('output/longtext', [
	'value' => $value,
]);

$friendlytime = elgg_view_friendly_time($annotation->time_created);

$body = <<<HTML
<div class="mbn">
	$menu
	$owner_link
	<span class="elgg-subtext">
		$friendlytime
	</span>
	$text
</div>
HTML;

echo elgg_view_image_block($icon, $body);
