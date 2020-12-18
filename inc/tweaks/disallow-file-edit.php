<?php

add_action( 'init','wp_tweaks_disable_file_edit' );
function wp_tweaks_disallow_file_edit () {
	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) define( 'DISALLOW_FILE_EDIT', true );
}
