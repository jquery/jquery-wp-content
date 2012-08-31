<?php
/*
Partial Category Archive Template Called by API Sites
*/
?>
<?php global $sidebar; ?>

	<section id="body" class="clearfix <?php echo $sidebar; ?>">
	<div class="inner" role="main">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php
				printf( __( '%s', 'twentyeleven' ), '<span>' . single_cat_title( '', false ) . '</span>' );
			?></h1>
			<hr class="dots">
			<?php
				$category_description = category_description();
				if ( ! empty( $category_description ) )
					echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
			?>
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
				<p><?php _e( 'Apologies, but no results were found for the requested archive.', 'twentyeleven' ); ?></p>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->

	<?php endif; ?>

	</div><!-- .inner -->

	<?php if ( $sidebar ) : get_sidebar( 'api' ); endif; ?>

</section><!-- #body -->
