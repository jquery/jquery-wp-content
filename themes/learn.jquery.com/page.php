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

				<?php comments_template( '', true ); ?>
				<h2>Suggestions, Problems, Feedback</h2>
				<a class="btn" href="<?php echo jq_get_github_url(); ?>"><i class="icon-github"></i>  Open an Issue or Submit a Pull Request on GitHub</a>


        <div class='bottom-links'>
            <?php if (isset($next_prev_arr['prev'])): ?>
                   <div class='prev'>
                       <a href="<?php echo $next_prev_arr['prev']->guid; ?>">
                           <i class="icon-chevron-left"></i>
                           <?php echo $next_prev_arr['prev']->post_title; ?>
                       </a>
                   </div>
            <?php endif; ?>
            <?php if (isset($next_prev_arr['next'])): ?>
                   <div class='next'>
                       <a href="<?php echo $next_prev_arr['next']->guid; ?>">
                           <?php echo $next_prev_arr['next']->post_title; ?>
                           <i class="icon-chevron-right"></i>
                       </a>
                   </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
    	<?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

<?php get_footer(); ?>
