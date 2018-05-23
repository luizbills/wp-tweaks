<?php
/**
 * Use jquery from a CDN
 *
 * @package wp-tweaks
 */

add_action( 'init', 'wp_tweaks_jquery_cdn' );
function wp_tweaks_jquery_cdn () {
	if ( ! is_admin() && ! is_login_page() ) {
		$jquery_version = WP_Tweaks_Settings::get_option( '_jquery-version' );

		if ( empty( $jquery_version ) ) return;

		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );

		wp_register_script( 'jquery', false, [ 'jquery-core' ], $jquery_version );
		wp_register_script( 'jquery-core', "https://ajax.googleapis.com/ajax/libs/jquery/$jquery_version/jquery.min.js", false, $jquery_version );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-core' );
	}
}
