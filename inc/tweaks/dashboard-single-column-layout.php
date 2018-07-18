<?php
/**
 * Change admin footer text
 *
 * @package wp-tweaks
 */

add_filter( 'screen_layout_columns', 'wp_tweaks_dashboard_single_column' );
function wp_tweaks_dashboard_single_column ( $columns ) {
	$columns['dashboard'] = 1;
	return $columns;
}

add_filter( 'get_user_option_screen_layout_dashboard', 'wp_tweaks_dashboard_layout_avaliable' );
function wp_tweaks_dashboard_layout_avaliable (){
	return 1;
}
