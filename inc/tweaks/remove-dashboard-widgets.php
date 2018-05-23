<?php
/**
 * Remove some Dashboard Widgets
 *
 * @package wp-tweaks
 */

add_action( 'admin_init', 'wp_tweaks_remove_dashboard_widgets' );
function wp_tweaks_remove_dashboard_widgets () {
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
