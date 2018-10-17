<?php
/**
 *  Force default setting for image link to "none"
 *
 * @package wp-tweaks
 */

add_action('admin_init', 'wp_tweaks_default_image_link_to_none', 10);

function wp_tweaks_default_image_link_to_none() {
  if ( get_option( 'image_default_link_type' ) !== 'none' ) {
    update_option('image_default_link_type', 'none');
  }
}
