<?php
/**
 * Disable the built-in WP CRON, defining the DISABLE_WP_CRON constant.
 *
 * @package wp-tweaks
 */

if ( ! defined( 'DISABLE_WP_CRON' ) ) {
	define( 'DISABLE_WP_CRON', true );
}
