<?php
/**
 * Add some security headers
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp_headers', 'wp_tweaks_security_headers' );
function wp_tweaks_security_headers ( $headers ) {
	$options = WP_Tweaks::get_option( 'security-headers' );
	$values = apply_filters(
		'wp_tweaks_security_headers_values',
		[
			'strict-transport-security' => 'max-age=31536000; includeSubDomains; preload',
			'x-content-type-options'    => 'nosniff',
			'x-xss-protection'          => '1; mode=block',
			'content-security-policy'   => "default-src https:; font-src https: data:; img-src https: data:; script-src https: 'unsafe-inline'; style-src https: 'unsafe-inline'",
			'x-frame-options'           => 'sameorigin',
			'referrer-policy'           => 'strict-origin-when-cross-origin',
		]
	);

	foreach ( $options as $key ) {
		$headers[ $key ] = $values[ $key ];
	}

	return $headers;
}
