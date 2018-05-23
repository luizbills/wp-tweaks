<?php
/**
 * Remove Query String from scripts (javaScript and CSS)
 *
 * @package wp-tweaks
 */

add_filter( 'style_loader_src', 'wp_tweaks_remove_query_string_from_scripts', 10, 2 );
add_filter( 'script_loader_src', 'wp_tweaks_remove_query_string_from_scripts', 10, 2 );
function wp_tweaks_remove_query_string_from_scripts ( $src ) {
	if( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
