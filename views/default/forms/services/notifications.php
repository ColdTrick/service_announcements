<?php
/**
 * Form to manage service notification settings
 *
 * @uses $vars['entity'] The user to list services for
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggUser)) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'user_guid',
	'value' => $entity->guid,
]);

$dbprefix = elgg_get_config('dbprefix');

$services = elgg_get_entities_from_relationship([
	'type' => 'object',
	'subtype' => Service::SUBTYPE,
	'limit' => false,
	'batch' => true,
	'relationship_guid' => $entity->guid,
	'joins' => [
		"JOIN {$dbprefix}objects_entity oe ON e.guid = oe.guid",
	],
	'wheres' => [
		'(r.relationship LIKE "notify_incident_%" OR r.relationship LIKE "notify_maintenance_%")',
	],
	'order_by' => 'oe.title ASC',
	'group_by' => 'e.guid', // needed otherwise results in multiple of the same service
]);
$rows = [];
$notification_methods = elgg_get_notification_methods();
$announcement_types = [
	'maintenance',
	'incident',
];
/* @var $service Service */
foreach ($services as $service) {
	$row = [];
	
	$subscription = $service->getUserSubscriptions($entity->guid);
	
	// title of service
	$row[] = elgg_format_element('td', [], $service->getDisplayName());
	
	// settings
	foreach ($announcement_types as $type) {
		$prefix = elgg_view_field([
			'#type' => 'hidden',
			'name' => "subscriptions[{$service->guid}][{$type}]",
			'value' => 0,
		]);
		
		foreach ($notification_methods as $method) {
			$row[] = elgg_format_element('td', ['class' => 'center'], $prefix . elgg_view_field([
				'#type' => 'checkbox',
				'#class' => 'mbn',
				'name' => "subscriptions[{$service->guid}][{$type}][]",
				'title' => elgg_echo("notification:method:{$method}"),
				'default' => false,
				'value' => $method,
				'checked' => in_array($method, $subscription[$type]),
			]));
			
			$prefix = '';
		}
	}
	
	// add to list
	$rows[] = elgg_format_element('tr', [], implode('', $row));
}

if (empty($rows)) {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('service_announcements:services:notifications:notfound'),
	]);
	return;
}

// make table
$table_content = '';

// add header
$header = [];

// first row
$row = [];
$row[] = elgg_format_element('th', [], '&nbsp;');
foreach ($announcement_types as $type) {
	$row[] = elgg_format_element('th', ['colspan' => 2, 'class' => 'center'], elgg_echo("service_announcements:announcement_type:{$type}"));
}

$header[] = elgg_format_element('tr', [], implode('', $row));

// second row
$row = [];
$row[] = elgg_format_element('th', [], '&nbsp;');
foreach ($announcement_types as $type) {
	
	foreach ($notification_methods as $method) {
		$row[] = elgg_format_element('th', ['class' => 'center'], elgg_echo("notification:method:{$method}"));
	}
}

$header[] = elgg_format_element('tr', [], implode('', $row));

$table_content .= elgg_format_element('thead', [], implode('', $header));

// add body
$table_content .= elgg_format_element('tbody', [], implode('', $rows));

echo elgg_format_element('table', ['class' => 'elgg-table-alt mvm'], $table_content);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
