<?php

function is_subpage() {
    global $post;                              // load details about this page

    if ( is_page() && $post->post_parent ) {   // test to see if the page has a parent
        return $post->post_parent;             // return the ID of the parent post

    } else {                                   // there is no parent so ...
        return false;                          // ... the answer to the question is false
    }
}

if ( version_compare( $wp_version, '3.5-alpha', '<' ) ) :
    add_filter( 'posts_where_paged', function( $where, $query ) {
        global $wpdb;
        if ( $menu_order = absint( $query->get( 'menu_order' ) ) )
            $where .= " AND $wpdb->posts.menu_order = " . $menu_order;
        return $where;
    }, 10, 2 );
endif;

function get_next_prev_post() {
    global $post;
    $menu_order_prev = $post->menu_order - 1;
    $menu_order_next = $post->menu_order + 1;

    $posts_prev= new WP_Query( array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'menu_order' => $menu_order_prev
    ) );

    $posts_next= new WP_Query( array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'menu_order' => $menu_order_next
    ) );

    return array(
        'prev' => count($posts_prev->posts) ? $posts_prev->posts[0] : NULL,
        'next' => count($posts_next->posts) ? $posts_next->posts[0] : NULL
    );
}

function learn_chapter_listing() {
  $chapters = new WP_Query( array(
    'post_type' => 'page',
    'post_parent' => '0',
    'orderby' => 'menu_order',
    'order' => 'asc',
    'nopaging' => true,
    'meta_query' => array(
      array(
      'key' => 'is_chapter',
      'compare' => 'NOT EXISTS'
      )
    )
  ));

  return $chapters;
}

function has_children( $p ) {
  $children = get_children( array(
    'post_type' => 'page',
    'post_parent' => $p->ID,
  ));

  return $children ? true : false;
}
function learn_nested_items( $chapter_id ) {

  $items = new WP_Query( array(
    'post_type' => 'page',
    'post_parent' => $chapter_id,
    'orderby' => 'menu_order',
    'order' => 'asc',
    'nopaging' => true,
  ));


  return $items;
}
