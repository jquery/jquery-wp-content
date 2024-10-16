<?php

// Allow inline scripts and styles in API demos
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'script-src' ] = "'self' 'unsafe-inline' code.jquery.com";
	$policy[ 'style-src' ] = "'self' 'unsafe-inline' code.jquery.com";
	return $policy;
} );
