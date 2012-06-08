<?php
/**
 * The template for displaying Category Archive pages.
 */

get_header(); ?>

    <?php global $sidebar; ?>
    <section id="body" class="clearfix <?php echo $sidebar; ?>">
			<div class="inner" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php
						printf( __( '%s', 'twentyeleven' ), '<span>' . single_cat_title( '', false ) . '</span>' );
					?></h1>

					<?php
						$category_description = category_description();
						if ( ! empty( $category_description ) )
							echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
					?>
				</header>

				<?php //twentyeleven_content_nav( 'nav-above' ); ?>

				<?php /* Start the Loop */ ?>
        <?php
        $args = array(
          'orderby' => 'slug',
          'order' => 'ASC',
          'posts_per_page' => -1
        );
        $args = array_merge( $wp_query->query, $args );
        query_posts( $args );

        ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						get_template_part( 'content' );
					?>

				<?php endwhile; ?>

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
      <?php if($sidebar): ?>
            <?php get_sidebar(); ?>
      <?php endif; ?>

		</section><!-- #body -->

<?php get_footer(); ?>
