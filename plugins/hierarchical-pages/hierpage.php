<?php
  /*
   * Plugin Name: Hierarchical Pages
   * Version: 1.2
   * Plugin URI: http://www.saltriversystems.com/website/hierpage/
   * Description: Adds a sidebar widget to display a context-based list of "nearby" pages.
   * Author: William Lindley
   * Author URI: http://www.wlindley.com/
   */

  /*  Copyright 2007-2011 William Lindley (email : bill -at- saltriversystems -dot- com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */

class SRCS_WP_Widget extends WP_Widget
{
  function form_html($instance) {
    $option_menu = $this->known_params(1);
    foreach (array_keys($option_menu) as $param) {
      $param_display[$param] = htmlspecialchars($instance[$param]);
    }

    foreach ($option_menu as $option_name => $option) {
      $checkval='';
      $desc = '';
      if ($option['desc'])
	$desc = "<br /><small>{$option['desc']}</small>";
      switch ($option['type']) {
      case 'checkbox':
	if ($instance[$option_name]) // special HTML and override value
	  $checkval = 'checked="yes" ';
	$param_display[$option_name] = 'yes';
	break;
      case '':
	$option['type'] = 'text';
	break;
      }
      print '<p style="text-align:right;"><label for="' . $this->get_field_name($option_name) . '">' . 
	__($option['title']) . 
	' <input style="width: 200px;" id="' . $this->get_field_id($option_name) . 
	'" name="' . $this->get_field_name($option_name) . 
	"\" type=\"{$option['type']}\" {$checkval}value=\"{$param_display[$option_name]}\" /></label>$desc</p>";
    }
  }
}


class HierPageWidget extends SRCS_WP_Widget
{
  /**
   * Declares the HierPageWidget class.
   *
   */
  function HierPageWidget(){
    $widget_ops = array('classname' => 'widget_hier_page', 'description' => __( "Hierarchical Page Directory Widget") );
    $control_ops = array('width' => 300, 'height' => 300);
    $this->WP_Widget('hierpage', __('Hierarchical Pages'), $widget_ops, $control_ops);
  }

  /**
   * Helper function
   *
   */
  function hierpages_list_pages($args = '') {
    global $post;
    global $wp_query;

    if ( !isset($args['echo']) )
      $args['echo'] = 1;

    $output = '';

    // Query pages.  NOTE: The array is sorted in alphabetical, or menu, order.
    $pages = & get_pages($args);
    $page_info = Array();

    if ( $pages ) {
      $current_post = $wp_query->get_queried_object_id();

      foreach ( $pages as $page ) {
	$page_info[$page->ID]['parent'] = $page->post_parent;
	$page_info[$page->post_parent]['children'][] = $page->ID;
      }

      // Display the front page?
      $front_page = -1; // assume no static front page
      if ('page' == get_option('show_on_front')) {
	$front_page = get_option('page_on_front');
	// Regard flag: always show front page?  Otherwise: Show front page only if it has children
	if (($args['show_home'] == 'yes') || (sizeof($page_info[$front_page]['children']))) {
	  $page_info[$front_page]['show'] = 1;	// always show front page
	}
      }
	
      // add all children of the root node, but only to single depth.
      if ($args['show_root'] == 'yes') {
	foreach ( $page_info[0]['children'] as $child ) {
	  if ($child != $front_page) {
	    $page_info[$child]['show'] = 1;
	  }
	}
      }
      
      if (is_page()) {
	if ($post->ID != $front_page ) {
	  // The current page is always shown, unless it is the static front page (see above)
	  $page_info[$post->ID]['show'] = 1;
	}
	
	// show the current page's children, if any.
	if (is_array($page_info[$current_post]['children'] )) {
	   foreach ( $page_info[$current_post]['children'] as $child ) {
	      $page_info[$child]['show'] = 1;
	   }
	}

	$post_parent = $page_info[$current_post]['parent'];
	if ($post_parent && ($args['show_siblings'] == 'yes')) {
	  // if showing siblings, add the current page's parent's other children.
	  foreach ( $page_info[$post_parent]['children'] as $child ) {
	    if ($child != $front_page) {
	      $page_info[$child]['show'] = 1;
	    }
	  }

	  // Also show parent node's siblings.
	  $post_grandparent = $page_info[$post_parent]['parent'];
	  if ($post_grandparent) {
	    foreach ( $page_info[$post_grandparent]['children'] as $child ) {
	      if ($child != $front_page) {
		$page_info[$child]['show'] = 1;
	      }
	    }
	  }
	}
	
	// add all ancestors of the current page.
	while ($post_parent) {
	  $page_info[$post_parent]['show'] = 1;
	  $post_parent = $page_info[$post_parent]['parent'];
	}
      }
      
      // Add pages that were selected
      $my_includes = Array();
      foreach ( $pages as $page ) {
	if ($page_info[$page->ID]['show']) {
	  $my_includes[] = $page->ID;
	}
      }
      if ($args['child_of']) {
        $my_includes[] = $args['child_of'];
      }
      
      if (!empty($my_includes)) {
        // List pages, if any. Blank title_li suppresses unwanted elements.
        $output .= wp_list_pages( Array('title_li' => '',
					'sort_column' => $args['sort_column'],
					'sort_order' => $args['sort_order'],
					'include' => $my_includes) );
      }
    }

    $output = apply_filters('wp_list_pages', $output);
    
    if ( $args['echo'] )
      echo $output;
    else
      return $output;
  }

