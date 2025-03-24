<?php

// Allow scripts from cdnjs.cloudflare.com for the download builder
// Allow connections to the amd builder subdomain for the download builder
// Allow frames from amd-builder.jquerymobile.com for the download builder
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'script-src' ] = "'self' code.jquery.com cdnjs.cloudflare.com";
	$policy[ 'connect-src' ] = "'self' typesense.jquery.com *.jquerymobile.com";
	$policy[ 'frame-src' ] = "'self' *.jquerymobile.com";
	return $policy;
} );
