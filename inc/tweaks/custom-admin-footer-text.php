<?php
/**
 * Change admin footer text
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'admin_footer_text', 'wp_tweaks_custom_admin_footer_text' );
function wp_tweaks_custom_admin_footer_text () {
	$text_template = WP_Tweaks_Settings::get_option( 'custom-admin-footer-text' );
	$text = $text_template;
	$placeholders = apply_filters( 'wp_tweaks_admin_footer_placeholders', [
		'{copyright}' => '&copy;',
		'{current_year}' => date('Y'),
		'{site_name}' => get_bloginfo('name')
	] );

	foreach ( $placeholders as $placeholder => $value ) {
		$text = str_replace( $placeholder, $value, $text );
	}

	echo apply_filters( 'wp_tweaks_admin_footer_text', $text );
}