  /**
   * Displays the Widget
   *
   */
  function widget($args, $instance){

    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
    $known_params = $this->known_params(0);
    foreach ($known_params as $param) {
      if (strlen($instance[$param])) {
	$page_options[$param] = $instance[$param];
      }
    }
      
    if ($instance['menu_order'] == 'yes') { // Deprecated, eliminated upon form display (see below)
      $page_options['sort_column']='menu_order,post_title';
    }

    print $args['before_widget'];
    if ( $title )
      print "{$args['before_title']}{$title}{$args['after_title']}";
    print '<ul>';
    $this->hierpages_list_pages($page_options);
    print "</ul>{$args['after_widget']}";
  }

  function known_params ($options = 0) {
    $option_menu = array('title' => array('title' => 'Title:'),
			 'show_siblings' => array('title' => 'Show siblings to the current page?',
						  'type' => 'checkbox'),
			 'show_root' => array('title' => 'Always show top-level pages?',
					      'type' => 'checkbox'),
			 'show_home' => array('title' => 'Show the static home page?',
					      'desc' => '(always shown if it has child pages)',
					      'type' => 'checkbox'),
			 'child_of' => array('title' => 'Root page ID:'),
			 'exclude' => array('title' => 'Exclude pages:',
					    'desc' => 'List of page IDs to exclude'),
			 'sort_column' => array('title' => 'Sort field:',
						'desc' => 'Comma-separated list: <em>post_title, menu_order, post_date, post_modified, ID, post_author, post_name</em>'),
			 'sort_order' => array('title' => 'Sort direction:',
					       'desc' => '(default: ASC)'),
			 'meta_key' => array('title' => 'Meta Key:'),
			 'meta_value' => array('title' => 'Meta-key Value:',
					     'desc' => 'for selecting pages by custom fields'),
			 'authors' => array('title' => 'Authors:'),
			 'post_status' => array('title' => 'Post status:',
						'desc' => '(default: publish)'),
			 );
    return ($options ? $option_menu : array_keys($option_menu));
  }

  /**
   * Saves the widget's settings.
   *
   */
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $known_params = $this->known_params();
    unset($instance['menu_order']);
    foreach ($known_params as $param) {
      $instance[$param] = strip_tags(stripslashes($new_instance[$param]));
    }
    $instance['sort_order'] = strtolower($instance['sort_order']) == 'desc'?'DESC':'ASC';
    return $instance;
  }

  /**
   * Creates the edit form for the widget.
   *
   */
  function form($instance){
    $instance = wp_parse_args( (array) $instance, array('title'=>'') );
    if ($instance['menu_order']) {
      $instance['sort_column'] = 'menu_order,post_title';
    }
    if (empty($instance['sort_column'])) {
      $instance['sort_column'] = 'post_title';
    }

    $this->form_html($instance);
  }

}// END class

/**
 * Register Hierarchical Pages widget.
 *
 * Calls 'widgets_init' action after the widget has been registered.
 */
function HierPageInit() {
  register_widget('HierPageWidget');
}

  /*
   * Plugin Name: Hierarchical Categories (combined with Hierarchical Pages)
   * Plugin URI: http://www.wlindley.com/
   * Description: Adds a sidebar widget to display a context-based list of "nearby" categories.
   * Author: William Lindley
   * Author URI: http://www.wlindley.com/
   */

class HierCatWidget extends SRCS_WP_Widget
{
  /**
   * Declares our class.
   *
   */
  function HierCatWidget(){
    $widget_ops = array('classname' => 'widget_hier_cat', 'description' => __( "Hierarchical Category Widget") );
    $control_ops = array('width' => 300, 'height' => 300);
    $this->WP_Widget('hiercat', __('Hierarchical Categories'), $widget_ops, $control_ops);
  }

