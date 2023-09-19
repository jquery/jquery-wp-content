<?php

// Plugins enabled everywhere for local dev (jquery-wp-docker) and old WP-MU servers.
//
// For production, plugin enablement is managed via Puppet:
// https://github.com/jquery/infrastructure-puppet/blob/staging/modules/profile/manifests/wordpress/docs.pp

require_once __DIR__ . '/../plugins/disable-emojis/disable-emojis.php';
require_once __DIR__ . '/../plugins/gilded-wordpress/gilded-wordpress.php';
require_once __DIR__ . '/../plugins/jquery-actions.php';
require_once __DIR__ . '/../plugins/jquery-filters.php';
require_once __DIR__ . '/../plugins/jquery-tags-on-pages.php';
require_once __DIR__ . '/../plugins/redirects.php';
