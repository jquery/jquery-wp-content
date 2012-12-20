<?php
/**
 * The Template for displaying all single posts.
 */

get_header(); ?>

		<div id="body" class="clearfix">
			<div class="inner" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

					<hr>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- .inner -->
		</div><!-- #body -->

<?php get_footer(); ?>
