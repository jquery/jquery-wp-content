<?php
/*
 * The main header navigation menu for each project site.
 *
 * If a function exists for a subdomain (such as projects_jquery_com) it will
 * be used instead of the jquery_com function.
 */

function menu_header_jquery_com() {
	return array(
		'http://plugins.jquery.com/' => 'Plugins',
		'http://jquery.com/download/' => 'Download',
		'http://api.jquery.com/' => 'API Documentation',
		'http://jquery.com/browser-support/' => 'Browser Support',
	);
}

function menu_header_plugins_jquery_com() {
	return array(
		'http://plugins.jquery.com/docs/names/' => 'Naming Your Plugin',
		'http://plugins.jquery.com/docs/publish/' => 'Publishing Your Plugin',
	);
}

function menu_header_qunitjs_com() {
	return array(
		'http://qunitjs.com/' => 'Home',
		'http://qunitjs.com/cookbook/' => 'Cookbook',
		'http://api.qunitjs.com/' => 'API Documentation',
		'http://qunitjs.com/intro/' => 'Intro to Unit Testing',
		'http://qunitjs.com/extensions/' => 'Extensions',
	);
}

function menu_header_jquerymobile_com() {
	return array(
		'http://mobile.jquery.com/docs' => 'Docs',
		'http://mobile.jquery.com/download' => 'Download',
		'http://mobile.jquery.com/gbs' => 'Platforms',
		'http://mobile.jquery.com/themeroller' => 'Themes',
		'http://mobile.jquery.com/resources' => 'Resources',
		'http://forum.jquery.com/jquery-mobile' => 'Forum',
		'http://mobile.jquery.com/blog' => 'Blog',
	);
}

function menu_header_jqueryui_com() {
	return array(
		'http://jqueryui.com/demos/' => 'Demos',
		'http://jqueryui.com/download' => 'Download',
		'http://api.jqueryui.com/' => 'API Documentation',
		'http://jqueryui.com/themeroller' => 'Themes',
		'http://jqueryui.com/development' => 'Development',
		'http://jqueryui.com/support' => 'Support',
		'http://blog.jqueryui.com/' => 'Blog',
		'http://jqueryui.com/about' => 'About',
	);
}

function menu_header_brand_jquery_org() {
	return array(
		'http://brand.jquery.org/' => 'Home',
		'http://brand.jquery.org/trademark/' => 'Trademark'
	);
}

/*
 * Avert your eyes.
 */


$domain = explode( '.', JQUERY_LIVE_SITE );
$func = 'menu_header_' . implode( '_', $domain );
if ( ! function_exists( $func ) ) {
	array_shift( $domain );
	$func = 'menu_header_' . implode( '_', $domain );
}
if ( function_exists( $func ) )
	jquery_render_menu( call_user_func( $func ) );
unset( $domain, $func );

function jquery_render_menu( $items ) {
	$current = trailingslashit( set_url_scheme( 'http://' . JQUERY_LIVE_SITE . $_SERVER['REQUEST_URI'] ) );
	error_log( $current );
	?>
<div class="menu-top-container">
  <ul id="menu-top" class="menu">
<?php
 	foreach ( $items as $url => $anchor ) {
 		$class = 'menu-item';
 		$url = set_url_scheme( $url );
 		error_log( $url );
 		if ( 0 === strpos( $current, $url ) )
 			$class .= ' current';
 		echo '    <li class="' . $class . '"><a href="' . $url . '">' . $anchor . "</a></li>\n";
 	}
?>
  </ul>
</div>
<?php
}
