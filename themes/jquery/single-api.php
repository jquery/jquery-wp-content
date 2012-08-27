<?php
/**
 * The Template for displaying single posts in API sites.
 */
 ?>
<?php global $sidebar; ?>
    <div id="body" class="clearfix <?php echo $sidebar; ?>">
      <div class="inner" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

          <?php get_template_part( 'content', 'single-api' ); ?>
          <?php
          // typically, we'd use the following here:
          // comments_template( '', true )
          // however, API sites aren't using comments.
          ?>
        <?php endwhile; // end of the loop. ?>

      </div><!-- .inner -->
			<?php if($sidebar): ?>
				<?php get_sidebar( 'api' ); ?>
			<?php endif; ?>

    </div><!-- #body -->
