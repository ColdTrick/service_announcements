<?php
/**
 * This file is called during the de-activation of the plugin
 */

update_subtype('object', Service::SUBTYPE);
update_subtype('object', ServiceAnnouncement::SUBTYPE);
