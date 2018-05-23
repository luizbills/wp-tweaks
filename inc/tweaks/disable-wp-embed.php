<?php
/**
 * Disable wp embed
 *
 * @package wp-tweaks
*/

add_action( 'wp_footer', 'wp_tweaks_disable_wp_embed_js' );
function wp_tweaks_disable_wp_embed_js (){
	wp_dequeue_script( 'wp-embed' );
}

add_action( 'init', 'wp_tweaks_disable_oembed', 20 );
function wp_tweaks_disable_oembed () {
	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'wp_tweaks_disable_embeds_tiny_mce_plugin' );

	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'wp_tweaks_disable_embeds_rewrites' );

	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

function wp_tweaks_disable_embeds_tiny_mce_plugin ( $plugins ) {
	return array_diff( $plugins, [ 'wpembed' ] );
}

function wp_tweaks_disable_embeds_rewrites ( $rules ) {
	foreach( $rules as $rule => $rewrite ) {
		if( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}
	return $rules;
}
