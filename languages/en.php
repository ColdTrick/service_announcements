<?php

return [
	
	// generic
	'item:object:service_announcements_service' => "Service",
	'item:object:service_announcement' => "Service announcement",
	
	'services:add' => "Add service",
	'service_announcements:add' => "Add service announcement",
	
	'service_announcements:edit:icon' => "Icon",
	'service_announcements:edit:icon:limit' => "Maximum allowed file size is %s",
	'service_announcements:edit:remove_icon' => "Remove current icon",
	'service_announcements:edit:remove_icon:help' => "You can choose to remove the existing icon. This checkbox take president over uploading a new icon.",
	
	'service_announcements:service_announcements:error:no_services' => "No services were found in the system, create at least one service before continueing",
	
	// breadcrumbs
	'service_announcements:breadcrumb:service_announcements:all' => "Service announcements",
	'service_announcements:breadcrumb:services:all' => "Services",
	'service_announcements:breadcrumb:services:notifications' => "Service notifications",
	
	// menus
	'service_announcements:menu:site:service_announcements' => 'Service announcements',
	
	'service_announcements:menu:page:service_announcements' => 'Service announcements',
	'service_announcements:menu:page:services' => 'Services',
	'service_announcements:menu:page:services:notifications' => 'Service notifications',
	
	'service_announcements:menu:title:service_announcement:status:update' => "Status update",
	'service_announcements:menu:title:service_announcement:status:close' => "Close",
	
	'service_announcements:menu:filter:past' => "Past",
	'service_announcements:menu:filter:calendar' => "Calendar",
	'service_announcements:menu:filter:staff' => "Staff",
	
	'service_announcements:menu:user_hover:staff:assign' => "Make service announcements staff",
	'service_announcements:menu:user_hover:staff:unassign' => "Remove service announcments staff",
	
	// pages
	'service_announcements:service_announcements:all' => "All service announcements",
	'service_announcements:service_announcements:past' => "Past service announcements",
	'service_announcements:service_announcements:calendar' => "All service announcements",
	'service_announcements:service_announcements:add' => "Add a service announcement",
	'service_announcements:service_announcements:edit' => "Edit service announcement: %s",
	'service_announcements:service_announcements:status_update' => "Status update for: %s",
	'service_announcements:service_announcements:staff' => "Service announcements staff",
	
	'service_announcements:services:all' => "All services",
	'service_announcements:services:add' => 'Add a service',
	'service_announcements:services:edit' => 'Edit service: %s',
	'service_announcements:services:notifications' => 'Service notification settings',
	
	// service announcements
	'service_announcements:service_announcements:edit:services' => "Affected services",
	'service_announcements:service_announcements:edit:services:help' => "Please select the services affected.",
	'service_announcements:service_announcements:startdate' => "Start date",
	'service_announcements:service_announcements:enddate' => "End date",
	
	'service_announcements:status_update:text' => "Status text",
	'service_announcements:status_update:close:default' => "The service announcement was closed",
	'service_announcements:service_announcements:status_update' => "Status updates",
	
	// services
	'service_announcements:service:announcements:current' => "Current announcements",
	'service_announcements:service:announcements:past' => "Past announcements",
	'service_announcements:service:announcements:upcomming' => "Upcomming announcements",
	
	'service_announcements:services:sidebar:subscriptions' => "Keep me up-to-date",
	
	'service_announcements:services:notifications:description' => "Below is a list of the currently subscribed services. Here you can easily change the subscriptions.",
	'service_announcements:services:notifications:notfound' => "No service subscriptions could be found.",
	
	// announcement types
	'service_announcements:announcement_type' => "Announcement type",
	'service_announcements:announcement_type:maintenance' => "Maintenance",
	'service_announcements:announcement_type:incident' => "Incident",
	
	// priority
	'service_announcements:priority' => "Priority",
	'service_announcements:priority:low' => "Low",
	'service_announcements:priority:medium' => "Medium",
	'service_announcements:priority:high' => "High",
	'service_announcements:priority:critical' => "Critical",
	
	// calendar
	'service_announcements:calendar:today' => 'Today',
	'service_announcements:calendar:month' => 'Month',
	'service_announcements:calendar:week' => 'Week',
	'service_announcements:calendar:day' => 'Day',
	
	// river
	'river:create:object:service_announcement' => "%s made a service announcement: %s",
	'river:status_update:object:service_announcement' => "%s posted an update on: %s",
	
	// notifications
	// status update: update
	'service_announcements:notification:status_update:update:subject' => "A new status update has been posted for: %s",
	'service_announcements:notification:status_update:update:summary' => "A new status update has been posted for: %s",
	'service_announcements:notification:status_update:update:body' => "Hi %s,

A new status update has been posted for: %s

%s

To view the status update, click here:
%s
",
	// status update: close
	'service_announcements:notification:status_update:close:subject' => "The service announcement '%s' was closed",
	'service_announcements:notification:status_update:close:summary' => "The service announcement '%s' was closed",
	'service_announcements:notification:status_update:close:body' => "Hi %s,

The service announcement '%s' was closed:

%s

To view the status update, click here:
%s
",
	'service_announcements:notification:status_update:close:body:no_value' => "Hi %s,

The service announcement '%s' was closed.

To view the status update, click here:
%s
",
	// service announcement: incident
	'service_announcements:notification:service_announcement:incident:subject' => "A new incident was created: %s",
	'service_announcements:notification:service_announcement:incident:summary' => "A new incident was created: %s",
	'service_announcements:notification:service_announcement:incident:body' => "Hi %s,

A new incident was created: %s

%s

This incident affects the following services:
%s

To view the incident, click here:
%s
",
	
	// service announcement: maintenace (scheduled)
	'service_announcements:notification:service_announcement:maintenace:scheduled:site:subject' => "New scheduled maintenance: %s",
	'service_announcements:notification:service_announcement:maintenace:scheduled:site:summary' => "New scheduled maintenance: %s",
	'service_announcements:notification:service_announcement:maintenace:scheduled:site:body' => "Hi %s,

New maintenance has been scheduled: %s

%s

This maintenance affects the following services:
%s

To view the maintenance, click here:
%s
",
	
	'service_announcements:notification:service_announcement:maintenace:scheduled:email:subject' => "Scheduled maintenance for next week",
	'service_announcements:notification:service_announcement:maintenace:scheduled:email:body' => "Hi %s,

The following maintenance has been scheduled for next week:

%s
",
	
	// actions
	// status update delete
	'service_announcements:action:service_announcement:status_update:delete:error' => "An error occured while deleting the status update",
	'service_announcements:action:service_announcement:status_update:delete:success' => "The status update was deleted",
	
	// toggle staff
	'service_announcments:action:service_announcements:admin:toggle_staff:error:invalid_user' => "Invalid user supplied for this action",
	'service_announcments:action:service_announcements:admin:toggle_staff:error:unassign' => "An error occured while removing %s from the service announcements staff",
	'service_announcments:action:service_announcements:admin:toggle_staff:error:assign' => "An error occured while assigning %s to the service announcements staff",
	'service_announcments:action:service_announcements:admin:toggle_staff:success:unassign' => "%s is no longer part of the service announcements staff",
	'service_announcments:action:service_announcements:admin:toggle_staff:success:assign' => "%s is now part of the service announcements staff",
	
	// service announcement
	'service_announcments:action:service_announcements:edit:error:enddate' => "The enddate can't be before the startdate",
];
