<?php get_header(); ?>

  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">

    <!-- inner -->
    <div class="inner">
				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

		    <?php if($sidebar): ?>
		    	<?php get_sidebar(); ?>
		    <?php endif; ?>

    </div>
    <!-- /inner -->

  </div>
  <!-- /body -->

<?php get_footer(); ?>
