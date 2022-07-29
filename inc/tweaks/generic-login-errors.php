<?php
/**
 * Display
 *
 * @package wp-tweaks
 */
add_filter( 'login_errors', 'wp_tweaks_login_errors' );
function wp_tweaks_login_errors () {
    return esc_html__( 'Your username or password is incorrect!', 'wp-tweaks' );
}
