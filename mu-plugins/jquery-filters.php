<?php
/* Plugin Name: jQuery Filters
 * Description: Default filters, option values, and other tweaks.
 */

// Disable smilies.
add_filter( 'pre_option_use_smilies', '__return_false' );

// Turn on XML RPC
add_action( 'enable_xmlrpc', '__return_true' );
