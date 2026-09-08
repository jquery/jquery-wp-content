<?php
/*
  Plugin Name: Limit Login Attempts
  Plugin URI: http://devel.kostdoktorn.se/limit-login-attempts
  Description: Limit rate of login attempts, including by way of cookies, for each IP.
  Author: Johan Eenfeldt
  Author URI: http://devel.kostdoktorn.se
  Text Domain: limit-login-attempts
  Version: 1.7.2

  Copyright 2008 - 2012 Johan Eenfeldt

  Thanks to Michael Skerwiderski for reverse proxy handling suggestions.

  Licenced under the GNU GPL:

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 * Constants
 */

/* Different ways to get remote address: direct & behind proxy */
define('LIMIT_LOGIN_DIRECT_ADDR', 'REMOTE_ADDR');
define('LIMIT_LOGIN_PROXY_ADDR', 'HTTP_X_FORWARDED_FOR');

/* Notify value checked against these in limit_login_sanitize_variables() */
define('LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED', 'log,email');

/*
 * Variables
 *
 * Assignments are for default value -- change on admin page.
 */

$limit_login_options =
	array(
		  /* Are we behind a proxy? */
		  'client_type' => LIMIT_LOGIN_DIRECT_ADDR

		  /* Lock out after this many tries */
		  , 'allowed_retries' => 4

		  /* Lock out for this many seconds */
		  , 'lockout_duration' => 1200 // 20 minutes

		  /* Long lock out after this many lockouts */
		  , 'allowed_lockouts' => 4

		  /* Long lock out for this many seconds */
		  , 'long_duration' => 86400 // 24 hours

		  /* Reset failed attempts after this many seconds */
		  , 'valid_duration' => 43200 // 12 hours

		  /* Also limit malformed/forged cookies? */
		  , 'cookies' => true

		  /* Notify on lockout. Values: '', 'log', 'email', 'log,email' */
		  , 'lockout_notify' => 'log'

		  /* If notify by email, do so after this number of lockouts */
		  , 'notify_email_after' => 4
		  );

$limit_login_my_error_shown = false; /* have we shown our stuff? */
$limit_login_just_lockedout = false; /* started this pageload??? */
$limit_login_nonempty_credentials = false; /* user and pwd nonempty */


/*
 * Startup
 */

add_action('plugins_loaded', 'limit_login_setup', 99999);


/*
 * Functions start here
 */

/* Get options and setup filters & actions */
function limit_login_setup() {
	load_plugin_textdomain('limit-login-attempts', false
			       , dirname(plugin_basename(__FILE__)));

	limit_login_setup_options();

	/* Filters and actions */
	add_action('wp_login_failed', 'limit_login_failed');
	if (limit_login_option('cookies')) {
		limit_login_handle_cookies();
		add_action('auth_cookie_bad_username', 'limit_login_failed_cookie');

		global $wp_version;

		if (version_compare($wp_version, '3.0', '>=')) {
			add_action('auth_cookie_bad_hash', 'limit_login_failed_cookie_hash');
			add_action('auth_cookie_valid', 'limit_login_valid_cookie', 10, 2);
		} else {
			add_action('auth_cookie_bad_hash', 'limit_login_failed_cookie');
		}
	}
	add_filter('wp_authenticate_user', 'limit_login_wp_authenticate_user', 99999, 2);
	add_filter('shake_error_codes', 'limit_login_failure_shake');
	add_action('login_head', 'limit_login_add_error_message');
	add_action('login_errors', 'limit_login_fixup_error_messages');
	add_action('admin_menu', 'limit_login_admin_menu');

	/*
	 * This action should really be changed to the 'authenticate' filter as
	 * it will probably be deprecated. That is however only available in
	 * later versions of WP.
	 */
	add_action('wp_authenticate', 'limit_login_track_credentials', 10, 2);
}


/* Get current option value */
function limit_login_option($option_name) {
	global $limit_login_options;

	if (isset($limit_login_options[$option_name])) {
		return $limit_login_options[$option_name];
	} else {
		return null;
	}
}


/* Get correct remote address */
function limit_login_get_address($type_name = '') {
	$type = $type_name;
	if (empty($type)) {
		$type = limit_login_option('client_type');
	}

	if (isset($_SERVER[$type]) && filter_var($_SERVER[$type], FILTER_VALIDATE_IP)) {
		return $_SERVER[$type];
	}

	/*
	 * Not found. Did we get proxy type from option?
	 * If so, try to fall back to direct address.
	 */
	if ( empty($type_name) && $type == LIMIT_LOGIN_PROXY_ADDR
		 && isset($_SERVER[LIMIT_LOGIN_DIRECT_ADDR])
		 && filter_var($_SERVER[LIMIT_LOGIN_DIRECT_ADDR], FILTER_VALIDATE_IP)) {

		/*
		 * NOTE: Even though we fall back to direct address -- meaning you
		 * can get a mostly working plugin when set to PROXY mode while in
		 * fact directly connected to Internet it is not safe!
		 *
		 * Client can itself send HTTP_X_FORWARDED_FOR header fooling us
		 * regarding which IP should be banned.
		 */

		return $_SERVER[LIMIT_LOGIN_DIRECT_ADDR];
	}
	
	return '';
}


