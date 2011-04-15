<?php
/*
Plugin Name: Site Admin Skip Email Confirmation
Description: Restore the skip email notification option on the add users page for site admins.
Author: _FindingSimple
Author URI: http://findingsimple.com/
Version: 0.1
*/

/**
 * When the calling function is the super admin check for email notifications on the 
 * user-new.php page, add all admin users to the returns array so it will return true 
 * for any admin user and display the email notification option. 
 *
 * A horrible hack that will break at the drop of a hat.
 **/
function sac_spoof_super_admin( $admins ){
	global $pagenow;

	if( $pagenow == 'user-new.php' ){
		// Get the details of the calling function
		$stacktrace = array_pop( debug_backtrace() );

		// If calling function was a 'is_super_admin' call from specific points in the user-new.php file
		if( strpos( $stacktrace[ 'file' ], 'user-new.php' ) && in_array( $stacktrace[ 'line' ], array( 335, 251, 69, 115, 111 ) ) && $stacktrace[ 'function' ] == 'is_super_admin' ){
			// Add site admins to the super admin array
			$all_admins = array();
			foreach( get_users( array( 'role' => 'administrator' ) ) as $user )
				array_push( $all_admins, $user->user_login );
			$admins = array_merge( $admins, $all_admins );
			$admins = array_unique( $admins );
		}
	}
	return $admins;
}
add_filter( 'site_option_site_admins', 'sac_spoof_super_admin' );
