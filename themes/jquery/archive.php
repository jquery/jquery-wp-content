<?php get_header(); ?>

<div class="content-right twelve columns">
	<div id="content">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<h1 class="page-title">
				<?php if ( is_day() ) : ?>
					<?php printf( __( 'Daily Archives: %s', 'twentyeleven' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'twentyeleven' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'twentyeleven' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
				<?php else : ?>
					<?php _e( 'Blog Archives', 'twentyeleven' ); ?>
				<?php endif; ?>
			</h1>
			<hr class="dots">
		</header>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content' );
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
