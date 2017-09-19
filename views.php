<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}
return [
	// viewtype
	'default' => [
		// calendar
		'js/fullcalendar.js' => $composer_path . 'vendor/bower-asset/fullcalendar/dist/fullcalendar.min.js',
		'js/moment.js' => $composer_path . 'vendor/bower-asset/moment/min/moment-with-locales.min.js',
		'css/service_announcements/fullcalendar.css' => $composer_path . 'vendor/bower-asset/fullcalendar/dist/fullcalendar.min.css',
		
		// datetime picker
		'jquery-ui-timepicker/' => __DIR__ . '/vendors/jquery-ui-timepicker/',
	],
];
