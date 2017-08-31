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

foreach ($subscriptions as $type => $methods) {
	if (!is_array($methods)) {
		$subscriptions[$type] = [];
	}
}

$current_subscriptions = $entity->getUserSubscriptions();

$removed = [];
$added = [];
foreach ($current_subscriptions as $type => $methods) {
	$removed[$type] = array_diff($methods, $subscriptions[$type]);
	$added[$type] = array_diff($subscriptions[$type], $methods);
}

$result = true;
foreach ($removed as $type => $methods) {
	if (empty($methods)) {
		continue;
	}
	
	foreach ($methods as $method) {
		$result &= $entity->removeUserSubscription($type, $method);
	}
}

foreach ($added as $type => $methods) {
	if (empty($methods)) {
		continue;
	}
	
	foreach ($methods as $method) {
		$result &= $entity->addUserSubscription($type, $method);
	}
}

if (!$result) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('save:success'), REFERER);
