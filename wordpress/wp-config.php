<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'jquery_redesign');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'O46; q<fC_|HG4|V+M>$+WFfn9[G[:UeU9K>`I(Em@T_t//Q.ohrUW$z5//]^sZe');
define('SECURE_AUTH_KEY',  '#+ANvO7b^oDf&BC><KiU(4~C.89b-Rc8@)Q7zA`(iuj!@N0ZI6BQ#OypWoQ2MBBf');
define('LOGGED_IN_KEY',    'TyD9 yyQ/y8/918.&O<=U/uDa*LYTFgJofqzAo65TLfho$TIslCw(Gl]fHV#WtWd');
define('NONCE_KEY',        'VN0Z,DH|qE?$/0]`)Fe9.Ee_ #hfro:2H68oK4b$JkqXR)+*rOS#+5R|Xh]<@]`7');
define('AUTH_SALT',        '--L]y&Z6,}f@oT6-Fh-PE%%s;spD0vvzoR.2^zl<2%g|YNizp`z2]<k65Zm.pb0f');
define('SECURE_AUTH_SALT', '%H7S(rhkF1_N-u0NXc1F0m9HXGx$:D_LOl+Ia2tIIM4hHd~#+O2O+:;}u<l(N?Az');
define('LOGGED_IN_SALT',   '^0.?c}zwXe^2NclvHc?/Uwu; GHF:kr@A.3ZYB1,3oJJEPQ-&|0IBa]i7iA*Q}SZ');
define('NONCE_SALT',       'q[k^PAcW^m#53A^s1h;Sw}d]WzGqelMu]S.D)09I,R:jNVJJZo!H|E4JV[Ni@FxL');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
