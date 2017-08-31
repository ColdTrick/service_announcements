<?php
/**
 * Allow a user to subscribe to announcements for the given service
 *
 * @uses $vars['entity'] the Service being viewed
 */

if (!elgg_is_logged_in()) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Service)) {
	return;
}

$form = elgg_view_form('services/subscriptions', [], $vars);
if (empty($form)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('service_announcements:services:sidebar:subscriptions'), $form);
