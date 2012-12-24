<?php
/*
 * Template Name: No Sidebar
 */
get_header(); ?>

<div id="body">
	<div class="inner" role="main">

		<?php the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	</div>
</div>

<?php get_footer(); ?>
