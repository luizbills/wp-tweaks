<?php
/**
 * Overrides the function user_can_richedit and only checks the
 * user preferences instead of doing UA sniffing.
 * 
 * @package wp-tweaks
 */

add_filter( 'user_can_richedit', 'wp_tweaks_user_can_richedit_custom' );
function wp_tweaks_user_can_richedit_custom ( $value ) {
	global $wp_rich_edit;
	$wp_rich_edit = is_user_logged_in() && 'true' === get_user_option( 'rich_editing' );
	return $wp_rich_edit;
}
