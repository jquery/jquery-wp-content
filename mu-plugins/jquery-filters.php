<?php
/* Plugin Name: jQuery Filters
 * Description: Default filters, option values, and other tweaks.
 */

// Disable smilies.
add_filter( 'pre_option_use_smilies', '__return_zero' );

// Turn on XML-RPC.
add_filter( 'pre_option_enable_xmlrpc', '__return_true' );

// Disable WordPress auto-paragraphing for posts.
remove_filter( 'the_content', 'wpautop' );

// Disable more restrictive multisite upload settings.
remove_filter( 'upload_mimes', 'check_upload_mimes' );

// Allow full HTML in term descriptions.
add_action( 'init', 'jquery_unfiltered_html_for_term_descriptions' );
add_action( 'set_current_user', 'jquery_unfiltered_html_for_term_descriptions' );
function jquery_unfiltered_html_for_term_descriptions() {
	remove_filter( 'pre_term_description', 'wp_filter_kses' );
	remove_filter( 'pre_term_description', 'wp_filter_post_kses' );
	if ( ! current_user_can( 'unfiltered_html' ) )
		add_filter( 'pre_term_description', 'wp_filter_post_kses' );
}