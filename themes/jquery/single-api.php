<?php
/**
 * The Template for displaying single posts in API sites.
 */
?>

<div class="content-right twelve columns">
	<div id="content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', 'single-api' ); ?>
		<?php endwhile; ?>
	</div>

	<?php get_sidebar(); ?>
</div>