/*
 * Check if IP is whitelisted.
 *
 * This function allow external ip whitelisting using a filter. Note that it can
 * be called multiple times during the login process.
 *
 * Note that retries and statistics are still counted and notifications
 * done as usual for whitelisted ips , but no lockout is done.
 *
 * Example:
 * function my_ip_whitelist($allow, $ip) {
 * 	return ($ip == 'my-ip') ? true : $allow;
 * }
 * add_filter('limit_login_whitelist_ip', 'my_ip_whitelist', 10, 2);
 */
function is_limit_login_ip_whitelisted($ip = null) {
	if (is_null($ip)) {
		$ip = limit_login_get_address();
	}
	$whitelisted = apply_filters('limit_login_whitelist_ip', false, $ip);

	return ($whitelisted === true);
}


/* Check if it is ok to login */
function is_limit_login_ok() {
	$ip = limit_login_get_address();

	/* Check external whitelist filter */
	if (is_limit_login_ip_whitelisted($ip)) {
		return true;
	}

	/* lockout active? */
	$lockouts = get_option('limit_login_lockouts');
	return (!is_array($lockouts) || !isset($lockouts[$ip]) || time() >= $lockouts[$ip]);
}


/* Filter: allow login attempt? (called from wp_authenticate()) */
function limit_login_wp_authenticate_user($user, $password) {
	if (is_wp_error($user) || is_limit_login_ok() ) {
		return $user;
	}

	global $limit_login_my_error_shown;
	$limit_login_my_error_shown = true;

	$error = new WP_Error();
	// This error should be the same as in "shake it" filter below
	$error->add('too_many_retries', limit_login_error_msg());
	return $error;
}


/* Filter: add this failure to login page "Shake it!" */
function limit_login_failure_shake($error_codes) {
	$error_codes[] = 'too_many_retries';
	return $error_codes;
}


/*
 * Must be called in plugin_loaded (really early) to make sure we do not allow
 * auth cookies while locked out.
 */
function limit_login_handle_cookies() {
	if (is_limit_login_ok()) {
		return;
	}

	limit_login_clear_auth_cookie();
}


/*
 * Action: failed cookie login hash
 *
 * Make sure same invalid cookie doesn't get counted more than once.
 *
 * Requires WordPress version 3.0.0, previous versions use limit_login_failed_cookie()
 */
function limit_login_failed_cookie_hash($cookie_elements) {
	limit_login_clear_auth_cookie();

	/*
	 * Under some conditions an invalid auth cookie will be used multiple
	 * times, which results in multiple failed attempts from that one
	 * cookie.
	 *
	 * Unfortunately I've not been able to replicate this consistently and
	 * thus have not been able to make sure what the exact cause is.
	 *
	 * Probably it is because a reload of for example the admin dashboard
	 * might result in multiple requests from the browser before the invalid
	 * cookie can be cleard.
	 *
	 * Handle this by only counting the first attempt when the exact same
	 * cookie is attempted for a user.
	 */

	extract($cookie_elements, EXTR_OVERWRITE);

	// Check if cookie is for a valid user
	$user = get_userdatabylogin($username);
	if (!$user) {
		// "shouldn't happen" for this action
		limit_login_failed($username);
		return;
	}

	$previous_cookie = get_user_meta($user->ID, 'limit_login_previous_cookie', true);
	if ($previous_cookie && $previous_cookie == $cookie_elements) {
		// Identical cookies, ignore this attempt
		return;
	}

	// Store cookie
	if ($previous_cookie)
		update_user_meta($user->ID, 'limit_login_previous_cookie', $cookie_elements);
	else
		add_user_meta($user->ID, 'limit_login_previous_cookie', $cookie_elements, true);

	limit_login_failed($username);
}


/*
 * Action: successful cookie login
 *
 * Clear any stored user_meta.
 *
 * Requires WordPress version 3.0.0, not used in previous versions
 */
function limit_login_valid_cookie($cookie_elements, $user) {
	/*
	 * As all meta values get cached on user load this should not require
	 * any extra work for the common case of no stored value.
	 */

	if (get_user_meta($user->ID, 'limit_login_previous_cookie')) {
		delete_user_meta($user->ID, 'limit_login_previous_cookie');
	}
}


/* Action: failed cookie login (calls limit_login_failed()) */
function limit_login_failed_cookie($cookie_elements) {
	limit_login_clear_auth_cookie();

	/*
	 * Invalid username gets counted every time.
	 */

	limit_login_failed($cookie_elements['username']);
}


