<?php

if ( ! defined( 'WPINC' ) ) die();
if ( class_exists( 'WP_Tweaks_Markdown' ) ) return;

class WP_Tweaks_Markdown {
	protected static $instance = null;

	/**
	 * @var Parsedown
	 */
	protected $parsedown;

	protected function __construct() {
		$this->parsedown = new Parsedown();
		$this->parsedown->setMarkupEscaped(true);
	}

	protected static function get_instance () {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function render_line ( $string ) {
		return self::get_instance()->parsedown->line( $string );
	}
}
