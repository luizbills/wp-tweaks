<?php
/**
 * Disable sidebar meta widget
 *
 * @package wp-tweaks
 */

add_action( 'widgets_init', 'wp_tweaks_disable_meta_widget', 20 );
function wp_tweaks_disable_meta_widget () {
	unregister_widget( 'WP_Widget_Meta' );
}
