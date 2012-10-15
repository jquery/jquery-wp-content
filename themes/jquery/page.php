<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

get_header(); ?>

		<div id="body" class="clearfix">
			<div class="inner" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

				<h2>Suggestions, Problems, Feedback</h2>
				<a class="btn" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i>  Open an Issue or Submit a Pull Request on GitHub</a>

			</div><!-- .inner -->
		</div><!-- #body -->

<?php get_footer(); ?>
