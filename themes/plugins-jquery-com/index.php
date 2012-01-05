<?php get_header(); ?>
		
  <!-- body -->
  <div id="body" class="clearfix">
    
    <!-- inner -->
    <div class="inner">
  
<?php

// TODO
//
// Make a much more interesting index page
//
// TODO

$toplvlpages = get_pages( array( 'parent' => 0 ) );
foreach( $toplvlpages as $post ) {
	setup_postdata($post);
	if ( $post->post_name !== 'update' ) {
	   get_template_part('excerpt', 'index');
	}
}
?>
  
    </div>
    <!-- /inner -->
  </div>
  <!-- /body -->

<?php get_footer(); ?>
