<?php
/**
 * The template used to display Tag Archive pages
 */

get_header(); ?>

<div class="content-right twelve columns">
	<div id="content">
	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php
				printf( __( 'Tagged: %s', 'twentyeleven' ), '<span>' . single_tag_title( '', false ) . '</span>' );
			?></h1>
			<hr>
			<?php
				$tag_description = tag_description();
				if ( ! empty( $tag_description ) ) {
					echo apply_filters( 'tag_archive_meta',
						'<div class="tag-archive-meta">' . $tag_description . '</div>' );
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
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</article>

	<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
