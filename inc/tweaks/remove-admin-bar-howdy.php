<?php
/**
 * Remove "Howdy" from admin bar
 *
 * @package wp-tweaks
 */

add_action( 'admin_bar_menu', 'wp_tweaks_remove_howdy', 11 );
 
function wp_tweaks_remove_howdy( $wp_admin_bar ) {
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy, ', '', $my_account->title );
    $wp_admin_bar->add_node( array(
      'id' => 'my-account',
      'title' => $newtitle,
    ) );
}