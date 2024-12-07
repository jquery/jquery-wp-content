<?php
 
// Allow loading a Vimeo video on
// https://contribute.jquery.org/markup-conventions/
add_filter( 'jq_content_security_policy', function ( $policy ) {
	$policy[ 'frame-src' ] = "'self' player.vimeo.com";
	return $policy;
} );
