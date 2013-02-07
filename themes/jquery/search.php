<?php
/**
 * The template for displaying Search Results pages.
 */
?>
<?php get_header(); ?>

<div class="content-right twelve columns">
	<div id="content">

		<header class="page-header">
			<h1 class="page-title"><?php
				printf( __( 'Search Results for: %s', 'twentyeleven' ), '<span>' . get_search_query() . '</span>' );
			?></h1>
			<hr>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'listing' );
				endwhile;

				echo jq_content_nav();
			?>
		<?php else : ?>
			<article id="post-0" class="post no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'Apologies, but nothing matched your search criteria.', 'twentyeleven' ); ?></p>
				</div>
			</article>
		<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
