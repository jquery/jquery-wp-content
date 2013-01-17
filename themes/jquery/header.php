<!doctype html>
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

	<meta name="author" content="jQuery Foundation - jquery.org">
	<meta name="description" content="jQuery: The Write Less, Do More, JavaScript Library">

	<meta name="viewport" content="width=device-width">

	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/base.css?v=1">
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if lt IE 7]><link rel="stylesheet" href="css/font-awesome-ie7.css"><![endif]-->

	<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.custom.2.6.2.min.js"></script>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>window.jQuery || document.write(unescape('%3Cscript src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.8.3.min.js"%3E%3C/script%3E'))</script>

	<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

	<script src="//use.typekit.net/wde1aof.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>

<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>

</head>
<body <?php body_class(); ?>>

<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->

<header>
	<section id="global-nav">
		<nav>
			<div class="constrain">
				<ul class="projects">
					<li class="project jquery"><a href="http://jquery.com/" title="jQuery">jQuery</a></li>
					<li class="project jquery-ui"><a href="http://jqueryui.com/" title="jQuery UI">jQuery UI</a></li>
					<li class="project jquery-mobile"><a href="http://jquerymobile.com/" title="jQuery Mobile">jQuery Mobile</a></li>
					<li class="project sizzlejs"><a href="http://sizzlejs.com/" title="Sizzle">Sizzle</a></li>
					<li class="project qunitjs"><a href="http://qunitjs.com/" title="QUnit">QUnit</a></li>
				</ul>
				<ul class="links">
					<li><a href="http://plugins.jquery.com/">Plugins</a></li>
					<li class="dropdown"><a href="http://events.jquery.org/">Events</a>
						<ul>
							<li><a href="http://events.jquery.org/2013/eu/">Europe</a></li>
							<li><a href="http://jqueryto.com">Toronto</a></li>
							<li><a href="http://events.jquery.org/2013/UK/">UK</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="#">Support</a>
						<ul>
							<li><a href="http://irc.jquery.com/">IRC/Chat</a></li>
							<li><a href="http://forum.jquery.com/">Forums</a></li>
							<li><a href="http://stackoverflow.com/tags/jquery/info">Stack Overflow</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="http://jquery.org/">jQuery Foundation</a>
						<ul>
							<li><a href="http://jquery.org/about/">About</a></li>
							<li><a href="http://jquery.org/history/">History</a></li>
							<li><a href="http://jquery.org/team/">Team</a></li>
							<li><a href="http://jquery.org/sponsors/">Sponsors</a></li>
							<li><a href="http://jquery.org/donate/" >Donate</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</section>
</header>

<div id="container">
	<div id="logo-events" class="constrain clearfix">
		<h2 class="logo"><a href="/" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h2>

		<!--Ads or events
		<aside></aside> -->
	</div>

	<nav id="main" class="constrain clearfix">
		<?php get_template_part('menu', 'header'); ?>

		<?php get_search_form(); ?>
	</nav>

	<div id="content-wrapper" class="clearfix row">
