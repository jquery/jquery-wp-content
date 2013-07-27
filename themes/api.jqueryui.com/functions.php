<?php

function jq_ui_api_versions() {
	// Must be listed with newest first
	return array(
		"1.10" => "1.6 and newer",
		"1.9" => "1.6 and newer",
		"1.8" => "1.3.2 and newer",
	);
}

function jq_ui_api_version_latest() {
	$versions = jq_ui_api_versions();
	return key( $versions );
}

function jq_ui_api_version_current() {
	$thisVersion = explode( "/", JQUERY_LIVE_SITE );
	return count( $thisVersion ) === 2 ?
		$thisVersion[ 1 ] :
		jq_ui_api_version_latest();
}
