<?php
/*
 * The base configuration of the WordPress jQuery.com setup.
 */

/*
 * jQuery.com settings
 */

define( 'JQUERY_STAGING', true );
define( 'JQUERY_STAGING_PREFIX', 'dev.' );

// WordPress debugging mode (enables PHP E_NOTICE and WordPress notices)
define( 'WP_DEBUG', (bool) JQUERY_STAGING );

/*
 * Database Settings
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'database_name_here');

/** MySQL database username */
define('DB_USER', 'username_here');

/** MySQL database password */
define('DB_PASSWORD', 'password_here');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/*
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * Use https://api.wordpress.org/secret-key/1.1/salt/
 */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

/*
 * WordPress Database Table prefix.
 */
$table_prefix  = 'wp_';


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** jQuery.com configuration file */
require( dirname( __FILE__ ) . '/web-base-template/config.php' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
