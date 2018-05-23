<?php

if ( ! defined( 'WPINC' ) ) die();

if ( ! class_exists( 'WP_Tweaks_Settings' ) ) :

class WP_Tweaks_Settings {

	protected static $settings_page = null;

	public function __construct () {

		$page = wp_create_admin_page( [
			'menu_name' => __( 'Tweaks', WP_Tweaks::TEXT_DOMAIN ),
			'id' => WP_Tweaks::TEXT_DOMAIN,
			'prefix'    => WP_Tweaks::PREFIX,
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
				'label' => 'Clear uploaded file names',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'custom-admin-footer-text' => [
				'label' => 'Custom admin footer text',
				'type' => 'content',
				'default' => '&copy; {current_year} {site_name} · All rights reserved.',
				'desc' => 'placeholders: `{current_year}` and `{site_name}`',
				'height' => 100,
			],
			'disable-author-search' => [
				'label' => 'Remove author pages',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable',
				'desc' => 'disables author search via url (e.g: yoursite.com/?author=1) and author pages'
			],
			'disable-emoji' => [
				'label' => 'Remove wordpress emojis',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'disable-meta-widget' => [
				'label' => 'Remove Meta widget',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'disable-public-rest-api' => [
				'label' => 'Remove public REST API access',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable',
				'desc' => 'only logged in users will have access to REST API'
			],
			'disable-website-field' => [
				'label' => 'Remove "website" field in comment form',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'disable-wp-embed' => [
				'label' => 'Remove oEmbed support',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'disable-xmlrpc' => [
				'label' => 'Remove XML-RPC support',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'hide-admin-bar' => [
				'label' => 'Show admin bar for admin users only',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'hide-update-notice' => [
				'label' => 'Hide WordPress Update Nag to All But Admins',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'jquery-cdn' => [
				'label' => 'Use jQuery from Google CDN',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'_jquery-version' => [
				'label' => 'jQuery version',
				'type' => 'text',
				'default' => '3.3.1',
				'props' => [
					'class' => 'small-text'
				]
			],
			'remove-admin-bar-logo' => [
				'label' => 'Remove WordPress logo from admin bar',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'remove-admin-bar-new-content' => [
				'label' => 'Remove "+ New" from admin bar',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'remove-dashboard-widgets' => [
				'label' => 'Remove some default Dashboard widgets (useless for clients)',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'remove-query-strings' => [
				'label' => 'Remove Query String from frontend scripts',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'remove-shortlink' => [
				'label' => 'Remove &lt;link rel="shortlink"&gt;',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'remove-welcome-panel' => [
				'label' => 'Remove Dashboard "welcome panel"',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
			'remove-wordpress-version' => [
				'label' => 'Remove WordPress version number in frontend',
				'type' => 'checkbox',
				'default' => 'on',
				'after' => 'Enable'
			],
		];
	}

	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . WP_Tweaks::TEXT_DOMAIN . '">' . __( 'Settings', WP_Tweaks::TEXT_DOMAIN ) . '</a>';
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