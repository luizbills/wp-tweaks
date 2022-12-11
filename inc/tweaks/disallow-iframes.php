<?php
/**
 * Disallow to render the site in a <iframe> tag
 *
 * @package wp-tweaks
 */
add_action( 'send_headers', 'wp_tweaks_disallow_iframes' );
function wp_tweaks_disallow_iframes () {
	header( 'X-Frame-Options: sameorigin' );
}
