<?php
/**
 * Hide WordPress version in admin footer
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'admin_menu', 'wp_tweaks_hide_version_in_admin_footer' );
function wp_tweaks_hide_version_in_admin_footer () {
	if ( ! current_user_can( 'install_plugins' ) ) {
		remove_filter( 'update_footer', 'core_update_footer' );
	}
}
