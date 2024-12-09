<?php
/**
 * Limit or disable post revisions
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

$GLOBALS['wp_tweaks_revisions_to_keep'] = $option;

add_filter( 'wp_revisions_to_keep', 'wp_tweaks_post_revisions_to_keep' );
function wp_tweaks_post_revisions_to_keep ( $num ) {
	$defined = $GLOBALS['wp_tweaks_revisions_to_keep'];
	return '' === $defined ? $num : (int) $defined;
}
