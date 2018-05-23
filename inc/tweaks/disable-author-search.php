<?php
/**
 * Disable author page and author search by url
 *
 * @package wp-tweaks
 */

add_action( 'wp', 'wp_tweaks_disable_author_search' );
function wp_tweaks_disable_author_search () {
	$disable_author_page = apply_filters( 'wp_tweaks_disable_author_page', true );
	$disable_author_query = apply_filters( 'wp_tweaks_disable_author_query', true );

	global $wp_query;

	if ( $disable_author_query && isset( $_GET['author'] ) ) {
		$wp_query->set_404();
		status_header( 404 );
	} else if ( $disable_author_page && is_author() ) {
		$wp_query->set_404();
		status_header( 404 );
	}
}
