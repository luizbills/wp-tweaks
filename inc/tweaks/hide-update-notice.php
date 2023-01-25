<?php
/**
 * Hide WordPress Update Nag to All But Admins
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'admin_head', 'wp_tweaks_hide_update_notice', 1 );
function wp_tweaks_hide_update_notice () {
	if ( ! current_user_can( 'update_core' ) ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}
