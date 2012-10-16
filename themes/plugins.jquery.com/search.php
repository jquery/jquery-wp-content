<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

<div id="content">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php printf( __("Search Results for '%s'", 'twentyeleven'), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content-listing', get_post_format() ); ?>
		<?php endwhile; ?>

		<div class="nav-previous">
			<?php next_posts_link( '&larr; Older plugins' ); ?>
		</div>
		<div class="nav-next">
			<?php previous_posts_link( 'Newer plugins &rarr;' ); ?>
		</div>

	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<p>Sorry, but nothing matched your search criteria.</p>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->

	<?php endif; ?>

</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
