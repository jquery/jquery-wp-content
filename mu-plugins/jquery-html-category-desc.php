<?php
/*
Plugin Name: HTML Category Descriptions
Plugin URI: http://www.learningjquery.com/
Description: Allows full HTML in Category Descriptions.
Author: Karl Swedberg
Author URI: http://www.learningjquery.com/
Version: 1.0

Copyright (c) 2012 Karl Swedberg
Dual licensed under the MIT or GPL Version 2 licenses.
*/


add_action('init', 'better_cat_desc');

function better_cat_desc() {
  if ( ( is_admin() || defined('DOING_AJAX') ) &&  current_user_can('manage_categories') ) {
    remove_filter('pre_term_description', 'wp_filter_kses');
  }
}

// KARL'S ADDITION TO ALLOW HTML IN CATEGORY DESCRIPTIONS
function ks_term_description( $category = 0, $taxonomy = 'category') {
  if ( !$term && ( is_tax() || is_tag() || is_category() ) ) {
    global $wp_query;
    $term = $wp_query->get_queried_object();
    $taxonomy = $term->taxonomy;
    $term = $term->term_id;
  }
  return ks_get_term_field( 'description', $term, $taxonomy );

}

function ks_get_term_field( $field, $term, $taxonomy, $context = 'display' ) {
  $term = (int) $term;
  $term = get_term( $term, $taxonomy );
  if ( is_wp_error($term) ) {
    return $term;
  }

  if ( !is_object($term) ) {
    return '';
  }

  if ( !isset($term->$field) ) {
    return '';
  }

  if ( preg_match('@</(p(re)?|div|ul|ol)@', $term->$field) ) {
    $context = 'raw';
  }

  return sanitize_term_field($field, $term->$field, $term->term_id, $taxonomy, $context);
}
