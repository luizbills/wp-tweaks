<?php
/**
 * Plugin Name: WP Tweaks
 * Plugin URI: https://github.com/luizbills/wp-tweaks
 * Description: Several opinionated WordPress tweaks focused in security and performance.
 * Version: 2.0.0
 * Requires at least: 4.0
 * Requires PHP: 7.4
 * Author: Luiz Bills
 * Author URI: https://luizpb.com/en
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wp-tweaks
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) die();

if ( ! class_exists( 'WP_Tweaks' ) ) :

class WP_Tweaks {
	const FILE = __FILE__;
	const DIR = __DIR__;
	const PREFIX = 'wp_tweaks_';

	protected static $instance= null;
	protected static $settings = null;

	protected function __construct () {
		$this->includes();
		$this->hooks();
	}

	protected function hooks () {
		add_action( 'init', [ $this, 'load_plugin_translations' ], 0 );
		add_action( 'init', [ $this, 'load_tweaks' ] );
	}

	public function includes () {
		require_once self::DIR . '/inc/lib/class-parsedown.php';
		require_once self::DIR . '/inc/helpers.php';
		require_once self::DIR . '/inc/classes/class-wp-tweaks-markdown.php';
		require_once self::DIR . '/inc/classes/class-wp-tweaks-options-page.php';
		require_once self::DIR . '/inc/classes/class-wp-tweaks-settings.php';

		self::$settings = new WP_Tweaks_Settings();
	}

	public function load_tweaks () {
		foreach ( self::$settings->get_fields() as $field ) {
			$id = $field['id'] ?? '';
			if ( ! $id || '_' === substr( $id, 0, 1 ) ) continue;
			if ( apply_filters( "wp_tweaks_skip_{$id}", false ) ) continue;

			$tweak_file = WP_Tweaks::DIR . "/inc/tweaks/{$id}.php";
			if ( file_exists( $tweak_file ) && ! empty( self::get_option( $id ) ) ) {
				include_once $tweak_file;
			}

			// debug constants warning
			include_once WP_Tweaks::DIR . '/inc/debug-warning.php';
		}
	}

	public function load_plugin_translations () {
		load_plugin_textdomain(
			'wp-tweaks',
			false,
			dirname( plugin_basename( self::FILE ) ) . '/languages'
		);
	}

	public static function get_option ( $key ) {
		return self::$settings->get_option( $key );
	}

	public static function get_instance () {
		if ( null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

function wp_tweaks () {
	return WP_Tweaks::get_instance();
}

wp_tweaks();

endif;
