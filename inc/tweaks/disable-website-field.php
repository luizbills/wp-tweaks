<?php
/**
 * Disable "website field" from comment form
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'comment_form_default_fields', 'wp_tweaks_disable_website_field' );
function wp_tweaks_disable_website_field ( $field ) {
	if( isset( $field['url'] ) ) {
		unset( $field['url'] );
	}
	return $field;
}
