<?php

function jquery_domains() {
	return array( /* blog_id, cookie domain */
		'jquery.com' => array(
			'blog_id' => 1,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery',
				'stylesheet' => 'jquery.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'blog.jquery.com' => array(
			'blog_id' => 2,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Blog',
				'stylesheet' => 'blog-jquery-com',
        // http://codex.wordpress.org/Using_Permalinks#Using_.25category.25_with_multiple_categories_on_a_post
        // 'permalink_structure' => '/%category%/%postname%/',
			),
		),
		'api.jquery.com' => array(
			'blog_id' => 3,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery API Documentation',
				'stylesheet' => 'api.jquery.com',
			),
		),
		'plugins.jquery.com' => array(
			'blog_id' => 4,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Plugins',
				'stylesheet' => 'plugins.jquery.com',
			),
		),
		'learn.jquery.com' => array(
			'blog_id' => 5,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'Learn jQuery',
				'stylesheet' => 'learn.jquery.com',
			),
		),
		'jqueryui.com' => array(
			'blog_id' => 6,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI',
				'stylesheet' => 'jqueryui.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'blog.jqueryui.com' => array(
			'blog_id' => 7,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI Blog',
				'stylesheet' => 'blog.jqueryui.com',
			),
		),
		'api.jqueryui.com' => array(
			'blog_id' => 8,
			'cookie_domain' => '.jqueryui.com',
			'options' => array(
				'blogname' => 'jQuery UI API Documentation',
				'stylesheet' => 'api.jqueryui.com',
			),
		),
		'jquery.org' => array(
			'blog_id' => 9,
			'cookie_domain' => '.jquery.org',
			'options' => array(
				'blogname' => 'jQuery Foundation',
				'stylesheet' => 'jquery.org',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'qunitjs.com' => array(
			'blog_id' => 10,
			'cookie_domain' => '.qunitjs.com',
			'options' => array(
				'blogname' => 'QUnit',
				'stylesheet' => 'qunitjs.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'sizzlejs.com' => array(
			'blog_id' => 11,
			'cookie_domain' => '.sizzlejs.com',
			'options' => array(
				'blogname' => 'Sizzle JS',
				'stylesheet' => 'sizzlejs.com',
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
				'stylesheet' => 'jquerymobile.com',
				'active_plugins' => array(
					'jquery-static-index.php',
				),
			),
		),
		'api.jquerymobile.com' => array(
			'blog_id' => 13,
			'cookie_domain' => '.jquerymobile.com',
			'options' => array(
				'blogname' => 'jQuery Mobile API Documentation',
				'stylesheet' => 'api.jquerymobile.com',
			),
		),
		'api.qunitjs.com' => array(
			'blog_id' => 14,
			'cookie_domain' => '.qunitjs.com',
			'options' => array(
				'blogname' => 'QUnit API Documentation',
				'stylesheet' => 'api.qunitjs.com',
			),
		),
		'books.jquery.com' => array(
			'blog_id' => 15,
			'cookie_domain' => '.jquery.com',
			'options' => array(
				'blogname' => 'jQuery Books',
				'stylesheet' => 'books.jquery.com',
			),
		),
		'events.jquery.org' => array(
			'blog_id' => 16,
			'cookie_domain' => '.jquery.org',
			'options' => array(
				'blogname' => 'jQuery Events',
				'stylesheet' => 'events.jquery.org',
				'active_plugins' => array(
					'allow-numeric-stubs/allow-numeric-stubs.php',
				),
			),
		),
	);
}

function jquery_default_site_options() {
	return array(
		'enable_xmlrpc' => 1,
		'template' => 'jquery',
		'blogdescription' => '',
		'permalink_structure' => '/%postname%/',
		'use_smilies' => 0,
	);

}
