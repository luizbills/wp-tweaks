<?php
/**
 * Remove <link rel="shortlink" ...> from <head>
 *
 * @package wp-tweaks
 */

add_action( 'after_setup_theme', 'wp_tweaks_remove_shortlink', 20 );
function wp_tweaks_remove_shortlink () {
	// remove HTML meta tag
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