/* Make sure auth cookie really get cleared (for this session too) */
function limit_login_clear_auth_cookie() {
	wp_clear_auth_cookie();

	if (!empty($_COOKIE[AUTH_COOKIE])) {
		$_COOKIE[AUTH_COOKIE] = '';
	}
	if (!empty($_COOKIE[SECURE_AUTH_COOKIE])) {
		$_COOKIE[SECURE_AUTH_COOKIE] = '';
	}
	if (!empty($_COOKIE[LOGGED_IN_COOKIE])) {
		$_COOKIE[LOGGED_IN_COOKIE] = '';
	}
}

/*
 * Action when login attempt failed
 *
 * Increase nr of retries (if necessary). Reset valid value. Setup
 * lockout if nr of retries are above threshold. And more!
 *
 * A note on external whitelist: retries and statistics are still counted and
 * notifications done as usual, but no lockout is done.
 */
function limit_login_failed($username) {
	$ip = limit_login_get_address();

	/* if currently locked-out, do not add to retries */
	$lockouts = get_option('limit_login_lockouts');
	if (!is_array($lockouts)) {
		$lockouts = array();
	}
	if(isset($lockouts[$ip]) && time() < $lockouts[$ip]) {
		return;
	}

	/* Get the arrays with retries and retries-valid information */
	$retries = get_option('limit_login_retries');
	$valid = get_option('limit_login_retries_valid');
	if (!is_array($retries)) {
		$retries = array();
		add_option('limit_login_retries', $retries, '', 'no');
	}
	if (!is_array($valid)) {
		$valid = array();
		add_option('limit_login_retries_valid', $valid, '', 'no');
	}

	/* Check validity and add one to retries */
	if (isset($retries[$ip]) && isset($valid[$ip]) && time() < $valid[$ip]) {
		$retries[$ip] ++;
	} else {
		$retries[$ip] = 1;
	}
	$valid[$ip] = time() + limit_login_option('valid_duration');

	/* lockout? */
	if($retries[$ip] % limit_login_option('allowed_retries') != 0) {
		/* 
		 * Not lockout (yet!)
		 * Do housecleaning (which also saves retry/valid values).
		 */
		limit_login_cleanup($retries, null, $valid);
		return;
	}

	/* lockout! */

	$whitelisted = is_limit_login_ip_whitelisted($ip);

	$retries_long = limit_login_option('allowed_retries')
		* limit_login_option('allowed_lockouts');

	/* 
	 * Note that retries and statistics are still counted and notifications
	 * done as usual for whitelisted ips , but no lockout is done.
	 */
	if ($whitelisted) {
		if ($retries[$ip] >= $retries_long) {
			unset($retries[$ip]);
			unset($valid[$ip]);
		}
	} else {
		global $limit_login_just_lockedout;
		$limit_login_just_lockedout = true;

		/* setup lockout, reset retries as needed */
		if ($retries[$ip] >= $retries_long) {
			/* long lockout */
			$lockouts[$ip] = time() + limit_login_option('long_duration');
			unset($retries[$ip]);
			unset($valid[$ip]);
		} else {
			/* normal lockout */
			$lockouts[$ip] = time() + limit_login_option('lockout_duration');
		}
	}

	/* do housecleaning and save values */
	limit_login_cleanup($retries, $lockouts, $valid);

	/* do any notification */
	limit_login_notify($username);

	/* increase statistics */
	$total = get_option('limit_login_lockouts_total');
	if ($total === false || !is_numeric($total)) {
		add_option('limit_login_lockouts_total', 1, '', 'no');
	} else {
		update_option('limit_login_lockouts_total', $total + 1);
	}
}


/* Clean up old lockouts and retries, and save supplied arrays */
function limit_login_cleanup($retries = null, $lockouts = null, $valid = null) {
	$now = time();
	$lockouts = !is_null($lockouts) ? $lockouts : get_option('limit_login_lockouts');

	/* remove old lockouts */
	if (is_array($lockouts)) {
		foreach ($lockouts as $ip => $lockout) {
			if ($lockout < $now) {
				unset($lockouts[$ip]);
			}
		}
		update_option('limit_login_lockouts', $lockouts);
	}

	/* remove retries that are no longer valid */
	$valid = !is_null($valid) ? $valid : get_option('limit_login_retries_valid');
	$retries = !is_null($retries) ? $retries : get_option('limit_login_retries');
	if (!is_array($valid) || !is_array($retries)) {
		return;
	}

	foreach ($valid as $ip => $lockout) {
		if ($lockout < $now) {
			unset($valid[$ip]);
			unset($retries[$ip]);
		}
	}

	/* go through retries directly, if for some reason they've gone out of sync */
	foreach ($retries as $ip => $retry) {
		if (!isset($valid[$ip])) {
			unset($retries[$ip]);
		}
	}

	update_option('limit_login_retries', $retries);
	update_option('limit_login_retries_valid', $valid);
}


