<?php

list( $live_domain ) = explode( '/', JQUERY_LIVE_SITE, 2 );
if ( 0 === validate_file( $live_domain ) ) {
	foreach ( (array) glob( dirname( __FILE__ ) . "/$live_domain/*.php" ) as $domain_specific_file ) {
		require_once( $domain_specific_file );
	}
	list( $subdomain ) = $live_domain = explode( '.', $live_domain );
	if ( count( $live_domain ) > 2 ) {
		foreach ( (array) glob( dirname( __FILE__ ) . "/$subdomain-sites/*.php" ) as $type_specific_file ) {
			require_once( $type_specific_file );
		}
	}
}
unset( $live_domain, $subdomain, $domain_specific_file, $type_specific_file );
