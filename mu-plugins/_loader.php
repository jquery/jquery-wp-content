<?php

$live_domain = JQUERY_LIVE_DOMAIN;
if ( 0 === validate_file( $live_domain ) ) {
	foreach ( (array) glob( dirname( __FILE__ ) . "/$live_domain/*.php" ) as $domain_specific_file ) {
		require_once( $domain_specific_file );
	}
}
unset( $live_domain, $domain_specific_file );

require_once WPMU_PLUGIN_DIR . '/relevanssi/relevanssi.php';
require 'disable-emojis/disable-emojis.php';
