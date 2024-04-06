<?php
/**
 * Plugin Name: jQuery Actions
 * Description: Default actions for all jQuery sites.
 */

// Ensure relative links remain on the current protocol
// (such as references to theme assets and intra-site links).
// This does not influence 'home' and 'siteurl' options, and thus
// does not affect <link rel=canonical> and sitemap output.
if ( @$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
    $_SERVER['HTTPS'] = '1';
} elseif ( @$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http' ) {
    $_SERVER['HTTPS'] = '0';
}
add_filter( 'wp_headers', function ( $headers ) {
	if ( isset( $headers['Vary'] ) ) {
		$headers['Vary'] .= ',X-Forwarded-Proto';
	} else {
		$headers['Vary'] = 'X-Forwarded-Proto';
	}
	return $headers;
}, 10, 1 );

/**
 * Add rel=me link to HTML head for Mastodon domain verification
 *
 * Usage:
 *
 * Put one or more comma-separated URLs in the 'jquery_xfn_rel_me' WordPress option.
 *
 * Example:
 *
 *     'jquery_xfn_rel_me' => 'https://example.org/@foo,https://social.example/@bar'
 *
 * See also:
 *
 * - https://docs.joinmastodon.org/user/profile/#verification
 * - https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/rel/me
 * - https://microformats.org/wiki/rel-me
 * - https://gmpg.org/xfn/
 */
function jquery_xfnrelme_wp_head() {
	$option = get_option( 'jquery_xfn_rel_me' , '' );
	$links = $option !== '' ? explode( ',', $option ) : array();
	foreach ( $links as $url ) {
		// We use esc_attr instead of esc_url, as the latter would shorten
		// the URL to be scheme-less as "//example" instead of "https://example",
		// which prevents relation verification.
		echo '<link rel="me" href="' . esc_attr( trim( $url ) ) . '">' . "\n";
	}
}

add_action('wp_head', 'jquery_xfnrelme_wp_head');
