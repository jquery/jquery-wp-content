<?php get_header(); ?>
		
  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">
    
    <!-- inner -->
    <div class="inner">
    
	<h2 class="title">The jQuery Learning Site</h2>
									
	<p>This is going to be a site about learning jQuery, JavaScript, and the assortment of web technologies that use them.</p>
	       
    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
    	<?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

<?php get_footer(); ?>
