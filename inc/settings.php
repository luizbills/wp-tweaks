<?php

if ( ! defined( 'WPINC' ) ) die();

if ( ! class_exists( 'WP_Tweaks_Settings' ) ) :

class WP_Tweaks_Settings {

	protected static $settings_page = null;
	protected static $page_id = 'wp-tweaks';

	public function __construct () {
		$page = wp_create_admin_page( [
			'menu_name' => esc_html__( 'Tweaks', 'wp-tweaks' ),
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
				'label' => esc_html__( 'Clear uploaded file names', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'custom-admin-footer-text' => [
				'label' => esc_html__( 'Custom admin footer text', 'wp-tweaks' ),
				'type' => 'content',
				'default' => sprintf( esc_html__( '&copy; %1$s %2$s · All Rights Reserved.', 'wp-tweaks' ), '{current_year}', '{site_name}' ),
				'desc' => sprintf( esc_html__( '%1$s prints the current year. %2$s prints your site name.', 'wp-tweaks' ), '`{current_year}`', '`{site_name}`' ),
				'height' => 100,
			],
			'dashboard-single-column-layout' => [
				'label' => esc_html__( 'Dashboard with only one column', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
			],
			'disable-author-search' => [
				'label' => esc_html__( 'Remove author pages', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => esc_html__( 'disables author search via url (e.g: yoursite.com/?author=1) and author pages', 'wp-tweaks' )
			],
			'disable-emoji' => [
				'label' => esc_html__( 'Disable WordPress emojis', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disable-meta-widget' => [
				'label' => esc_html__( 'Remove "meta widget"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disable-public-rest-api' => [
				'label' => esc_html__( 'Remove public REST API access', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => esc_html__( 'only logged in users will have access to REST API', 'wp-tweaks' )
			],
			'disable-website-field' => [
				'label' => esc_html__( 'Remove "website" field in comment form', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disable-wp-embed' => [
				'label' => esc_html__( 'Remove oEmbed support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disable-xmlrpc' => [
				'label' => esc_html__( 'Remove XML-RPC support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'hide-admin-bar' => [
				'label' => esc_html__( 'Show admin bar for admin users only', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'hide-update-notice' => [
				'label' => esc_html__( 'Hide WordPress update nag to all but admins', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'hide-wp-version-in-admin-footer' => [
				'label' => esc_html__( 'Hide WordPress version in admin footer to all but admins', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'jquery-cdn' => [
				'label' => esc_html__( 'Use jQuery from Google CDN', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => esc_html__( 'this option also disables `jquery-migrate`', 'wp-tweaks' )
			],
			'_jquery-version' => [
				'label' => esc_html__( 'jQuery version', 'wp-tweaks' ),
				'type' => 'text',
				'default' => '2.2.4',
				'props' => [
					'class' => 'small-text'
				],
				'desc' => esc_html__( 'current stable versions: ', 'wp-tweaks' ) . '`1.12.4`, `2.2.4` or `3.3.1`.',
			],
			'remove-admin-bar-logo' => [
				'label' => esc_html__( 'Remove WordPress logo from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-admin-bar-new-content' => [
				'label' => esc_html__( 'Remove "+ New" button from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-dashboard-widgets' => [
				'label' => esc_html__( 'Remove some default dashboard widgets (useless for clients)', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-query-strings' => [
				'label' => esc_html__( 'Remove query string from static scripts', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-shortlink' => [
				'label' => esc_html__( 'Remove', 'wp-tweaks' ) . esc_html( ' <link rel="shortlink">' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-welcome-panel' => [
				'label' => esc_html__( 'Remove Dashboard "welcome panel"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-wordpress-version' => [
				'label' => esc_html__( 'Remove WordPress version number in frontend', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-update-notifications-from-non-admins' => [
				'label' => esc_html__( 'Remove update notifications from non-admins.', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
		];
	}

	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . self::$page_id . '">' . esc_html__( 'Settings', 'wp-tweaks' ) . '</a>';
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
