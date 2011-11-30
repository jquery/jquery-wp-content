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
function custom_sanitize_title_with_dashes($title) {
	 $title = strip_tags($title);
	 // Preserve escaped octets.
	 $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	 // Remove percent signs that are not part of an octet.
	 $title = str_replace('%', '', $title);
	 // Restore octets.
	 $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	 $title = remove_accents($title);
	 if (seems_utf8($title)) {
    // if (function_exists('mb_strtolower')) {
    //  $title = mb_strtolower($title, 'UTF-8');
    // }
       $title = utf8_uri_encode($title, 200);
       }

  // $title = strtolower($title);
  $title = preg_replace('/&.+?;/', '', $title); // kill entities
  // $title = str_replace('.', '-', $title);
  $title = preg_replace('/[^%a-zA-Z0-9 ._-]/', '', $title);
  $title = preg_replace('/\s+/', '-', $title);
  $title = preg_replace('|-+|', '-', $title);
  $title = trim($title, '-');

  return $title;
}


function jquery_slugs($post_name) {
  return $post_name;
}

remove_filter('sanitize_title', 'sanitize_title_with_dashes');
add_filter('sanitize_title', 'custom_sanitize_title_with_dashes');
add_filter('pre_post_name', 'jquery_slugs', 1);
add_filter('pre_post_slug', 'jquery_slugs', 1);

?>