/* Is this WP Multisite? */
function is_limit_login_multisite() {
	return function_exists('get_site_option') && function_exists('is_multisite') && is_multisite();
}


/* Email notification of lockout to admin (if configured) */
function limit_login_notify_email($user) {
	$ip = limit_login_get_address();
	$whitelisted = is_limit_login_ip_whitelisted($ip);

	$retries = get_option('limit_login_retries');
	if (!is_array($retries)) {
		$retries = array();
	}

	/* check if we are at the right nr to do notification */
	if ( isset($retries[$ip])
		 && ( ($retries[$ip] / limit_login_option('allowed_retries'))
			  % limit_login_option('notify_email_after') ) != 0 ) {
		return;
	}

	/* Format message. First current lockout duration */
	if (!isset($retries[$ip])) {
		/* longer lockout */
		$count = limit_login_option('allowed_retries')
			* limit_login_option('allowed_lockouts');
		$lockouts = limit_login_option('allowed_lockouts');
		$time = round(limit_login_option('long_duration') / 3600);
		$when = sprintf(_n('%d hour', '%d hours', $time, 'limit-login-attempts'), $time);
	} else {
		/* normal lockout */
		$count = $retries[$ip];
		$lockouts = floor($count / limit_login_option('allowed_retries'));
		$time = round(limit_login_option('lockout_duration') / 60);
		$when = sprintf(_n('%d minute', '%d minutes', $time, 'limit-login-attempts'), $time);
	}

	$blogname = is_limit_login_multisite() ? get_site_option('site_name') : get_option('blogname');	

	if ($whitelisted) {
		$subject = sprintf(__("[%s] Failed login attempts from whitelisted IP"
				      , 'limit-login-attempts')
				   , $blogname);
	} else {
		$subject = sprintf(__("[%s] Too many failed login attempts"
				      , 'limit-login-attempts')
				   , $blogname);
	}

	$message = sprintf(__("%d failed login attempts (%d lockout(s)) from IP: %s"
			      , 'limit-login-attempts') . "\r\n\r\n"
			   , $count, $lockouts, $ip);
	if ($user != '') {
		$message .= sprintf(__("Last user attempted: %s", 'limit-login-attempts')
				    . "\r\n\r\n" , $user);
	}
	if ($whitelisted) {
		$message .= __("IP was NOT blocked because of external whitelist.", 'limit-login-attempts');
	} else {
		$message .= sprintf(__("IP was blocked for %s", 'limit-login-attempts'), $when);
	}

	$admin_email = is_limit_login_multisite() ? get_site_option('admin_email') : get_option('admin_email');

	@wp_mail($admin_email, $subject, $message);
}


/* Logging of lockout (if configured) */
function limit_login_notify_log($user) {
	$log = $option = get_option('limit_login_logged');
	if (!is_array($log)) {
		$log = array();
	}
	$ip = limit_login_get_address();

	/* can be written much simpler, if you do not mind php warnings */
	if (isset($log[$ip])) {
		if (isset($log[$ip][$user])) {	
			$log[$ip][$user]++;
		} else {
			$log[$ip][$user] = 1;
		}
	} else {
		$log[$ip] = array($user => 1);
	}

	if ($option === false) {
		add_option('limit_login_logged', $log, '', 'no'); /* no autoload */
	} else {
		update_option('limit_login_logged', $log);
	}
}


/* Handle notification in event of lockout */
function limit_login_notify($user) {
	$args = explode(',', limit_login_option('lockout_notify'));

	if (empty($args)) {
		return;
	}

	foreach ($args as $mode) {
		switch (trim($mode)) {
		case 'email':
			limit_login_notify_email($user);
			break;
		case 'log':
			limit_login_notify_log($user);
			break;
		}
	}
}


/* Construct informative error message */
function limit_login_error_msg() {
	$ip = limit_login_get_address();
	$lockouts = get_option('limit_login_lockouts');

	$msg = __('<strong>ERROR</strong>: Too many failed login attempts.', 'limit-login-attempts') . ' ';

	if (!is_array($lockouts) || !isset($lockouts[$ip]) || time() >= $lockouts[$ip]) {
		/* Huh? No timeout active? */
		$msg .=  __('Please try again later.', 'limit-login-attempts');
		return $msg;
	}

	$when = ceil(($lockouts[$ip] - time()) / 60);
	if ($when > 60) {
		$when = ceil($when / 60);
		$msg .= sprintf(_n('Please try again in %d hour.', 'Please try again in %d hours.', $when, 'limit-login-attempts'), $when);
	} else {
		$msg .= sprintf(_n('Please try again in %d minute.', 'Please try again in %d minutes.', $when, 'limit-login-attempts'), $when);
	}

	return $msg;
}


