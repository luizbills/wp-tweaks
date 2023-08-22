<?php
/**
 * Display generic error message in login form
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'wp_login_errors', 'wp_tweaks_login_error', 99 );
function wp_tweaks_login_error ( $errors ) {
	if ( ! is_wp_error( $errors ) ) return new WP_Error();

	$has_login_error = false;
	$login_errors = [
		'empty_username',
		'invalid_username',
		'empty_password',
		'incorrect_password',
		'invalid_email',
	];

	foreach ( $login_errors as $code ) {
		if ( 0 === count( $errors->get_error_messages( $code ) ) ) {
			continue;
		}
		$errors->remove( $code );
		$has_login_error = true;
	}

	if ( $has_login_error ) {
		$errors->add(
			'invalid',
			apply_filters(
				'wp_tweaks_login_error',
				esc_html__( 'Incorrect username or password.', 'wp-tweaks' )
			)
		);
	}

	return $errors;
}
