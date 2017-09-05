<?php

$user_guid = (int) get_input('user_guid');
$subscriptions = (array) get_input('subscriptions');

if (empty($user_guid) || empty($subscriptions)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$user = get_user($user_guid);
if (empty($user) || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$result = true;
foreach ($subscriptions as $service_guid => $subscription) {
	$service = get_entity($service_guid);
	if (!($service instanceof Service)) {
		continue;
	}
	
	$result &= $service->setUserSubscriptions($subscription, $user_guid);
}

if (!$result) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('save:success'), REFERER);
