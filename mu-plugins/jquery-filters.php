<?php
/* Plugin Name: jQuery Filters
 * Description: Default filters, option values, and other tweaks.
 */

// Disable smilies.
add_filter( 'pre_option_use_smilies', '__return_zero' );

// Turn on XML-RPC
add_filter( 'pre_option_enable_xmlrpc', '__return_true' );
