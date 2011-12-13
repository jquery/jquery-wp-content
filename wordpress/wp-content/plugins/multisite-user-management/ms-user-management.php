<?php
/*
Plugin Name: Multisite User Management
Plugin URI: http://github.com/thenbrent/multisite-user-management
Description: Running a WordPress network? You no longer need to manually add users to each of your sites.
Author: Brent Shepherd
Author URI: http://find.brentshepherd.com/
Version: 1.0
Network: true
*/

function msum_add_roles( $user_id ){

	foreach( msum_get_blog_list( 0, 'all' ) as $key => $blog ) { 

		if( is_user_member_of_blog( $user_id, $blog[ 'blog_id' ] ) )
			continue;

		switch_to_blog( $blog[ 'blog_id' ] );

		$role = get_option( 'msum_default_user_role', 'none' ); // if no default set, use 'none'

		if( $role != 'none' )
			add_user_to_blog( $blog[ 'blog_id' ], $user_id, $role );

		restore_current_blog();
	}
	update_user_meta( $user_id, 'msum_has_caps', 'true' );
}
// When a user activates their account, allocate them the default role set for each site. This plugin needs to be in mu-plugins folder for this hook to work, otherwise role will be allocated on login.
add_action( 'wpmu_activate_user', 'msum_add_roles', 10, 1 );
add_action( 'wpmu_new_user', 'msum_add_roles', 10, 1 );


// For users activating both a blog and an account
function msum_activate_blog_user( $blog_id, $user_id ){
	msum_add_roles( $user_id, $blog_id );
}
add_action( 'wpmu_activate_blog', 'msum_activate_blog_user', 10, 2 );


// If plugin is not in mu-plugins folder, allocate roles on login.
function msum_maybe_add_roles( $user_login ) {
	$userdata = get_userdatabylogin( $user_login );

	if( $userdata != false && get_user_meta( $userdata->ID, 'msum_has_caps', true ) != 'true' ){
		msum_add_roles( $userdata->ID );
	}
}
add_action( 'wp_login', 'msum_maybe_add_roles', 10, 1 );
add_action( 'social_connect_login', 'msum_maybe_add_roles', 10, 1 );


// Role assignment selection boxes on the 'Network Admin | Settings' page
function msum_options(){

	$blogs = msum_get_blog_list( 0, 'all' );
	echo '<h3>' . __( 'Multisite User Management', 'msum' ). '</h3>';

	if( empty( $blogs ) ) {
		echo '<p><b>' . __( 'No public, safe sites available.', 'msum' ) . '</b></p>';
	} else {
		echo '<p>' . __( 'Select the default role for each of your sites.', 'msum' ) . '</p>';
		echo '<p>' . __( 'New users will receive these roles when activating their account. Existing users will receive these roles only if they have the current default role or no role at all for each particular site.', 'msum' ) . '</p>';
		echo '<table class="form-table">';
		foreach( $blogs as $key => $blog ) { 

			switch_to_blog( $blog[ 'blog_id' ] );
			?>
			<tr valign="top">
				<th scope="row"><?php echo get_bloginfo( 'name' ); ?></th>
				<td>
					<select name="msum_default_user_role[<?php echo $blog[ 'blog_id' ]; ?>]" id="msum_default_user_role[<?php echo $blog[ 'blog_id' ]; ?>]">
						<option value="none"><?php _e( '-- None --', 'msum' )?></option>
						<?php wp_dropdown_roles( get_option( 'msum_default_user_role' ) ); ?>
					</select>
				</td> 
			</tr>
		<?php restore_current_blog();
		}
		echo '</table>';
	}
		echo '<p>' . __( '<b>Note:</b> only public, non-mature and non-dashboard sites appear here. Set the default role for the dashboard site above under <b>Dashboard Settings</b>.', 'msum' ) . '</p>';
}
add_action( 'wpmu_options', 'msum_options' );


// Update Default Roles on submission of the multisite options page
function msum_options_update(){

	if( !isset( $_POST[ 'msum_default_user_role' ] ) || !is_array( $_POST[ 'msum_default_user_role' ] ) )
		return;

	foreach( $_POST[ 'msum_default_user_role' ] as $blog_id => $new_role ) { 
		switch_to_blog( $blog_id );
		$old_role = get_option( 'msum_default_user_role', 'none' ); // default to none

		if( $old_role == $new_role ) {
			restore_current_blog();
			continue;
		}

		$blog_users = msum_get_users_with_role( $old_role );
		foreach( $blog_users as $blog_user ) {
			if( $old_role != 'none' )
				remove_user_from_blog( $blog_user, $blog_id );
			if( $new_role != 'none' )
				add_user_to_blog( $blog_id, $blog_user, $new_role );
			update_user_meta( $blog_user, 'msum_has_caps', 'true' );
		}
		update_option( 'msum_default_user_role', $new_role );

		restore_current_blog();
	}
}
add_action( 'update_wpmu_options', 'msum_options_update' );


function msum_get_users_with_role( $role ) {
	global $wpdb;

	if( $role != 'none' ) {
		$sql = $wpdb->prepare( "SELECT DISTINCT($wpdb->users.ID) FROM $wpdb->users 
						INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id
						WHERE $wpdb->usermeta.meta_key = '{$wpdb->prefix}capabilities' 
						AND $wpdb->usermeta.meta_value LIKE %s", '%' . $role . '%' );

	} else { // get users without a role for current site
		$sql = "SELECT DISTINCT($wpdb->users.ID) FROM $wpdb->users
				 WHERE $wpdb->users.ID NOT IN (
					SELECT $wpdb->usermeta.user_id FROM $wpdb->usermeta
					WHERE $wpdb->usermeta.meta_key = '{$wpdb->prefix}capabilities' 
					)";
	}

	$users = $wpdb->get_col( $sql );

	if( $role == 'none' ) { // if we got all users without a capability for the site, that includes super admins
		$super_users = get_super_admins();

		foreach( $users as $key => $user ){ //never modify caps for super admins
			if( is_super_admin( $user ) )
				unset( $users[$key] );
		}
	}

	return $users;
}

// Clean up when plugin is deleted
function msum_uninstall(){
	foreach( msum_get_blog_list( 0, 'all' ) as $key => $blog ) { 
		switch_to_blog( $blog[ 'blog_id' ] );
		delete_option( 'msum_default_user_role', $role );
		restore_current_blog();
	}
}
register_uninstall_hook( __FILE__, 'msum_uninstall' );


// A Copy of the WPMU deprecated get_blog_list function. Except this function gets all blogs, even if they are marked as mature and private
function msum_get_blog_list( $start = 0, $num = 10 ) {
	global $wpdb;

	$blogs = $wpdb->get_results( $wpdb->prepare( "SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND archived = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC", $wpdb->siteid ), ARRAY_A );

	foreach ( (array) $blogs as $details ) {
		$blog_list[ $details[ 'blog_id' ] ] = $details;
		$blog_list[ $details[ 'blog_id' ] ]['postcount'] = $wpdb->get_var( "SELECT COUNT(ID) FROM " . $wpdb->get_blog_prefix( $details['blog_id'] ). "posts WHERE post_status='publish' AND post_type='post'" );
	}
	unset( $blogs );
	$blogs = $blog_list;

	if ( false == is_array( $blogs ) )
		return array();

	if ( $num == 'all' )
		return array_slice( $blogs, $start, count( $blogs ) );
	else
		return array_slice( $blogs, $start, $num );
}
