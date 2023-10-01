<?php
/**
 * Disable RSS feed
 *
 * @package wp-tweaks
 */

if ( ! defined( 'WPINC' ) ) die();

function wp_tweaks_disable_rss_feed () {
	wp_safe_redirect( home_url(), 301 );
	exit;
}

add_action( 'do_feed', 'wp_tweaks_disable_rss_feed', 0 );
add_action( 'do_feed_rdf', 'wp_tweaks_disable_rss_feed', 0 );
add_action( 'do_feed_rss', 'wp_tweaks_disable_rss_feed', 0 );
add_action( 'do_feed_rss2', 'wp_tweaks_disable_rss_feed', 0 );
add_action( 'do_feed_atom', 'wp_tweaks_disable_rss_feed', 0 );
add_action( 'do_feed_rss2_comments', 'wp_tweaks_disable_rss_feed', 0 );
add_action( 'do_feed_atom_comments', 'wp_tweaks_disable_rss_feed', 0 );

remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
