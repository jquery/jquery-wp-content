<?php
global $sidebar;
$sidebar = "sidebar-left";
?>
<!DOCTYPE html>
<!--[if IE 7 ]>		 <html class="no-js ie ie7 lte7 lte8 lte9" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>		 <html class="no-js ie ie8 lte8 lte9" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>		 <html class="no-js ie ie9 lte9>" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
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

<meta name="viewport" content="width=device-width">

<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/base.css?v=1">
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 7]><link rel="stylesheet" href="css/font-awesome-ie7.css"><![endif]-->

<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.custom.2.0.6.min.js"></script>
<script type="text/javascript" src="//use.typekit.net/rib7dfk.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>

</head>
<body id="body-<?php echo $tlsite; ?>" <?php body_class(); ?>>

<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->

<!--Header =================================================================-->

	<header>
       
       <section id="global-nav">
       
        <div id="global-project-select">
        	<div class="constrain">
	        	<ul>
	        		<li>
	        			<a href="http://dev.jquery.com/">
	        				<img src="<?php echo get_template_directory_uri(); ?>/images/global-nav-logo-jquery.png" alt="jQuery" />
	        				<em>The core JS framework that lets you write less &amp; do more.</em>
	        			</a>
	        		</li>
	        		<li>
	        			<a href="http://dev.jqueryui.com/">
	        				<img src="<?php echo get_template_directory_uri(); ?>/images/global-nav-logo-ui.png" alt="jQuery UI" />
	        				<em>The officially supported User Interface library for jQuery.</em>
	        			</a>
	        		</li>
	        		<li>
	        			<a href="http://dev.jquerymobile.com/">
	        				<img src="<?php echo get_template_directory_uri(); ?>/images/global-nav-logo-mobile.png" alt="jQuery Mobile">
	        				<em>Build mobile web apps with jQuery using this framework.</em>
	        			</a>
	        		</li>
	        		<li>
	        			<a href="http://dev.sizzlejs.com/">
	        				<img src="<?php echo get_template_directory_uri(); ?>/images/global-nav-logo-sizzle.png" alt="SizzleJS" />
	        				<em>A smoking fast CSS selector engine for JavaScript.</em>
	        			</a>
	        		</li>
	        		<li>
	        			<a href="http://dev.qunitjs.com/">
	        				<img src="<?php echo get_template_directory_uri(); ?>/images/global-nav-logo-qunit.png" alt="QUnit" />
	        				<em>Write solid JavaScript apps by unit testing with QUnit.</em>
	        			</a>
	        		</li>
	        	</ul>
        	</div><!--End Constrain-->
        </div>
        
        
        
        	<nav>
        		<div class="constrain">
	        		<ul class="projects">
	        			<li class="jquery current"><a href="http://dev.jquery.com/">jQuery</a></li>
	        			<li class="jquery-ui"><a href="http://dev.jqueryui.com/">jQuery UI</a></li>
	        			<li class="jquery-mobile"><a href="http://dev.jquerymobile.com/">jQuery Mobile</a></li>
	        			<li class="toggle-projects"><a href="http://dev.jquery.org/projects/">All Projects <i class="icon-caret-down"></i></a></li>
	        		</ul>
	        		<ul class="links">
	           			<li class="dropdown"><a href="#">Support</a>
	        				<ul>
	        					<li><a href="http://forum.jquery.com/">Forum</a></li>
	        					<li><a href="http://irc.jquery.com/">IRC/Chat</a></li>
	        					<li><a href="http://stackoverflow.com/tags/jquery/info">Stack Overflow</a></li>
	           				</ul>
	        			</li>
	        			<li class="dropdown"><a href="#" title="Community">Community</a>
	        				<ul>
	        					<li><a href="http://dev.blog.jquery.com/">Blog</a></li>
	        					<li><a href="http://forum.jquery.com/">Forums</a></li>
	        					<li><a href="http://meetups.jquery.com/">Meetups</a></li>
	        					<li><a href="http://dev.events.jquery.org/">Events</a></li>
	        				</ul>
	        			</li>
	        			<li class="dropdown"><a href="#" >Contribute</a>
	        				<ul>
	        					<li><a href="http://dev.jquery.org/donate/" >Donate</a></li>
	        					<li><a href="http://dev.jquery.org/sponsor/">Sponsor</a></li>
	          				</ul>
	        			</li>
	        			<li class="dropdown"><a href="http://dev.jquery.org/about/">About</a>
	        				<ul class="last">
	        					<li><a href="http://dev.jquery.org/projects/">Projects</a></li>
	        					<li><a href="http://dev.jquery.org/team/">Team</a></li>
	        					<li><a href="http://dev.jquery.org/history/">History</a></li>
	        					<li><a href="http://dev.jquery.org/sponsors/">Sponsors</a></li>
	        				</ul>
	        			</li>
	        		</ul>
        		</div><!--End constrain-->
        	</nav>
        	
        	</section><!--End Global Nav-->
        </header>
        <!--End Header =================================================================-->

        <!--Container ======================================================================-->
        <div id="container">	        	
        	
        	<div id="logo-events" class="constrain clearfix">
        	
        	<h2 class="logo"><a href="/" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h2>
        	<!--Ads or events-->
        	<aside></aside>
        	
       		</div><!--End Constrain-->
       		
       		
       		
	        <!--Main Nav ===================================================================-->
	    	<nav id="main" class="constrain clearfix">
	    		<?php get_template_part('menu', 'header'); ?>
	    		
	    		<?php get_search_form(); ?>
	    	</nav>        
	        <!--End Main Nav =================================================================-->
	        
	 	        
	        <!--Content Wrapper ===============================================================-->
	        <div id="content-wrapper" class="clearfix">

