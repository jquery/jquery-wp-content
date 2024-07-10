<?php

function jq_ui_api_versions() {
	// Must be listed with current stable first
	return array(
		"1.14" => "latest versions of jQuery 1.x, 2.x, 3.x & 4.x; see <a href=\"https://jqueryui.com/changelog/\">the changelogs of a specific release</a> for more detailed support information",
		"1.13" => "jQuery 1.8 and newer",
		"1.12" => "jQuery 1.7 and newer",
		"1.11" => "jQuery 1.6 and newer",
		"1.10" => "jQuery 1.6 and newer",
		"1.9" => "jQuery 1.6 and newer",
		"1.8" => "jQuery 1.3.2 and newer",
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
