<?php
/**
 * Disable comments for blog posts and pages
 *
 * @package wp-tweaks
 */

/**
 * Check if the comments should be disabled
 *
 * @param int|WP_Post $post
 * @return bool
 */
function wp_tweaks_disable_comments_is_disabled ( $post = null ) {
	$post_types = apply_filters(
		'wp_tweaks_disable_comments_post_types',
		[ 'post', 'page' ]
	);
	$the_post_type = get_post_type( $post );
	return in_array( $the_post_type, $post_types );
}

add_filter( 'comments_template', 'wp_tweaks_dummy_comments_template', 9999 );
function wp_tweaks_dummy_comments_template ( $template ) {
    if ( wp_tweaks_disable_comments_is_disabled() ) {
        return WP_Tweaks::DIR . '/templates/comments.php';
    }
    return $template;
}

add_filter( 'comments_open', 'wp_tweaks_disable_comments', 9999, 2 );
function wp_tweaks_disable_comments ( $status, $post_id ) {
    if ( wp_tweaks_disable_comments_is_disabled( $post_id ) ) {
        return false;
    }
    return $status;
}

add_filter( 'get_default_comment_status', 'wp_tweaks_default_comment_status', 9999 );
function wp_tweaks_default_comment_status ( $status ) {
    if ( wp_tweaks_disable_comments_is_disabled() ) {
        $status = 'closed';
    }
    return $status;
}
