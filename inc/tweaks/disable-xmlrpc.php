<?php
/**
 * Disable xmlrpc.php
 *
 * @package wp-tweaks
 */

add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

add_filter( 'xmlrpc_methods', 'disablePingback' );
function wp_tweaks_disable_pingback ( $methods ) {
	unset( $methods['pingback.ping'] );
	return $methods;
}

add_filter( 'wp_headers', 'wp_tweaks_remove_pingback_headers' );
function wp_tweaks_remove_pingback_headers ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

add_filter( 'bloginfo_url', 'wp_tweaks_remove_pingback_url', 10, 2 );
function wp_tweaks_remove_pingback_url ( $output, $show ) {
	return $show === 'pingback_url' ? '' : $output;
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
