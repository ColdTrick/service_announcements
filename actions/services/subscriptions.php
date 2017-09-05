<?php
/**
 * Store the subscription for a user
 */

$guid = (int) get_input('guid');
$subscriptions = (array) get_input('subscriptions');

$entity = get_entity($guid);
if (!($entity instanceof Service)) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if (!$entity->setUserSubscriptions($subscriptions)) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('save:success'), REFERER);