/* Construct retries remaining message */
function limit_login_retries_remaining_msg() {
	$ip = limit_login_get_address();
	$retries = get_option('limit_login_retries');
	$valid = get_option('limit_login_retries_valid');

	/* Should we show retries remaining? */

	if (!is_array($retries) || !is_array($valid)) {
		/* no retries at all */
		return '';
	}
	if (!isset($retries[$ip]) || !isset($valid[$ip]) || time() > $valid[$ip]) {
		/* no: no valid retries */
		return '';
	}
	if (($retries[$ip] % limit_login_option('allowed_retries')) == 0 ) {
		/* no: already been locked out for these retries */
		return '';
	}

	$remaining = max((limit_login_option('allowed_retries') - ($retries[$ip] % limit_login_option('allowed_retries'))), 0);
	return sprintf(_n("<strong>%d</strong> attempt remaining.", "<strong>%d</strong> attempts remaining.", $remaining, 'limit-login-attempts'), $remaining);
}


/* Return current (error) message to show, if any */
function limit_login_get_message() {
	/* Check external whitelist */
	if (is_limit_login_ip_whitelisted()) {
		return '';
	}

	/* Is lockout in effect? */
	if (!is_limit_login_ok()) {
		return limit_login_error_msg();
	}

	return limit_login_retries_remaining_msg();
}


