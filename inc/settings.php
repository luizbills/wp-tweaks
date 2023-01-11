<?php

if ( ! defined( 'WPINC' ) ) die();

if ( ! class_exists( 'WP_Tweaks_Settings' ) ) :

class WP_Tweaks_Settings {

	protected static $settings_page = null;
	protected static $page_id = 'wp-tweaks';

	public function __construct () {
		add_action( 'init', [ $this, 'init' ], 1 );
	}

	public function init () {
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

		add_filter( 'plugin_action_links_' . plugin_basename( WP_Tweaks::FILE ), [ $this, 'add_settings_link' ] );

		// tweaks
		foreach ( WP_Tweaks_Settings::get_settings() as $id => $_ ) {
			if ( '_' === $id[0] ) continue;
			if ( apply_filters( "wp_tweaks_skip_{$id}", false ) ) continue;

			$file = WP_Tweaks::DIR . "/inc/tweaks/{$id}.php";
			if ( file_exists( $file ) && ! empty( self::get_option( $id ) ) ) {
				include_once $file;
			}

			// warn about debug constants
			include_once WP_Tweaks::DIR . '/inc/debug-warning.php';
		}
	}

	public static function get_settings () {
		return [
			'change-posts-menu-name' => [
				'label' => esc_html__( 'Change menu "Posts" to "Blog"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => '',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'clear-file-name' => [
				'label' => esc_html__( 'Clear uploaded file names', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'custom-admin-footer-text' => [
				'label' => esc_html__( 'Custom admin footer text', 'wp-tweaks' ),
				'type' => 'content',
				'default' => sprintf( '%1$s %2$s %3$s · ' . esc_html__( 'All Rights Reserved.', 'wp-tweaks' ), '{copyright}', '{current_year}', '{site_name}' ),
				/* translators: %1$s, %2$s and %3$s are variables */
				'desc' => sprintf( esc_html__( '%1$s prints the copyright symbol. %2$s prints the current year. %3$s prints your site name.', 'wp-tweaks' ), '`{copyright}`', '`{current_year}`', '`{site_name}`' ),
				'height' => 100,
			],
			'dashboard-single-column-layout' => [
				'label' => esc_html__( 'Dashboard with only one column', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
			],
			'disable-author-query' => [
				'label' => esc_html__( 'Disable author query', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => sprintf(
					/* translators: %s is an example URL */
					esc_html__( 'disables author search via url (e.g: %s)', 'wp-tweaks' ),
					home_url( '?author=1' )
				)
			],
			'disable-author-pages' => [
				'label' => esc_html__( 'Disable author pages', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => '',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => sprintf(
					/* translators: %s is an example URL */
					esc_html__( 'disables author pages (e.g: %s)', 'wp-tweaks' ),
					home_url( '/author/admin' )
				)
			],
			'disable-emojis' => [
				'label' => esc_html__( 'Disable WordPress emojis', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disable-jquery-migrate' => [
				'label' => esc_html__( 'Disable jQuery Migrate', 'wp-tweaks' ),
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
				'default' => '',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => esc_html__( 'only logged in users will have access to REST API', 'wp-tweaks' )
			],
			'disable-rest-api-users-endpoint' => [
				'label' => esc_html__( 'Remove users endpoint from REST API', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => esc_html__( 'Disable users enumeration via REST API.', 'wp-tweaks' )
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
				'default' => '',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disable-xmlrpc' => [
				'label' => esc_html__( 'Remove XML-RPC and Pingbacks support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disallow-file-edit' => [
				'label' => esc_html__( 'Disallow file edit', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'disallow-iframes' => [
				'label' => esc_html__( 'Disallow your site in iframes', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => '',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
				'desc' => esc_html__( 'Disallow to render your site in a <frame>, <iframe>, <embed> or <object>.', 'wp-tweaks' )
			],
			'generic-login-errors' => [
				'label' => esc_html__( 'Generic login error message', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'on',
				'after' => esc_html__( 'Enable', 'wp-tweaks' ),
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
			'remove-admin-bar-comments' => [
				'label' => esc_html__( 'Remove "Comments" from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'off',
				'after' => esc_html__( 'Enable', 'wp-tweaks' )
			],
			'remove-admin-bar-logo' => [
				'label' => esc_html__( 'Remove "WordPress Logo" from admin bar', 'wp-tweaks' ),
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
			'remove-howdy' => [
				'label' => esc_html__( 'Remove "Howdy" from admin bar', 'wp-tweaks' ),
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
			'security-headers' => [
				'label' => esc_html__( 'Security Headers', 'wp-tweaks' ),
				'type' => 'checkbox_multi',
				'choices' => [
					'x-frame-options' => 'X-Frame-Options',
					'strict-transport-security' => 'Strict-Transport-Security',
					'x-content-type-options' => 'X-Content-Type-Options',
					'x-xss-protection' => 'X-XSS-Protection',
					'content-security-policy' => 'Content-Security-Policy',
				],
				'desc' => sprintf(
					/* translators: %s is the securityHeaders.io URL */
					esc_html__( 'You can utilise this headers to make your site more secure. Once you have setup each header, check it using [securityHeaders.io](%s).', 'wp-tweaks' ),
					add_query_arg(
						[
							'q' => home_url(),
							'followRedirects' => 'on'
						],
						'https://securityHeaders.io'
					)
				),
			]
		];
	}

	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . intval( self::$page_id ) . '">' . esc_html__( 'Settings', 'wp-tweaks' ) . '</a>';
		return array_merge( [ $settings_link ], $links );
	}

	public static function get_option ( $key ) {
		return self::$settings_page->get_field_value( $key );
		return false;
	}

}

new WP_Tweaks_Settings();

endif;
