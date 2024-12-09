<?php

namespace Tweaks\Dependencies;

/**
 * WP_Options_Page class
 *
 * @package WP_Options_Page
 * @author Luiz Bills <luizbills@pm.me>
 * @version 0.7.0
 * @see https://github.com/luizbills/wp-options-page
 */
class WP_Options_Page {
	/**
	 * The package version.
	 *
	 * @since 0.6.0
	 * @var string
	 */
	const VERSION = '0.7.0';

	/**
	 * The ID (also the slug) of the page. Should be unique for this menu page and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with `sanitize_key()`.
	 *
	 * @since 0.1.0
	 * @see https://developer.wordpress.org/reference/functions/sanitize_key/
	 * @var string
	 */
	public $id = null;

	/**
	 * The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 *
	 * @since 0.1.0
	 * @var string|null
	 */
	public $menu_parent = null;

	/**
	 * The text to be displayed in the <title> tag of the page when the menu is selected.
	 * This text is also used in a <h1> tag at the top of the page, if the $insert_title is equal to `true`.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::$insert_title
	 * @var string|null
	 */
	public $page_title = null;

	/**
	 * A text that appears immediately after the <h1> title of the page, if the $insert_title is equal to `true`.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::$insert_title
	 * @var string|null
	 */
	public $page_description = null;

	/**
	 * If enabled, inserts a <h1> tag title and description at the top of the page.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::$page_description
	 * @see WP_Options_Page::$page_title
	 * @var bool
	 */
	public $insert_title = true;

	/**
	 * The text to be used for the menu.
	 *
	 * @since 0.1.0
	 * @var string|null
	 */
	public $menu_title = null;

	/**
	 * The position in the menu order this item should appear.
	 *
	 * @since 0.1.0
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/#parameters
	 * @var int|float
	 */
	public $menu_position = null;

	/**
	 * The hook priority used if this page is a subpage.
	 *
	 * @since 0.1.0
	 * @var int|float
	 */
	public $menu_priority = 10;

	/**
	 * The URL to the icon to be used for this menu.
	 *   - Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	 *   - Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'.
	 *   - Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 *
	 * @since 0.1.0
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/#parameters
	 * @var string
	 */
	public $menu_icon = 'dashicons-admin-generic';

	/**
	 * The capability (or user role) required for this menu to be displayed to the user.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	public $capability = 'manage_options';

	/**
	 * The option name where all options of the page will be stored.
	 * By default is `"{$this->id}_options"`.
	 *
	 * @since 0.1.0
	 * @var string|null
	 */
	public $option_name = null;

	/**
	 * The prefix appended in all field name attributes.
	 * By default is `"{$this->id}_"`.
	 *
	 * @since 0.1.0
	 * @var string|null
	 */
	public $field_prefix = null;

	/**
	 * The prefix appended in all hooks triggered by the page.
	 * By default is `"{$this->field_prefix}_"`.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::add_action()
	 * @see WP_Options_Page::add_filter()
	 * @var string|null
	 */
	protected $hook_prefix = null;

	/**
	 * The page's hook_suffix returned by `add_menu_page()` or `add_submenu_page()`.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::get_hook_suffix()
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/#return
	 * @var string
	 */
	protected $hook_suffix = null;

	/**
	 * The fields of the page.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::init_fields()
	 * @var array
	 */
	public $fields = [];

	/**
	 * Array with some strings that are used on the page. You can overwrite them to change or make them translatable.
	 *
	 * @since 0.1.0
	 * @var string[]
	 */
	public $strings = [];

	/**
	 * @since 0.1.0
	 * @see WP_Options_Page::add_notice()
	 * @var array
	 */
	protected $admin_notices = [];

	/**
	 * A flag used during the page rendering process.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::render_field()
	 * @see WP_Options_Page::maybe_open_or_close_table()
	 * @var bool
	 */
	protected $table_is_open = false;

	/**
	 * @since 0.1.0
	 * @see WP_Options_Page::add_script()
	 * @var array
	 */
	protected $scripts = [];

