<?php
/**
 * Plugin Name: WP Tweaks
 * Plugin URI: https://github.com/luizbills/wp-tweaks
 * Description: Several opinionated WordPress tweaks focused in security and performance.
 * Version: 2.3.4
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

final class WP_Tweaks {
	const FILE = __FILE__;
	const DIR = __DIR__;
	const PREFIX = 'wp_tweaks_';

	private static $settings = null;
	private static $instance = null;

	public static function instance () {
		if ( ! self::$instance ) self::$instance = new self();
		return self::$instance;
	}

	private function __construct () {
		if ( ! $this->autoload() ) {
			add_action( 'admin_notices', function () {
				echo '<div class="notice notice-error"><p><strong>Error</strong>: Missing <code>vendor</code> directory. Please reinstall the <strong>WP Tweaks</strong> plugin via WordPress Repository.</p></div>';
			} );
			return;
		}
		$this->includes();
		$this->hooks();
		self::$settings = \Tweaks\Settings::instance();
	}

	public function includes () {
		include self::DIR . '/includes/helpers.php';
	}

	private function hooks () {
		add_action( 'init', [ $this, 'load_plugin_translations' ], 0 );
		add_action( 'init', [ $this, 'load_tweaks' ], 5 );
	}

	private function autoload () {
		$autoload = self::DIR . '/vendor/autoload.php';
		if ( file_exists( $autoload ) ) {
			include $autoload;
			\Tweaks\Settings::instance();
			return true;
		}
		return false;
	}

	public function load_tweaks () {
		// debug constants warning
		include_once WP_Tweaks::DIR . '/includes/debug-warning.php';

		// user selected tweaks
		foreach ( self::$settings->get_fields() as $field ) {
			$id = $field['id'] ?? '';
			if ( ! $id || '_' === substr( $id, 0, 1 ) ) continue;
			if ( apply_filters( "wp_tweaks_skip_{$id}", false ) ) continue;

			$option = self::get_option( $id );
			if ( ! empty( $option ) ) {
				include_once WP_Tweaks::DIR . "/includes/tweaks/{$id}.php";
			}
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
}

function wp_tweaks () {
	return \WP_Tweaks::instance();
}

wp_tweaks();

endif;
