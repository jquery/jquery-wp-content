<?php

// Allow inline scripts and styles in API demos
// Allow flickr script and images on https://api.jquery.com/jQuery.getJSON/
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'script-src' ] = "'self' 'unsafe-inline' code.jquery.com api.flickr.com";
	$policy[ 'style-src' ] = "'self' 'unsafe-inline' code.jquery.com";
	$policy[ 'img-src' ] = "'self' data: code.jquery.com live.staticflickr.com";
	return $policy;
} );
