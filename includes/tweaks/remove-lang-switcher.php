<?php
/**
 * Remove the language switcher from login page
 *
 * @package wp-tweaks
 */

if ( ! defined( 'WPINC' ) ) die();

add_filter( 'login_display_language_dropdown', '__return_false' );
