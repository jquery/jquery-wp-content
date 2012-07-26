<?php get_header(); ?>

	<!-- body -->
	<div id="body" class="clearfix">

		<!-- inner -->
		<div class="inner">

<div id="banner">
	<h1>Plugins Make jQuery More Awesomer</h1>
	<h2>Level up your project, not your grammar</h2>
	
	<?php get_search_form(); ?>
	
</div>

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

		</div> <!-- /.inner -->
		 <h3>New Plugins</h3>
		  <?php jq_new_plugins(); ?>
		  <h3>Recent Updates</h3>
		  <?php jq_updated_plugins(); ?>
		
		  <h3>Popular Tags</h3>
		  <ul>
		  <?php
		  $tags_args = array('orderby' => 'count', 'order' => 'DESC', 'number' => 10);
		  $tags = get_tags( $tags_args );
		  $tag_html = '';
		  foreach ($tags as $tag){
		    $tag_link = get_tag_link($tag->term_id);
		    $tag_html .= '<li>';
		    $tag_html .= '<a href="' . $tag_link . '">';
		      $tag_html .= $tag->name;
		    $tag_html .= '</a>';
		    $tag_html .=  $tag->count;
		    $tag_html .= '</li>';
		  }
		
		  echo $tag_html;
		  ?>
		  </ul>

	</div>
	<!-- /body -->

<?php get_footer(); ?>
