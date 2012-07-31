<?php get_header(); ?>

	<!-- body -->
	<div id="body" class="clearfix">

		<!-- inner -->
		<div class="inner" class="clearfix">

<div id="banner">
	<div class="glow">
	<h1>Plugins Make jQuery More Awesomer</h1>
	<h2>Level up your project, not your grammar</h2>
	
	<?php get_search_form(); ?>
	</div>
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
		
		<div id="content" class="clearfix">
		 <h3><i class="icon-star"></i>New Plugins</h3>
		  <!--<?php jq_new_plugins(); ?>-->
		  <article class="clearfix">
		  	<h4><a href="#">jQuery Color</a></h4>
		  	<p>This is a description of the plugin. It’s great and awesome and bacon-flavored. Tasty? Yes. You’d better believe it suckah. I need another sentence to make this look nice...</p>
		  	<div class="action">
		  	<a class="button" href="#">View Plugin</a>
		  	<p class="date">Updated 3/23/12</p>
		  	</div>
		  </article>
		  
		  <article class="clearfix">
		  	<h4><a href="#">jQuery Color</a></h4>
		  	<p>This is a description of the plugin. It’s great and awesome and bacon-flavored. Tasty? Yes. You’d better believe it suckah. I need another sentence to make this look nice...</p>
		  	<div class="action">
		  	<a class="button" href="#">View Plugin</a>
		  	<p class="date">Updated 3/23/12</p>
		  	</div>
		  </article>
		  
		  <article class="clearfix">
		  	<h4><a href="#">jQuery Color</a></h4>
		  	<p>This is a description of the plugin. It’s great and awesome and bacon-flavored. Tasty? Yes. You’d better believe it suckah. I need another sentence to make this look nice...</p>
		  	<div class="action">
		  	<a class="button" href="#">View Plugin</a>
		  	<p class="date">Updated 3/23/12</p>
		  	</div>
		  </article>
		 </div>
		   
		  
		<div class="sidebar left">
		  <h3><i class="icon-tags"></i>Popular Tags</h3>
		  <ul>
		  <?php
		  $tags_args = array('orderby' => 'count', 'order' => 'DESC', 'number' => 10);
		  $tags = get_tags( $tags_args );
		  $tag_html = '';
		  foreach ($tags as $tag){
		    $tag_link = get_tag_link($tag->term_id);
		    $tag_html .= '<li class="icon-caret-right">';
		    $tag_html .= '<a href="' . $tag_link . '">';
		      $tag_html .= $tag->name;
		    $tag_html .= '</a>';
		     $tag_html .= ' (';
		    $tag_html .=  $tag->count;
		     $tag_html .= ')';
		    $tag_html .= '</li>';
		  }
		
		  echo $tag_html;
		  ?>
		  </ul>
		</div>
		
		
		<div class="sidebar right">
			<h3><i class="icon-calendar"></i>Recent Updates</h3>
			<?php jq_updated_plugins(); ?>
		</div> 
		
	</div>
	<!-- /body -->

<?php get_footer(); ?>
