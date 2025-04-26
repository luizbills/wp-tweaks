<?php
/**
 * Disable `/users` endpoint in REST API
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'rest_endpoints', 'wp_tweaks_disable_rest_api_users_endpoint' );
function wp_tweaks_disable_rest_api_users_endpoint ( $endpoints ) {
	if ( is_user_logged_in() ) return $endpoints;

	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}

	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}

	return $endpoints;
}
