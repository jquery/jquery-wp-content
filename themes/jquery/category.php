<?php
/**
 * The template for displaying Category Archive pages.
 */

get_header(); ?>

<div class="content-right twelve columns">
	<div id="content">
	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php
				printf( __( 'Category: %s', 'twentyeleven' ), '<span>' . single_cat_title( '', false ) . '</span>' );
			?></h1>
			<hr>
			<?php
				$category_description = category_description();
				if ( ! empty( $category_description ) ) {
					echo apply_filters( 'category_archive_meta',
						'<div class="category-archive-meta">' . $category_description . '</div>' );
				}
			?>
		</header>

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
