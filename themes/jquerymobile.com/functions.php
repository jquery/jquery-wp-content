<?php

// Allow scripts from cdnjs.cloudflare.com for the download builder
// Allow connections to the amd builder subdomain for the download builder
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'script-src' ] = "'self' code.jquery.com cdnjs.cloudflare.com";
	$policy[ 'connect-src' ] = "'self' typesense.jquery.com *.jquerymobile.com";
	return $policy;
} );
