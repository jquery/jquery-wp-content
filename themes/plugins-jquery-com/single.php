<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="body" class="clearfix sidebar-left">
			<div class="inner" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- .inner -->

			<div id="sidebar" class="widget-area" role="complementary">
			</div><!-- #sidebar -->
		</div><!-- #body -->

<?php get_footer(); ?>