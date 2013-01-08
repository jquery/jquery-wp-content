<?php
/* Plugin Name: Enable WP Auto-Paragraphing
 * Description: jQuery's jquery-wp-content disables auto-paragraphing in WordPress. This plugin can be activated to re-enable it for specific sites.
 * Author: Andrew Nacin
 */

add_filter( 'the_content', 'wpautop' );
