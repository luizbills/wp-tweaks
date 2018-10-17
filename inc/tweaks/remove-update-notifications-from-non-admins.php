<?php
/**
 * Remove update notifications from non-admins
 *
 * @package wp-tweaks
 */

add_action( 'admin_head', 'wp_tweaks_remove_update_nags_for_non_admins', 1 );

function wp_tweaks_remove_update_nags_for_non_admins() {
  if (!current_user_can('update_core')) {
    remove_action( 'admin_notices', 'update_nag', 3 );
  }
}
