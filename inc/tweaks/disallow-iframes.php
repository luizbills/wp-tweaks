<?php
/**
 * Disallow to render the site in a <iframe> tag
 *
 * @package wp-tweaks
 */
add_action( 'wp_headers', 'wp_tweaks_disallow_iframes' );
function wp_tweaks_disallow_iframes ( $headers ) {
	$headers['X-Frame-Options'] = 'sameorigin';
	return $headers;
}
