<?php
/**
 * Disable or limit Heartbeat API
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

if ( 'disable' === $option ) {
	add_action( 'init', 'wp_tweaks_disable_heartbeat' );
	function wp_tweaks_disable_heartbeat () {
		wp_deregister_script('heartbeat');
	}
} else {
	$GLOBALS['heartbeat_interval'] = (int) $option;
	add_filter( 'heartbeat_settings', 'wp_tweaks_heartbeat_settings', 100 );
	function wp_tweaks_heartbeat_settings ( $settings ) {
		$settings['interval'] = $GLOBALS['heartbeat_interval'];
		unset( $GLOBALS['heartbeat_interval'] );
		return $settings;
	}
}
