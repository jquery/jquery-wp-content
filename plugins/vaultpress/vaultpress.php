<?php
/*
 * Plugin Name: VaultPress
 * Plugin URI: http://vaultpress.com/?utm_source=plugin-uri&amp;utm_medium=plugin-description&amp;utm_campaign=1.0
 * Description: Protect your content, themes, plugins, and settings with <strong>realtime backup</strong> and <strong>automated security scanning</strong> from <a href="http://vaultpress.com/?utm_source=wp-admin&amp;utm_medium=plugin-description&amp;utm_campaign=1.0" rel="nofollow">VaultPress</a>. Activate, enter your registration key, and never worry again. <a href="http://vaultpress.com/help/?utm_source=wp-admin&amp;utm_medium=plugin-description&amp;utm_campaign=1.0" rel="nofollow">Need some help?</a>
 * Version: 1.3.9
 * Author: Automattic
 * Author URI: http://vaultpress.com/?utm_source=author-uri&amp;utm_medium=plugin-description&amp;utm_campaign=1.0
 * License: GPL2+
 * Text Domain: vaultpress
 * Domain Path: /languages/
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) )
	return;

class VaultPress {
	var $option_name    = 'vaultpress';
	var $db_version     = 2;
	var $plugin_version = '1.3.8';

	function VaultPress() {
		$this->__construct();
	}

	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		$options = get_option( $this->option_name );
		if ( !is_array( $options ) )
			$options = array();

		$defaults = array(
			'db_version'            => 0,
			'key'                   => '',
			'secret'                => '',
			'connection'            => false,
			'service_ips'           => false
		);

		$this->options = wp_parse_args( $options, $defaults );
		$this->reset_pings();

		$this->upgrade();

		if ( is_admin() )
			$this->add_admin_actions_and_filters();

		if ( $this->is_registered() ) {
			$do_not_backup = $this->get_option( 'do_not_backup' );
			if ( $do_not_backup )
				$this->add_vp_required_filters();
			else
				$this->add_listener_actions_and_filters();
		}
	}

	function &init() {
		static $instance = false;

		if ( !$instance ) {
			$instance = new VaultPress();
		}

		return $instance;
	}

	function activate( $network_wide ) {
		$type = $network_wide ? 'network' : 'single';
		$this->update_option( 'activated', $type );

		// force a connection check after an activation
		$this->clear_connection();
	}

	function deactivate() {
		if ( $this->is_registered() )
			$this->contact_service( 'plugin_status', array( 'vp_plugin_status' => 'deactivated' ) );
	}

	function upgrade() {
		$current_db_version = $this->get_option( 'db_version' );

		if ( $current_db_version < 1 ) {
			$this->options['connection']  = get_option( 'vaultpress_connection' );
			$this->options['key']         = get_option( 'vaultpress_key' );
			$this->options['secret']      = get_option( 'vaultpress_secret' );
			$this->options['service_ips'] = get_option( 'vaultpress_service_ips' );

			// remove old options
			$old_options = array(
				'vaultpress_connection',
				'vaultpress_hostname',
				'vaultpress_key',
				'vaultpress_secret',
				'vaultpress_service_ips',
				'vaultpress_timeout',
				'vp_allow_remote_execution',
				'vp_debug_request_signing',
				'vp_disable_firewall',
			);

			foreach ( $old_options as $option )
				delete_option( $option );

			$this->options['db_version'] = $this->db_version;
			$this->update_options();
		}

		if ( $current_db_version < 2 ) {
			$this->delete_option( 'timeout' );
			$this->delete_option( 'disable_firewall' );
			$this->update_option( 'db_version', $this->db_version );
			$this->clear_connection();
		}
	}

	function get_option( $key ) {
		if ( 'hostname' == $key ) {
			if ( defined( 'VAULTPRESS_HOSTNAME' ) )
				return VAULTPRESS_HOSTNAME;
			else
				return 'vaultpress.com';
		}

		if ( 'timeout' == $key ) {
			if ( defined( 'VAULTPRESS_TIMEOUT' ) )
				return VAULTPRESS_TIMEOUT;
			else
				return 60;
		}

		if ( 'disable_firewall' == $key ) {
			if ( defined( 'VAULTPRESS_DISABLE_FIREWALL' ) )
				return VAULTPRESS_DISABLE_FIREWALL;
			else
				return false;
		}

		if ( isset( $this->options[$key] ) )
			return $this->options[$key];

		return false;
	}

	function update_option( $key, $value ) {
		$this->options[$key] = $value;
		$this->update_options();
	}

	function delete_option( $key ) {
		unset( $this->options[$key] );
		$this->update_options();
	}

	function update_options() {
		update_option( $this->option_name, $this->options );
	}

	function admin_init() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		load_plugin_textdomain( 'vaultpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	function admin_head() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		if ( $activated = $this->get_option( 'activated' ) ) {
			if ( 'network' == $activated ) {
				add_action( 'network_admin_notices', array( $this, 'activated_notice' ) );
			} else {
				foreach ( array( 'user_admin_notices', 'admin_notices' ) as $filter )
					add_action( $filter, array( $this, 'activated_notice' ) );
			}
		}

		// ask the user to connect their site w/ VP
		if ( !$this->is_registered() ) {
			foreach ( array( 'user_admin_notices', 'admin_notices' ) as $filter )
				add_action( $filter, array( $this, 'connect_notice' ) );

		// if we have an error make sure to let the user know about it
		} else {
			$error_code = $this->get_option( 'connection_error_code' );
		 	if ( !empty( $error_code ) ) {
				foreach ( array( 'user_admin_notices', 'admin_notices' ) as $filter )
					add_action( $filter, array( $this, 'error_notice' ) );
			}
		}
	?>

		<style type="text/css">
			#toplevel_page_vaultpress div.wp-menu-image {
				background: url(<?php echo esc_url( $this->server_url() ); ?>images/vp-icon-sprite.png?20111216) center top no-repeat;
				background-size: 28px 84px;
			}

			.admin-color-classic #toplevel_page_vaultpress div.wp-menu-image {
				background-position: center -28px;
			}

			#toplevel_page_vaultpress.current div.wp-menu-image,
			#toplevel_page_vaultpress:hover div.wp-menu-image {
				background-position: center bottom;
			}

			@media only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-device-pixel-ratio: 1.5) {
				#toplevel_page_vaultpress div.wp-menu-image {
					background-image: url(<?php echo esc_url( $this->server_url() ); ?>images/vp-icon-sprite-2x.png?20111216);
				}
			}
		</style>

	<?php

	}

	function admin_menu() {
		// if Jetpack is loaded then we need to wait for that menu to be added
		if ( class_exists( 'Jetpack' ) )
			add_action( 'jetpack_admin_menu', array( $this, 'load_menu' ) );
		else
			$this->load_menu();
	}

	function load_menu() {
		if ( class_exists( 'Jetpack' ) ) {
			$hook = add_submenu_page( 'jetpack', 'VaultPress', 'VaultPress', 'manage_options', 'vaultpress', array( $this, 'ui' ) );
		} else {
			$hook = add_menu_page( 'VaultPress', 'VaultPress', 'manage_options', 'vaultpress', array( $this, 'ui' ), 'div' );
		}

		add_action( "load-$hook", array( $this, 'ui_load' ) );
		add_action( 'admin_print_styles', array( $this, 'styles' ) );
	}

	function styles() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		// force the cache to bust every day
		wp_enqueue_style( 'vaultpress', $this->server_url() . 'css/plugin.css' , false, date( 'Ymd' ) );
	}

	// display a security threat notice if one exists
	function toolbar( $wp_admin_bar ) {
		global $wp_version;

		// these new toolbar functions were introduced in 3.3
		// http://codex.wordpress.org/Function_Reference/add_node
		if ( version_compare( $wp_version, '3.3', '<') )
			return;

		if ( !current_user_can( 'manage_options' ) )
			return;

		$messages = $this->get_messages();
		if ( !empty( $messages['security_notice_count'] ) ) {
			$count = (int)$messages['security_notice_count'];
			if ( $count > 0 ) {
				$count = number_format( $count, 0 );
				$wp_admin_bar->add_node( array(
					'id' => 'vp-notice',
					'title' => '<strong><span class="ab-icon"></span>' .
						sprintf( _n( '%s Security Threat', '%s Security Threats', $count , 'vaultpress'), $count ) .
					' </strong>',
					'parent' => 'top-secondary',
					'href' => sprintf( 'https://dashboard.vaultpress.com/%d/security/', $messages['site_id'] ),
					'meta'  => array(
						'title' => __( 'Visit VaultPress Security' , 'vaultpress'),
						'onclick' => 'window.open( this.href ); return false;',
						'class' => 'error'
					),
				) );
			}
		}
	}

	// get any messages from the VP servers
	function get_messages( $force_reload = false ) {
		$last_contact = $this->get_option( 'messages_last_contact' );

		// only run the messages check every 30 minutes
		if ( ( time() - (int)$last_contact ) > 1800 || $force_reload ) {
			$messages = base64_decode( $this->contact_service( 'messages', array() ) );
			$messages = unserialize( $messages );
			$this->update_option( 'messages_last_contact', time() );
			$this->update_option( 'messages', $messages );
		} else {
			$messages = $this->get_option( 'messages' );
		}

		return $messages;
	}

	function server_url() {
		if ( !isset( $this->_server_url ) ) {
			$scheme = is_ssl() ? 'https' : 'http';
			$this->_server_url = sprintf( '%s://%s/', $scheme, $this->get_option( 'hostname' ) );
		}

		return $this->_server_url;
	}

	// show message if plugin is activated but not connected to VaultPress
	function connect_notice() {
		if ( isset( $_GET['page'] ) && 'vaultpress' == $_GET['page'] )
			return;

		$message = sprintf(
			__( 'You must enter your registration key before VaultPress can back up and secure your site. <a href="%1$s">Register&nbsp;VaultPress</a>', 'vaultpress' ),
			admin_url( 'admin.php?page=vaultpress' )
		);
		$this->ui_message( $message, 'notice', __( 'VaultPress needs your attention!', 'vaultpress' ) );
	}

	// show message after activation
	function activated_notice() {
		if ( 'network' == $this->get_option( 'activated' ) ) {
			$message = sprintf(
				__( 'Each site will need to be registered with VaultPress separately. You can purchase new keys from your <a href="%1$s">VaultPress&nbsp;Dashboard</a>.', 'vaultpress' ),
				'https://dashboard.vaultpress.com/'
			);
			$this->ui_message( $message, 'activated', __( 'VaultPress has been activated across your network!', 'vaultpress' ) );

		// key and secret already exist in db
		} elseif ( $this->is_registered() ) {
			if ( $this->check_connection() ) {
				$message = sprintf(
					__( 'VaultPress has been registered and is currently backing up your site. <a href="%1$s">View Backup Status</a>', 'vaultpress' ),
					admin_url( 'admin.php?page=vaultpress' )
				);
				$this->ui_message( $message, 'registered',  __( 'VaultPress has been activated!', 'vaultpress' ) );
			}
		}

		$this->delete_option( 'activated' );
	}

	function error_notice() {
		$error_message = $this->get_option( 'connection_error_message' );

		// link to the VaultPress page if we're not already there
		if ( !isset( $_GET['page'] ) || 'vaultpress' != $_GET['page'] )
			$error_message .= ' ' . sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=vaultpress' ), __( 'Visit&nbsp;the&nbsp;VaultPress&nbsp;page' , 'vaultpress') );

		if ( !empty( $error_message ) )
			$this->ui_message( $error_message, 'error' );
	}

	function ui() {
		if ( !empty( $_GET['error'] ) ) {
			$this->error_notice();
			$this->clear_connection();
		}

		if ( !$this->is_registered() ) {
			$this->ui_register();
			return;
		}

		$status = $this->contact_service( 'status' );
		if ( !$status ) {
			$error_code = $this->get_option( 'connection_error_code' );
			if ( 0 == $error_code )
				$this->ui_fatal_error();
			else
				$this->ui_register();
			return;
		}

		$ticker = $this->contact_service( 'ticker' );
		if ( is_array( $ticker ) && isset( $ticker['faultCode'] ) ) {
			$this->error_notice();
			$this->ui_register();
			return;
		}

		$this->ui_main();
	}

	function ui_load() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		// run code that might be updating the registration key
		if ( isset( $_POST['action'] ) && 'register' == $_POST['action'] ) {
			check_admin_referer( 'vaultpress_register' );

			// reset the connection info so messages don't cross
			$this->clear_connection();

			$registration_key = trim( $_POST[ 'registration_key' ] );
			if ( empty( $registration_key ) ) {
				$this->update_option( 'connection_error_code', 1 );
				$this->update_option(
					'connection_error_message',
					sprintf(
						__( '<strong>That\'s not a valid registration key.</strong> Head over to the <a href="%1$s" title="Sign in to your VaultPress Dashboard">VaultPress&nbsp;Dashboard</a> to find your key.', 'vaultpress' ),
						'https://dashboard.vaultpress.com/'
					)
				);
				wp_redirect( admin_url( 'admin.php?page=vaultpress&error=true' ) );
				exit();
			}

			// try to register the plugin
			$nonce = wp_create_nonce( 'vp_register_' . $registration_key );
			$args = array( 'registration_key' =>  $registration_key, 'nonce' => $nonce );
			$response = $this->contact_service( 'register', $args );

			// we received an error from the VaultPress servers
			if ( !empty( $response['faultCode'] ) ) {
				$this->update_option( 'connection_error_code',    $response['faultCode'] );
				$this->update_option( 'connection_error_message', $response['faultString'] );
				wp_redirect( admin_url( 'admin.php?page=vaultpress&error=true' ) );
				exit();
			}

			// make sure the returned data looks valid
			if ( empty( $response['key'] ) || empty( $response['secret'] ) || empty( $response['nonce'] ) || $nonce != $response['nonce'] ) {
				$this->update_option( 'connection_error_code', 1 );
				$this->update_option( 'connection_error_message', sprintf( __( 'There was a problem trying to register your subscription. Please try again. If you&rsquo;re still having issues please <a href="%1$s">contact the VaultPress&nbsp;Safekeepers</a>.', 'vaultpress' ), 'http://vaultpress.com/contact/' ) );
				wp_redirect( admin_url( 'admin.php?page=vaultpress&error=true' ) );
				exit();
			}

			// need to update these values in the db so the servers can try connecting to the plugin
			$this->update_option( 'key', $response['key'] );
			$this->update_option( 'secret', $response['secret'] );
			if ( $this->check_connection( true ) ) {
				wp_redirect( admin_url( 'admin.php?page=vaultpress' ) );
				exit();
			}

			// reset the key and secret
			$this->update_option( 'key', '' );
			$this->update_option( 'secret', '' );
			wp_redirect( admin_url( 'admin.php?page=vaultpress&error=true' ) );
			exit();
		}
	}

	function ui_register() {
?>
	<div id="vp-wrap" class="wrap">
		<div id="vp-head">
			<h2>VaultPress<a href="https://dashboard.vaultpress.com/" class="vp-visit-dashboard" target="_blank"><?php _e( 'Visit Dashboard', 'vaultpress' ); ?></a></h2>
		</div>

		<div id="vp_registration">
			<div class="vp_view-plans">
				<h1><?php _e( 'The VaultPress plugin <strong>requires a monthly&nbsp;subscription</strong>.', 'vaultpress' ); ?></h1>
				<p><?php _e( 'Get realtime backups, automated security scanning, and support from WordPress&nbsp;experts.', 'vaultpress' ); ?></p>
				<p class="vp_plans-btn"><a href="https://vaultpress.com/plugin/?utm_source=plugin-unregistered&amp;utm_medium=view-plans-and-pricing&amp;utm_campaign=1.0-plugin"><strong><?php _e( 'View plans and pricing&nbsp;&raquo;', 'vaultpress' ); ?></strong></a></p>
			</div>

			<div class="vp_register-plugin">
				<h3><?php _e( 'Already have a VaultPress&nbsp;account?', 'vaultpress' ); ?></h3>
				<p><?php _e( 'Paste your registration key&nbsp;below:', 'vaultpress' ); ?></p>
				<form method="post" action="">
					<fieldset>
						<textarea placeholder="<?php echo esc_attr( __( 'Enter your key here...', 'vaultpress' ) ); ?>" name="registration_key"></textarea>
						<button><strong><?php _e( 'Register ', 'vaultpress' ); ?></strong></button>
						<input type="hidden" name="action" value="register" />
						<?php wp_nonce_field( 'vaultpress_register' ); ?>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
<?php
	}

	function ui_main() {
?>
	<div id="vp-wrap" class="wrap">
		<?php
			$response = base64_decode( $this->contact_service( 'plugin_ui' ) );
			echo $response;
		?>
	</div>
<?php
	}

	function ui_fatal_error() {
	?>
		<div id="vp-wrap" class="wrap">
			<h2>VaultPress</h2>

			<p><?php printf( __( 'Yikes! We&rsquo;ve run into a serious issue and can&rsquo;t connect to %1$s.', 'vaultpress' ), esc_html( $this->get_option( 'hostname' ) ) ); ?></p>
			<p><?php printf( __( 'Please make sure that your website is accessible via the Internet. If you&rsquo;re still having issues please <a href="%1$s">contact the VaultPress&nbsp;Safekeepers</a>.', 'vaultpress' ), 'http://vaultpress.com/contact/' ); ?></p>
		</div>
	<?php
	}

	function ui_message( $message, $type = 'notice', $heading = '' ) {
		if ( empty( $heading ) ) {
			switch ( $type ) {
				case 'error':
					$heading = __( 'Oops... there seems to be a problem.', 'vaultpress' );
					break;

				case 'success':
					$heading = __( 'Yay! Things look good.', 'vaultpress' );
					break;

				default:
					$heading = __( 'VaultPress needs your attention!', 'vaultpress' );
					break;
			}
		}
?>
		<div id="vp-notice" class="vp-<?php echo $type; ?> updated">
			<div class="vp-message">
				<h3><?php echo $heading; ?></h3>
				<p><?php echo $message; ?></p>
			</div>
		</div>
<?php
	}

	function get_config( $key ) {
		$val = get_option( $key );
		if ( $val )
			return $val;
		switch( $key ) {
			case '_vp_config_option_name_ignore':
				$val = $this->get_option_name_ignore( true );
				update_option( '_vp_config_option_name_ignore', $val );
				break;
		}
		return $val;
	}

	// Option name patterns to ignore
	function get_option_name_ignore( $return_defaults = false ) {
		$defaults = array(
			'vaultpress',
			'cron',
			'wpsupercache_gc_time',
			'rewrite_rules',
			'akismet_spam_count',
			'/_transient_/',
			'/^_vp_/',
		);
		if ( $return_defaults )
			return $defaults;
		$ignore_names = $this->get_config( '_vp_config_option_name_ignore' );
		return array_unique( array_merge( $defaults, $ignore_names ) );
	}

	###
	### Section: Backup Notification Hooks
	###

	// Handle Handle Notifying VaultPress of Options Activity At this point the options table has already been modified
	//
	// Note: we handle deleted, instead of delete because VaultPress backs up options by name (which are unique,) that
	// means that we do not need to resolve an id like we would for, say, a post.
	function option_handler( $option_name ) {
		global $wpdb;
		// Step 1 -- exclusionary rules, don't send these options to vaultpress, because they
		// either change constantly and/or are inconsequential to the blog itself and/or they
		// are specific to the VaultPress plugin process and we want to avoid recursion
		$should_ping = true;
		$ignore_names = $this->get_option_name_ignore();
		foreach( (array)$ignore_names as $val ) {
			if ( $val{0} == '/' ) {
				if ( preg_match( $val, $option_name ) )
					$should_ping = false;
			} else {
				if ( $val == $option_name )
					$should_ping = false;
			}
			if ( !$should_ping )
				break;
		}
		if ( $should_ping )
			$this->add_ping( 'db', array( 'option' => $option_name ) );

		// Step 2 -- If WordPress is about to kick off a some "cron" action, we need to
		// flush vaultpress, because the "remote" cron threads done via http fetch will
		// be happening completely inside the window of this thread.  That thread will
		// be expecting touched and accounted for tables
		if ( $option_name == '_transient_doing_cron' )
			$this->do_pings();

		return $option_name;
	}

	// Handle Notifying VaultPress of Comment Activity
	function comment_action_handler( $comment_id ) {
		if ( !is_array( $comment_id ) ) {
			if ( wp_get_comment_status( $comment_id ) != 'spam' )
				$this->add_ping( 'db', array( 'comment' => $comment_id ) );
		} else {
			foreach ( $comment_id as $id ) {
				if ( wp_get_comment_status( $comment_id ) != 'spam' )
					$this->add_ping( 'db', array( 'comment' => $id) );
			}
		}
	}

	// Handle Notifying VaultPress of Theme Switches
	function theme_action_handler( $theme ) {
		$this->add_ping( 'themes', array( 'theme' => get_option( 'stylesheet' ) ) );
	}

	// Handle Notifying VaultPress of Upload Activity
	function upload_handler( $file ) {
		$this->add_ping( 'uploads', array( 'upload' => str_replace( $this->resolve_upload_path(), '', $file['file'] ) ) );
		return $file;
	}

	// Handle Notifying VaultPress of Plugin Activation/Deactivation
	function plugin_action_handler( $plugin='' ) {
		$this->add_ping( 'plugins', array( 'name' => $plugin ) );
	}

	// Handle Notifying VaultPress of User Edits
	function userid_action_handler( $user_or_id ) {
		if ( is_object($user_or_id) )
			$userid = intval( $user_or_id->ID );
		else
			$userid = intval( $user_or_id );
		if ( !$userid )
			return;
		$this->add_ping( 'db', array( 'user' => $userid ) );
	}

	// Handle Notifying VaultPress of term changes
	function term_handler( $term_id, $tt_id=null ) {
		$this->add_ping( 'db', array( 'term' => $term_id ) );
		if ( $tt_id )
			$this->term_taxonomy_handler( $tt_id );
	}

	// Handle Notifying VaultPress of term_taxonomy changes
	function term_taxonomy_handler( $tt_id ) {
		$this->add_ping( 'db', array( 'term_taxonomy' => $tt_id ) );
	}
	// add(ed)_term_taxonomy handled via the created_term hook, the term_taxonomy_handler is called by the term_handler

	// Handle Notifying VaultPress of term_taxonomy changes
	function term_taxonomies_handler( $tt_ids ) {
		foreach( (array)$tt_ids as $tt_id ) {
			$this->term_taxonomy_handler( $tt_id );
		}
	}

	// Handle Notifying VaultPress of term_relationship changes
	function term_relationship_handler( $object_id, $term_id ) {
		$this->add_ping( 'db', array( 'term_relationship' => array( 'object_id' => $object_id, 'term_taxonomy_id' => $term_id ) ) );
	}

	// Handle Notifying VaultPress of term_relationship changes
	function term_relationships_handler( $object_id, $term_ids ) {
		foreach ( (array)$term_ids as $term_id ) {
			$this->term_relationship_handler( $object_id, $term_id );
		}
	}

	// Handle Notifying VaultPress of term_relationship changes
	function set_object_terms_handler( $object_id, $terms, $tt_ids ) {
		$this->term_relationships_handler( $object_id, $tt_ids );
	}

	// Handle Notifying VaultPress of UserMeta changes
	function usermeta_action_handler( $umeta_id, $user_id, $meta_key, $meta_value='' ) {
		$this->add_ping( 'db', array( 'usermeta' => $umeta_id ) );
	}

	// Handle Notifying VaultPress of Post Changes
	function post_action_handler($post_id) {
		if ( current_filter() == 'delete_post' )
			return $this->add_ping( 'db', array( 'post' => $post_id ), 'delete_post' );
		return $this->add_ping( 'db', array( 'post' => $post_id ), 'edit_post' );
	}

	// Handle Notifying VaultPress of Link Changes
	function link_action_handler( $link_id ) {
		$this->add_ping( 'db', array( 'link' => $link_id ) );
	}

	// Handle Notifying VaultPress of Commentmeta Changes
	function commentmeta_insert_handler( $meta_id, $comment_id=null ) {
		if ( empty( $comment_id ) || wp_get_comment_status( $comment_id ) != 'spam' )
			$this->add_ping( 'db', array( 'commentmeta' => $meta_id ) );
	}

	function commentmeta_modification_handler( $meta_id, $object_id, $meta_key, $meta_value ) {
		if ( !is_array( $meta_id ) )
			return $this->add_ping( 'db', array( 'commentmeta' => $meta_id ) );
		foreach ( $meta_id as $id ) {
			$this->add_ping( 'db', array( 'commentmeta' => $id ) );
		}
	}

	// Handle Notifying VaultPress of PostMeta changes via newfangled metadata functions
	function postmeta_insert_handler( $meta_id, $post_id, $meta_key, $meta_value='' ) {
		$this->add_ping( 'db', array( 'postmeta' => $meta_id ) );
	}

	function postmeta_modification_handler( $meta_id, $object_id, $meta_key, $meta_value ) {
		if ( !is_array( $meta_id ) )
			return $this->add_ping( 'db', array( 'postmeta' => $meta_id ) );
		foreach ( $meta_id as $id ) {
			$this->add_ping( 'db', array( 'postmeta' => $id ) );
		}
	}

	// Handle Notifying VaultPress of PostMeta changes via old school cherypicked hooks
	function postmeta_action_handler( $meta_id ) {
		if ( !is_array($meta_id) )
			return $this->add_ping( 'db', array( 'postmeta' => $meta_id ) );
		foreach ( $meta_id as $id )
			$this->add_ping( 'db', array( 'postmeta' => $id ) );
	}

	function verify_table( $table ) {
		global $wpdb;
		$table = $wpdb->escape( $table );
		$status = $wpdb->get_row( "SHOW TABLE STATUS WHERE Name = '$table'" );
		if ( !$status || !$status->Update_time || !$status->Comment || $status->Engine != 'MyISAM' )
			return true;
		if ( preg_match( '/([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2})/', $status->Comment, $m ) )
			return ( $m[1] == $status->Update_time );
		return false;
	}

	// Emulate $wpdb->last_table
	function record_table( $table ) {
		global $vaultpress_last_table;
		$vaultpress_last_table = $table;
		return $table;
	}

	// Emulate $wpdb->last_table
	function get_last_table() {
		global $wpdb, $vaultpress_last_table;
		if ( is_object( $wpdb ) && isset( $wpdb->last_table ) )
			return $wpdb->last_table;
		return $vaultpress_last_table;
	}

	// Emulate hyperdb::is_write_query()
	function is_write_query( $q ) {
		$word = strtoupper( substr( trim( $q ), 0, 20 ) );
		if ( 0 === strpos( $word, 'SELECT' ) )
			return false;
		if ( 0 === strpos( $word, 'SHOW' ) )
			return false;
		if ( 0 === strpos( $word, 'CHECKSUM' ) )
			return false;
		return true;
	}

	// Emulate hyperdb::get_table_from_query()
	function get_table_from_query( $q ) {
		global $wpdb, $vaultpress_last_table;

		if ( is_object( $wpdb ) && method_exists( $wpdb, "get_table_from_query" ) )
			return $wpdb->get_table_from_query( $q );

		// Remove characters that can legally trail the table name
		$q = rtrim( $q, ';/-#' );
		// allow ( select... ) union [...] style queries. Use the first queries table name.
		$q = ltrim( $q, "\t (" );

		// Quickly match most common queries
		if ( preg_match( '/^\s*(?:'
				. 'SELECT.*?\s+FROM'
				. '|INSERT(?:\s+IGNORE)?(?:\s+INTO)?'
				. '|REPLACE(?:\s+INTO)?'
				. '|UPDATE(?:\s+IGNORE)?'
				. '|DELETE(?:\s+IGNORE)?(?:\s+FROM)?'
				. ')\s+`?(\w+)`?/is', $q, $maybe) )
			return $this->record_table($maybe[1] );

		// Refer to the previous query
		if ( preg_match( '/^\s*SELECT.*?\s+FOUND_ROWS\(\)/is', $q ) )
			return $this->get_last_table();

		// Big pattern for the rest of the table-related queries in MySQL 5.0
		if ( preg_match( '/^\s*(?:'
				. '(?:EXPLAIN\s+(?:EXTENDED\s+)?)?SELECT.*?\s+FROM'
				. '|INSERT(?:\s+LOW_PRIORITY|\s+DELAYED|\s+HIGH_PRIORITY)?(?:\s+IGNORE)?(?:\s+INTO)?'
				. '|REPLACE(?:\s+LOW_PRIORITY|\s+DELAYED)?(?:\s+INTO)?'
				. '|UPDATE(?:\s+LOW_PRIORITY)?(?:\s+IGNORE)?'
				. '|DELETE(?:\s+LOW_PRIORITY|\s+QUICK|\s+IGNORE)*(?:\s+FROM)?'
				. '|DESCRIBE|DESC|EXPLAIN|HANDLER'
				. '|(?:LOCK|UNLOCK)\s+TABLE(?:S)?'
				. '|(?:RENAME|OPTIMIZE|BACKUP|RESTORE|CHECK|CHECKSUM|ANALYZE|OPTIMIZE|REPAIR).*\s+TABLE'
				. '|TRUNCATE(?:\s+TABLE)?'
				. '|CREATE(?:\s+TEMPORARY)?\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?'
				. '|ALTER(?:\s+IGNORE)?\s+TABLE'
				. '|DROP\s+TABLE(?:\s+IF\s+EXISTS)?'
				. '|CREATE(?:\s+\w+)?\s+INDEX.*\s+ON'
				. '|DROP\s+INDEX.*\s+ON'
				. '|LOAD\s+DATA.*INFILE.*INTO\s+TABLE'
				. '|(?:GRANT|REVOKE).*ON\s+TABLE'
				. '|SHOW\s+(?:.*FROM|.*TABLE)'
				. ')\s+`?(\w+)`?/is', $q, $maybe ) )
			return $this->record_table( $maybe[1] );

		// All unmatched queries automatically fall to the global master
		return $this->record_table( '' );
	}

	function table_notify_columns( $table ) {
			$want_cols = array(
				// data
				'posts'                 => '`ID`',
				'users'                 => '`ID`',
				'links'                 => '`link_id`',
				'options'               => '`option_id`,`option_name`',
				'comments'              => '`comment_ID`',
				// metadata
				'postmeta'              => '`meta_id`',
				'commentmeta'           => '`meta_id`',
				'usermeta'              => '`umeta_id`',
				// taxonomy
				'term_relationships'    => '`object_id`,`term_taxonomy_id`',
				'term_taxonomy'         => '`term_taxonomy_id`',
				'terms'                 => '`term_id`',
				// plugin special cases
				'wpo_campaign'          => '`id`', // WP-o-Matic
				'wpo_campaign_category' => '`id`', // WP-o-Matic
				'wpo_campaign_feed'     => '`id`', // WP-o-Matic
				'wpo_campaign_post'     => '`id`', // WP-o-Matic
				'wpo_campaign_word'     => '`id`', // WP-o-Matic
				'wpo_log'               => '`id`', // WP-o-Matic
			);
			if ( isset( $want_cols[$table] ) )
				return $want_cols[$table];
			return '*';
	}

	function ai_ping_next() {
		global $wpdb;
		$name = "_vp_ai_ping";
		$rval = $wpdb->query( $wpdb->prepare( "REPLACE INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, '', 'no')", $name ) );
		if ( !$rval )
			return false;
		return $wpdb->insert_id;
	}

	function ai_ping_insert( $value ) {
		$new_id = $this->ai_ping_next();
		if ( !$new_id )
			return false;
		add_option( '_vp_ai_ping_' . $new_id, $value, '', 'no' );
	}

	function ai_ping_count() {
		global $wpdb;
		return $wpdb->get_var( "SELECT COUNT(`option_id`) FROM $wpdb->options WHERE `option_name` LIKE '\_vp\_ai\_ping\_%'" );
	}

	function ai_ping_get( $num=1, $order='ASC' ) {
		global $wpdb;
		if ( strtolower($order) != 'desc' )
			$order = 'ASC';
		else
			$order = 'DESC';
		return $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $wpdb->options WHERE `option_name` LIKE '\_vp\_ai\_ping\_%%' ORDER BY `option_id` $order LIMIT %d",
			min( 10, max( 1, (int)$num ) )
		) );
	}

	function update_firewall() {
		$args     = array( 'timeout' => $this->get_option( 'timeout' ) );
		$hostname = $this->get_option( 'hostname' );
		$data     = wp_remote_get( "http://$hostname/service-ips", $args );

		if ( $data )
			$data = @unserialize( $data['body'] );

		if ( $data ) {
			$newval = array( 'updated' => time(), 'data' => $data );
			$this->update_option( 'service_ips', $newval );
		}

		// update cloudflare IP address list
		$cf_data = wp_remote_retrieve_body( wp_remote_get( 'https://www.cloudflare.com/ips-v4', $args ) );
		if ( $cf_data && !empty( $cf_data['body'] ) ) {
			$this->update_option( 'cloudflare_ips', array(
				'updated' => time(),
				'data' => explode( "\n", $cf_data['body'] )
			) );
		}

		if ( $data ) {
			return $data;
		} else { 
			return null;
		}
	}

	function check_connection( $force_check = false ) {
		$connection = $this->get_option( 'connection' );

		if ( !$force_check && !empty( $connection ) ) {
			// already established a connection
		 	if ( 'ok' == $connection )
				return true;

			// only run the connection check every 5 minutes
			if ( ( time() - (int)$connection ) < 300 )
				return false;
		}

		// if we're running a connection test we don't want to run it a second time
		$connection_test = $this->get_option( 'connection_test' );
		if ( $connection_test )
			return true;

		// force update firewall settings
		$this->update_firewall();

		// initial connection test to server
		$this->update_option( 'connection_test', true );
		$this->delete_option( 'allow_forwarded_for' );
		$connect = $this->contact_service( 'test', array( 'host' => $_SERVER['HTTP_HOST'], 'uri' => $_SERVER['REQUEST_URI'], 'ssl' => is_ssl() ) );

		// we can't see the servers at all
		if ( !$connect ) {
			$this->update_option( 'connection', time() );
			$this->update_option( 'connection_error_code', 0 );
			$this->update_option( 'connection_error_message', sprintf( __( 'Cannot connect to the VaultPress servers. Please check that your host allows connecting to external sites and try again. If you&rsquo;re still having issues please <a href="%1$s">contact the VaultPress&nbsp;Safekeepers</a>.', 'vaultpress' ), 'http://vaultpress.com/contact/' ) );

			$this->delete_option( 'connection_test' );
			return false;
		}

		// VaultPress gave us a meaningful error
		if ( !empty( $connect['faultCode'] ) ) {
			$this->update_option( 'connection', time() );
			$this->update_option( 'connection_error_code', $connect['faultCode'] );
			$this->update_option( 'connection_error_message', $connect['faultString'] );
			$this->delete_option( 'connection_test' );
			return false;
		}

		$this->update_option( 'do_not_backup', ( false === $connect['do_backups'] ) );
		if ( !empty( $connect['signatures'] ) ) {
			delete_option( '_vp_signatures' );
			add_option( '_vp_signatures', maybe_unserialize( $connect['signatures'] ), '', 'no' );
		}

		// test connection between the site and the servers
		$connect = (string)$this->contact_service( 'test', array( 'type' => 'connect' ) );
		if ( 'ok' != $connect ) {

			// still not working so see if we're behind a load balancer
			$this->update_option( 'allow_forwarded_for', true );
			$connect = (string)$this->contact_service( 'test', array( 'type' => 'firewall-off' ) );

			if ( 'ok' != $connect ) {
				if ( 'error' == $connect ) {
					$this->update_option( 'connection_error_code', -1 );
					$this->update_option( 'connection_error_message', sprintf( __( 'The VaultPress servers cannot connect to your site. Please check that your site is visible over the Internet and there are no firewall or load balancer settings on your server that might be blocking the communication. If you&rsquo;re still having issues please <a href="%1$s">contact the VaultPress&nbsp;Safekeepers</a>.', 'vaultpress' ), 'http://vaultpress.com/contact/' ) );
				} elseif ( !empty( $connect['faultCode'] ) ) {
					$this->update_option( 'connection_error_code', $connect['faultCode'] );
					$this->update_option( 'connection_error_message', $connect['faultString'] );
				}

				$this->update_option( 'connection', time() );
				$this->delete_option( 'connection_test' );
				return false;
			}
		}

		// successful connection established
		$this->update_option( 'connection', 'ok' );
		$this->delete_option( 'connection_error_code' );
		$this->delete_option( 'connection_error_message' );
		$this->delete_option( 'connection_test' );
		return true;
	}

	function parse_request( $wp ) {
		if ( !isset( $_GET['vaultpress'] ) || $_GET['vaultpress'] !== 'true' )
			return $wp;

		global $wpdb, $current_blog;

		// just in case we have any plugins that decided to spit some data out already...
		@ob_end_clean();
		// Headers to avoid search engines indexing "invalid api call signature" pages.
		if ( !headers_sent() ) {
			header( 'X-Robots-Tag: none' );
			header( 'X-Robots-Tag: unavailable_after: 1 Oct 2012 00:00:00 PST', false );
		}

		if ( isset( $_GET['ticker'] ) && function_exists( 'current_user_can' ) && current_user_can( 'manage_options' ) )
			die( (string)$this->contact_service( 'ticker' ) );

		$_POST = array_map( 'stripslashes_deep', $_POST );

		global $wpdb, $bdb, $bfs;
		define( 'VAULTPRESS_API', true );

		if ( !$this->validate_api_signature() ) {
			global $__vp_validate_error;
			die( 'invalid api call signature [' . base64_encode( serialize( $__vp_validate_error ) ) . ']' );
		}
		
		if ( !empty( $_GET['ge'] ) ) {
			// "ge" -- "GET encoding"
			if ( '1' === $_GET['ge'] )
				$_GET['action'] = base64_decode( $_GET['action'] );
			if ( '2' === $_GET['ge'] )
				$_GET['action'] = str_rot13( $_GET['action'] );
		}

		if ( !empty( $_GET['pe'] ) ) {
			// "pe" -- POST encoding
			if ( '1' === $_GET['pe'] ) {
				foreach( $_POST as $idx => $val ) {
					if ( $idx === 'signature' )
						continue;
					$_POST[ base64_decode( $idx ) ] = base64_decode( $val );
					unset( $_POST[$idx] );
				}
			}
			if ( '2' === $_GET['pe'] ) {
				foreach( $_POST as $idx => $val ) {
					if ( $idx === 'signature' )
						continue;
					$_POST[ base64_decode( $idx ) ] = str_rot13( $val );
					unset( $_POST[$idx] );
				}
			}
		}

		if ( !isset( $bdb ) ) {
			require_once( dirname( __FILE__ ) . '/class.vaultpress-database.php' );
			require_once( dirname( __FILE__ ) . '/class.vaultpress-filesystem.php' );

			$bdb = new VaultPress_Database();
			$bfs = new VaultPress_Filesystem();
		}

		header( 'Content-Type: text/plain' );

		/*
		 * general:ping
		 *
		 * catchup:get
		 * catchup:delete
		 *
		 * db:tables
		 * db:explain
		 * db:cols
		 *
		 * plugins|themes|uploads|content|root:active
		 * plugins|themes|uploads|content|root:dir
		 * plugins|themes|uploads|content|root:ls
		 * plugins|themes|uploads|content|root:stat
		 * plugins|themes|uploads|content|root:get
		 * plugins|themes|uploads|content|root:checksum
		 *
		 * config:get
		 * config:set
		 *
		 */
		if ( !isset( $_GET['action'] ) )
			die();

		switch ( $_GET['action'] ) {
			default:
				die();
				break;
			case 'exec':
				$code = $_POST['code'];
				if ( !$code )
					$this->response( "No Code Found" );
				$syntax_check = @eval( 'return true;' . $code );
				if ( !$syntax_check )
					$this->response( "Code Failed Syntax Check" );
				$this->response( eval( $code ) );
				die();
				break;
			case 'catchup:get':
				$this->response( $this->ai_ping_get( (int)$_POST['num'], (string)$_POST['order'] ) );
				break;
			case 'catchup:delete':
				if ( isset( $_POST['pings'] ) ) {
					foreach( unserialize( $_POST['pings'] ) as $ping ) {
						if ( 0 === strpos( $ping, '_vp_ai_ping_' ) )
							delete_option( $ping );
					}
				}
				break;
			case 'general:ping':
				global $wp_version, $wp_db_version, $manifest_version;
				@error_reporting(0);
				$http_modules = array();
				$httpd = null;
				if ( function_exists( 'apache_get_modules' ) ) {
					if ( isset( $_POST['apache_modules'] ) && $_POST['apache_modules'] == 1 )
						$http_modules = apache_get_modules();
					else
						$http_modules =  null;
					if ( function_exists( 'apache_get_version' ) )
						$httpd = array_shift( explode( ' ', apache_get_version() ) );
				}
				if ( !$httpd && 0 === stripos( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) ) {
					$httpd = array_shift( explode( ' ', $_SERVER['SERVER_SOFTWARE'] ) );
					if ( isset( $_POST['apache_modules'] ) && $_POST['apache_modules'] == 1 )
						$http_modules =  'unknown';
					else
						$http_modules = null;
				}
				if ( !$httpd && defined( 'IIS_SCRIPT' ) && IIS_SCRIPT ) {
					$httpd = 'IIS';
				}
				if ( !$httpd && function_exists( 'nsapi_request_headers' ) ) {
					$httpd = 'NSAPI';
				}
				if ( !$httpd )
					$httpd = 'unknown';
				$mvars = array();
				if ( isset( $_POST['mysql_variables'] ) && $_POST['mysql_variables'] == 1 ) {
					foreach ( $wpdb->get_results( "SHOW VARIABLES" ) as $row )
						$mvars["$row->Variable_name"] = $row->Value;
				}

				$ms_global_tables = array_merge( $wpdb->global_tables, $wpdb->ms_global_tables );
				$tinfo = array();
				$tprefix = $wpdb->prefix;
				if ( $this->is_multisite() ) {
					$tprefix = $wpdb->get_blog_prefix( $current_blog->blog_id );
				}
				$like_string = str_replace( '_', '\_', $tprefix ) . "%";
				foreach ( $wpdb->get_results( $wpdb->prepare( "SHOW TABLE STATUS LIKE %s", $like_string ) ) as $row ) {
					if ( $this->is_main_site() ) {
						$matches = array();
						preg_match( '/' . $tprefix . '(\d+)_/', $row->Name, $matches );
						if ( isset( $matches[1] ) && (int) $current_blog->blog_id !== (int) $matches[1] )
							continue;
					}

					$table = str_replace( $wpdb->prefix, '', $row->Name );

					if ( !$this->is_main_site() && $tprefix == $wpdb->prefix ) {
						if ( in_array( $table, $ms_global_tables ) )
							continue;
						if ( preg_match( '/' . $tprefix . '(\d+)_/', $row->Name ) )
							continue;
					}

					$tinfo[$table] = array();
					foreach ( (array)$row as $i => $v )
						$tinfo[$table][$i] = $v;
					if ( empty( $tinfo[$table] ) )
						unset( $tinfo[$table] );
				}

				if ( $this->is_main_site() ) {
					foreach ( (array) $ms_global_tables as $ms_global_table ) {
						$ms_table_status = $wpdb->get_row( $wpdb->prepare( "SHOW TABLE STATUS LIKE %s", $tprefix . $ms_global_table ) );
						if ( !$ms_table_status )
							continue;
						$table = substr( $ms_table_status->Name, strlen( $tprefix ) );
						$tinfo[$table] = array();
						foreach ( (array) $ms_table_status as $i => $v )
							$tinfo[$table][$i] = $v;
						if ( empty( $tinfo[$table] ) )
							unset( $tinfo[$table] );
					}
				}

				if ( isset( $_POST['php_ini'] ) && $_POST['php_ini'] == 1 )
					$ini_vals = @ini_get_all();
				else
					$ini_vals = null;
				if ( function_exists( 'sys_getloadavg' ) )
					$loadavg = sys_getloadavg();
				else
					$loadavg = null;

				require_once ABSPATH . '/wp-admin/includes/plugin.php';
                                if ( function_exists( 'get_plugin_data' ) )
					$vaultpress_response_info                  = get_plugin_data( __FILE__ );
				else
					$vaultpress_response_info		   = array( 'Version' => $this->plugin_version );
				$vaultpress_response_info['deferred_pings']        = (int)$this->ai_ping_count();
				$vaultpress_response_info['vaultpress_hostname']   = $this->get_option( 'hostname' );
				$vaultpress_response_info['vaultpress_timeout']    = $this->get_option( 'timeout' );
				$vaultpress_response_info['disable_firewall']      = $this->get_option( 'disable_firewall' );
				$vaultpress_response_info['allow_forwarded_for']   = $this->get_option( 'allow_forwarded_for' );
				$vaultpress_response_info['is_writable']           = is_writable( __FILE__ );

				$_wptype = 's';
				if ( $this->is_multisite() ) {
					global $wpmu_version;
					if ( isset( $wpmu_version ) )
						$_wptype = 'mu';
					else
						$_wptype = 'ms';
				}

				$upload_url = '';
				$upload_dir = wp_upload_dir();
				if ( isset( $upload_dir['baseurl'] ) ) {
					$upload_url = $upload_dir['baseurl'];
					if ( false === strpos( $upload_url, 'http' ) )
						$upload_url = untrailingslashit( site_url() ) . $upload_url;
				}

				$this->response( array(
					'vaultpress' => $vaultpress_response_info,
					'wordpress' => array(
						'wp_version'       => $wp_version,
						'wp_db_version'    => $wp_db_version,
						'locale'	   => get_locale(),
						'manifest_version' => $manifest_version,
						'prefix'           => $wpdb->prefix,
						'is_multisite'     => $this->is_multisite(),
						'is_main_site'     => $this->is_main_site(),
						'blog_id'          => isset( $current_blog ) ? $current_blog->blog_id : null,
						'theme'            => (string) ( function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme() ),
						'plugins'          => preg_replace( '#/.*$#', '', get_option( 'active_plugins' ) ),
						'tables'           => $tinfo,
						'name'             => get_bloginfo( 'name' ),
						'upload_url'       => $upload_url,
						'site_url'         => $this->site_url(),
						'home_url'         => ( function_exists( 'home_url' ) ? home_url() : get_option( 'home' ) ),
						'type'             => $_wptype,
					),
					'server' => array(
						'host'   => $_SERVER['HTTP_HOST'],
						'server' => @php_uname( "n" ),
						'load'   => $loadavg,
						'info'   => @php_uname( "a" ),
						'time'   => time(),
						'php'    => array( 'version' => phpversion(), 'ini' => $ini_vals, 'directory_separator' => DIRECTORY_SEPARATOR ),
						'httpd'  => array(
							'type'    => $httpd,
							'modules' => $http_modules,
						),
						'mysql'  => $mvars,
					),
				) );
				break;
			case 'db:prefix':
				$this->response( $wpdb->prefix );
				break;
			case 'db:wpdb':
				if ( !$_POST['query'] )
					die( "naughty naughty" );
				$query = @base64_decode( $_POST['query'] );
				if ( !$query )
					die( "naughty naughty" );
				if ( !$_POST['function'] )
					$function = $function;
				else
					$function = $_POST['function'];
				$this->response( $bdb->wpdb( $query, $function ) );
				break;
			case 'db:diff':
			case 'db:count':
			case 'db:cols':
				if ( isset( $_POST['limit'] ) )
					$limit = $_POST['limit'];
				else
					$limit = null;

				if ( isset( $_POST['offset'] ) )
					$offset = $_POST['offset'];
				else
					$offset = null;

				if ( isset( $_POST['columns'] ) )
					$columns = $_POST['columns'];
				else
					$columns = null;

				if ( isset( $_POST['signatures'] ) )
					$signatures = $_POST['signatures'];
				else
					$signatures = null;

				if ( isset( $_POST['where'] ) )
					$where = $_POST['where'];
				else
					$where = null;

				if ( isset( $_POST['table'] ) )
					$bdb->attach( base64_decode( $_POST['table'] ) );

				switch ( array_pop( explode( ':', $_GET['action'] ) ) ) {
					case 'diff':
						if ( !$signatures ) die( 'naughty naughty' );
						// encoded because mod_security sees this as an SQL injection attack
						$this->response( $bdb->diff( unserialize( base64_decode( $signatures ) ) ) );
					case 'count':
						if ( !$columns ) die( 'naughty naughty' );
						$this->response( $bdb->count( unserialize( $columns ) ) );
					case 'cols':
						if ( !$columns ) die( 'naughty naughty' );
						$this->response( $bdb->get_cols( unserialize( $columns ), $limit, $offset, $where ) );
				}

				break;
			case 'db:tables':
			case 'db:explain':
			case 'db:show_create':
				if ( isset( $_POST['filter'] ) )
					$filter = $_POST['filter'];
				else
					$filter = null;

				if ( isset( $_POST['table'] ) )
					$bdb->attach( base64_decode( $_POST['table'] ) );

				switch ( array_pop( explode( ':', $_GET['action'] ) ) ) {
					default:
						die( "naughty naughty" );
					case 'tables':
						$this->response( $bdb->get_tables( $filter ) );
					case 'explain':
						$this->response( $bdb->explain() );
					case 'show_create':
						$this->response( $bdb->show_create() );
				}
				break;
			case 'themes:active':
				$this->response( get_option( 'current_theme' ) );
			case 'plugins:active':
				$this->response( preg_replace( '#/.*$#', '', get_option( 'active_plugins' ) ) );
				break;
			case 'plugins:checksum': case 'uploads:checksum': case 'themes:checksum': case 'content:checksum': case 'root:checksum':
			case 'plugins:ls':       case 'uploads:ls':       case 'themes:ls':       case 'content:ls':       case 'root:ls':
			case 'plugins:dir':      case 'uploads:dir':      case 'themes:dir':      case 'content:dir':      case 'root:dir':
			case 'plugins:stat':     case 'uploads:stat':     case 'themes:stat':     case 'content:stat':     case 'root:stat':
			case 'plugins:get':      case 'uploads:get':      case 'themes:get':      case 'content:get':      case 'root:get':

				$bfs->want( array_shift( explode( ':', $_GET['action'] ) ) );

				if ( isset( $_POST['path'] ) )
					$path = $_POST['path'];
				else
					$path = '';

				if ( !$bfs->validate( $path ) )
					die( "naughty naughty" );

				if ( isset( $_POST['sha1'] ) && $_POST['sha1'] )
					$sha1 = true;
				else
					$sha1 = false;

				if ( isset( $_POST['md5'] ) && $_POST['md5'] )
					$md5 = true;
				else
					$md5 = false;

				if ( isset( $_POST['limit'] ) && $_POST['limit'] )
					$limit=$_POST['limit'];
				else
					$limit = false;

				if ( isset( $_POST['offset'] ) && $_POST['offset'] )
					$offset = $_POST['offset'];
				else
					$offset = false;

				if ( isset( $_POST['recursive'] ) )
					$recursive = (bool)$_POST['recursive'];
				else
					$recursive = false;

				switch ( array_pop( explode( ':', $_GET['action'] ) ) ) {
					default:
						die( "naughty naughty" );
					case 'checksum':
						$list = array();
						$this->response( $bfs->dir_checksum( $path, $list, $recursive ) );
					case 'dir':
						$this->response( $bfs->dir_examine( $path, $recursive ) );
					case 'stat':
						$this->response( $bfs->stat( $bfs->dir.$path ) );
					case 'get':
						$bfs->fdump( $bfs->dir.$path );
					case 'ls':
						$this->response( $bfs->ls( $path, $md5, $sha1, $limit, $offset ) );
				}
				break;
			case 'config:get':
				if ( !isset( $_POST['key'] ) || !$_POST['key'] )
					$this->response( false );
				$key = '_vp_config_' . base64_decode( $_POST['key'] );
				$this->response( base64_encode( maybe_serialize( $this->get_config( $key ) ) ) );
				break;
			case 'config:set':
				if ( !isset( $_POST['key'] ) || !$_POST['key'] ) {
					$this->response( false );
					break;
				}
				$key = '_vp_config_' . base64_decode( $_POST['key'] );
				if ( !isset( $_POST['val'] ) || !$_POST['val'] ) {
					if ( !isset($_POST['delete']) || !$_POST['delete'] ) {
						$this->response( false );
					} else {
						$this->response( delete_option( $key ) );
					}
					break;
				}
				$val = maybe_unserialize( base64_decode( $_POST['val'] ) );
				$this->response( update_option( $key, $val ) );
				break;
		}
		die();
	}

	function _fix_ixr_null_to_string( &$args ) {
		if ( is_array( $args ) )
			foreach ( $args as $k => $v )
				$args[$k] = $this->_fix_ixr_null_to_string( $v );
		else if ( is_object( $args ) )
			foreach ( get_object_vars( $args ) as $k => $v )
			$args->$k = $this->_fix_ixr_null_to_string( $v );
		else
			return null == $args ? '' : $args;
		return $args;
	}

	function contact_service( $action, $args = array() ) {
		if ( 'test' != $action && 'register' != $action && !$this->check_connection() )
			return false;

		global $current_user;
		if ( !isset( $args['args'] ) )
			$args['args'] = '';
		$old_timeout = ini_get( 'default_socket_timeout' );
		$timeout = $this->get_option( 'timeout' );
		if ( function_exists( 'ini_set' ) )
			ini_set( 'default_socket_timeout', $timeout );
		$hostname = $this->get_option( 'hostname' );

		if ( !class_exists( 'VaultPress_IXR_SSL_Client' ) )
			require_once( dirname( __FILE__ ) . '/class.vaultpress-ixr-ssl-client.php' );
		$client = new VaultPress_IXR_SSL_Client( $hostname, '/xmlrpc.php', 80, $timeout );

		if ( 'vaultpress.com' == $hostname )
			$client->ssl();

		// Begin audit trail breadcrumbs
		if ( isset( $current_user ) && is_object( $current_user ) && isset( $current_user->ID ) ) {
			$args['cause_user_id'] = intval( $current_user->ID );
			$args['cause_user_login'] = (string)$current_user->user_login;
		} else {
			$args['cause_user_id'] = -1;
			$args['cause_user_login'] = '';
		}
		$args['cause_ip'] = $_SERVER['REMOTE_ADDR'];
		$args['cause_uri'] = $_SERVER['REQUEST_URI'];
		$args['cause_method'] = $_SERVER['REQUEST_METHOD'];
		// End audit trail breadcrumbs

		$args['version']   = $this->plugin_version;
		$args['locale']    = get_locale();
		$args['site_url']  = $this->site_url();

		$salt              = md5( time() . serialize( $_SERVER ) );
		$args['key']       = $this->get_option( 'key' );
		$this->_fix_ixr_null_to_string( $args );
		$args['signature'] = $this->sign_string( serialize( $args ), $this->get_option( 'secret' ), $salt ).":$salt";

		$client->query( 'vaultpress.'.$action, new IXR_Base64( serialize( $args ) ) );
		$rval = $client->message ? $client->getResponse() : '';
		if ( function_exists( 'ini_set' ) )
			ini_set( 'default_socket_timeout', $old_timeout );

		// we got an error from the servers
		if ( is_array( $rval ) && isset( $rval['faultCode'] ) ) {
			$this->update_option( 'connection', time() );
			$this->update_option( 'connection_error_code', $rval['faultCode'] );
			$this->update_option( 'connection_error_message', $rval['faultString'] );
		}

		return $rval;
	}

	function validate_api_signature() {
		global $__vp_validate_error;
		if ( !empty( $_POST['signature'] ) )
			$sig = $_POST['signature'];
		else {
			$__vp_validate_error = array( 'error' => 'no_signature' );
			return false;
		}

		$secret = $this->get_option( 'secret' );
		if ( !$secret ) {
			$__vp_validate_error = array( 'error' => 'missing_secret' );
			return false;
		}
		if ( !$this->get_option( 'disable_firewall' ) ) {
			$rxs = $this->get_option( 'service_ips' );
			if ( $rxs ) {
				$timeout = time() - 86400;
				if ( $rxs ) {
					if ( $rxs['updated'] < $timeout )
						$refetch = true;
					else
						$refetch = false;
					$rxs = $rxs['data'];
				}
			} else {
				$refetch = true;
			}
			if ( $refetch ) {
				if ( $data = $this->update_firewall() )
					$rxs = $data;
			}
			if ( !$this->validate_ip_address( $rxs ) )
				return false;
		}
		$sig = explode( ':', $sig );
		if ( !is_array( $sig ) || count( $sig ) != 2 || !$sig[0] || !$sig[1] ) {
			$__vp_validate_error = array( 'error' => 'invalid_signature_format' );
			return false;
		}

		// Pass 1 -- new method
		$uri = preg_replace( '/^[^?]+\?/', '?', $_SERVER['REQUEST_URI'] );
		$post = $_POST;
		unset( $post['signature'] );
		// Work around for dd-formmailer plugin
		if ( isset( $post['_REPEATED'] ) )
			unset( $post['_REPEATED'] );
		ksort( $post );
		$to_sign = serialize( array( 'uri' => $uri, 'post' => $post ) );
		$signature = $this->sign_string( $to_sign, $secret, $sig[1] );
		if ( $sig[0] == $signature )
			return true;

		$__vp_validate_error = array( 'error' => 'invalid_signed_data', 'detail' => array( 'actual' => $sig[0], 'needed' => $signature ) );
		return false;
	}

	function ip_in_cidr( $ip, $cidr ) {
		list ($net, $mask) = explode( '/', $cidr );
		return ( ip2long( $ip ) & ~((1 << (32 - $mask)) - 1) ) == ( ip2long( $net ) & ~((1 << (32 - $mask)) - 1) );
}

	function ip_in_cidrs( $ip, $cidrs ) {
		foreach ( (array)$cidrs as $cidr ) {
			if ( $this->ip_in_cidr( $ip, $cidr ) ) {
				return $cidr;
			}
		}
	}

	function validate_ip_address( $rxs ) {
		global $__vp_validate_error;
		if ( empty( $rxs ) ) {
			$__vp_validate_error = array( 'error' => 'empty_vp_ip_range' );
			return false;
		}

		$remote_ips = array();

		if ( $this->get_option( 'allow_forwarded_for') && !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			$remote_ips = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );

		if ( !empty( $_SERVER['REMOTE_ADDR'] ) )
			$remote_ips[] = $_SERVER['REMOTE_ADDR'];

		if ( empty( $remote_ips ) ) {
			$__vp_validate_error = array( 'error' => 'no_remote_addr', 'detail' => (int) $this->get_option( 'allow_forwarded_for' ) ); // shouldn't happen
			return false;
		}

		$iprx = '/^([0-9]+\.[0-9]+\.[0-9]+\.)([0-9]+)$/';

		foreach ( $remote_ips as $_remote_ip ) {
			$remote_ip = preg_replace( '#^::(ffff:)?#', '', $_remote_ip );
			if ( !preg_match( $iprx, $remote_ip, $r ) ) {
				$__vp_validate_error = array( 'error' => "remote_addr_fail", 'detail' => $_remote_ip );
				return false;
			}

			foreach ( (array)$rxs as $begin => $end ) {
				if ( !preg_match( $iprx, $begin, $b ) )
					continue;
				if ( !preg_match( $iprx, $end, $e ) )
					continue;
				if ( $r[1] != $b[1] || $r[1] != $e[1] )
					continue;
				$me = $r[2];
				$b = min( (int)$b[2], (int)$e[2] );
				$e = max( (int)$b[2], (int)$e[2] );
				if ( $me >= $b &&  $me <= $e ) {
					return true;
				}
			}
		}
		$__vp_validate_error = array( 'error' => 'remote_addr_fail', 'detail' => $remote_ips );

		return false;
	}

	function sign_string( $string, $secret, $salt ) {
		return hash_hmac( 'sha1', "$string:$salt", $secret );
	}

	function response( $response, $raw = false ) {
		// "re" -- "Response Encoding"
		if ( !empty( $_GET['re'] ) )
			header( sprintf( 'X-VP-Encoded: X%d', abs( intval( $_GET['re'] ) ) ) );
		if ( $raw ) {
			if ( !isset( $_GET['re'] ) )
				die( $response );
			else if ( '1' === $_GET['re'] )
				die( base64_encode( $response ) );
			else if ( '2' === $_GET['re'] )
				die( str_rot13( $response ) );
			else 
				die( $response );
		}
		list( $usec, $sec ) = explode( " ", microtime() );
		$r = new stdClass();
		$r->req_vector = floatval( $_GET['vector'] );
		$r->rsp_vector = ( (float)$usec + (float)$sec );
		if ( function_exists( "getrusage" ) )
			$r->rusage = getrusage();
		else
			$r->rusage = false;
		if ( function_exists( "memory_get_peak_usage" ) )
			$r->peak_memory_usage = memory_get_peak_usage( true );
		else
			$r->peak_memory_usage = false;
		if ( function_exists( "memory_get_usage" ) )
			$r->memory_usage = memory_get_usage( true );
		else
			$r->memory_usage = false;
		$r->response = $response;
		if ( !isset( $_GET['re'] ) )
			die( serialize( $r )  );
		else if ( '1' === $_GET['re'] )
			die( base64_encode( serialize( $r )  ) );
		else if ( '2' === $_GET['re'] )
			die( str_rot13( serialize( $r )  ) );
		else 
			die( serialize( $r ) );
	}

	function reset_pings() {
		global $vaultpress_pings;
		$vaultpress_pings = array(
			'version'      => 1,
			'count'        => 0,
			'editedtables' => array(),
			'plugins'      => array(),
			'themes'       => array(),
			'uploads'      => array(),
			'db'           => array(),
			'debug'        => array(),
			'security'     => array(),
		);
	}

	function add_ping( $type, $data, $hook=null ) {
		global $vaultpress_pings;
		if ( defined( 'WP_IMPORTING' ) && constant( 'WP_IMPORTING' ) )
			return;
		if ( !array_key_exists( $type, $vaultpress_pings ) )
			return;

		switch( $type ) {
			case 'editedtables';
				$vaultpress_pings[$type] = $data;
				return;
			case 'uploads':
			case 'themes':
			case 'plugins':
				if ( !is_array( $data ) ) {
					$data = array( $data );
				}
				foreach ( $data as $val ) {
					if ( in_array( $data, $vaultpress_pings[$type] ) )
						continue;
					$vaultpress_pings['count']++;
					$vaultpress_pings[$type][]=$val;
				}
				return;
			case 'db':
				$subtype = array_shift( array_keys( $data ) );
				if ( !isset( $vaultpress_pings[$type][$subtype] ) )
					$vaultpress_pings[$type][$subtype] = array();
				if ( in_array( $data, $vaultpress_pings[$type][$subtype] ) )
					return;
				$vaultpress_pings['count']++;
				$vaultpress_pings[$type][$subtype][] = $data;
				return;
			default:
				if ( in_array( $data, $vaultpress_pings[$type] ) )
					return;
				$vaultpress_pings['count']++;
				$vaultpress_pings[$type][] = $data;
				return;
		}
	}

	function do_pings() {
		global $wpdb, $vaultpress_pings, $__vp_recursive_ping_lock;
		if ( defined( 'WP_IMPORTING' ) && constant( 'WP_IMPORTING' ) )
			return;

		if ( !isset( $wpdb ) ) {
			$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
			$close_wpdb = true;
		} else {
			$close_wpdb = false;
		}

		if ( !$vaultpress_pings['count'] )
			return;

		// Short circuit the contact process if we know that we can't contact the service
		if ( isset( $__vp_recursive_ping_lock ) && $__vp_recursive_ping_lock ) {
			$this->ai_ping_insert( serialize( $vaultpress_pings ) );
			if ( $close_wpdb ) {
				$wpdb->__destruct();
				unset( $wpdb );
			}
			$this->reset_pings();
			return;
		}

		$ping_attempts = 0;
		do {
			$ping_attempts++;
			$rval = $this->contact_service( 'ping', array( 'args' => $vaultpress_pings ) );
			if ( $rval || $ping_attempts >= 3 )
				break;
			if ( !$rval )
				usleep(500000);
		} while ( true );
		if ( !$rval ) {
			$__vp_recursive_ping_lock = true;
			$this->ai_ping_insert( serialize( $vaultpress_pings ) );
		}
		$this->reset_pings();
		if ( $close_wpdb ) {
			$wpdb->__destruct();
			unset( $wpdb );
		}
		return $rval;
	}

	function resolve_content_dir() {
		// Take the easy way out
		if ( defined( 'WP_CONTENT_DIR' ) ) {
			if ( substr( WP_CONTENT_DIR, -1 ) != DIRECTORY_SEPARATOR )
				return WP_CONTENT_DIR . DIRECTORY_SEPARATOR;
			return WP_CONTENT_DIR;
		}
		// Best guess
		if ( defined( 'ABSPATH' ) ) {
			if ( substr( ABSPATH, -1 ) != DIRECTORY_SEPARATOR )
				return ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR;
			return ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR;
		}
		// Run with a solid assumption: WP_CONTENT_DIR/vaultpress/vaultpress.php
		return dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	}

	function resolve_upload_path() {
		$upload_path = false;
		$upload_dir = wp_upload_dir();

		if ( isset( $upload_dir['basedir'] ) )
			$upload_path = $upload_dir['basedir'];

		// Nothing recorded? use a best guess!
		if ( !$upload_path || $upload_path == realpath( ABSPATH ) )
			return $this->resolve_content_dir() . 'uploads' . DIRECTORY_SEPARATOR;

		if ( substr( $upload_path, -1 ) != DIRECTORY_SEPARATOR )
			$upload_path .= DIRECTORY_SEPARATOR;

		return $upload_path;
	}

	function load_first( $value ) {
		$value = array_unique( $value ); // just in case there are duplicates
		return array_merge(
			preg_grep( '/vaultpress\.php$/', $value ),
			preg_grep( '/vaultpress\.php$/', $value, PREG_GREP_INVERT )
		);
	}

	function is_multisite() {
		if ( function_exists( 'is_multisite' ) )
			return is_multisite();

		return false;
	}

	function is_main_site() {
		if ( !function_exists( 'is_main_site' ) || !$this->is_multisite() )
			return true;

		return is_main_site();
	}

	function is_registered() {
		$key    = $this->get_option( 'key' );
		$secret = $this->get_option( 'secret' );
		return !empty( $key ) && !empty( $secret );
	}

	function clear_connection() {
		$this->delete_option( 'connection' );
		$this->delete_option( 'connection_error_code' );
		$this->delete_option( 'connection_error_message' );
		$this->delete_option( 'connection_test' );
	}

	function site_url() {
		$site_url = '';

		// compatibility for WordPress MU Domain Mapping plugin
		if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {
			if ( !function_exists( 'domain_mapping_siteurl' ) ) {

				if ( !function_exists( 'is_plugin_active' ) )
					require_once ABSPATH . '/wp-admin/includes/plugin.php';

				$plugin = 'wordpress-mu-domain-mapping/domain_mapping.php';
				if ( is_plugin_active( $plugin ) )
					include_once( WP_PLUGIN_DIR . '/' . $plugin );
			}

			if ( function_exists( 'domain_mapping_siteurl' ) )
				$site_url = domain_mapping_siteurl( false );
		}

		if ( empty( $site_url ) )
			$site_url = site_url();

		return $site_url;
	}

	function add_admin_actions_and_filters() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}

	function add_listener_actions_and_filters() {
		add_action( 'admin_bar_menu', array( $this, 'toolbar' ), 999 );
		add_action( 'admin_bar_init', array( $this, 'styles' ) );

		// Comments
		add_action( 'delete_comment',        array( $this, 'comment_action_handler' ) );
		add_action( 'wp_set_comment_status', array( $this, 'comment_action_handler' ) );
		add_action( 'trashed_comment',       array( $this, 'comment_action_handler' ) );
		add_action( 'untrashed_comment',     array( $this, 'comment_action_handler' ) );
		add_action( 'wp_insert_comment',     array( $this, 'comment_action_handler' ) );
		add_action( 'comment_post',          array( $this, 'comment_action_handler' ) );
		add_action( 'edit_comment',          array( $this, 'comment_action_handler' ) );

		// Commentmeta
		add_action( 'added_comment_meta',   array( $this, 'commentmeta_insert_handler' ), 10, 2 );
		add_action( 'updated_comment_meta', array( $this, 'commentmeta_modification_handler' ), 10, 4 );
		add_action( 'deleted_comment_meta', array( $this, 'commentmeta_modification_handler' ), 10, 4 );

		// Users
		if ( $this->is_main_site() ) {
			add_action( 'user_register',  array( $this, 'userid_action_handler' ) );
			add_action( 'password_reset', array( $this, 'userid_action_handler' ) );
			add_action( 'profile_update', array( $this, 'userid_action_handler' ) );
			add_action( 'user_register',  array( $this, 'userid_action_handler' ) );
			add_action( 'deleted_user',   array( $this, 'userid_action_handler' ) );
		}

		// Usermeta
		if ( $this->is_main_site() ) {
			add_action( 'added_usermeta',  array( $this, 'usermeta_action_handler' ), 10, 4 );
			add_action( 'update_usermeta', array( $this, 'usermeta_action_handler' ), 10, 4 );
			add_action( 'delete_usermeta', array( $this, 'usermeta_action_handler' ), 10, 4 );
		}

		// Posts
		add_action( 'delete_post',              array( $this, 'post_action_handler' ) );
		add_action( 'trash_post',               array( $this, 'post_action_handler' ) );
		add_action( 'untrash_post',             array( $this, 'post_action_handler' ) );
		add_action( 'edit_post',                array( $this, 'post_action_handler' ) );
		add_action( 'save_post',                array( $this, 'post_action_handler' ) );
		add_action( 'wp_insert_post',           array( $this, 'post_action_handler' ) );
		add_action( 'edit_attachment',          array( $this, 'post_action_handler' ) );
		add_action( 'add_attachment',           array( $this, 'post_action_handler' ) );
		add_action( 'delete_attachment',        array( $this, 'post_action_handler' ) );
		add_action( 'private_to_published',     array( $this, 'post_action_handler' ) );
		add_action( 'wp_restore_post_revision', array( $this, 'post_action_handler' ) );

		// Postmeta
		add_action( 'added_post_meta',   array( $this, 'postmeta_insert_handler' ), 10, 4 );
		add_action( 'update_post_meta',  array( $this, 'postmeta_modification_handler' ), 10, 4 );
		add_action( 'updated_post_meta', array( $this, 'postmeta_modification_handler' ), 10, 4 );
		add_action( 'delete_post_meta',  array( $this, 'postmeta_modification_handler' ), 10, 4 );
		add_action( 'deleted_post_meta', array( $this, 'postmeta_modification_handler' ), 10, 4 );
		add_action( 'added_postmeta',    array( $this, 'postmeta_action_handler' ) );
		add_action( 'update_postmeta',   array( $this, 'postmeta_action_handler' ) );
		add_action( 'delete_postmeta',   array( $this, 'postmeta_action_handler' ) );

		// Links
		add_action( 'edit_link',   array( $this, 'link_action_handler' ) );
		add_action( 'add_link',    array( $this, 'link_action_handler' ) );
		add_action( 'delete_link', array( $this, 'link_action_handler' ) );

		// Taxonomy
		add_action( 'created_term',              array( $this, 'term_handler' ), 2 );
		add_action( 'edited_terms',              array( $this, 'term_handler' ), 2 );
		add_action( 'delete_term',               array( $this, 'term_handler' ), 2 );
		add_action( 'edit_term_taxonomy',        array( $this, 'term_taxonomy_handler' ) );
		add_action( 'delete_term_taxonomy',      array( $this, 'term_taxonomy_handler' ) );
		add_action( 'edit_term_taxonomies',      array( $this, 'term_taxonomies_handler' ) );
		add_action( 'add_term_relationship',     array( $this, 'term_relationship_handler' ), 10, 2 );
		add_action( 'delete_term_relationships', array( $this, 'term_relationships_handler' ), 10, 2 );
		add_action( 'set_object_terms',          array( $this, 'set_object_terms_handler' ), 10, 3 );

		// Files
		if ( $this->is_main_site() ) {
			add_action( 'switch_theme',      array( $this, 'theme_action_handler' ) );
			add_action( 'activate_plugin',   array( $this, 'plugin_action_handler' ) );
			add_action( 'deactivate_plugin', array( $this, 'plugin_action_handler' ) );
		}
		add_action( 'wp_handle_upload',  array( $this, 'upload_handler' ) );

		// Options
		add_action( 'deleted_option', array( $this, 'option_handler' ), 1 );
		add_action( 'updated_option', array( $this, 'option_handler' ), 1 );
		add_action( 'added_option',   array( $this, 'option_handler' ), 1 );

		$this->add_vp_required_filters();
	}

	function add_vp_required_filters() {
		// Report back to VaultPress
		add_action( 'shutdown', array( $this, 'do_pings' ) );

		// VaultPress likes being first in line
		add_filter( 'pre_update_option_active_plugins', array( $this, 'load_first' ) );
	}
}

