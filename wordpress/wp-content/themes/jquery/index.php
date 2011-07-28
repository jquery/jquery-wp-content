<?php get_header(); ?>
		
<!-- container --> 
<div id="container" class="constrain"> 
  
  <!-- header -->
  <header class="clearfix">
  
    <!-- logo -->
    <h1><a href="#" title="jQuery">jQuery</a></h1>
    <!-- /logo -->
    
    <!-- ads or events -->
    <aside></aside>
    <!-- /ads  or events -->
    
    <!-- secondary nav -->
    <nav class="clearfix">
      <ul>
        <li class="active"><a href="#" title="Overview">Overview</a></li>
        <li><a href="#" title="Plugins">Plugins</a></li>
        <li><a href="#" title="API">API</a></li>
        <li><a href="#" title="Documentation">Documentation</a></li>
        <li><a href="#" title="Development">Development</a></li>
        <li><a href="#" title="Download">Download</a></li>
      </ul>
      <form method="get" action="" class="search">
        <input type="text" id="search" name="search"></li>
        <label for="search" class="text">Search jQuery.com</label> 
        <a href="#" class="icon icon-search" title="Submit Search">Submit Search</a>
      </form>
    </nav>
    <!-- /secondary nav -->

  </header>
  <!-- /header -->
  
  <!-- body -->
  <?php global $sidebar; ?>
  <div id="body" class="clearfix <?php echo $sidebar; ?>">
    
    <!-- inner -->
    <div class="inner">
    
	        
	       
    </div>
    <!-- /inner -->
    
    <?php if($sidebar): ?>
    	<?php get_sidebar(); ?>
    <?php endif; ?>
    
  </div>
  <!-- /body -->

</div>
<!-- /container -->
<?php get_footer(); ?>