/* Should we show errors and messages on this page? */
function should_limit_login_show_msg() {
	if (isset($_GET['key'])) {
		/* reset password */
		return false;
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

	return ( $action != 'lostpassword' && $action != 'retrievepassword'
			 && $action != 'resetpass' && $action != 'rp'
			 && $action != 'register' );
}


/* Fix up the error message before showing it */
function limit_login_fixup_error_messages($content) {
	global $limit_login_just_lockedout, $limit_login_nonempty_credentials, $limit_login_my_error_shown;

	if (!should_limit_login_show_msg()) {
		return $content;
	}

	/*
	 * During lockout we do not want to show any other error messages (like
	 * unknown user or empty password).
	 */
	if (!is_limit_login_ok() && !$limit_login_just_lockedout) {
		return limit_login_error_msg();
	}

	/*
	 * We want to filter the messages 'Invalid username' and
	 * 'Invalid password' as that is an information leak regarding user
	 * account names (prior to WP 2.9?).
	 *
	 * Also, if more than one error message, put an extra <br /> tag between
	 * them.
	 */
	$msgs = explode("<br />\n", $content);

	if (strlen(end($msgs)) == 0) {
		/* remove last entry empty string */
		array_pop($msgs);
	}

	$count = count($msgs);
	$my_warn_count = $limit_login_my_error_shown ? 1 : 0;

	if ($limit_login_nonempty_credentials && $count > $my_warn_count) {
		/* Replace error message, including ours if necessary */
		$content = __('<strong>ERROR</strong>: Incorrect username or password.', 'limit-login-attempts') . "<br />\n";
		if ($limit_login_my_error_shown) {
			$content .= "<br />\n" . limit_login_get_message() . "<br />\n";
		}
		return $content;
	} elseif ($count <= 1) {
		return $content;
	}

	$new = '';
	while ($count-- > 0) {
		$new .= array_shift($msgs) . "<br />\n";
		if ($count > 0) {
			$new .= "<br />\n";
		}
	}

	return $new;
}


/* Add a message to login page when necessary */
function limit_login_add_error_message() {
	global $error, $limit_login_my_error_shown;

	if (!should_limit_login_show_msg() || $limit_login_my_error_shown) {
		return;
	}

	$msg = limit_login_get_message();

	if ($msg != '') {
		$limit_login_my_error_shown = true;
		$error .= $msg;
	}

	return;
}


/* Keep track of if user or password are empty, to filter errors correctly */
function limit_login_track_credentials($user, $password) {
	global $limit_login_nonempty_credentials;

	$limit_login_nonempty_credentials = (!empty($user) && !empty($password));
}


/*
 * Admin stuff
 */

/* Make a guess if we are behind a proxy or not */
function limit_login_guess_proxy() {
	return isset($_SERVER[LIMIT_LOGIN_PROXY_ADDR])
		? LIMIT_LOGIN_PROXY_ADDR : LIMIT_LOGIN_DIRECT_ADDR;
}


/* Only change var if option exists */
function limit_login_get_option($option, $var_name) {
	$a = get_option($option);

	if ($a !== false) {
		global $limit_login_options;

		$limit_login_options[$var_name] = $a;
	}
}


/* Setup global variables from options */
function limit_login_setup_options() {
	limit_login_get_option('limit_login_client_type', 'client_type');
	limit_login_get_option('limit_login_allowed_retries', 'allowed_retries');
	limit_login_get_option('limit_login_lockout_duration', 'lockout_duration');
	limit_login_get_option('limit_login_valid_duration', 'valid_duration');
	limit_login_get_option('limit_login_cookies', 'cookies');
	limit_login_get_option('limit_login_lockout_notify', 'lockout_notify');
	limit_login_get_option('limit_login_allowed_lockouts', 'allowed_lockouts');
	limit_login_get_option('limit_login_long_duration', 'long_duration');
	limit_login_get_option('limit_login_notify_email_after', 'notify_email_after');

	limit_login_sanitize_variables();
}


/* Update options in db from global variables */
function limit_login_update_options() {
	update_option('limit_login_client_type', limit_login_option('client_type'));
	update_option('limit_login_allowed_retries', limit_login_option('allowed_retries'));
	update_option('limit_login_lockout_duration', limit_login_option('lockout_duration'));
	update_option('limit_login_allowed_lockouts', limit_login_option('allowed_lockouts'));
	update_option('limit_login_long_duration', limit_login_option('long_duration'));
	update_option('limit_login_valid_duration', limit_login_option('valid_duration'));
	update_option('limit_login_lockout_notify', limit_login_option('lockout_notify'));
	update_option('limit_login_notify_email_after', limit_login_option('notify_email_after'));
	update_option('limit_login_cookies', limit_login_option('cookies') ? '1' : '0');
}


/* Make sure the variables make sense -- simple integer */
function limit_login_sanitize_simple_int($var_name) {
	global $limit_login_options;

	$limit_login_options[$var_name] = max(1, intval(limit_login_option($var_name)));
}


/* Make sure the variables make sense */
function limit_login_sanitize_variables() {
	global $limit_login_options;

	limit_login_sanitize_simple_int('allowed_retries');
	limit_login_sanitize_simple_int('lockout_duration');
	limit_login_sanitize_simple_int('valid_duration');
	limit_login_sanitize_simple_int('allowed_lockouts');
	limit_login_sanitize_simple_int('long_duration');

	$limit_login_options['cookies'] = !!limit_login_option('cookies');

	$notify_email_after = max(1, intval(limit_login_option('notify_email_after')));
	$limit_login_options['notify_email_after'] = min(limit_login_option('allowed_lockouts'), $notify_email_after);

	$args = explode(',', limit_login_option('lockout_notify'));
	$args_allowed = explode(',', LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED);
	$new_args = array();
	foreach ($args as $a) {
		if (in_array($a, $args_allowed)) {
			$new_args[] = $a;
		}
	}
	$limit_login_options['lockout_notify'] = implode(',', $new_args);

	if ( limit_login_option('client_type') != LIMIT_LOGIN_DIRECT_ADDR
		 && limit_login_option('client_type') != LIMIT_LOGIN_PROXY_ADDR ) {
		$limit_login_options['client_type'] = LIMIT_LOGIN_DIRECT_ADDR;
	}
}


/* Add admin options page */
function limit_login_admin_menu() {
	global $wp_version;

	// Modern WP?
	if (version_compare($wp_version, '3.0', '>=')) {
	    add_options_page('Limit Login Attempts', 'Limit Login Attempts', 'manage_options', 'limit-login-attempts', 'limit_login_option_page');
	    return;
	}

	// Older WPMU?
	if (function_exists("get_current_site")) {
	    add_submenu_page('wpmu-admin.php', 'Limit Login Attempts', 'Limit Login Attempts', 9, 'limit-login-attempts', 'limit_login_option_page');
	    return;
	}

	// Older WP
	add_options_page('Limit Login Attempts', 'Limit Login Attempts', 9, 'limit-login-attempts', 'limit_login_option_page');
}


/* Show log on admin page */
function limit_login_show_log($log) {
	if (!is_array($log) || count($log) == 0) {
		return;
	}

	echo('<tr><th scope="col">' . _x("IP", "Internet address", 'limit-login-attempts') . '</th><th scope="col">' . __('Tried to log in as', 'limit-login-attempts') . '</th></tr>');
	foreach ($log as $ip => $arr) {
		echo('<tr><td class="limit-login-ip">' . esc_html($ip) . '</td><td class="limit-login-max">');
		$first = true;
		foreach($arr as $user => $count) {
			$count_desc = sprintf(_n('%d lockout', '%d lockouts', $count, 'limit-login-attempts'), $count);
			if (!$first) {
				echo(', ' . esc_html($user) . ' (' .  esc_html($count_desc) . ')');
			} else {
				echo(esc_html($user) . ' (' .  esc_html($count_desc) . ')');
			}
			$first = false;
		}
		echo('</td></tr>');
	}
}

/* Actual admin page */
function limit_login_option_page()	{	
	limit_login_cleanup();

	if (!current_user_can('manage_options')) {
		wp_die('Sorry, but you do not have permissions to change settings.');
	}

	/* Make sure post was from this page */
	if (count($_POST) > 0) {
		check_admin_referer('limit-login-attempts-options');
	}
		
	/* Should we clear log? */
	if (isset($_POST['clear_log'])) {
		delete_option('limit_login_logged');
		echo '<div id="message" class="updated fade"><p>'
			. __('Cleared IP log', 'limit-login-attempts')
			. '</p></div>';
	}
		
	/* Should we reset counter? */
	if (isset($_POST['reset_total'])) {
		update_option('limit_login_lockouts_total', 0);
		echo '<div id="message" class="updated fade"><p>'
			. __('Reset lockout count', 'limit-login-attempts')
			. '</p></div>';
	}
		
	/* Should we restore current lockouts? */
	if (isset($_POST['reset_current'])) {
		update_option('limit_login_lockouts', array());
		echo '<div id="message" class="updated fade"><p>'
			. __('Cleared current lockouts', 'limit-login-attempts')
			. '</p></div>';
	}

	/* Should we update options? */
	if (isset($_POST['update_options'])) {
		global $limit_login_options;

		$limit_login_options['client_type'] = $_POST['client_type'];
		$limit_login_options['allowed_retries'] = $_POST['allowed_retries'];
		$limit_login_options['lockout_duration'] = $_POST['lockout_duration'] * 60;
		$limit_login_options['valid_duration'] = $_POST['valid_duration'] * 3600;
		$limit_login_options['allowed_lockouts'] = $_POST['allowed_lockouts'];
		$limit_login_options['long_duration'] = $_POST['long_duration'] * 3600;
		$limit_login_options['notify_email_after'] = $_POST['email_after'];
		$limit_login_options['cookies'] = (isset($_POST['cookies']) && $_POST['cookies'] == '1');

		$v = array();
		if (isset($_POST['lockout_notify_log'])) {
			$v[] = 'log';
		}
		if (isset($_POST['lockout_notify_email'])) {
			$v[] = 'email';
		}
		$limit_login_options['lockout_notify'] = implode(',', $v);

		limit_login_sanitize_variables();
		limit_login_update_options();
		echo '<div id="message" class="updated fade"><p>'
			. __('Options changed', 'limit-login-attempts')
			. '</p></div>';
	}

	$lockouts_total = get_option('limit_login_lockouts_total', 0);
	$lockouts = get_option('limit_login_lockouts');
	$lockouts_now = is_array($lockouts) ? count($lockouts) : 0;

	$cookies_yes = limit_login_option('cookies') ? ' checked ' : '';
	$cookies_no = limit_login_option('cookies') ? '' : ' checked ';

	$client_type = limit_login_option('client_type');
	$client_type_direct = $client_type == LIMIT_LOGIN_DIRECT_ADDR ? ' checked ' : '';
	$client_type_proxy = $client_type == LIMIT_LOGIN_PROXY_ADDR ? ' checked ' : '';

	$client_type_guess = limit_login_guess_proxy();

	if ($client_type_guess == LIMIT_LOGIN_DIRECT_ADDR) {
		$client_type_message = sprintf(__('It appears the site is reached directly (from your IP: %s)','limit-login-attempts'), limit_login_get_address(LIMIT_LOGIN_DIRECT_ADDR));
	} else {
		$client_type_message = sprintf(__('It appears the site is reached through a proxy server (proxy IP: %s, your IP: %s)','limit-login-attempts'), limit_login_get_address(LIMIT_LOGIN_DIRECT_ADDR), limit_login_get_address(LIMIT_LOGIN_PROXY_ADDR));
	}
	$client_type_message .= '<br />';

	$client_type_warning = '';
	if ($client_type != $client_type_guess) {
		$faq = 'http://wordpress.org/extend/plugins/limit-login-attempts/faq/';

		$client_type_warning = '<br /><br />' . sprintf(__('<strong>Current setting appears to be invalid</strong>. Please make sure it is correct. Further information can be found <a href="%s" title="FAQ">here</a>','limit-login-attempts'), $faq);
	}

	$v = explode(',', limit_login_option('lockout_notify')); 
	$log_checked = in_array('log', $v) ? ' checked ' : '';
	$email_checked = in_array('email', $v) ? ' checked ' : '';
	?>
	<div class="wrap">
	  <h2><?php echo __('Limit Login Attempts Settings','limit-login-attempts'); ?></h2>
	  <h3><?php echo __('Statistics','limit-login-attempts'); ?></h3>
	  <form action="options-general.php?page=limit-login-attempts" method="post">
		<?php wp_nonce_field('limit-login-attempts-options'); ?>
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top"><?php echo __('Total lockouts','limit-login-attempts'); ?></th>
			<td>
			  <?php if ($lockouts_total > 0) { ?>
			  <input name="reset_total" value="<?php echo __('Reset Counter','limit-login-attempts'); ?>" type="submit" />
			  <?php echo sprintf(_n('%d lockout since last reset', '%d lockouts since last reset', $lockouts_total, 'limit-login-attempts'), $lockouts_total); ?>
			  <?php } else { echo __('No lockouts yet','limit-login-attempts'); } ?>
			</td>
		  </tr>
		  <?php if ($lockouts_now > 0) { ?>
		  <tr>
			<th scope="row" valign="top"><?php echo __('Active lockouts','limit-login-attempts'); ?></th>
			<td>
			  <input name="reset_current" value="<?php echo __('Restore Lockouts','limit-login-attempts'); ?>" type="submit" />
			  <?php echo sprintf(__('%d IP is currently blocked from trying to log in','limit-login-attempts'), $lockouts_now); ?> 
			</td>
		  </tr>
		  <?php } ?>
		</table>
	  </form>
	  <h3><?php echo __('Options','limit-login-attempts'); ?></h3>
	  <form action="options-general.php?page=limit-login-attempts" method="post">
		<?php wp_nonce_field('limit-login-attempts-options'); ?>
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top"><?php echo __('Lockout','limit-login-attempts'); ?></th>
			<td>
			  <input type="text" size="3" maxlength="4" value="<?php echo(limit_login_option('allowed_retries')); ?>" name="allowed_retries" /> <?php echo __('allowed retries','limit-login-attempts'); ?> <br />
			  <input type="text" size="3" maxlength="4" value="<?php echo(limit_login_option('lockout_duration')/60); ?>" name="lockout_duration" /> <?php echo __('minutes lockout','limit-login-attempts'); ?> <br />
			  <input type="text" size="3" maxlength="4" value="<?php echo(limit_login_option('allowed_lockouts')); ?>" name="allowed_lockouts" /> <?php echo __('lockouts increase lockout time to','limit-login-attempts'); ?> <input type="text" size="3" maxlength="4" value="<?php echo(limit_login_option('long_duration')/3600); ?>" name="long_duration" /> <?php echo __('hours','limit-login-attempts'); ?> <br />
			  <input type="text" size="3" maxlength="4" value="<?php echo(limit_login_option('valid_duration')/3600); ?>" name="valid_duration" /> <?php echo __('hours until retries are reset','limit-login-attempts'); ?>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><?php echo __('Site connection','limit-login-attempts'); ?></th>
			<td>
			  <?php echo $client_type_message; ?>
			  <label>
				<input type="radio" name="client_type" 
					   <?php echo $client_type_direct; ?> value="<?php echo LIMIT_LOGIN_DIRECT_ADDR; ?>" /> 
					   <?php echo __('Direct connection','limit-login-attempts'); ?> 
			  </label>
			  <label>
				<input type="radio" name="client_type" 
					   <?php echo $client_type_proxy; ?> value="<?php echo LIMIT_LOGIN_PROXY_ADDR; ?>" /> 
				  <?php echo __('From behind a reversy proxy','limit-login-attempts'); ?>
			  </label>
			  <?php echo $client_type_warning; ?>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><?php echo __('Handle cookie login','limit-login-attempts'); ?></th>
			<td>
			  <label><input type="radio" name="cookies" <?php echo $cookies_yes; ?> value="1" /> <?php echo __('Yes','limit-login-attempts'); ?></label> <label><input type="radio" name="cookies" <?php echo $cookies_no; ?> value="0" /> <?php echo __('No','limit-login-attempts'); ?></label>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><?php echo __('Notify on lockout','limit-login-attempts'); ?></th>
			<td>
			  <input type="checkbox" name="lockout_notify_log" <?php echo $log_checked; ?> value="log" /> <?php echo __('Log IP','limit-login-attempts'); ?><br />
			  <input type="checkbox" name="lockout_notify_email" <?php echo $email_checked; ?> value="email" /> <?php echo __('Email to admin after','limit-login-attempts'); ?> <input type="text" size="3" maxlength="4" value="<?php echo(limit_login_option('notify_email_after')); ?>" name="email_after" /> <?php echo __('lockouts','limit-login-attempts'); ?>
			</td>
		  </tr>
		</table>
		<p class="submit">
		  <input name="update_options" value="<?php echo __('Change Options','limit-login-attempts'); ?>" type="submit" />
		</p>
	  </form>
	  <?php
		$log = get_option('limit_login_logged');

		if (is_array($log) && count($log) > 0) {
	  ?>
	  <h3><?php echo __('Lockout log','limit-login-attempts'); ?></h3>
	  <form action="options-general.php?page=limit-login-attempts" method="post">
		<?php wp_nonce_field('limit-login-attempts-options'); ?>
		<input type="hidden" value="true" name="clear_log" />
		<p class="submit">
		  <input name="submit" value="<?php echo __('Clear Log','limit-login-attempts'); ?>" type="submit" />
		</p>
	  </form>
	  <style type="text/css" media="screen">
		.limit-login-log th {
			font-weight: bold;
		}
		.limit-login-log td, .limit-login-log th {
			padding: 1px 5px 1px 5px;
		}
		td.limit-login-ip {
			font-family:  "Courier New", Courier, monospace;
			vertical-align: top;
		}
		td.limit-login-max {
			width: 100%;
		}
	  </style>
	  <div class="limit-login-log">
		<table class="form-table">
		  <?php limit_login_show_log($log); ?>
		</table>
	  </div>
	  <?php
		} /* if showing $log */
	  ?>

	</div>	
	<?php		
}	
?>