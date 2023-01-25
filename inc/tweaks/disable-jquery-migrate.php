<?php
/**
 * Disable the jquery-migrade JavaScript.
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

if ( is_admin() ) return;

add_action( 'wp_print_scripts', 'wp_tweaks_dequeue_jquery_migrate' );
function wp_tweaks_dequeue_jquery_migrate () {
	global $wp_scripts;
	if ( isset( $wp_scripts->registered['jquery'] ) ) {
		$jquery = $wp_scripts->registered['jquery'];
		if ( ! empty( $jquery->deps ) ) {
			$jquery->deps = array_diff(
				$jquery->deps,
				[ 'jquery-migrate' ]
			);
		}
	}
}
