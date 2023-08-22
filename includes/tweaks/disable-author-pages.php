<?php
/**
 * Disable author pages
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp', 'wp_tweaks_disable_author_pages' );
function wp_tweaks_disable_author_pages () {
	global $wp_query;
	$disabled = apply_filters( 'wp_tweaks_disable_author_pages', true );

	if ( $disabled && $wp_query->is_author() ) {
		$wp_query->set_404();
		status_header( 404 );
	}
}
