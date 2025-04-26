<?php

namespace Tweaks;

use WP_Tweaks;
use Tweaks\Markdown;
use Tweaks\Dependencies\WP_Options_Page;

class Settings extends WP_Options_Page {
	protected $parsedown = null;

	private static $instance = null;
	public static function instance () {
		if ( ! self::$instance ) self::$instance = new self();
		return self::$instance;
	}

	private function __construct () {
		add_action( 'init', [ $this, 'init' ], 1 );
	}

	public function init () {
		$this->id = 'wp-tweaks';
		$this->menu_title = esc_html__( 'Tweaks', 'wp-tweaks' );
		$this->page_title = esc_html__( 'Tweaks Options', 'wp-tweaks' );
		$this->menu_parent = 'options-general.php';
		$this->field_prefix = WP_Tweaks::PREFIX;
		$this->hook_prefix = WP_Tweaks::PREFIX;

		$this->strings['checkbox_enable'] = esc_html__( 'Enable', 'wp-tweaks' );

		// register the page
		parent::init();
	}

	protected function init_hooks () {
		parent::init_hooks();
		$this->add_filter(
			'prepare_field',
			[ $this, 'render_markdown' ]
		);
		$this->add_filter(
			'prepare_field_content_editor',
			[ $this, 'prepare_field_content_editor' ]
		);
		add_filter(
			'plugin_action_links_' . plugin_basename( WP_Tweaks::FILE ),
			[ $this, 'add_settings_link' ]
		);
	}

