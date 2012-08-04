<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="body" class="clearfix">
			<div class="inner" role="main">
				<div class="notify">
					<p><i class="icon-warning-sign"></i><span>This version is old school. To get the latest version<a href="#">go here</a></span></p>
					<a class="close"><i class="icon-remove-circle"></i></a>
				</div>
				<?php the_post(); ?>

				<?php get_template_part( 'content', 'jquery_plugin' ); ?>

			</div><!-- .inner -->
		</div><!-- #body -->

<?php get_footer(); ?>
