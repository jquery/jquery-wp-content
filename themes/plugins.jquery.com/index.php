<?php get_header(); ?>

<div class="home">

	<div id="banner-secondary" class="large-banner">
		<h1>Plugins Make jQuery More Awesomer</h1>
		<p>Level up your project, not your grammar</p>
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

	<div class="info-bar">
		<h3><i class="icon-tags"></i>Popular Tags</h3>
		<ul><?php
			$tags_args = array(
				'orderby' => 'count',
				'order' => 'DESC',
				'number' => 10
			);
			$tags = get_tags( $tags_args );
			foreach ( $tags as $tag ) {
				echo
				'<li>' .
					'<a href="' . get_tag_link( $tag->term_id ) . '">' .
						$tag->name . '</a>' .
					' (' . $tag->count . ')' .
				'</li>';
			}
		?></ul>
	</div>

	<div id="content">
		<h3><i class="icon-star"></i>New Plugins</h3>
		<?php
		$new_plugins = new WP_Query( array(
			'post_type' => 'jquery_plugin',
			'post_parent' => 0
		));
		while ( $new_plugins->have_posts() ) : $new_plugins->the_post();
		?>
			<article class="clearfix">
				<h4 class="plugin-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h4>
				<?php the_excerpt(); ?>
				<div class="action">
					<a class="button" href="<?php the_permalink(); ?>">View Plugin</a>
					<p class="date">Updated <?php the_date(); ?></p>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>

	<div id="sidebar">
		<h3><i class="icon-calendar"></i>Recent Updates</h3>
		<?php jq_updated_plugins(); ?>
	</div>

</div>

<?php get_footer(); ?>
