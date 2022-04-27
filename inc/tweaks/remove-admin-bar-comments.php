<?php
/**
 * Remove "wordpress logo" from admin bar
 *
 * @package wp-tweaks
 */

add_action( 'wp_before_admin_bar_render', 'wp_tweaks_remove_admin_bar_comments', 20 );
function wp_tweaks_remove_admin_bar_comments () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'comments' );
}
