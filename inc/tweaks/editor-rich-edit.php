<?php
/**
 * Overrides the function user_can_richedit and only checks the
 * user preferences instead of doing UA sniffing.
 * 
 * @package wp-tweaks
 */

add_filter('user_can_richedit', 'wp_tweaks_user_can_richedit_custom');

function wp_tweaks_user_can_richedit_custom() {
  global $wp_rich_edit;
 
  if (get_user_option('rich_editing') == 'true' || !is_user_logged_in()) {
    $wp_rich_edit = true;
    return true;
  }
 
  $wp_rich_edit = false;
  return false;
}