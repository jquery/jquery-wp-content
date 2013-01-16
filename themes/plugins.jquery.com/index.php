<?php get_header(); ?>

<div class="content-full full-width listing twelve columns">
	<div id="banner-secondary" class="large-banner">
		<h1>The jQuery Plugin Registry</h1>
		<?php get_search_form(); ?>
	</div>

	<div id="content">
		<div class="three columns">
			<aside class="widget">
				<h3><i class="icon-tags"></i>Popular Tags</h3>
				<?php jq_popular_tags(); ?>
			</aside>
		</div>

		<div class="six columns">
			<h2 class="center-txt"><i class="icon-star"></i>New Plugins</h2>
			<?php
			$new_plugins = new WP_Query( array(
				'post_type' => 'jquery_plugin',
				'post_parent' => 0,
				'order_by' => 'date',
				'order' => 'DESC'
			));
			while ( $new_plugins->have_posts() ) : $new_plugins->the_post();
				get_template_part( 'content', 'listing' );
			endwhile; wp_reset_postdata();
			?>
		</div>

		<div class="three columns">
			<aside>
				<h3><i class="icon-calendar"></i>Recent Updates</h3>
				<?php jq_updated_plugins(); ?>
			</aside>
		</div>
	</div>
</div>

<?php get_footer(); ?>
