<?php
/**
 * Change admin footer text
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'admin_footer_text', 'wp_tweaks_custom_admin_footer_text' );
function wp_tweaks_custom_admin_footer_text () {
	$text = WP_Tweaks::get_option( 'custom-admin-footer-text' );
	$placeholders = apply_filters( 'wp_tweaks_admin_footer_placeholders', [
		'{copyright}' => '&copy;',
		'{current_year}' => date('Y'),
		'{site_name}' => get_bloginfo('name')
	] );

	// search and replace the placeholders
	foreach ( $placeholders as $placeholder => $value ) {
		$text = str_replace( $placeholder, $value, $text );
	}

	// remove <p> tags
	$text = str_replace( [ '<p>', '</p>' ], '', $text );

	return apply_filters( 'wp_tweaks_admin_footer_text', $text );
}
