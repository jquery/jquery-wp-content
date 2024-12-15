<?php

require_once __DIR__ . '/functions.content.php';

// Allow inline scripts on https://jqueryui.com/themeroller/
// Load scripts from download.jqueryui.com on https://jqueryui.com/download/
// Load styles from download.jqueryui.com on https://jqueryui.com/themeroller/
// Load images from download.jqueryui.com on https://jqueryui.com/themeroller/
// Allow form actions to download.jqueryui.com on https://jqueryui.com/download/
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'script-src' ] = "'self' code.jquery.com download.jqueryui.com";
	$policy[ 'style-src' ] = "'self' 'unsafe-inline' code.jquery.com download.jqueryui.com";
	$policy[ 'img-src' ] = "'self' data: code.jquery.com download.jqueryui.com";
	$policy[ 'form-action' ] = "'self' download.jqueryui.com";
	return $policy;
} );
