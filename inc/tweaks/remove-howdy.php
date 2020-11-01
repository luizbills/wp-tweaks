<?php
/**
 * Remove "Howdy" from admin bar
 *
 * @package wp-tweaks
 */

add_action( 'admin_bar_menu', 'wp_tweaks_remove_howdy', 11 );
function wp_tweaks_remove_howdy ( $wp_admin_bar ) {
	$current_user = wp_get_current_user();
	$avatar = get_avatar( $current_user->ID, 28 );

	$wp_admin_bar->add_node( [
		'id' => 'my-account',
		'title' => $current_user->display_name . $avatar
	] );
}