	public function get_fields () {
		return [
			[
				'id' => 'change-posts-menu-name',
				'title' => __( 'Change menu "Posts" to "Blog"', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false
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
				'type' => 'content_editor',
				'default' => sprintf( '%1$s %2$s %3$s · ' . esc_html__( 'All Rights Reserved.', 'wp-tweaks' ), '{copyright}', '{current_year}', '{site_name}' ),
				/* translators: %1$s, %2$s and %3$s are variables */
				'description' => sprintf( esc_html__( 'This field accepts the following placeholders: %1$s prints the copyright symbol. %2$s prints the current year. %3$s prints your site name.', 'wp-tweaks' ), '`{copyright}`', '`{current_year}`', '`{site_name}`' ),
				'editor_settings' => [
					'textarea_rows' => 3,
					'wpautop' => false,
				]
			],
			[
				'id' => 'dashboard-single-column-layout',
				'title' => __( 'Dashboard with only one column', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'disable-comments',
				'title' => __( 'Disable comments', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
				'description' => esc_html__( 'Hides and disables new comments on pages and blog posts.', 'wp-tweaks' )
			],
			[
				'id' => 'disable-cron',
				'title' => __( 'Disable WP CRON', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
				'description' => esc_html__( 'When enabling this option, be sure to create an external cron job.', 'wp-tweaks' ),
			],
			[
				'id' => 'disable-author-query',
				'title' => __( 'Disable author query', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
				'description' => sprintf(
					/* translators: %s is an example URL */
					esc_html__( 'Disables author search via url (e.g: %s)', 'wp-tweaks' ),
					home_url( '?author=1' )
				)
			],
			[
				'id' => 'disable-author-pages',
				'title' => __( 'Disable author pages', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
				'description' => sprintf(
					/* translators: %s is an example URL */
					esc_html__( 'Disables author pages (e.g: %s)', 'wp-tweaks' ),
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
				'default' => false,
				'description' => esc_html__( 'Only logged in users will have access to REST API.', 'wp-tweaks' )
			],
			[
				'id' => 'disable-rest-api-users-endpoint',
				'title' => __( 'Remove users endpoint from REST API', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
				'description' => esc_html__( 'Disable user enumeration via REST API for non-logged in users.', 'wp-tweaks' )
			],
			[
				'id' => 'disable-right-click',
				'title' => __( 'Disable right click', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
				'description' => esc_html__( 'Disable right click, also copy and cut commands. Only on front-end.', 'wp-tweaks' )
			],
			[
				'id' => 'disable-rss',
				'title' => __( 'Disable RSS feed', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
				'description' => esc_html__( "Tip: Disable RSS feed if your site doesn't have a blog.", 'wp-tweaks' )
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
				'default' => false,
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
				'id' => 'display-php-version',
				'title' => __( 'Show PHP version in admin footer', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
				'description' => esc_html__( 'For admin users only.', 'wp-tweaks' ),
			],
			[
				'id' => 'generic-login-errors',
				'title' => __( 'Generic login error message', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'heartbeat',
				'title' => __( 'Heartbeat API', 'wp-tweaks' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'wp-tweaks' ),
					'60' => __( '60 seconds', 'wp-tweaks' ),
					'120' => __( '120 seconds', 'wp-tweaks' ),
					'disable' => __( 'Disable', 'wp-tweaks' ),
				],
				'default' => '',
				'description' => esc_html__( 'Search for "What is WordPress Heartbeat?" in your browser to learn more.', 'wp-tweaks' )
			],
			[
				'id' => 'hide-admin-bar',
				'title' => __( 'Show admin bar for admin users only', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
			],
			[
				'id' => 'hide-admin-notices',
				'title' => __( 'Hide annoying messages', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => true,
				'description' =>  sprintf(
					/* translators: %s is the Plugins page URL */
					esc_html__( 'Hide almost all annoying messages that keep appearing. But it will show them on the [Plugins](%s) page, just to make sure they are visible somewhere.', 'wp-tweaks' ),
					admin_url( '/plugins.php' )
				),
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
				'id' => 'post-revisions',
				'title' => __( 'Post Revisions', 'wp-tweaks' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'wp-tweaks' ),
					'1' => 1,
					'3' => 3,
					'5' => 5,
					'0' => __( 'Disable', 'wp-tweaks' ),
				],
				'default' => '',
				'description' => esc_html__( 'Limit or disable the number of posts revisions that WordPress stores in the database.', 'wp-tweaks' )
			],
			[
				'id' => 'remove-admin-bar-comments',
				'title' => __( 'Remove "Comments" from admin bar', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
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
				'id' => 'remove-lang-switcher',
				'title' => __( 'Remove language switcher from login page', 'wp-tweaks' ),
				'type' => 'checkbox',
				'default' => false,
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
				'options' => apply_filters( 'wp_tweaks_security_headers_options', [
					'x-frame-options'           => 'X-Frame-Options',
					'strict-transport-security' => 'Strict-Transport-Security',
					'x-content-type-options'    => 'X-Content-Type-Options',
					'x-xss-protection'          => 'X-XSS-Protection',
					'content-security-policy'   => 'Content-Security-Policy',
					'referrer-policy'           => 'Referrer-Policy',
					'permissions-policy'        => 'Permissions-Policy'
				] ),
				'description' => sprintf(
					/* translators: %s is the securityHeaders.io URL. Please preserve the markdown syntax. */
					esc_html__( 'Only activate these options if you are sure of what you are doing and don\'t forget to test your site. This headers are used to make your site more secure. Once you have setup each header, check it using [securityHeaders.io](%s).', 'wp-tweaks' ),
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
		$field['description'] = $desc ? Markdown::render_line( $desc ) : null;
		return $field;
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

	protected function render_field_content_editor ( $field ) {
		$value = $this->get_field_value( $field );
		$name = $field['name'];
		$desc = $field['description'];
		$args = $field['editor_settings'] ?? [];

		$args['textarea_rows'] = $args['textarea_rows'] ?? 5;
		$args['wpautop'] = $args['wpautop'] ?? true;
		unset( $args['textarea_name'] );

		// render field
		\wp_editor( $value, $name, $args );

		$this->do_action( 'after_field_input', $field, $this );

		if ( $desc ) : ?>
		<p class="description"><?php echo $desc ?></p>
		<?php endif;
	}

	public function prepare_field_content_editor ( $field ) {
		$field['@sanitize'] = 'wp_kses_post';
		return $field;
	}
}
