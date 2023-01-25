<?php
/**
 * Disable author page and author search by url
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp', 'wp_tweaks_disable_author_query' );
function wp_tweaks_disable_author_query () {
	global $wp_query;
	$disabled = apply_filters( 'wp_tweaks_disable_author_query', true );

	if ( $disabled && isset( $_GET['author'] ) ) {
		$wp_query->set_404();
		status_header( 404 );
	}
}
