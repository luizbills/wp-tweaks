<?php
/**
 * Change the menu "Posts" to "Blog".
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'admin_menu', 'wp_tweaks_change_menu_posts' );
function wp_tweaks_change_menu_posts () {
	global $menu;
	foreach ( $menu as $i => $item ) {
		if ( 'edit.php' === $item[2] ) {
			$menu[ $i ][0] = esc_attr__( 'Blog', 'wp-tweaks' );
			break;
		}
	}
}
