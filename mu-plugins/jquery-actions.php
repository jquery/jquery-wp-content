<?php
/**
 * Plugin Name: jQuery Actions
 * Description: Default actions for all jQuery sites.
 */

remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'rel_canonical' );

// Remove shortlink <head> and header.
remove_action( 'wp_head',             'wp_shortlink_wp_head', 10 );
remove_action( 'template_redirect',   'wp_shortlink_header',  11 );

// https://docs.joinmastodon.org/user/profile/#verification
// https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/rel/me
// https://microformats.org/wiki/rel-me
// https://gmpg.org/xfn/
function jquery_xfnrelme_wp_head() {
	$sites = jquery_sites();
	$links = isset( $sites[ JQUERY_LIVE_SITE ]['xfn-rel-me'] ) ?
		$sites[ JQUERY_LIVE_SITE ]['xfn-rel-me'] :
		array();
	foreach ( $links as $url ) {
		// We use esc_attr instead of esc_url, as the latter would shorten
		// the URL to be scheme-less as "//example" instead of "https://example",
		// which prevents relation verification.
		echo '<link rel="me" href="' . esc_attr( $url ) . '">' . "\n";
	}
}

add_action('wp_head', 'jquery_xfnrelme_wp_head');
