<?php
/**
 * Remove "wordpress logo" from admin bar
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp_before_admin_bar_render', 'wp_tweaks_remove_admin_bar_wp_logo', 20 );
function wp_tweaks_remove_admin_bar_wp_logo () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'wp-logo' );
}
