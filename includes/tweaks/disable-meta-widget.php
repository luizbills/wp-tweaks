<?php
/**
 * Disable sidebar meta widget
 *
 * @package wp-tweaks
 */
if ( ! defined( 'WPINC' ) ) die();

unregister_widget( 'WP_Widget_Meta' );
