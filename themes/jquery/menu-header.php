<?php
/*
 * The main header navigation menu for each project site.
 *
 * If a function exists for a subdomain (such as projects_jquery_com) it will
 * be used instead of the jquery_com function.
 */

function menu_header_jquery_com() {
	return array(
		'https://jquery.com/download/' => 'Download',
		'https://api.jquery.com/' => 'API Documentation',
		'https://blog.jquery.com/' => 'Blog',
		'https://plugins.jquery.com/' => 'Plugins',
		'https://jquery.com/browser-support/' => 'Browser Support',
		'https://jquery.com/support/' => 'Version Support',
	);
}

function menu_header_learn_jquery_com() {
	return array(
		'https://learn.jquery.com' => 'Home',
		'https://learn.jquery.com/about/' => 'About',
		'https://learn.jquery.com/contributing/' => 'Contributing',
		'https://learn.jquery.com/style-guide/' => 'Style Guide',
	);
}

function menu_header_releases_jquery_com() {
	return array(
		'/jquery/' => 'jQuery Core',
		'/ui/' => 'jQuery UI',
		'/mobile/' => 'jQuery Mobile',
		'/color/' => 'jQuery Color',
		'/qunit/' => 'QUnit',
		'/pep/' => 'PEP'
	);
}

function menu_header_jquerymobile_com() {
	return array(
		'https://jquerymobile.com/demos/' => 'Demos',
		'https://jquerymobile.com/download/' => 'Download',
		'https://api.jquerymobile.com/' => 'API Documentation',
		'https://themeroller.jquerymobile.com' => 'Themes',
		'https://jquerymobile.com/resources/' => 'Resources',
		'https://blog.jquerymobile.com/' => 'Blog',
		'https://jquerymobile.com/about/' => 'About'
	);
}

function menu_header_jqueryui_com() {
	return array(
		'https://jqueryui.com/demos/' => 'Demos',
		'https://jqueryui.com/download/' => 'Download',
		'https://api.jqueryui.com/' => 'API Documentation',
		'https://jqueryui.com/themeroller/' => 'Themes',
		'https://jqueryui.com/development/' => 'Development',
		'https://jqueryui.com/support/' => 'Support',
		'https://blog.jqueryui.com/' => 'Blog',
		'https://jqueryui.com/about/' => 'About',
	);
}

function menu_header_jquery_org() {
	return array(
		'https://jquery.com/' => 'Home',
		'https://meetings.jquery.org/' => 'Meetings',
		'https://jquery.org/team/' => 'Team',
		'https://brand.jquery.org/' => 'Brand Guide',
		'https://jquery.org/support/' => 'Support',
		'https://jquery.org/history/' => 'History',
	);
}

function menu_header_brand_jquery_org() {
	return array(
		'https://brand.jquery.org/logos/' => 'Logos',
		'https://brand.jquery.org/colors/' => 'Colors',
		'https://brand.jquery.org/typography/' => 'Typography',
		'https://brand.jquery.org/naming-conventions/' => 'Naming Conventions',
		'https://brand.jquery.org/events-conferences/' => 'Events &amp; Conferences',
		'https://brand.jquery.org/press-contact/' => 'Press &amp; Contact'
	);
}

function menu_header_contribute_jquery_org() {
	return array(
		'https://cla.openjsf.org/' => 'CLA',
		'https://contribute.jquery.org/style-guide/' => 'Style Guides',
		'https://contribute.jquery.org/markup-conventions/' => 'Markup Conventions',
		'https://contribute.jquery.org/commits-and-pull-requests/' => 'Commits &amp; Pull Requests'
	);
}

/*
 * Avert your eyes.
 */

$site = explode( '/', JQUERY_LIVE_SITE, 2 );
$domain = explode( '.', $site[0] );
$path = count( $site ) === 2 ? explode( '/', str_replace( '.', '_', $site[1] ) ) : array();
$func = 'menu_header_' . implode( '_', $domain ) .
	(count( $path ) ? '_' . implode ( '_', $path ) : '');
while ( !function_exists( $func ) && count( $path ) > 1 ) {
	array_pop( $path );
	$func = 'menu_header_' . implode( '_', $domain ) . '_' . implode( '_', $path );
}

while ( !function_exists( $func ) && count( $domain ) > 1 ) {
	array_shift( $domain );
	$func = 'menu_header_' . implode( '_', $domain );
}
if ( function_exists( $func ) )
	jquery_render_menu( call_user_func( $func ) );
unset( $site, $domain, $path, $func );

function jquery_render_menu( $items ) {
	$current = trailingslashit( 'https://' . JQUERY_LIVE_SITE . $_SERVER['REQUEST_URI'] );
	?>
<div class="menu-top-container">
	<button hidden id="menu-trigger" class="button menu-trigger" aria-expanded="false" aria-haspopup="menu">Navigation</button>
	<ul id="menu-top" class="menu" role="menu" aria-labelledby="menu-trigger">
<?php
	foreach ( $items as $url => $anchor ) {
		$class = 'menu-item';
		if ( $anchor === 'Home' ) {
			if ( $current === $url ) {
				$class .= ' current';
			}
		} else {
			if ( 0 === strpos( $current, $url ) ) {
				$class .= ' current';
			}
		}
		echo '<li class="' . $class . '"><a href="' . $url . '">' . $anchor . "</a></li>\n";
	}
?>
	</ul>
</div>
<?php
}
