<?php
/**
 * Display generic error message in login form
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'wp_login_errors', 'wp_tweaks_login_errors', 99 );
function wp_tweaks_login_errors ( $errors ) {
	if ( ! is_wp_error( $errors ) ) return new WP_Error();

	$errors->remove( 'empty_username' );
	$errors->remove( 'invalid_username' );
	$errors->remove( 'empty_password' );
	$errors->remove( 'incorrect_password' );
	$errors->remove( 'invalid_email' );

	$errors->add(
		'invalid_login',
		esc_html__( 'Incorrect email address or password.', 'wp-tweaks' )
	);

	return $errors;
}
