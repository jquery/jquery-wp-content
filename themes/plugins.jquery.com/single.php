<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

<div id="content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'single' ); ?>
	<?php endwhile; ?>
</div><!-- #content -->

<div id="sidebar" role="complementary"></div>

<?php get_footer(); ?>