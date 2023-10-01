<?php
/**
 * Disable right click, copy, cut and paste commands.
 *
 * @package wp-tweaks
 */

if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp_enqueue_scripts', 'wp_tweaks_disable_right_click_scripts' );
function wp_tweaks_disable_right_click_scripts () {
	wp_enqueue_script(
		'wp_tweaks_disable_right_click',
		plugins_url( 'assets/js/disable-right-click.js', WP_Tweaks::FILE ),
		[],
		false,
		[
			'strategy' => 'async',
			'in_footer' => true
		]
	);
}
