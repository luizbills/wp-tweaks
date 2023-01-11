<?php
/**
 * Remove <link rel="shortlink" ...> from <head>
 *
 * @package wp-tweaks
 */

remove_action( 'wp_head', 'wp_shortlink_wp_head' );
