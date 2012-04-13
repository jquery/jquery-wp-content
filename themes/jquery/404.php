<?php
/**
 * The template for displaying 404 pages (Not Found).
 */

get_header(); ?>

	<div id="body">
		<div class="inner" role="main">

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'twentyeleven' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'twentyeleven' ); ?></p>

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		</div><!-- .inner -->
	</div><!-- #body -->

<?php get_footer(); ?>