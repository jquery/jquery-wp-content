<?php

function jq_mobile_api_versions() {
	// Must be listed with newest first
	return array(
		'1.4' => '1.8 and newer',
		'1.3' => '1.7 and newer',
	);
}

function jq_mobile_api_version_latest() {
	$versions = jq_mobile_api_versions();
	return key( $versions );
}

function jq_mobile_api_version_current() {
	$thisVersion = explode( "/", JQUERY_LIVE_SITE );
	return count( $thisVersion ) === 2 ?
		$thisVersion[ 1 ] :
		jq_mobile_api_version_latest();
}