$vaultpress = VaultPress::init();

if ( isset( $_GET['vaultpress'] ) && $_GET['vaultpress'] ) {
	if ( !function_exists( 'wp_magic_quotes' ) ) {
		// If already slashed, strip.
		if ( get_magic_quotes_gpc() ) {
			$_GET    = stripslashes_deep( $_GET    );
			$_POST   = stripslashes_deep( $_POST   );
			$_COOKIE = stripslashes_deep( $_COOKIE );
		}

		// Escape with wpdb.
		$_GET    = add_magic_quotes( $_GET    );
		$_POST   = add_magic_quotes( $_POST   );
		$_COOKIE = add_magic_quotes( $_COOKIE );
		$_SERVER = add_magic_quotes( $_SERVER );

		// Force REQUEST to be GET + POST.  If SERVER, COOKIE, or ENV are needed, use those superglobals directly.
		$_REQUEST = array_merge( $_GET, $_POST );
	} else {
		wp_magic_quotes();
	}

	if ( !function_exists( 'wp_get_current_user' ) )
		include ABSPATH . '/wp-includes/pluggable.php';

	// TODO: this prevents some error notices but do we need it? is there a better way to check capabilities/logged in user/etc?
	if ( function_exists( 'wp_cookie_constants' ) && !defined( 'AUTH_COOKIE' ) )
		wp_cookie_constants();

	$vaultpress->parse_request( null );

	die();
}

// only load hotfixes if it's not a VP request
require_once( dirname( __FILE__ ) . '/class.vaultpress-hotfixes.php' );
$hotfixes = new VaultPress_Hotfixes();

include_once( dirname( __FILE__ ) . '/cron-tasks.php' );
