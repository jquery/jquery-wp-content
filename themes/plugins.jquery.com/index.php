<?php get_header(); ?>

<div class="content-left listing row collapse">
	<div id="banner-secondary" class="large-banner">
		<h1>Plugins Make jQuery More Awesomer</h1>
		<p>Level up your project, not your grammar</p>
		<?php get_search_form(); ?>
	</div>

	<div id="content">
		<div class="four columns">
			<aside class="widget">
				<h3><i class="icon-tags"></i>Popular Tags</h3>
				<ul><?php
					$tags = get_tags( array(
						'orderby' => 'count',
						'order' => 'DESC',
						'number' => 10
					));
					foreach ( $tags as $tag ) {
						echo
						'<li>' .
							'<a href="' . get_tag_link( $tag->term_id ) . '">' .
								$tag->name . '</a>' .
							' (' . $tag->count . ')' .
						'</li>';
					}
				?></ul>
			</aside>
		</div>

		<div class="eight columns">
			<h2 class="center-txt"><i class="icon-star"></i>New Plugins</h2>
			<?php
			$new_plugins = new WP_Query( array(
				'post_type' => 'jquery_plugin',
				'post_parent' => 0,
				'order_by' => 'date',
				'order' => 'DESC'
			));
			while ( $new_plugins->have_posts() ) : $new_plugins->the_post();
			?>
				<article class="hentry clearfix">
					<header class="entry-header">
						<div class="entry-meta">
							Updated <?php the_date(); ?>
						</div>
						<h3 class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
					</head>
					<div class="entry-summary row collapse">
						<div class="eight columns">
							<?php the_excerpt(); ?>
						</div>
						<div class="four columns">
							<a class="button" style="float:right" href="<?php the_permalink(); ?>">View Plugin</a>
						</div>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>

	<div id="sidebar" class="widget-area" role="complementary">
		<aside>
			<h3><i class="icon-calendar"></i>Recent Updates</h3>
			<?php jq_updated_plugins(); ?>
		</aside>
	</div>
</div>

<?php get_footer(); ?>
