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
	);
}

function menu_header_plugins_jquery_com() {
	return array(
		// 'https://plugins.jquery.com/docs/names/' => 'Naming Your Plugin',
		// 'https://plugins.jquery.com/docs/publish/' => 'Publishing Your Plugin',
		// 'https://plugins.jquery.com/docs/package-manifest/' => 'Package Manifest',
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

function menu_header_codeorigin_jquery_com() {
	return array(
		'/jquery/' => 'jQuery Core',
		'/ui/' => 'jQuery UI',
		'/mobile/' => 'jQuery Mobile',
		'/color/' => 'jQuery Color',
		'/qunit/' => 'QUnit',
		'/pep/' => 'PEP'
	);
}

function menu_header_qunitjs_com() {
	return array(
		'https://qunitjs.com/' => 'Home',
		'https://qunitjs.com/intro/' => 'Intro to Unit Testing',
		'https://api.qunitjs.com/' => 'API Documentation',
		'https://qunitjs.com/cookbook/' => 'Cookbook',
		'https://qunitjs.com/plugins/' => 'Plugins',
		'https://qunitjs.com/upgrade-guide-2.x/' => '2.x Upgrade Guide',
		'https://qunitjs.com/about/' => 'About'
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

function menu_header_sizzlejs_com() {
	return array(
		'https://sizzlejs.com/' => 'Home',
		'https://github.com/jquery/sizzle' => 'Source Code',
	);
}

function menu_header_jquery_org() {
	return array(
		'https://jquery.org/' => 'Home',
		'https://jquery.org/projects/' => 'Projects',
		'https://jquery.org/join/' => 'Join',
		'https://jquery.org/members/' => 'Members',
		'https://jquery.org/support/' => 'Support',
		'https://jquery.org/team/' => 'Team',
		'https://jquery.org/conduct/' => 'Conduct',
		'https://meetings.jquery.org/' => 'Meetings',
		'https://jquery.org/history/' => 'History',
		'https://brand.jquery.org/' => 'Brand Guide',
		'https://jquery.org/donate/' => 'Donate',
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
		'https://js.foundation/CLA' => 'CLA',
		'https://contribute.jquery.org/style-guide/' => 'Style Guides',
		'https://contribute.jquery.org/markup-conventions/' => 'Markup Conventions',
		'https://contribute.jquery.org/commits-and-pull-requests/' => 'Commits &amp; Pull Requests'
	);
}

function menu_header_irc_jquery_org() {
	return array(
		'https://irc.jquery.org/irc-help' => 'IRC Help',
		'https://jquery.org/meeting/' => 'Meetings',
	);
}

function menu_header_events_jquery_org() {
	global $events;

	$items = array();
	foreach( $events[ 'future' ] as $event ) {
		$items[ $event->url ] = substr( $event->date, 0, -5 ) . ' | ' . $event->title;
	}
	return $items;
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
	$current = trailingslashit( set_url_scheme( 'https://' . JQUERY_LIVE_SITE . $_SERVER['REQUEST_URI'] ) );
	?>
<div class="menu-top-container">
	<ul id="menu-top" class="menu">
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
