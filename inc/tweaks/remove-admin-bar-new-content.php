<?php
/**
 * Remove "+ New" from admin bar
 *
 * @package wp-tweaks
 */

add_action( 'wp_before_admin_bar_render', 'wp_tweaks_remove_admin_bar_new_content', 20 );
function wp_tweaks_remove_admin_bar_new_content () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'new-content' );
}
