<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit( 1 );

global $wpdb;

// delete all plugin options
$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'wp_tweaks_%'" );
