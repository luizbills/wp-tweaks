<?php
/**
 * Disable xmlrpc.php and pingbacks
 *
 * @package wp-tweaks
 */

add_filter( 'xmlrpc_enabled', '__return_false', 99 );
add_filter( 'pings_open', '__return_false', 99 );
add_filter( 'pre_update_option_enable_xmlrpc', '__return_false', 99 );
add_filter( 'pre_option_enable_xmlrpc', '__return_zero', 99 );

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

add_filter( 'xmlrpc_methods', 'wp_tweaks_unset_pingback_methods', 99 );
function wp_tweaks_unset_pingback_methods ( $methods ) {
	return [];
}

add_filter( 'wp_headers', 'wp_tweaks_remove_pingback_headers', 99 );
function wp_tweaks_remove_pingback_headers ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

add_filter( 'bloginfo_url', 'wp_tweaks_remove_pingback_url', 99, 2 );
function wp_tweaks_remove_pingback_url ( $output, $show ) {
	return 'pingback_url' === $show ? '' : $output;
}

add_filter( 'xmlrpc_call', 'wp_tweaks_remove_pingback_xmlrpc' );
function wp_tweaks_remove_pingback_xmlrpc ( $action ) {
	if ( $action === 'pingback.ping' ) {
		wp_die(
			__( 'Pingbacks are not supported', 'wp-tweaks' ),
			__( 'Not Allowed!', 'wp-tweaks' ),
			[ 'response' => 403 ]
		);
	}
}

add_filter('rewrite_rules_array', 'wp_tweaks_remove_trackback_rewrite_rules');
function wp_tweaks_remove_trackback_rewrite_rules ( $rules ) {
	foreach ( array_keys( $rules ) as $rule ) {
		if ( preg_match( '/trackback\/\?\$$/i', $rule ) ) {
			unset( $rules[ $rule ] );
		}
	}
	return $rules;
}

add_action( 'admin_init', 'wp_tweaks_remove_trackbacks_support' );
function wp_tweaks_remove_trackbacks_support () {
	foreach ( get_post_types() as $post_type ) {
		if ( post_type_supports( $post_type, 'trackbacks' ) ) {
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
};
