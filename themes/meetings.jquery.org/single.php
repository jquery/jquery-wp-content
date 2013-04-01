<?php
/**
 * The Template for displaying all single posts.
 */

get_header(); ?>

<div class="content-right twelve columns">
	<div id="content">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

					<hr>

				<?php endwhile; // end of the loop. ?>

	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
