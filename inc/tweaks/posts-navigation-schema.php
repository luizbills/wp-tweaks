<?php
/**
 * Add Schema markup to posts navigations
 *
 * @package wp-tweaks
 */

add_filter( 'next_posts_link_attributes', 'wp_tweaks_next_posts_attributes' );

function wp_tweaks_next_posts_attributes( $attr ) {
  return $attr . ' itemprop="relatedLink/pagination" ';
}
