<?php
/**
 * Replacement for {{service_announcements_services}} placeholder
 */

echo elgg_view_field([
	'#type' => 'services',
	'#label' => elgg_echo('service_announcements:wizard:replacements:services:label'),
	'name' => 'wizard_services',
]);
