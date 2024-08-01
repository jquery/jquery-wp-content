<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
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

	<meta name="author" content="<?php echo get_option( 'jquery_author', 'OpenJS Foundation - openjsf.org' ); ?>">
	<meta name="description" content="<?php echo get_option( 'jquery_description', '' ); ?>">
	<meta name="viewport" content="width=device-width">

	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/i/favicon.ico">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/lib/typesense-minibar/typesense-minibar.css?v=1.3.2">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/base.css?v=17">
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>?v=8">

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
<?php
	if ( jq_search_get_provider() === 'typesense' ) :
?>
	<script defer type="module" src="<?php echo get_template_directory_uri(); ?>/lib/typesense-minibar/typesense-minibar.js?v=1.3.2"></script>
<?php
	endif;

	if ( get_option( 'thread_comments' ) && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

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
							<li><a href="https://cla.openjsf.org/">CLA</a></li>
							<li><a href="https://contribute.jquery.org/style-guide/">Style Guides</a></li>
							<li><a href="https://contribute.jquery.org/triage/">Bug Triage</a></li>
							<li><a href="https://contribute.jquery.org/code/">Code</a></li>
							<li><a href="https://contribute.jquery.org/documentation/">Documentation</a></li>
							<li><a href="https://contribute.jquery.org/web-sites/">Web Sites</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="https://events.jquery.org/">Events</a>
						<ul class="wide">
						</ul>
					</li>
					<li class="dropdown"><a href="https://jquery.com/support/">Support</a>
						<ul>
							<li><a href="https://learn.jquery.com/">Learning Center</a></li>
							<li><a href="https://jquery.com/support/">Chat</a></li>
							<li><a href="https://stackoverflow.com/tags/jquery/info">Stack Overflow</a></li>
							<li><a href="https://contribute.jquery.org/bug-reports/">Report a bug</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="https://openjsf.org/">OpenJS Foundation</a>
						<ul>
							<li><a href="https://openjsf.org/about/join/">Join</a></li>
							<li><a href="https://openjsf.org/about/members/">Members</a></li>
							<li><a href="https://jquery.com/team">jQuery Team</a></li>
							<li><a href="https://openjsf.org/about/governance/">Governance</a></li>
							<li><a href="https://code-of-conduct.openjsf.org/">Conduct</a></li>
							<li><a href="https://openjsf.org/about/project-funding-opportunities/">Donate</a></li>
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

		<aside id="broadcast"></aside>
	</div>

	<nav id="main" class="constrain clearfix">
		<?php get_template_part('menu', 'header'); ?>

		<?php get_search_form(); ?>
	</nav>

	<div id="content-wrapper" class="clearfix row">
