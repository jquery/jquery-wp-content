<?php
/**
 * The Template for displaying single posts in API sites.
 */
 ?>

<div class="content-right">
    <div id="content">

        <?php while ( have_posts() ) : the_post(); ?>

          <?php get_template_part( 'content', 'single-api' ); ?>
          <?php
          // typically, we'd use the following here:
          // comments_template( '', true )
          // however, API sites aren't using comments.
          ?>
        <?php endwhile; // end of the loop. ?>
    </div><!-- #content -->

		<?php get_sidebar(); ?>
</div>
