<?php
/**
 * Plugin Name: jQuery Filters
 * Description: Default filters for all jQuery sites.
 */

if ( defined( 'WP_INSTALLING' ) ) {
	return;
}

require_once __DIR__ . '/../sites.php';

$options = jquery_default_site_options();
$sites = jquery_sites();
$options = array_merge( $options, $sites[ JQUERY_LIVE_SITE ]['options'] );
foreach ( $options as $option => $value ) {
	// Skip these on live sites (both production and staging),
	// where they are managed by puppet.
	// Local testing with a fresh database does not
	// currently work if these are skipped.
	if ( JQUERY_STAGING !== 'local' ) {
		if ( $option === 'stylesheet' || $option === 'template' ) {
			// Don't mess with themes for now.
			continue;
		}
		if ( $option === 'active_plugins' ) {
			// On live sites (including staging ones),
			// Puppet manages activation of per-site plugins.
			continue;
		}
	}
	add_filter( 'pre_option_' . $option, function () use ( $value ) {
		return $value;
	} );
}
unset( $sites, $options, $option );

// Ensure that the local port is used for template assets, if it exists.
add_filter( 'theme_root_uri', function( $value ) {
	// All environment variables are set either in the local wp-config.php or via puppet.
	// Staging sites set JQUERY_STAGING to the boolean `true` instead of 'local'.
	// Production sites set it to false.
	if ( JQUERY_STAGING === 'local' ) {
		// Don't specify http vs https here, as the site may be accessed via either.
		$siteurl = '//' . strtr( JQUERY_STAGING_FORMAT, [ '%s' => JQUERY_LIVE_SITE ] );
		$value = $siteurl . '/wp-content/themes';
	}
	return $value;
});

// Remove misc links from <head> on non-blog sites
if ( !get_option( 'jquery_is_blog' ) ) {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

	// Remove shortlink <head> and header.
	remove_action( 'wp_head',             'wp_shortlink_wp_head', 10 );
	remove_action( 'template_redirect',   'wp_shortlink_header',  11 );

	// Disable WordPress auto-paragraphing for posts, except on actual blog sites
	remove_filter( 'the_content', 'wpautop' );

	add_filter( 'option_uploads_use_yearmonth_folders', '__return_false' );

	add_filter( 'upload_dir', function( $upload_dir ) {
		if ( defined( 'UPLOADS' ) ) {
			$upload_dir['path'] = $upload_dir['basedir'] = UPLOADS;
		} else {
			$upload_dir['path'] = $upload_dir['basedir'] = WP_CONTENT_DIR . '/uploads';
		}

		return $upload_dir;
	});
}

/**
 * Content Security Policy
 * https://github.com/jquery/infrastructure-puppet/issues/54
 */
add_action( 'send_headers', function() {
	$nonce = bin2hex( random_bytes( 8 ) );
	$report_url = 'https://csp-report-api.openjs-foundation.workers.dev/';
	$policy = array(
		'default-src' => "'self'",
		// The nonce is here so inline scripts can be used in the theme
		'script-src' => "'self' 'nonce-$nonce' code.jquery.com",
		'style-src' => "'self' 'nonce-$nonce' code.jquery.com",
		// Allow style="" attributes in blog posts and markdown.
		'style-src-attr' => "'unsafe-inline'",
		// data: SVG images are used in typesense
		// Allow gravatars in wordpress admins
		'img-src' => "'self' data: secure.gravatar.com code.jquery.com",
		'connect-src' => "'self' typesense.jquery.com",
		// Allow data fonts for the wordpress admins
		'font-src' => "'self' data:",
		'object-src' => "'none'",
		'frame-ancestors' => "'none'",
		'base-uri' => "'self'",
		'block-all-mixed-content' => '',
		'report-to' => 'csp-endpoint',
		// Add report-uri for Firefox, which
		// does not yet support report-to
		'report-uri' => $report_url,
	);

	$policy = apply_filters( 'jq_content_security_policy', $policy );

	if ( get_option( 'jquery_is_blog' ) ) {
		// Allow <style> in blog posts
		$policy[ 'style-src' ] = "'self' 'unsafe-inline' code.jquery.com";
		// Allow re-use of attachments between blog.jquery.com, blog.jqueryui.com, and blog.jquerymobile.com
		// Allow use of doc site resources from jqueryui.com in posts on blog.jqueryui.com
		// Allow use of content.jquery.com (aka static.jquery.com) in blog posts, e.g. event avatars.
		$policy[ 'img-src' ] = "'self' data: secure.gravatar.com code.jquery.com content.jquery.com static.jquery.com jquery.com jqueryui.com jquerymobile.com blog.jquery.com blog.jqueryui.com blog.jquerymobile.com";
		// Allow Vimeo on blog.jquerymobile.com and other blogs
		$policy[ 'frame-src' ] = "'self' player.vimeo.com";

		// wp-admin requires inline scripts, inline styles, and workers from blob: URLs
		if ( is_admin() ) {
			$policy[ 'script-src' ] = "'self' 'unsafe-inline' blob: code.jquery.com";
		}
	} elseif ( is_admin() ) {
		// wp-admin (as used by blogs) requires inline scripts, inline styles, and workers from blob: URLs
		$policy[ 'script-src' ] = "'self' 'unsafe-inline' blob: code.jquery.com";
		$policy[ 'style-src' ] = "'self' 'unsafe-inline' code.jquery.com";
	}

	$policy_string = '';
	foreach ( $policy as $key => $value ) {
		$policy_string .= $key . ' ' . $value . '; ';
	}

	header( 'Reporting-Endpoints: csp-endpoint="' . $report_url . '"' );
	header( 'Content-Security-Policy: ' . $policy_string );
} );

