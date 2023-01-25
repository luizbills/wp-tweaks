<?php
/**
 * Display generic error message in login form
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'login_errors', 'wp_tweaks_login_errors', 1 );
function wp_tweaks_login_errors ( $errors ) {
	return '<strong>' . __( 'ERROR', 'wp-tweaks' ) . '</strong>: ' . esc_html__( 'Incorrect email address or password.', 'wp-tweaks' );
}
