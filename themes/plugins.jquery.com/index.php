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
					<div class="entry-summary row">
						<div class="eight columns">
							<?php the_excerpt(); ?>
						</div>
						<div class="four columns">
							<a class="button" href="<?php the_permalink(); ?>">View Plugin</a>
						</div>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
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
