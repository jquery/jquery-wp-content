<?php
/*
 * Template Name: jQuery Boston 2010
 */

the_post();
$noSidebar = $post->post_name === 'sponsors';

include( 'boston-2010/header.php' );
the_content();
include( 'boston-2010/footer.php' );
