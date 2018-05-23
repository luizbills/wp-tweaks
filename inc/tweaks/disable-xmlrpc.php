<?php
/**
 * Disable xmlrpc.php
 *
 * @package wp-tweaks
 */

add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
