<?php
/**
 * Show admin bar for admin users only
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'show_admin_bar', 'wp_tweaks_hide_admin_bar_for_nonadmin_users' );
function wp_tweaks_hide_admin_bar_for_nonadmin_users ( $content ) {
	if ( ! current_user_can('administrator') ) {
		return false;
	}
	return $content;
}
