<?php
/**
 * The template for displaying Search Results pages.
 */
?>
<?php global $sidebar; ?>

	<section id="body" class="clearfix <?php echo $sidebar; ?>">
	<div class="inner" role="main">

	<?php
		$searchquery = get_search_query();
		$searchquery = preg_replace('/\$/', 'jQuery', $searchquery);
		$featuredlist = array();
		$entrylist = array();
	?>

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php
				printf( __( 'Search Results for: %s', 'twentyeleven' ), '<span>' . get_search_query() . '</span>' );
			?></h1>
			<hr class="dots">
		</header>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'api' );
			endwhile;
		?>

		<?php twentyeleven_content_nav( 'nav-below' ); ?>

	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<p><?php _e( 'Apologies, but nothing matched your search criteria.', 'twentyeleven' ); ?></p>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->

	<?php endif; ?>

	</div><!-- .inner -->

	<?php if ( $sidebar ) : get_sidebar( 'api' ); endif; ?>

</section><!-- #body -->
