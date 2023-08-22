<?php
/**
 * Remove WordPress version number in frontend
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

add_filter( 'the_generator', '__return_empty_string' );
