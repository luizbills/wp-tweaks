<?php

if ( ! defined( 'WPINC' ) ) die();

if ( ! class_exists( 'WP_Tweaks_Settings' ) ) :

class WP_Tweaks_Settings {

	protected static $settings_page = null;
	protected static $page_id = 'wp-tweaks';

	public function __construct () {

		$page = wp_create_admin_page( [
			'menu_name' => __( 'Tweaks', 'wp-tweaks' ),
			'id' => self::$page_id,
			'prefix' => WP_Tweaks::PREFIX,
			'parent' => 'options-general.php'
		] );

		self::$settings_page = $page;

		foreach ( self::get_settings() as $id => $options ) {
			$options['id'] = $id;
			$page->add_field( $options );
		}

		add_filter( 'plugin_action_links_'.  plugin_basename( WP_Tweaks::FILE ), [ $this, 'add_settings_link' ] );
	}

	public static function get_settings () {
		return [
			'clear-file-name' => [
				'label' => __( 'Clear uploaded file names', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'custom-admin-footer-text' => [
				'label' => __( 'Custom admin footer text', 'wp-tweaks' ),
				'type' => 'content',
				'default' => __( '&copy; {current_year} {site_name} · All rights reserved.', 'wp-tweaks' ),
				'desc' => __( 'placeholders: `{current_year}` and `{site_name}`', 'wp-tweaks' ),
				'height' => 100,
			],
			'disable-author-search' => [
				'label' => __( 'Remove author pages', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' ),
				'desc' => __( 'disables author search via url (e.g: yoursite.com/?author=1) and author pages', 'wp-tweaks' )
			],
			'disable-emoji' => [
				'label' => __( 'Remove wordpress emojis', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'disable-meta-widget' => [
				'label' => __( 'Remove Meta widget', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'disable-public-rest-api' => [
				'label' => __( 'Remove public REST API access', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' ),
				'desc' => __( 'only logged in users will have access to REST API', 'wp-tweaks' )
			],
			'disable-website-field' => [
				'label' => __( 'Remove "website" field in comment form', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'disable-wp-embed' => [
				'label' => __( 'Remove oEmbed support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'disable-xmlrpc' => [
				'label' => __( 'Remove XML-RPC support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'hide-admin-bar' => [
				'label' => __( 'Show admin bar for admin users only', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'hide-update-notice' => [
				'label' => __( 'Hide WordPress Update Nag to All But Admins', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'jquery-cdn' => [
				'label' => __( 'Use jQuery from Google CDN', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'_jquery-version' => [
				'label' => __( 'jQuery version', 'wp-tweaks' ),
				'type' => 'text',
				'default' => '3.3.1',
				'props' => [
					'class' => 'small-text'
				]
			],
			'remove-admin-bar-logo' => [
				'label' => __( 'Remove WordPress logo from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'remove-admin-bar-new-content' => [
				'label' => __( 'Remove "+ New" from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'remove-dashboard-widgets' => [
				'label' => __( 'Remove some default Dashboard widgets (useless for clients)', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'remove-query-strings' => [
				'label' => __( 'Remove Query String from frontend scripts', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'remove-shortlink' => [
				'label' => __( 'Remove &lt;link rel="shortlink"&gt;', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'remove-welcome-panel' => [
				'label' => __( 'Remove Dashboard "welcome panel"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
			'remove-wordpress-version' => [
				'label' => __( 'Remove WordPress version number in frontend', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => __( 'Enable', 'wp-tweaks' )
			],
		];
	}

	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . self::$page_id . '">' . __( 'Settings', 'wp-tweaks' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public static function get_option ( $key ) {
		if ( ! is_null( self::$settings_page ) ) {
			return self::$settings_page->get_field_value( $key );
		}
		return false;
	}

}

new WP_Tweaks_Settings();

endif;