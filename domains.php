<?php

function jquery_domains() {
	return array( /* blog_id, cookie domain */
		'jquery.com' => array(
			'blog_id' => 1,
			'cookie_domain' => '.jquery.com',
		),
		'blog.jquery.com' => array(
			'blog_id' => 2,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Blog',
				'stylesheet' => 'blog-jquery-com',
			),
		),
		'api.jquery.com' => array(
			'blog_id' => 3,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery API Documentation',
				'stylesheet' => 'api-jquery-com',
			),
		),
		'plugins.jquery.com' => array(
			'blog_id' => 4,
			'cookie_domain' => '.jquery.com',
			'options' => array( 
				'blogname' => 'jQuery Plugins',
				'stylesheet' => 'plugins-jquery-com',
			),
		),
		'learn.jquery.com' => array(
			'blog_id' => 5,
			'cookie_domain' => '.jquery.com',
			'options' => array( 
				'blogname' => 'Learn jQuery',
				'stylesheet' => 'learn-jquery-com',
			),
		),
		'jqueryui.com' => array(
			'blog_id' => 6,
			'cookie_domain' => '.jqueryui.com',
			'options' => array( 
				'blogname' => 'jQuery UI',
				'stylesheet' => 'jqueryui-com',
			),
		),
		'blog.jqueryui.com' => array(
			'blog_id' => 7,
			'cookie_domain' => '.jqueryui.com',
			'options' => array( 
				'blogname' => 'jQuery UI Blog',
				'stylesheet' => 'blog-jqueryui-com',
			),
		),
		'api.jqueryui.com' => array(
			'blog_id' => 8,
			'cookie_domain' => '.jqueryui.com',
			'options' => array( 
				'blogname' => 'jQuery UI API Documentation',
				'stylesheet' => 'api-jqueryui-com',
			),
		),
		'jquery.org' => array(
			'blog_id' => 9,
			'cookie_domain' => '.jquery.org',
			'options' => array( 
				'blogname' => 'jQuery Foundation',
				'stylesheet' => 'jquery-org',
			),
		),
		'qunitjs.com' => array(
			'blog_id' => 10,
			'cookie_domain' => '.qunitjs.com',
			'options' => array( 
				'blogname' => 'qUnit',
				'stylesheet' => 'qunitjs-com',
			),
		),
		'sizzlejs.com' => array(
			'blog_id' => 11,
			'cookie_domain' => '.sizzlejs.com',
			'options' => array( 
				'blogname' => 'Sizzle JS',
				'stylesheet' => 'sizzlejs-com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'jquerymobile.com' => array(
			'blog_id' => 12,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array( 
				'blogname' => 'jQuery Mobile',
				'stylesheet' => 'jquerymobile-com',
			),
		),
		'api.jquerymobile.com' => array(
			'blog_id' => 13,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array( 
				'blogname' => 'jQuery Mobile API Documentation',
				'stylesheet' => 'api-jquerymobile-com',
			),
		),
	);
}