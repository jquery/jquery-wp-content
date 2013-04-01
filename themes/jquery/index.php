<?php
/**
 * The main template file.
 */

get_header(); ?>

<div class="content-right twelve columns listing">
	<div id="content">
	<?php if ( have_posts() ) : ?>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content' );
			endwhile;
		?>

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