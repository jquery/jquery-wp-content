<?php
/*
Plugin Name: jQuery Slugs
Plugin URI: http://www.learningjquery.com/
Description: Keep uppercase characters and dots (".")
Author: Karl Swedberg
Version: 0.1
Author URI: http://www.learningjquery.com/
*/

// Based on sanitize_title_with_dashes method (wp-includes/formatting.php)
function jquery_sanitize_title_with_dashes($title, $raw_title = '', $context = 'display') {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			// # JQUERY $title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}

	// # JQUERY $title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	// # JQUERY $title = str_replace('.', '-', $title);

	if ( 'save' == $context ) {
		// nbsp, ndash and mdash
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
		// iexcl and iquest
		$title = str_replace( array( '%c2%a1', '%c2%bf' ), '', $title );
		// angle quotes
		$title = str_replace( array( '%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba' ), '', $title );
		// curly quotes
		$title = str_replace( array( '%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d' ), '', $title );
		// copy, reg, deg, hellip and trade
		$title = str_replace( array( '%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2' ), '', $title );
	}

	$title = preg_replace('/[^%a-z0-9 _.-]/i', '', $title); # JQUERY added . and /i
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}
remove_filter('sanitize_title', 'sanitize_title_with_dashes', 10, 3);
add_filter('sanitize_title', 'jquery_sanitize_title_with_dashes', 10, 3);

?>
