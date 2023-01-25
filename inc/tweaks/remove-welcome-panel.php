<?php
/**
 * Remove dashboard welcome panel
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'admin_init', 'wp_tweaks_remove_welcome_panel' );
function wp_tweaks_remove_welcome_panel () {
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
}
