<?php 
global $sidebar;
$sidebar = "sidebar-right";
$site = str_replace(".", "-", str_replace("https://", "", str_replace("http://", "", get_site_url())));

$tlsite = "jquery";
if (stristr($site, "jqueryui-com")) {
  $tlsite = "jquery-ui";
}
if (stristr($site, "jquery-org")) {
  $tlsite = "jquery-project";
}
if (stristr($site, "jquerymobile-com")) {
  $tlsite = "jquery-mobile";
}
if (stristr($site, "learn-jquery-com")) {
  $tlsite = "jquery-learning";
}
if (stristr($site, "sizzlejs-com")) {
  $tlsite = "sizzlejs";
}
if (stristr($site, "qunitjs-com")) {
  $tlsite = "qunitjs";
}
?>
<!DOCTYPE html> 
<!--[if lt IE 7 ]> <html class="no-js ie6 <?php echo $site . " " . $tlsite; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7 <?php echo $site . " " . $tlsite; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8 <?php echo $site . " " . $tlsite; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="no-js ie9 <?php echo $site . " " . $tlsite; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js <?php echo $site . " " . $tlsite; ?>" <?php language_attributes(); ?>> <!--<![endif]-->
<head> 

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	?></title> 
 
<meta name="author" content="The jQuery Project - jQuery.org"> 
<meta name="description" content="jQuery: The Write Less, Do More, JavaScript Library"> 

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">
<link rel="stylesheet" href="<?php echo content_url() ?>/base/css/style.css?v=1">
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<script src="<?php echo content_url(); ?>/base/js/respond.min.js"></script>
<script src="<?php echo content_url(); ?>/base/js/modernizr-1.5.min.js"></script>
<!--[if (gte IE 6)&(lte IE 8)]>
<script src="<?php echo content_url(); ?>/base/js/selectivizr.js"></script>
<![endif]-->

<?php 
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	
	wp_head(); 
?>

</head> 
<body <?php body_class(); ?>>

<!-- projects -->
<div class="project-select">
  <ul class="constrain"> 
    <li>
      <a href="http://jquery.com/" title="" class=""> 
        <img src="<?php echo content_url(); ?>/base/i/logo-top-jquery.png" alt="jQuery" /> 
        <em>The core JS framework that allows you to write less, do more.</em> 
      </a>
    </li> 
    <li>
      <a href="http://jqueryui.com/" title="" class=""> 
        <img src="<?php echo content_url(); ?>/base/i/logo-top-ui.png" alt="jQuery UI" /> 
        <em>The officially supported User Interface library for jQuery.</em> 
      </a>
    </li> 
    <li>
      <a href="http://jquerymobile.com/" title="" class=""> 
        <img src="<?php echo content_url(); ?>/base/i/logo-top-mobile.png" alt="jQuery Mobile"> 
        <em>Build mobile web apps with jQuery using this framework.</em> 
      </a>
    </li> 
    <li>
      <a href="http://sizzlejs.com/" title="" class=""> 
        <img src="<?php echo content_url(); ?>/base/i/logo-top-sizzle.png" alt="SizzleJS" /> 
        <em>A smoking fast CSS selector engine for JavaScript.</em> 
      </a>
    </li> 
    <li>
      <a href="http://qunitjs.com/" title="" class=""> 
        <img src="<?php echo content_url(); ?>/base/i/logo-top-qunit.png" alt="QUnit" /> 
        <em>Write solid JavaScript apps by unit testing with QUnit.</em> 
      </a>
    </li>
  </ul>
</div>
<!-- /projects -->

<!-- nav -->
<header class="border clearfix">
  
  <nav class="constrain clearfix top">
    <ul class="projects">
      <li class="jquery"><a href="http://jquery.com/" title="jQuery">jQuery</a></li>
      <li class="jquery-ui"><a href="http://jqueryui.com/" title="jQuery UI">jQuery UI</a></li>
      <li class="jquery-mobile"><a href="http://jquerymobile.com/" title="jQuery Mobile">jQuery Mobile</a></li>
      <li class="toggle-projects"><a href="#" title="All Projects">All Projects</a></li>
    </ul>
    <ul class="links">
      <li><a href="http://learn.jquery.com/">Learning</a></li>
      <li class="dropdown"><a href="#" title="Support">Support</a>
        <ul>
          <li><a href="#" title="Forum">Forum</a></li>
          <li><a href="#" title="IRC/Chat">IRC/Chat</a></li>
          <li><a href="#" title="Getting Help">Getting Help</a></li>
          <li><a href="#" title="Report a Bug">Report a Bug</a></li>
          <li><a href="#" title="Enterprise Support">Enterprise Support</a></li>
        </ul>
      </li>
      <li class="dropdown"><a href="#" title="Community">Community</a>
        <ul>
           <li><a href="http://blog.jquery.com/" title="Blog">Blog</a></li>
               <li><a href="#" title="Podcast">Podcast</a></li>
               <li><a href="#" title="Forums">Forums</a></li>
               <li><a href="#" title="Meetups">Meetups</a></li>
               <li><a href="#" title="Events">Events</a></li>
        </ul>
      </li>
      <li class="dropdown"><a href="#" title="Contribute">Contribute</a>
        <ul>
          <li><a href="http://jquery.org/donate/" title="Donate">Donate</a></li>
          <li><a href="#">Fix Bugs</a></li>
          <li><a href="#">Answer Questions</a></li>
          <li><a href="#">Write Documentation</a></li>
        </ul>
      </li>
      <li class="dropdown"><a href="http://jquery.org/about/" title="">About</a>
        <ul class="last">
          <li><a href="http://jquery.org/" title="Overview">Overview</a></li>
              <li><a href="#" title="Projects">Projects</a></li>
              <li><a href="http://jquery.org/team/" title="Team">Team</a></li>
              <li><a href="http://jquery.org/history/" title="History">History</a></li>
              <li><a href="http://jquery.org/sponsors/" title="Sponsors">Sponsors</a></li>
              <li><a href="#" title="Contact">Contact</a></li>
        </ul>
      </li>
    </ul>
  </nav>
  
</header>
<!-- /nav -->

<!-- container --> 
<div id="container" class="constrain"> 
  
  <!-- header -->
  <header class="clearfix">
  
    <!-- logo -->
    <h1 class="site-title"><a href="/" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
    <!-- /logo -->
    
    <!-- ads or events -->
    <aside></aside>
    <!-- /ads  or events -->
    


    <!-- secondary nav -->
    <nav class="clearfix">

      <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

      <?php get_search_form(); ?>
    </nav>
    <!-- /secondary nav -->

  </header>
  <!-- /header -->
