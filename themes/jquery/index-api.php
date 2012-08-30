<?php
/**
 * The main template file.
 */
?>

    <!-- body -->
    <?php global $sidebar; ?>
    <div id="body" class="clearfix <?php echo $sidebar; ?>">
      <div class="inner" role="main">
      <?php if ( have_posts() ) : ?>

        <?php // twentyeleven_content_nav( 'nav-above' ); ?>

        <?php /* Start the Loop */ ?>
        <?php while ( have_posts() ) : the_post(); ?>

          <?php get_template_part( 'content', 'api' ); ?>
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
        <?php get_sidebar( 'api' ); ?>
      <?php endif; ?>

    </div><!-- #body -->

