<?php

require_once __DIR__ . '/functions.content.php';

// Allow inline scripts on https://jqueryui.com/themeroller/
// Load styles from download.jqueryui.com on https://jqueryui.com/themeroller/
// Load images from download.jqueryui.com on https://jqueryui.com/themeroller/
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'style-src' ] = "'self' 'unsafe-inline' code.jquery.com download.jqueryui.com";
	$policy[ 'img-src' ] = "'self' data: code.jquery.com download.jqueryui.com";
	return $policy;
} );
