<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head data-live-domain="<?php echo JQUERY_LIVE_DOMAIN; ?>">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?php
		global $page, $paged;
		wp_title( '|', true, 'right' );
		bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
	?></title>

	<meta name="author" content="JS Foundation - js.foundation">
	<meta name="description" content="<?php echo get_option( 'jquery_description', '' ); ?>">

	<meta name="viewport" content="width=device-width">

	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/base.css?v=4">
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>?v=2">

	<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.custom.2.8.3.min.js"></script>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

	<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

	<script src="https://use.typekit.net/wde1aof.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
	<!-- at the end of the HEAD -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/docsearch.css">

<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>

</head>
<body <?php body_class(); ?>>

<header>
	<section id="global-nav">
		<nav>
			<div class="constrain">
				<ul class="projects">
					<li class="project jquery"><a href="https://jquery.com/" title="jQuery">jQuery</a></li>
					<li class="project jquery-ui"><a href="https://jqueryui.com/" title="jQuery UI">jQuery UI</a></li>
					<li class="project jquery-mobile"><a href="https://jquerymobile.com/" title="jQuery Mobile">jQuery Mobile</a></li>
					<li class="project sizzlejs"><a href="https://sizzlejs.com/" title="Sizzle">Sizzle</a></li>
					<li class="project qunitjs"><a href="https://qunitjs.com/" title="QUnit">QUnit</a></li>
				</ul>
				<ul class="links">
					<li><a href="https://plugins.jquery.com/">Plugins</a></li>
					<li class="dropdown"><a href="https://contribute.jquery.org/">Contribute</a>
						<ul>
							<li><a href="https://js.foundation/CLA">CLA</a></li>
							<li><a href="https://contribute.jquery.org/style-guide/">Style Guides</a></li>
							<li><a href="https://contribute.jquery.org/triage/">Bug Triage</a></li>
							<li><a href="https://contribute.jquery.org/code/">Code</a></li>
							<li><a href="https://contribute.jquery.org/documentation/">Documentation</a></li>
							<li><a href="https://contribute.jquery.org/web-sites/">Web Sites</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="https://js.foundation/events">Events</a>
						<ul class="wide">
						</ul>
					</li>
					<li class="dropdown"><a href="https://jquery.org/support/">Support</a>
						<ul>
							<li><a href="https://learn.jquery.com/">Learning Center</a></li>
							<li><a href="https://irc.jquery.org/">IRC/Chat</a></li>
							<li><a href="https://forum.jquery.com/">Forums</a></li>
							<li><a href="https://stackoverflow.com/tags/jquery/info">Stack Overflow</a></li>
							<li><a href="https://jquery.org/support/">Commercial Support</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="https://js.foundation/">JS Foundation</a>
						<ul>
							<li><a href="https://js.foundation/about/join">Join</a></li>
							<li><a href="https://js.foundation/about/members">Members</a></li>
							<li><a href="https://js.foundation/about/leadership">Leadership</a></li>
							<li><a href="https://js.foundation/community/code-of-conduct">Conduct</a></li>
							<li><a href="https://js.foundation/about/donate">Donate</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</section>
</header>

<div id="container">
	<div id="logo-events" class="constrain clearfix">
		<h2 class="logo"><a href="<?php echo get_option( 'jquery_logo_link', '/' ); ?>" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h2>

		<aside>
			<div id="broadcast"></div>
		</aside>
	</div>

	<nav id="main" class="constrain clearfix">
		<?php get_template_part('menu', 'header'); ?>

		<?php get_search_form(); ?>
	</nav>

	<div id="content-wrapper" class="clearfix row">
