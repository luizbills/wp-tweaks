<?php

add_filter( 'update_footer', 'wp_tweaks_show_php_version', PHP_INT_MAX );
function wp_tweaks_show_php_version ( $text ) {
	return $text . ( $text ? '<br>' : '' ) . '<span style="float: right;" class="footer_php_version">PHP Version: ' . \esc_html( \PHP_VERSION );
}
