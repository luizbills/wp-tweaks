<?php

if ( ! function_exists( 'is_login_page' ) ) :
/**
 * @return bool
 */
function is_login_page () {
	return in_array( $GLOBALS['pagenow'], [ 'wp-login.php', 'wp-register.php' ] );
}
endif;

if ( ! function_exists( 'esc_unsafe_html' ) ) :
/**
 * @param string $html
 * @return string
 */
function esc_unsafe_html ( $html ) {
	// remove all script and style tags with code
	$html = \preg_replace( '/<(script|style)[^>]*?>.*?<\/\\1>/si', '', $html );
	// remove any script, style, link and iframe tags
	$html = \preg_replace( '/<(script|style|iframe|link)[^>]*?>/si', '', $html );
	return $html;
}
endif;
