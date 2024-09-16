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

			$current_url = home_url(add_query_arg(null, null));

			if (jq_is_version_deprecated($current_url)) {

			?>

			<div id="support-warning-box" class="warning"><svg width="20" height="20" viewBox="0 0 24 24">
				<circle cx="12" cy="12" r="10" fill="none" stroke="black" stroke-width="2" />
				<text x="50%" y="57%" dominant-baseline="middle" text-anchor="middle">i</text>
				</svg>&nbsp;&nbsp;This version is End-of-Life. Read more about support options&nbsp;<a href="https://jquery.com/support/">here</a>.
			</div>

		  <?php

			}

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
