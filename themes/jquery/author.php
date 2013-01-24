<?php
/**
 * The template for displaying Author Archive pages.
 */

get_header(); ?>

<div class="content-right twelve columns">
	<div id="content">
	<?php if ( have_posts() ) : ?>

		<?php
			/* Queue the first post, that way we know
			 * what author we're dealing with (if that is the case).
			 *
			 * We reset this later so we can run the loop
			 * properly with a call to rewind_posts().
			 */
			the_post();
		?>

		<header class="page-header">
			<h1 class="page-title author"><?php
				printf( __( 'Author Archives: %s', 'twentyeleven' ), get_the_author() );
			?></h1>
		</header>

		<?php
			/* Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();
		?>

		<?php
		// If a user has filled out their description, show a bio on their entries.
		if ( get_the_author_meta( 'description' ) ) : ?>
		<div id="author-info">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 60 ) ); ?>
			</div>
			<div id="author-description">
				<h2><?php printf( __( 'About %s', 'twentyeleven' ), get_the_author() ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
			</div>
		</div>
		<?php endif; ?>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'listing' );
			endwhile;
		?>

		<?php echo jq_content_nav(); ?>

	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
			</header>

			<div class="entry-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive.', 'twentyeleven' ); ?></p>
			</div>
		</article>

	<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
