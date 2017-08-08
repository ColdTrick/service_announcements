<?php
/**
 * This file is called during the activation of the plugin
 */

if (get_subtype_id('object', Service::SUBTYPE)) {
	update_subtype('object', Service::SUBTYPE, 'Service');
} else {
	add_subtype('object', Service::SUBTYPE, 'Service');
}

if (get_subtype_id('object', ServiceAnnouncement::SUBTYPE)) {
	update_subtype('object', ServiceAnnouncement::SUBTYPE, 'ServiceAnnouncement');
} else {
	add_subtype('object', ServiceAnnouncement::SUBTYPE, 'ServiceAnnouncement');
}
