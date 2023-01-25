<?php

if ( ! defined( 'WPINC' ) ) die();
if ( class_exists( 'WP_Tweaks_Settings' ) ) return;

class WP_Tweaks_Settings extends WP_Tweaks_Options_Page {
	protected $parsedown = null;

	public function __construct ( $init = true ) {
		$this->id = 'wp-tweaks';
		$this->menu_title = esc_html__( 'Tweaks', 'wp-tweaks' );
		$this->page_title = esc_html__( 'Tweaks Options', 'wp-tweaks' );
		$this->menu_parent = 'options-general.php';
		$this->field_prefix = WP_Tweaks::PREFIX;
		$this->hook_prefix = WP_Tweaks::PREFIX;

		$this->strings['checkbox_enable'] = esc_html__( 'Enable', 'wp-tweaks' );

		$this->init();
	}

	protected function init_hooks () {
		parent::init_hooks();

		add_filter( $this->hook_prefix . 'prepare_field', [ $this, 'render_markdown' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( WP_Tweaks::FILE ), [ $this, 'add_settings_link' ] );
	}

	public function get_fields () {
		return [
			[
				'id' => 'change-posts-menu-name',
				'title' => __( 'Change menu "Posts" to "Blog"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => ''
			],
			[
				'id' => 'clear-file-name',
				'title' => __( 'Clear uploaded file names', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'custom-admin-footer-text',
				'title' => __( 'Custom admin footer text', 'wp-tweaks' ),
				'type' => 'textarea',
				'default' => sprintf( '**%1$s %2$s %3$s · ' . esc_html__( 'All Rights Reserved.', 'wp-tweaks' ) . '**', '{copyright}', '{current_year}', '{site_name}' ),
				/* translators: %1$s, %2$s and %3$s are variables */
				'description' => sprintf( esc_html__( 'This field accepts Markdown and the following placeholders: %1$s prints the copyright symbol. %2$s prints the current year. %3$s prints your site name.', 'wp-tweaks' ), '`{copyright}`', '`{current_year}`', '`{site_name}`' ),
				'height' => 100,
			],
			[
				'id' => 'dashboard-single-column-layout',
				'title' => __( 'Dashboard with only one column', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disable-author-query',
				'title' => __( 'Disable author query', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
				'description' => sprintf(
					/* translators: %s is an example URL */
					esc_html__( 'disables author search via url (e.g: %s)', 'wp-tweaks' ),
					home_url( '?author=1' )
				)
			],
			[
				'id' => 'disable-author-pages',
				'title' => __( 'Disable author pages', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => '',
				'description' => sprintf(
					/* translators: %s is an example URL */
					esc_html__( 'disables author pages (e.g: %s)', 'wp-tweaks' ),
					home_url( '/author/admin' )
				)
			],
			[
				'id' => 'disable-emojis',
				'title' => __( 'Disable WordPress emojis', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disable-jquery-migrate',
				'title' => __( 'Disable jQuery Migrate', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disable-meta-widget',
				'title' => __( 'Remove "meta widget"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disable-public-rest-api',
				'title' => __( 'Remove public REST API access', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => '',
				'description' => esc_html__( 'only logged in users will have access to REST API', 'wp-tweaks' )
			],
			[
				'id' => 'disable-rest-api-users-endpoint',
				'title' => __( 'Remove users endpoint from REST API', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
				'description' => esc_html__( 'Disable users enumeration via REST API.', 'wp-tweaks' )
			],
			[
				'id' => 'disable-website-field',
				'title' => __( 'Remove "website" field in comment form', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disable-wp-embed',
				'title' => __( 'Remove oEmbed support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => '',
			],
			[
				'id' => 'disable-xmlrpc',
				'title' => __( 'Remove XML-RPC and Pingbacks support', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disallow-file-edit',
				'title' => __( 'Disallow file edit', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'generic-login-errors',
				'title' => __( 'Generic login error message', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'hide-admin-bar',
				'title' => __( 'Show admin bar for admin users only', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'hide-update-notice',
				'title' => __( 'Hide WordPress update nag to all but admins', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'hide-wp-version-in-admin-footer',
				'title' => __( 'Hide WordPress version in admin footer to all but admins', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-admin-bar-comments',
				'title' => __( 'Remove "Comments" from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => 'off',
			],
			[
				'id' => 'remove-admin-bar-logo',
				'title' => __( 'Remove "WordPress Logo" from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-admin-bar-new-content',
				'title' => __( 'Remove "+ New" button from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-dashboard-widgets',
				'title' => __( 'Remove some default dashboard widgets (useless for clients)', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-howdy',
				'title' => __( 'Remove "Howdy" from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-query-strings',
				'title' => __( 'Remove query string from static scripts', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-shortlink',
				'title' => __( 'Remove', 'wp-tweaks' ) . esc_html( ' <link rel="shortlink">' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-welcome-panel',
				'title' => __( 'Remove Dashboard "welcome panel"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'remove-wordpress-version',
				'title' => __( 'Remove WordPress version number in frontend', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'security-headers',
				'title' => __( 'Security Headers', 'wp-tweaks' ),
				'type' => 'checkboxes',
				'options' => [
					'x-frame-options' => 'X-Frame-Options',
					'strict-transport-security' => 'Strict-Transport-Security',
					'x-content-type-options' => 'X-Content-Type-Options',
					'x-xss-protection' => 'X-XSS-Protection',
					'content-security-policy' => 'Content-Security-Policy',
				],
				'description' => sprintf(
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
		$settings_link = '<a href="' . esc_attr( $this->get_url() ) . '">' . esc_html__( 'Settings', 'wp-tweaks' ) . '</a>';
		return array_merge( [ $settings_link ], $links );
	}

	public function render_markdown ( $field ) {
		$desc = $field['description'] ?? null;
		$field['description'] = $desc ? WP_Tweaks_Markdown::render_line( $desc ) : null;
		return $field;
	}

	public function handle_options () {
		parent::handle_options();
	}

	public function update_options ( $options ) {
		$updated = 0;
		foreach ( $options as $key => $data ) {
			$updated += update_option( $key, $data['value'] ) ? 1 : 0;
		}
		return $updated > 0;
	}

	public function get_option ( $field_id ) {
		$default = $this->get_field_default_value( $field_id );
		return get_option( $this->field_prefix . $field_id, $default );
	}
}
