<?php
/**
 * Enable second row on editor by default
 *
 * @package wp-tweaks
 */

add_filter( 'tiny_mce_before_init', 'wp_tweaks_second_editor_row' );

function wp_tweaks_second_editor_row ( $tinymce ) {
    $tinymce[ 'wordpress_adv_hidden' ] = FALSE;
    return $tinymce;
}