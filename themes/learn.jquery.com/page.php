<?php get_header(); ?>
		
  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">
    
    <!-- inner -->
    <div class="inner">
				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>
				<h2>Suggestions, Problems, Feedback</h2>
				<a class="btn" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i>  Open an Issue or Submit a Pull Request on GitHub</a>

    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
    	<?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

<?php get_footer(); ?>
