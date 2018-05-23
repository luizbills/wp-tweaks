<?php
/**
 * Disable WP REST API public access
 *
 * @package wp-tweaks
 */

add_filter( 'rest_authentication_errors', 'wp_tweaks_disable_rest_api' );
function wp_tweaks_disable_rest_api ( $flag ) {
	if ( ! is_user_logged_in() ) {
		$flag = new WP_Error( 'rest-api-disabled', 'WP Rest API Disabled for public access' );
	}
	return $flag;
}

// remove <link rel="https://api.w.org/" ...> from <head>
remove_action( 'wp_head', 'rest_output_link_wp_head' );