// Disable WordPress text transformations (smart quotes, etc.) for posts.
remove_filter( 'the_content', 'wptexturize' );

// Disable more restrictive multisite upload settings.
remove_filter( 'upload_mimes', 'check_upload_mimes' );
// Give unfiltered upload ability to super admins.
define( 'ALLOW_UNFILTERED_UPLOADS', true );
// Until unfiltered uploads make it into XML-RPC:
add_filter( 'upload_mimes', function( $mimes ) {
	$mimes['eot'] = 'application/vnd.ms-fontobject';
	$mimes['svg'] = 'image/svg+xml';
	$mimes['ttf'] = 'application/x-font-ttf';
	$mimes['woff'] = 'application/font-woff';
	$mimes['xml'] = 'text/xml';
	$mimes['php'] = 'application/x-php';
	$mimes['json'] = 'application/json';
	return $mimes;
} );

// Increase file size limit to 1GB
add_filter( 'pre_site_option_fileupload_maxk', function() {
	return 1024 * 1024;
} );

// Disable the new image sizes feature.
// It adds a style tag that would require a CSP exception.
add_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );

// Allow full HTML in term descriptions.
add_action( 'init', 'jquery_unfiltered_html_for_term_descriptions' );
add_action( 'set_current_user', 'jquery_unfiltered_html_for_term_descriptions' );
function jquery_unfiltered_html_for_term_descriptions() {
	remove_filter( 'pre_term_description', 'wp_filter_kses' );
	remove_filter( 'pre_term_description', 'wp_filter_post_kses' );
	if ( ! current_user_can( 'unfiltered_html' ) )
		add_filter( 'pre_term_description', 'wp_filter_post_kses' );
}

// Bypass multisite checks.
add_filter( 'ms_site_check', '__return_true' );

// Add body classes found in postmeta.
add_filter( 'body_class', function( $classes ) {
	$body_class_setting = get_option( 'jquery_body_class' );
	if ( $body_class_setting ) {
		array_unshift( $classes, $body_class_setting );
	}
	if ( strpos( JQUERY_LIVE_SITE, 'api.' ) === 0 ) {
		array_unshift( $classes, 'api' );
	}

	if ( is_page() ) {
		$classes[] = 'page-slug-' . sanitize_html_class( strtolower( get_queried_object()->post_name ) );
	}
	if ( is_singular() && $post_classes = get_post_meta( get_queried_object_id(), 'body_class', true ) ) {
		$classes = array_merge( $classes, explode( ' ', $post_classes ) );
	}
	if ( is_archive() || is_search() ) {
		$classes[] = 'listing';
	}

	return $classes;
});

add_filter( 'get_terms', function( $terms, $taxonomies, $args ) {
	if ( !isset( $args[ 'orderby' ] ) || $args[ 'orderby' ] !== 'natural' ) {
		return $terms;
	}

	$sortedTerms = array();
	foreach( $terms as $term ) {
		$sortedTerms[ $term->name ] = $term;
	}
	uksort( $sortedTerms, 'strnatcasecmp' );

	if ( strtolower( $args[ 'order' ] ) === 'desc' ) {
		$sortedTerms = array_reverse( $sortedTerms );
	}

	return $sortedTerms;
}, 20, 3 );

add_filter( 'xmlrpc_wp_insert_post_data', function ( $post_data, $content_struct ) {
	if ( $post_data['post_type'] !== 'page' ) {
		return $post_data;
	}

	if ( isset( $content_struct['page_template'] ) ) {
		$post_data['page_template'] = $content_struct['page_template'];
	}

	if ( isset( $content_struct['menu_order'] ) ) {
		$post_data['menu_order'] = $content_struct['menu_order'];
	}

	return $post_data;
}, 10, 2 );

if ( JQUERY_STAGING && !defined( 'XMLRPC_REQUEST' ) ) {
	ob_start( 'jquery_com_ob_local_urls' );
}
function jquery_com_ob_local_urls( $content ) {
	$pairs = [];
	foreach ( jquery_sites() as $site => $_ ) {
		// Replace HTTPS with protocol-relative so navigation stays within HTTP locally.
		$pairs[ 'https://' . $site ] = '//' . jquery_site_expand( $site );
		// Update any remaining HTTP or protocol-relative urls.
		$pairs[ '//' . $site ] = '//' . jquery_site_expand( $site );
	}
	return strtr( $content, $pairs );
}