	/**
	 * @since 0.1.0
	 * @see WP_Options_Page::add_style()
	 * @var array
	 */
	protected $styles = [];

	/**
	 * The default value of each field of the page.
	 *
	 * @since 0.1.0
	 * @see WP_Options_Page::init_fields()
	 * @var array
	 */
	protected $default_values = [];

	/**
	 * List of supported features.
	 * The intention is that it will be used by other developers to choose whether or not to activate a feature.
	 *
	 * @since 0.3.0
	 * @var array<string|int,mixed>
	 */
	public $supports = [];

	/**
	 * List of attributes of the <form> tag.
	 *
	 * @since 0.3.0
	 * @var array<string, mixed>
	 */
	public $form_attributes = [];

	/**
	 * @since 0.3.0
	 * @var boolean
	 */
	public $credits = true;

	/**
	 * @since 0.1.0
	 * @return void
	 */
	public function init () {
		if ( ! \did_action( 'init' ) ) {
			throw new \Exception( 'Please, don\'t use the ' . \get_class( $this ) . ' class before "init" hook.' );
		}
		if ( ! $this->id ) {
			throw new \Exception( 'Missing $id in ' . \get_class( $this ) );
		}

		$this->menu_title = $this->menu_title ?? $this->id;
		$this->page_title = $this->page_title ?? $this->menu_title;
		$this->option_name = $this->option_name ?? $this->id . '_options';
		$this->field_prefix = $this->field_prefix ?? $this->id . '_';
		$this->hook_prefix = $this->hook_prefix ?? $this->field_prefix;

		$default_strings = [
			'template_notice_error' => '<strong>Error</strong>: %s',
			'checkbox_enable' => 'Enable',
			'options_updated' => '<strong>' . \esc_html__( 'Settings saved.' ) . '</strong>',
			'submit_button_label' => \esc_html__( 'Save Changes' ),
		];
		$this->strings = \array_replace( $default_strings, (array) $this->strings);

		$this->form_attributes = (array) $this->form_attributes;

		\do_action( 'wp_options_page_init', $this );

		$this->init_hooks();
		$this->init_fields();
		$this->handle_options();
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	protected function init_hooks () {
		// register admin page
		\add_action( 'admin_menu', [ $this, 'add_menu_page' ], $this->menu_priority );

		// enqueue admin page JS and CSS files
		\add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// maybe show "Settings saved" on submit
		$this->add_action( 'after_update_options', [ $this, 'add_settings_saved_notice' ], 10, 2 );

		// Removes slashes from a $_POST field values
		$this->add_filter( 'posted_value', 'wp_unslash' );

		$this->do_action( 'after_init_hooks', $this );
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	public function init_fields () {
		$this->fields = $this->apply_filters(
			'get_fields',
			$this->fields ? $this->fields : $this->get_fields(),
			$this
		);

		foreach ( $this->fields as $field ) {
			$id = $field['id'] ?? false;
			if ( $id ) $this->default_values[ $id ] = $field['default'] ?? null;
		}

		if ( $this->insert_title ) {
			$primary_title = $this->apply_filters(
				'top_page_title',
				[
					'type' => 'title',
					'title' => $this->page_title,
					'tag' => 'h1',
					'description' => $this->page_description,
					'class' => 'page-title-top',
				],
				$this
			);
			if ( $primary_title ) {
				\array_unshift( $this->fields, $primary_title );
			}
		}

		$has_submit = false;
		foreach ( $this->fields as $key => $field ) {
			$field = $this->prepare_field( $field );
			if ( ! $field ) continue;
			$this->fields[ $key ] = $field;
			if ( ! $has_submit && 'submit' === $field['type'] ) $has_submit = true;
		}

		if ( ! $has_submit ) {
			$this->fields[] = $this->prepare_field( [
				'type' => 'submit'
			] );
		}

		$this->do_action( 'after_init_fields', $this );
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	public function add_menu_page () {
		if ( ! $this->menu_parent ) {
			$this->hook_suffix = \add_menu_page(
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->id,
				[ $this, 'render_page' ],
				$this->menu_icon,
				$this->menu_position
			);
		} else {
			$this->hook_suffix = \add_submenu_page(
				$this->menu_parent,
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->id,
				[ $this, 'render_page' ],
				$this->menu_position
			);
		}

		$this->do_action( 'admin_menu', $this, $this->hook_suffix );
	}

	/**
	 * @since 0.3.0
	 * @return string
	 */
	public function get_hook_suffix () {
		return $this->hook_suffix;
	}

	/**
	 * @since 0.1.0
	 * @return array
	 */
	public function get_fields () {
		return [
			[
				'type' => 'title',
				'tag' => 'h2',
				'title' => 'Hey!',
				'description' => 'You not defined any field for this page yet.',
			],
		];
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return mixed
	 */
	public function get_field_value ( $field ) {
		if ( isset( $field['value'] ) ) {
			return $field['value'];
		}
		return $this->apply_filters(
			'get_field_value',
			$this->get_option( $field['id'] ),
			$field,
			$this
		);
	}

	/**
	 * @since 0.1.0
	 * @param string $field_id
	 * @return mixed
	 */
	public function get_field_default_value ( $field_id ) {
		return $this->apply_filters(
			'get_field_default_value',
			$this->default_values[ $field_id ] ?? false,
			$field_id,
			$this
		);
	}

	/**
	 * @since 0.1.0
	 * @param string $field_id
	 * @return mixed
	 */
	public function get_option ( $field_id ) {
		$default = $this->get_field_default_value( $field_id );
		$options = \get_option( $this->option_name, [] );
		return $options[ $field_id ] ?? $default;
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return string
	 */
	public function get_field_name ( $field ) {
		return $this->apply_filters(
			'get_field_name',
			$this->field_prefix . $field['id'],
			$field,
			$this
		);
	}

	/**
	 * @since 0.1.0
	 * @param string $handle
	 * @param string $src
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param bool $in_footer
	 * @return void
	*/
	public function add_script ( $handle, $src = '', $deps = [], $ver = false, $in_footer = false ) {
		$this->scripts[] = [ $handle, $src, $deps, $ver, $in_footer ];
	}

	/**
	 * @since 0.1.0
	 * @param string $handle
	 * @param string $src
	 * @param string[] $deps
	 * @param string|bool|null $ver = false
	 * @param string $media
	 * @return void
	*/
	public function add_style ( $handle, $src = '', $deps = [], $ver = false, $media = 'all' ) {
		$this->styles[] = [ $handle, $src, $deps, $ver, $media ];
	}

	/**
	 * @since 0.1.0
	 * @return string
	 */
	public function get_nonce_action () {
		return $this->field_prefix . 'nonce';
	}

	/**
	 * @since 0.1.0
	 * @return string
	 */
	public function get_nonce_name () {
		return '_nonce';
	}

	/**
	 * @since 0.1.0
	 * @param string $message
	 * @param string $type Should be "error", "success", "warning" or "info".
	 * @param string $class
	 * @return void
	 */
	public function add_notice ( $message, $type = 'info', $class = '' ) {
		$this->admin_notices[] = [
			'message' => $message,
			'type' => $type,
			'class' => $class,
		];
	}

	/**
	 * @since 0.5.0
	 * @param string $message
	 * @param array $field
	 * @return void
	 */
	public function add_error ( $message, $field ) {
		$notice = \sprintf( $this->strings['template_notice_error'], $message );
		$notice = $this->apply_filters( 'get_error_notice', $notice, $field, $message, $this );
		if ( ! empty( $notice ) ) {
			$this->add_notice( $notice, 'error' );
		}
	}

	/**
	 * @since 0.1.0
	 * @param string $hook_suffix
	 * @return void
	 */
	public function enqueue_scripts ( $hook_suffix ) {
		if ( $this->hook_suffix !== $hook_suffix ) return;
		foreach ( $this->scripts as $params ) {
			\call_user_func_array( 'wp_enqueue_script', $params );
		}
		foreach ( $this->styles as $params ) {
			\call_user_func_array( 'wp_enqueue_style', $params );
		}
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	public function handle_options () {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) return;
		if ( ! \is_admin() ) return;
		if ( $this->id !== ( $_REQUEST['page'] ?? '' ) ) return;

		$nonce = $_POST[ $this->get_nonce_name() ] ?? '';
		$action = $this->get_nonce_action();
		$invalid_nonce = ! \wp_verify_nonce( $nonce, $action );
		$invalid_user = ! \current_user_can( $this->capability );
		if ( $invalid_nonce || $invalid_user ) {
			\wp_die( \esc_html__( 'Sorry, you are not allowed to access this page.' ), 403 );
		}
		$options = [];
		$has_errors = false;

		foreach ( $this->fields as &$field ) {
			// skip fields that it's has input
			if ( ! $field['__is_input'] ) continue;

			$name = $field['name'];
			$value = $this->apply_filters( 'posted_value', $_POST[ $name ] ?? '', $name, $this );
			$field['value'] = $value;
			$field['error'] = null;

			// maybe validate
			$validate = $field['@validate'] ?? null;
			if ( \is_callable( $validate ) ) {
				try {
					$validate( $value, $field );
					$this->do_action( 'validate_field_' . $field['type'], $field, $this );
				} catch ( \Throwable $e ) {
					$error_message = $this->apply_filters(
						'get_error_message',
						$e->getMessage(),
						$e,
						$this
					);
					$this->add_error( $error_message, $field );
					$field['error'] = $error_message;
					$has_errors = true;
					continue;
				}
			}

			// maybe sanitize
			$sanitize = $field['@sanitize'] ?? null;
			if ( \is_callable( $sanitize ) ) {
				if ( \is_scalar( $value ) ) {
					$value = $sanitize( $value );
				} else {
					$value = \maybe_unserialize( $sanitize( \serialize( $value ) ) );
				}
				$field['value'] = $value;
			}

			$options[ $name ] = [
				'id' => $field['id'],
				'value' => $value
			];
		}

		$abort_update = (bool) $this->apply_filters( 'abort_update', $has_errors, $this );

		if ( $abort_update ) return;

		$options = \apply_filters_deprecated(
			$this->hook_prefix . 'updated_options',
			[ $options, $this ],
			'0.5.0',
			$this->hook_prefix . 'update_options'
		);
		$options = $this->apply_filters( 'update_options', $options, $this );
		if ( ! empty( $options ) ) {
			$updated = $this->update_options( $options );
			$this->do_action( 'after_update_options', $options, $updated, $this );
		}
	}

	/**
	 * @since 0.1.0
	 * @param array $options
	 * @return bool
	 */
	public function update_options ( $options ) {
		$prev = \get_option( $this->option_name );
		$values = \is_array( $prev ) ? $prev : [];
		foreach ( $options as $data ) {
			$values[ $data['id'] ] = $data['value'];
		}
		return \update_option( $this->option_name, $values );
	}

	/**
	 * @since 0.5.0
	 * @param array $options
	 * @param bool $updated
	 * @return void
	 */
	public function add_settings_saved_notice ( $options, $updated ) {
		$this->add_notice(
			$this->strings['options_updated'],
			'success',
			'is-dismissible ' . ( $updated ? 'options-updated' : '' ),
		);
	}

	/**
	 * @since 0.1.0
	 * @return string
	 */
	public function get_url () {
		$path = 'admin.php';
		// woocommerce submenu support
		if ( $this->menu_parent && 'woocommerce' !== $this->menu_parent ) {
			$path = $this->menu_parent;
		}
		return \admin_url( $path . '?page=' . $this->id );
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	public function render_page () {
		// force some <form> attributes
		$this->form_attributes['method'] = 'POST';
		$this->form_attributes['action'] = \remove_query_arg( '_wp_http_referer' );
		?>
		<div class="wrap <?php \esc_attr( $this->id ); ?>">
			<?php $this->do_action( 'before_render_form', $this ); ?>

			<form <?php echo self::parse_tag_atts( $this->form_attributes ); ?>>
				<?php $this->render_notices() ?>
				<?php $this->render_nonce() ?>
				<?php $this->render_all_fields() ?>
			</form>

			<?php $this->do_action( 'after_render_form', $this ); ?>

			<?php $this->render_credits() ?>
		</div>
		<?php
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	protected function render_notices () {
		foreach ( $this->admin_notices as $notice ) {
			$message = $notice['message'] ?? '';
			$type = $notice['type'] ?? 'error';
			$class = $notice['class'] ?? '';
			$page_class = 'options-page-' . $this->id;
			\printf(
				'<div class="%s inline notice notice-%s %s"><p>%s</p></div>',
				\esc_attr( $page_class ),
				\esc_attr( $type ),
				\esc_attr( $class ),
				$message
			);
		}
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	protected function render_nonce () {
		\wp_nonce_field(
			$this->get_nonce_action(),
			$this->get_nonce_name(),
		);
	}

	/**
	 * @since 0.1.0
	 * @return void
	 */
	protected function render_all_fields () {
		$this->table_is_open = false;
		foreach ( $this->fields as $field ) {
			$this->render_field( $field );
		}
	}

	protected function render_credits () {
		if ( ! $this->credits ) return;

		\add_filter( 'update_footer', function () {
			return '<em>Powered by <a href="https://github.com/luizbills/wp-options-page" rel="nofollow noopener" target="_blank">WP Options Page</a>.</em>';
		}, \PHP_INT_MAX - 1 );
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function maybe_open_or_close_table ( $field ) {
		$is_input = $field['__is_input'];
		if ( ! $this->table_is_open ) {
			if ( $is_input ) {
				echo '<table class="form-table" role="presentation">';
				$this->table_is_open = true;
			}
		} elseif ( ! $is_input ) {
			echo '</table>';
			$this->table_is_open = false;
		}
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return array
	 */
	protected function prepare_field ( $field ) {
		$defaults = [
			'id' => '',
			'type' => 'text',
			'title' => null,
			'description' => null,
			'default' => null,
			'attributes' => [],
			'@sanitize' => null,
			'@validate' => null,
			'__is_input' => true,
		];
		$field = \array_replace( $defaults, $field );

		switch ( $field['type'] ) {
			case 'title':
			case 'subtitle':
			case 'submit':
				$field['__is_input'] = false;
				break;
			case 'textarea':
				$field['@sanitize'] = $field['@sanitize'] ?? 'sanitize_textarea_field';
				break;
			default:
				$field['@sanitize'] = $field['@sanitize'] ?? 'sanitize_text_field';
				break;
		}

		$field = $this->apply_filters( 'prepare_field_' . $field['type'], $field, $this );
		$field = $this->apply_filters( 'prepare_field', $field, $this );

		$field['id'] = trim( $field['id'] );

		if ( $field['__is_input'] ) {
			$field['name'] = $this->get_field_name( $field );
			if ( 0 === strlen( $field['id'] ) ) {
				throw new \Exception( 'Missing field "id" property. All fields must have a string "id". ' );
			}
		}

		return $field;
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field ( $field ) {
		$type = $field['type'];
		$method = 'render_field_' . $type;
		$this->maybe_open_or_close_table( $field );
		$this->do_action( 'before_render_field', $field, $this );

		if ( $field['__is_input'] ) {
			$this->open_wrapper( $field );
		}

		try {
			if ( \method_exists( $this, $method ) ) {
				$this->$method( $field );
			} else {
				\ob_start();
				$this->do_action( 'render_field_'  . $type, $field, $this );
				/** @var string */
				$output = trim( \ob_get_clean() );
				if ( '' !== $output ) {
					echo $output;
				} else {
					throw new \Exception( "Invalid field type \"{$field['type']}\"" );
				}
			}
		} catch ( \Throwable $e ) {
			echo '<code>' . \esc_html( $e->getMessage() ) . '</code>';
		}

		if ( $field['__is_input'] ) {
			$this->close_wrapper( $field );
		}

		$this->do_action( 'after_render_field', $field, $this );
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	public function open_wrapper ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		?>
		<tr>
			<th scope="row">
				<label for="<?php echo \esc_attr( $name ); ?>"><?php echo \esc_html( $title ) ?></label>
			</th>
			<td>
		<?php
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	public function close_wrapper ( $field ) {
		?>
			</td>
		</tr>
		<?php
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_text ( $field ) {
		$name = $field['name'];
		$desc = $field['description'];
		$value = $this->get_field_value( $field );

		$atts = $field['attributes'] ?? [];
		$atts['type'] = $atts['type'] ?? 'text';
		$atts['id'] = $name;
		$atts['name'] = $name;
		$atts['value'] = $value;
		$atts['class'] = $atts['class'] ?? 'regular-text';
		$atts['placeholder'] = $atts['placeholder'] ?? false;
		$atts['aria-describedby'] = $desc ? \esc_attr( $name ) . '-description' : false;
		?>

		<input <?php echo self::parse_tag_atts( $atts ); ?>>

		<?php $this->do_action( 'after_field_input', $field, $this ); ?>

		<?php if ( $desc ) : ?>
		<p class="description" id="<?php echo \esc_attr( $name ); ?>-description"><?php echo $desc ?></p>
		<?php endif;
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_textarea ( $field ) {
		$name = $field['name'];
		$desc = $field['description'];
		$value = $this->get_field_value( $field );

		$atts = $field['attributes'] ?? [];
		$atts['id'] = $name;
		$atts['name'] = $name;
		$atts['class'] = $atts['class'] ?? 'large-text';
		$atts['placeholder'] = $atts['placeholder'] ?? false;
		$atts['rows'] = $atts['rows'] ?? 5;
		$atts['aria-describedby'] = $desc ? \esc_attr( $name ) . '-description' : false;
		?>

		<textarea <?php echo self::parse_tag_atts( $atts ); ?>><?php echo \esc_html( $value ); ?></textarea>

		<?php $this->do_action( 'after_field_input', $field, $this ); ?>

		<?php if ( $desc ) : ?>
		<p class="description" id="<?php echo \esc_attr( $name ); ?>-description"><?php echo $desc ?></p>
		<?php endif;
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_select ( $field ) {
		$name = $field['name'];
		$desc = $field['description'];
		$value = $this->get_field_value( $field );
		$options = $this->parse_options( $field['options'] ?? [] );

		$atts = $field['attributes'] ?? [];
		$atts['id'] = $name;
		$atts['name'] = $name;
		$atts['class'] = $atts['class'] ?? 'regular-text';
		$atts['aria-describedby'] = $desc ? \esc_attr( $name ) . '-description' : false;
		?>

		<select <?php echo self::parse_tag_atts( $atts ); ?>>
			<?php foreach ( $options as $opt_value => $opt_label ) : ?>
				<option value="<?php echo \esc_attr( $opt_value ); ?>" <?php \selected( $opt_value, $value ) ?>><?php echo \esc_html( $opt_label ) ?></option>
			<?php endforeach; ?>
		</select>

		<?php $this->do_action( 'after_field_input', $field, $this ); ?>

		<?php if ( $desc ) : ?>
		<p class="description" id="<?php echo \esc_attr( $name ); ?>-description"><?php echo $desc ?></p>
		<?php endif;
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_radio ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$name = $field['name'];
		$desc = $field['description'];
		$value = $this->get_field_value( $field );
		$options = $this->parse_options( $field['options'] ?? [] );

		$atts = $field['attributes'] ?? [];
		$atts['type'] = 'radio';
		$atts['name'] = $name;

		unset( $atts['id'] );
		unset( $atts['value'] );
		unset( $atts['checked'] );
		?>

		<fieldset>
			<legend class="screen-reader-text"><span><?php echo \esc_html( \strip_tags( $title ) ); ?></span></legend>

			<?php foreach ( $options as $opt_value => $opt_label ) :
				$option_id = \esc_attr( $id . '_' . $opt_value ); ?>
				<label for="<?php echo \esc_attr( $option_id ) ?>">
					<input <?php echo self::parse_tag_atts( $atts ); ?> id="<?php echo \esc_attr( $option_id ) ?>" value="<?php echo \esc_attr( $opt_value ) ?>" <?php \checked( $opt_value, $value ); ?>>
					<span class="option-label"><?php echo $opt_label ?></span>
				</label>
				<br>
			<?php endforeach ?>

			<?php $this->do_action( 'after_field_input', $field, $this ); ?>

			<?php if ( $desc ) : ?>
			<p class="description"><?php echo $desc ?></p>
			<?php endif ?>
		</fieldset>

		<?php
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_checkbox ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$name = $field['name'];
		$desc = $field['description'];
		$label = $field['label'] ?? $this->strings['checkbox_enable'];
		$value = (bool) $this->get_field_value( $field );

		$atts = $field['attributes'] ?? [];
		$atts['type'] = 'checkbox';
		$atts['id'] = $name;
		$atts['name'] = $name;
		$atts['value'] = '1';

		unset( $atts['value'] );
		unset( $atts['checked'] );
		?>

		<fieldset>
			<legend class="screen-reader-text">
				<span><?php echo \esc_html( strip_tags( $title ) ); ?></span>
			</legend>
			<label for="<?php echo \esc_attr( $name ); ?>">
				<input <?php echo self::parse_tag_atts( $atts ); ?> <?php \checked( $value ); ?>>
				<span class="option-label"><?php echo $label ?></span>
			</label>

			<?php $this->do_action( 'after_field_input', $field, $this ); ?>

			<?php if ( $desc ) : ?>
			<p class="description"><?php echo $desc ?></p>
			<?php endif ?>
		</fieldset>

		<?php
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_checkboxes ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$desc = $field['description'];
		$options = $this->parse_options( $field['options'] ?? [] );
		$value = $this->get_field_value( $field );
		$value = is_array( $value ) ? $value : [ $value ];

		$atts = $field['attributes'] ?? [];
		$atts['type'] = 'checkbox';
		$atts['name'] = $name . '[]';

		unset( $atts['id'] );
		unset( $atts['value'] );
		unset( $atts['checked'] );
		?>

		<fieldset>
			<legend class="screen-reader-text"><span><?php echo \esc_html( strip_tags( $title ) ); ?></span></legend>

			<?php foreach ( $options as $opt_value => $opt_label ) :
				$option_id = \esc_attr( $id . '_' . $opt_value );
				$checked = \in_array( $opt_value, $value ) ? 'checked' : '' ?>
				<label for="<?php echo \esc_attr( $option_id ) ?>">
					<input <?php echo self::parse_tag_atts( $atts ); ?> id="<?php echo \esc_attr( $option_id ) ?>" value="<?php echo \esc_attr( $opt_value ) ?>" <?php echo $checked ?>>
					<span class="option-label"><?php echo $opt_label ?></span>
				</label>
				<br>
			<?php endforeach ?>

			<?php $this->do_action( 'after_field_input', $field, $this ); ?>

			<?php if ( $desc ) : ?>
			<p class="description"><?php echo $desc ?></p>
			<?php endif ?>
		</fieldset>

		<?php
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_title ( $field ) {
		$desc = $field['description'];
		$tag = \esc_html( $field['tag'] ?? 'h2' );
		$atts = $field['attributes'] ?? [];

		$title_html = "<{$tag} " . self::parse_tag_atts( $atts ) . '>';
		$title_html .= $field['title'] ?? '';
		$title_html .= "</{$tag}>";
		?>
		<?php echo $title_html; ?>
		<?php if ( $desc ) : ?>
		<p><?php echo $desc ?></p>
		<?php endif ?>
		<?php
	}

	/**
	 * @since 0.1.0
	 * @param array $field
	 * @return void
	 */
	protected function render_field_submit ( $field ) {
		$title = $field['title'] ?? $this->strings['submit_button_label'];
		$atts = $field['attributes'] ?? [];

		$atts['type'] = 'submit';
		$atts['name'] = $atts['name'] ?? 'submit';
		$atts['id'] = $atts['id'] ?? $atts['name'];
		$atts['class'] = $atts['class'] ?? 'button button-primary';
		$atts['value'] = $atts['value'] ?? $title;

		?>
		<p class="submit">
			<?php $this->do_action( 'before_submit_button', $this ) ?>

			<button <?php echo self::parse_tag_atts( $atts );  ?>>
				<?php echo \esc_html( $title ); ?>
			</button>

			<?php $this->do_action( 'after_submit_button', $this ) ?>
		</p>
		<?php
	}

	/**
	 * @since 0.2.0
	 * @param string $hook_name
	 * @param callable|string|array $callback
	 * @param integer $priority
	 * @param integer $args
	 * @return bool
	 */
	public function add_action ( $hook_name, $callback, $priority = 10, $args = 1 ) {
		return \add_filter( $this->hook_prefix . $hook_name, $callback, $priority, $args );
	}

	/**
	 * @since 0.2.0
	 * @param string $hook_name
	 * @param callable|string|array $callback
	 * @param integer $priority
	 * @param integer $args
	 * @return bool
	 */
	public function add_filter ( $hook_name, $callback, $priority = 10, $args = 1 ) {
		return \add_filter( $this->hook_prefix . $hook_name, $callback, $priority, $args );
	}

	/**
	 * @since 0.3.0
	 * @param string $hook_name
	 * @param mixed ...$args
	 * @return void
	 */
	public function do_action ( $hook_name, ...$args ) {
		\do_action( $this->hook_prefix . $hook_name, ...$args );
	}

	/**
	 * @since 0.3.0
	 * @param string $hook_name
	 * @param mixed $value
	 * @param mixed ...$args
	 * @return mixed The filtered value
	 */
	public function apply_filters ( $hook_name, $value, ...$args ) {
		return \apply_filters( $this->hook_prefix . $hook_name, $value, ...$args );
	}

	/**
	 * Check if this instance page supports a given feature.
	 *
	 * @since 0.3.0
	 * @param string $feature string The name of a feature to test support for.
	 * @return bool True if the gateway supports the feature, false otherwise.
	 */
	public function supports ( $feature ) {
		return isset( $this->supports[ $feature ] ) || \in_array( $feature, $this->supports );
	}

	/**
	 * @since 0.3.0
	 * @param array $atts
	 * @return string
	 */
	public static function parse_tag_atts ( $atts ) {
		$list = [];
		foreach ( $atts as $name => $value ) {
			// allow anonymous functions
			if ( is_callable( $value ) && is_object( $value ) ) {
				$value = call_user_func( $value );
			}
			// don't allow objects or arrays
			elseif ( \is_object( $value ) || is_array( $value ) ) {
				throw new \Exception( "Invalid value at key \"$name\" in " . __METHOD__ . ': array or objects are not allowed.' );
			}

			// delete the attribute with value null/false
			if ( in_array( $value, [ false, null ], true ) ) continue;

			// display only the attribute name if has empty string or TRUE
			if ( true === $value ) $value = '';

			$attr = \esc_html( $name );
			$value = strval( $value );
			if ( '' !== $value ) {
				$attr .= '="' . \esc_attr( $value ) . '"';
			}
			$list[] = $attr;
		}
		return \implode( ' ', $list );
	}

	/**
	 * @since 0.7.0
	 * @param array $options
	 * @return array
	 */
	protected function parse_options ( $options ) {
		if ( is_callable( $options ) ) {
			$options = (array) call_user_func( $options );
		}
		return $options;
	}
}