  /**
   * Helper function
   *
   */
  function hiercat_list_cats($args) {
    global $post;
    global $wp_query;

    if ( !isset($args['echo']) )
      $args['echo'] = 1;
    $sort_column = $args['sort_column'];

    $output = '';

    // Query categories.
    $cats = & get_categories($args);
    if ($cats['errors']) {
      print "<pre>"; print_r($cats); print "</pre>";
      return;
    }
    $cat_info = Array();

    if ( $cats ) {
      $current_cat = $wp_query->get_queried_object_id();

      foreach ( $cats as $cat ) {
	$cat_info[$cat->term_id]['parent'] = $cat->category_parent;
	$cat_info[$cat->category_parent]['children'][] = $cat->term_id;
      }

      // add all children of the root node, but only to single depth.
      foreach ( $cat_info[0]['children'] as $child ) {
	$cat_info[$child]['show'] = 1;
      }
      
      // If currently displaying a category, taxonomy, or tag; AND
      // if it is the same as the one in this widget...
      if ((is_category() || is_tax() || is_tag()) && 
	  ($args['taxonomy'] == get_queried_object()->taxonomy ) ) {
	// show the current category's children, if any.
	if (is_array($cat_info[$current_cat]['children'] )) {
	   foreach ( $cat_info[$current_cat]['children'] as $child ) {
	      $cat_info[$child]['show'] = 1;
	   }
	}

	$cat_parent = $cat_info[$current_cat]['parent'];
	if ($cat_parent && ($args['show_siblings'] == 'yes')) {
	  // if showing siblings, add the current category's parent's other children.
	  foreach ( $cat_info[$cat_parent]['children'] as $child ) {
	    $cat_info[$child]['show'] = 1;
	  }

	  # Also show parent node's siblings.
	  $cat_grandparent = $cat_info[$cat_parent]['parent'];
	  if ($cat_grandparent) {
	    foreach ( $cat_info[$cat_grandparent]['children'] as $child ) {
		$cat_info[$child]['show'] = 1;
	    }
	  }
	}
	
	// add all ancestors of the current category.
	while ($cat_parent) {
	  $cat_info[$cat_parent]['show'] = 1;
	  $cat_parent = $cat_info[$cat_parent]['parent'];
	}
      }
      
      $my_includes = Array();
      // Add categories that were selected
      foreach ( $cats as $cat ) {
	if ($cat_info[$cat->term_id]['show']) {
	  $my_includes[] =$cat->term_id;
	}
      }
      
      if (!empty($my_includes)) {
        // List categories, if any. Blank title_li suppresses unwanted elements.
        $qargs = Array('title_li' => '', 'hide_empty' => 0, 'include' => $my_includes,
                      'order' => $args['order'], 'orderby' => $args['orderby'],
                      'show_count' => $args['show_count']);
        if (!empty($args['taxonomy'])) {
          $qargs['taxonomy'] = $args['taxonomy'];
        }
        $output .= wp_list_categories( $qargs );
      }
    }

    $output = apply_filters('wp_list_categories', $output);
    
    if ( $args['echo'] )
      echo $output;
    else
      return $output;
  }

  function known_params ($options = 0) {
    $option_menu = array('title' => array('title' => 'Title:'),
			 'show_siblings' => array('title' => 'Show siblings to the current category?',
						  'type' => 'checkbox'),
			 'include' => array('title' => 'Include:',
					    'desc' => 'Comma-delimited list of category IDs, or blank for all'),
			 'exclude' => array('title' => 'Exclude:'),
			 'orderby' => array('title' => 'Sort field:',
					    'desc' => 'Enter one of: <em>name, count, term_group, slug</em> or a custom value. Default: name'),
			 'order' => array('title' => 'Sort direction:',
					  'desc' => '(default: ASC)'),
			 'child_of' => array('title' => 'Only display Categories below this ID'),
			 'hide_empty' => array('title' => 'Hide empty categories?', 
						  'type' => 'checkbox'),
			 'show_count' => array('title' => 'Show count of category entries?', 
						  'type' => 'checkbox'),
			 'taxonomy' => array('title' => 'Custom taxonomy:'),
			 );
    if ($options) {
      $taxons = get_taxonomies();
      $option_menu['taxonomy']['desc'] = 'Enter one of: <em>' . 
	implode(', ',array_keys($taxons)) . '</em> or blank for post categories.';
    }
    return ($options ? $option_menu : array_keys($option_menu));
  }
  /**
   * Displays the Widget
   *
   */
  function widget($args, $instance){
    $known_params = $this->known_params(0);
    foreach ($known_params as $param) {
      if (strlen($instance[$param]))
	$cat_options[$param] = $instance[$param];
    }
    $cat_options['title'] = apply_filters('widget_title', $cat_options['title']);
    // WordPress defaults to hiding: thus, always specify.
    $cat_options['hide_empty'] = $cat_options['hide_empty'] == 'yes' ? 1 : 0;
      
    print $args['before_widget'];
    if ( strlen($cat_options['title']) )
      print "{$args['before_title']}{$cat_options['title']}{$args['after_title']}";
    print "<ul>";

    $this->hiercat_list_cats($cat_options);
    print "</ul>{$after_widget}";
  }

  /**
   * Saves the widget's settings.
   *
   */
  function update($new_instance, $old_instance){
    $instance = $old_instance;

    $known_params = $this->known_params();
    foreach ($known_params as $param) {
      $instance[$param] = strip_tags(stripslashes($new_instance[$param]));
    }
    return $instance;
  }

  /**
   * Creates the edit form for the widget.
   *
   */
  function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('title'=>'') );

    $this->form_html($instance);
  }

}// END class

/**
 * Register Hierarchical Categories widget.
 *
 * Calls 'widgets_init' action after the widget has been registered.
 */
function HierCatInit() {
  register_widget('HierCatWidget');
}

/*
 * Initialize both widgets
 */

add_action('widgets_init', 'HierCatInit');
add_action('widgets_init', 'HierPageInit');

?>
