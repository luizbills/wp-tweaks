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
	$values = [
		'strict-transport-security' => 'max-age=31536000; includeSubDomains; preload',
		'x-content-type-options' => 'nosniff',
		'x-xss-protection' => '1; mode=block',
		'content-security-policy' => "default-src 'self'; connect-src *; font-src * data:; frame-src *; img-src * data:; media-src *; object-src *; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'",
		'x-frame-options' => 'sameorigin'
	];

	foreach ( $options as $key ) {
		$headers[ $key ] = $values[ $key ];
	}

	return $headers;
}
