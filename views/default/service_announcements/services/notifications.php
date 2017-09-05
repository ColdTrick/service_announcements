<?php
/**
 * Show a list of currently subscribed services in order to change the subscription settings
 *
 * @uses $vars['entity'] the user to show the list for
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggUser)) {
	return;
}

echo elgg_view('output/longtext', [
	'value' => elgg_echo('service_announcements:services:notifications:description'),
]);

echo elgg_view_form('services/notifications', [], $vars);
