<?php get_header(); ?>
<?php $next_prev_arr = get_next_prev_post(); ?>

  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">
    
    <!-- inner -->
    <div class="inner">
				<div class="beta-ribbon-wrapper"><div class="beta-ribbon">Beta</div></div>
				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
    	<?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

<?php get_footer(); ?>
