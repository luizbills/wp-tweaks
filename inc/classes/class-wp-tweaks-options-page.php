<?php
/**
 * WP_Tweaks_Options_Page class
 * based on https://github.com/luizbills/wp-options-page
 *
 * @package WP_Options_Page
 * @author Luiz Bills <luizbills@pm.me>
 * @version 1.0.0
 * @see https://github.com/luizbills/wp-options-page
 */

if ( ! defined( 'WPINC' ) ) die();
if ( class_exists( 'WP_Tweaks_Options_Page' ) ) return;

class WP_Tweaks_Options_Page {
	/**
	 * @var string
	 */
	public $id = null;

	/**
	 * @var string
	 */
	public $menu_parent = null;

	/**
	 * @var string
	 */
	public $page_title = null;

	/**
	 * @var string
	 */
	public $page_description = null;

	/**
	 * @var string
	 */
	public $insert_title = true;

	/**
	 * @var string
	 */
	public $menu_title = null;

	/**
	 * @var int|float
	 */
	public $menu_position = null;

	/**
	 * @var int|float
	 */
	public $menu_priority = 10;

	/**
	 * @var string
	 */
	public $menu_icon = 'dashicons-admin-generic';

	/**
	 * @var string
	 */
	public $capability = 'manage_options';

	/**
	 * @var string
	 */
	public $option_name = null;

	/**
	 * @var string
	 */
	public $field_prefix = null;

	/**
	 * @var string
	 */
	public $hook_prefix = null;

	/**
	 * @var string
	 */
	public $hook_suffix = null;

	/**
	 * @var array
	 */
	public $fields = null;

	/**
	 * @var string
	 */
	public $strings = [];

	/**
	 * @var array
	 */
	protected $admin_notices = [];

	/**
	 * @var bool
	 */
	protected $table_is_open = null;

	/** @var array */
	protected $scripts = [];

	/** @var array */
	protected $styles = [];

	/** @var array */
	protected $default_values = [];

	/**
	 * @return string
	 */
	public function get_url () {
		$path = 'admin.php';
		// woocommerce submenu support
		if ( $this->menu_parent && 'woocommerce' !== $this->menu_parent ) {
			$path = $this->menu_parent;
		}
		return admin_url( $path . '?page=' . $this->id );
	}

	/**
	 * @param array $field
	 * @return mixed
	 */
	public function get_field_value ( $field ) {
		if ( isset( $field['value'] ) ) {
			return $field['value'];
		}
		return apply_filters(
			$this->hook_prefix . 'get_field_value',
			$this->get_option( $field['id'] ),
			$field,
			$this
		);
	}

	/**
	 * @param string $field_id
	 * @return mixed
	 */
	public function get_field_default_value ( $field_id ) {
		return apply_filters(
			$this->hook_prefix . 'get_field_default_value',
			$this->default_values[ $field_id ] ?? false,
			$field_id,
			$this
		);
	}

	/**
	 * @param string $field_id
	 * @return mixed
	 */
	public function get_option ( $field_id ) {
		$default = $this->get_field_default_value( $field_id );
		$options = get_option( $this->option_name, [] );
		return $options[ $field_id ] ?? $default;
	}

	/**
	 * @param array $field
	 * @return string
	 */
	public function get_field_name ( $field ) {
		return $this->field_prefix . $field['id'];
	}

	/**
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
	 * @return string
	 */
	public function get_nonce_action () {
		return $this->field_prefix . 'nonce';
	}

	/**
	 * @return string
	 */
	public function get_nonce_name () {
		return '_nonce';
	}

	/**
	 * @param string $message
	 * @param string $type Should be "error", "success", "warning" or "info".
	 * @param string $class
	 * @return void
	 */
	public function add_notice ( $message, $type = 'error', $class = '' ) {
		$this->admin_notices[] = [
			'message' => $message,
			'type' => $type,
			'class' => $class,
		];
	}

	/**
	 * @return void
	 */
	public function init () {
		if ( ! did_action( 'init' ) ) {
			throw new \Exception( 'Please, don\'t use the ' . get_class( $this ) . ' class before "init" hook.' );
		}
		if ( ! $this->id ) {
			throw new \Exception( 'Missing $id in ' . get_class( $this ) );
		}

		$this->menu_title = $this->menu_title ?? $this->id;
		$this->page_title = $this->page_title ?? $this->menu_title;
		$this->option_name = $this->option_name ?? $this->id . '_options';
		$this->field_prefix = $this->field_prefix ?? $this->id . '_';
		$this->hook_prefix = $this->hook_prefix ?? $this->field_prefix;

		$this->strings = array_merge(
			[
				'notice_error' => '<strong>Error</strong>: %s',
				'checkbox_enable' => 'Enable',
				'options_updated' => '<strong>' . __( 'Settings saved.' ) . '</strong>',
			],
			$this->strings
		);

		$this->init_fields();
		$this->init_hooks();
		$this->handle_options();
	}

	/**
	 * @return void
	 */
	protected function init_hooks () {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_menu', [ $this, 'add_menu_page' ], $this->menu_priority );
	}

	/**
	 * @return void
	 */
	public function init_fields () {
		$this->fields = apply_filters(
			$this->hook_prefix . 'get_fields',
			$this->fields ? $this->fields : $this->get_fields(),
			$this
		);

		foreach ( $this->fields as $field ) {
			$id = $field['id'] ?? false;
			if ( $id ) $this->default_values[ $id ] = $field['default'] ?? null;
		}

		if ( $this->insert_title ) {
			array_unshift( $this->fields, [
				'type' => 'title',
				'title' => $this->page_title,
				'description' => $this->page_description
			] );
		}

		$has_submit = false;
		foreach ( $this->fields as $key => $field ) {
			$this->fields[ $key ] = $this->prepare_field( $field );
			if ( ! $has_submit && 'submit' === $field['type'] ) $has_submit = true;
		}

		if ( ! $has_submit ) {
			$this->fields[] = $this->prepare_field( [
				'type' => 'submit'
			] );
		}
	}

	/**
	 * @param array $field
	 * @return array
	 */
	protected function prepare_field ( $field ) {
		$defaults = [
			'id' => null,
			'type' => 'text',
			'title' => null,
			'title_icon' => null,
			'description' => '',
			'options' => [],
			'default' => '',
			'__sanitize' => null,
			'__validate' => null,
			'__is_input' => true,
		];
		$field = array_merge( $defaults, $field );
		$field['name'] = $this->get_field_name( $field );

		switch ( $field['type'] ) {
			case 'title':
			case 'subtitle':
			case 'submit':
				$field['__is_input'] = false;
				break;
			case 'textarea':
				$field['__sanitize'] = 'sanitize_textarea_field';
				break;
			default:
				$field['__sanitize'] = 'sanitize_text_field';
				break;
		}

		return apply_filters( $this->hook_prefix . 'prepare_field', $field );
	}

	/**
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
	}

	/**
	 * @return array
	 */
	public function get_fields () {
		return [
			[
				'title' => $this->page_title,
				'description' => 'Overrides the <code>' . __METHOD__ . '</code> to display your settings fields',
				'type' => 'title',
			],
		];
	}

	/**
	 * @param string $hook_suffix
	 * @return void
	 */
	public function enqueue_scripts ( $hook_suffix ) {
		if ( $this->hook_suffix !== $hook_suffix ) return;
		foreach ( $this->scripts as $params ) {
			call_user_func_array( 'wp_enqueue_script', $params );
		}
		foreach ( $this->styles as $params ) {
			call_user_func_array( 'wp_enqueue_style', $params );
		}
	}

	/**
	 * @return void
	 */
	public function handle_options () {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) return;
		if ( ! is_admin() ) return;
		if ( $this->id !== ( $_REQUEST['page'] ?? '' ) ) return;

		$nonce = $_REQUEST[ $this->get_nonce_name() ] ?? '';
		$action = $this->get_nonce_action();
		$invalid_nonce = ! wp_verify_nonce( $nonce, $action );
		$invalid_user = ! current_user_can( $this->capability );
		if ( $invalid_nonce || $invalid_user ) {
			wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );
		}

		$options = [];

		foreach ( $this->fields as &$field ) {
			// skip fields that it's has input
			if ( ! $field['__is_input'] ) continue;

			$name = $field['name'];
			$value = $_POST[ $name ] ?? '';

			// maybe validate
			if ( $field['__validate'] ) {
				$error = false;
				try {
					$field['__validate']( $value, $field );
				} catch ( \Throwable $e ) {
					$error = $e->getMessage();
					$message = $this->format_error_message( $error, $field );
					$this->add_notice( $message, 'error', 'field-' . $name );
					$field['error'] = $error;
					$field['value'] = $value;
					continue;
				}
			}

			// maybe sanitize
			$sanitize = $field['__sanitize'] ?? '';
			if ( $sanitize ) {
				if ( is_array( $value ) ) {
					$value = maybe_unserialize( $sanitize( serialize( $value ) ) );
				} else {
					$value = $sanitize( $value );
				}
			}

			$field['value'] = $value;
			$field['error'] = null;
			$options[ $name ] = [
				'id' => $field['id'],
				'value' => $value
			];
		}

		if ( count( $options ) > 0 ) {
			$updated = $this->update_options( $options );

			$this->add_notice(
				$this->strings['options_updated'],
				'success',
				'is-dismissible ' . ( $updated ? 'options-updated' : '' ),
			);
		}
	}

	/**
	 * @param string $error
	 * @param array $field
	 * @return string
	 */
	public function format_error_message ( $error, $field ) {
		return sprintf(
			$this->strings['notice_error'],
			$error
		);
	}

	/**
	 * @param array $options
	 * @return bool
	 */
	public function update_options ( $options ) {
		$old = get_option( $this->option_name );
		$values = is_array( $old ) ? $old : [];
		foreach ( $options as $data ) {
			$values[ $data['id'] ] = $data['value'];
		}
		return update_option( $this->option_name, $values );
	}

	/**
	 * @return void
	 */
	public function render_page () {
		?>
		<div class="wrap">
			<?php do_action( $this->hook_prefix . 'before_render_form', $this ); ?>

			<form method="post" action="<?= esc_attr( remove_query_arg( '_wp_http_referer' ) ) ?>" novalidate="novalidate">
				<?php $this->render_notices() ?>
				<?php $this->render_nonce() ?>
				<?php $this->render_all_fields() ?>
			</form>

			<?php do_action( $this->hook_prefix . 'after_render_form', $this ); ?>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	protected function render_notices () {
		foreach ( $this->admin_notices as $notice ) {
			$message = $notice['message'] ?? '';
			$type = $notice['type'] ?? 'error';
			$class = $notice['class'] ?? '';
			$page_class = 'options-page-' . $this->id;
			printf(
				'<div class="%s notice notice-%s %s"><p>%s</p></div>',
				esc_attr( $page_class ),
				esc_attr( $type ),
				esc_attr( $class ),
				$message
			);
		}
	}

	/**
	 * @return void
	 */
	protected function render_nonce () {
		wp_nonce_field(
			$this->get_nonce_action(),
			$this->get_nonce_name(),
		);
	}

	/**
	 * @return void
	 */
	protected function render_all_fields () {
		$this->table_is_open = false;
		foreach ( $this->fields as $field ) {
			$this->render_field( $field );
		}
	}

	/**
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
	 * @param string $icon
	 * @return string
	 */
	protected function get_icon ( $icon ) {
		$icon = esc_attr( trim( $icon ) );
		if ( 0 === strpos( $icon, 'dashicons-' ) ) {
			return " <span class=\"dashicons $icon\" aria-hidden=\"true\"></span>";
		}
		if ( 0 === strpos( $icon, 'data:image/' ) || 0 === strpos( $icon, 'https://' ) ) {
			return " <img src=\"$icon\" aria-hidden=\"true\">";
		}
		if ( $icon ) {
			return " <span class=\"$icon\" aria-hidden=\"true\"></span>";
		}
		return '';
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field ( $field ) {
		$type = $field['type'];
		$method = 'render_field_' . $type;
		$this->maybe_open_or_close_table( $field );
		do_action( $this->hook_prefix . 'before_render_field', $field, $this );
		if ( method_exists( $this, $method ) ) {
			$this->$method( $field );
		} else {
			ob_start();
			do_action( $this->hook_prefix . 'render_field_'  . $type, $field, $this );
			$output = ob_get_clean();
			if ( $output ) {
				echo $output;
			} else {
				throw new Exception( "Invalid field type \"{$field['type']}\" in " . get_class( $this ) );
			}
		}
		do_action( $this->hook_prefix . 'after_render_field', $field, $this );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function open_wrapper ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$icon = $this->get_icon( $field['title_icon'] );
		?>
		<tr>
			<th scope="row">
				<label for="<?= esc_attr( $name ); ?>"><?= esc_html( $title ) . $icon ?></label>
			</th>
			<td>
		<?php
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function close_wrapper ( $field ) {
		?>
			</td>
		</tr>
		<?php
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_text ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$value = $this->get_field_value( $field );
		$class = $field['class'] ?? 'regular-text';
		$type = $field['input_type'] ?? 'text';
		$placeholder = $field['placeholder'] ?? '';
		$desc = $field['description'];
		$describedby = $desc ? 'aria-describedby="' . esc_attr( $id ) . '-description"' : '';

		$this->open_wrapper( $field );
		?>

		<input name="<?= esc_attr( $name ); ?>" type="<?= esc_attr( $type ) ?>" id="<?= esc_attr( $name ); ?>" <?= $describedby ?> value="<?= esc_attr( $value ); ?>" class="<?= esc_attr( $class ); ?>" placeholder="<?= esc_attr( $placeholder ) ?>">

		<?php do_action( $this->hook_prefix . 'after_field_input', $field ); ?>

		<?php if ( $desc ) : ?>
		<p class="description" id="<?= esc_attr( $name ); ?>-description"><?= $desc ?></p>
		<?php endif ?>

		<?php $this->close_wrapper( $field );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_textarea ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$value = $this->get_field_value( $field );
		$class = $field['class'] ?? 'large-text';
		$placeholder = $field['placeholder'] ?? '';
		$desc = $field['description'];
		$describedby = $desc ? 'aria-describedby="' . esc_attr( $id ) . '-description"' : '';

		$this->open_wrapper( $field );
		?>

		<textarea name="<?= esc_attr( $name ); ?>" id="<?= esc_attr( $name ); ?>" <?= $describedby ?> class="<?= esc_attr( $class ); ?>" placeholder="<?= esc_attr( $placeholder ) ?>" rows="6"><?= esc_html( $value ); ?></textarea>

		<?php do_action( $this->hook_prefix . 'after_field_input', $field ); ?>

		<?php if ( $desc ) : ?>
		<p class="description" id="<?= esc_attr( $name ); ?>-description"><?= $desc ?></p>
		<?php endif; ?>

		<?php $this->close_wrapper( $field );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_select ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$value = $this->get_field_value( $field );
		$class = $field['class'] ?? '';
		$desc = $field['description'] ?? false;
		$describedby = $desc ? 'aria-describedby="' . esc_attr( $id ) . '-description"' : '';

		$this->open_wrapper( $field );
		?>

		<select name="<?= esc_attr( $name ); ?>" id="<?= esc_attr( $name ); ?>" <?= $describedby ?> class="<?= esc_attr( $class ); ?>">
			<?php foreach ( $field['options'] as $opt_value => $opt_label ) : ?>
				<option value="<?= esc_attr( $opt_value ); ?>" <?php selected( $opt_value, $value ) ?>><?= esc_html( $opt_label ) ?></option>
			<?php endforeach; ?>
		</select>

		<?php do_action( $this->hook_prefix . 'after_field_input', $field ); ?>

		<?php if ( $desc ) : ?>
		<p class="description" id="<?= esc_attr( $id ); ?>-description"><?= $desc ?></p>
		<?php endif ?>

		<?php $this->close_wrapper( $field );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_radio ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$desc = $field['description'];
		$options = $field['options'];
		$value = $this->get_field_value( $field );

		$this->open_wrapper( $field );
		?>

		<fieldset>
			<legend class="screen-reader-text"><span><?= esc_html( strip_tags( $title ) ); ?></span></legend>

			<?php foreach ( $options as $key => $label ) :
				$option_id = esc_attr( $id . '_' . $key ); ?>
				<label for="<?= $option_id ?>">
					<input name="<?= esc_attr( $name ) ?>" type="radio" id="<?= esc_attr( $option_id ) ?>" value="<?= esc_attr( $key ) ?>" <?php checked( $key, $value ); ?>>
					<?= $label ?>
				</label>
				<br>
			<?php endforeach ?>

			<?php do_action( $this->hook_prefix . 'after_field_input', $field ); ?>

			<?php if ( $desc ) : ?>
			<p class="description"><?= $desc ?></p>
			<?php endif ?>
		</fieldset>

		<?php $this->close_wrapper( $field );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_checkbox ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$value = boolval( $this->get_field_value( $field ) );
		$desc = $field['description'];
		$label = $field['label'] ?? $this->strings['checkbox_enable'];

		$this->open_wrapper( $field );
		?>

		<fieldset>
			<legend class="screen-reader-text"><span><?= esc_html( strip_tags( $title ) ); ?></span></legend>
			<label for="<?= $name ?>">
				<input name="<?= esc_attr( $name ) ?>" type="checkbox" id="<?= esc_attr( $name ) ?>" value="1" <?php checked( $value ); ?> />
				<?= $label ?>
			</label>

			<?php do_action( $this->hook_prefix . 'after_field_input', $field ); ?>

			<?php if ( $desc ) : ?>
			<p class="description"><?= $desc ?></p>
			<?php endif ?>
		</fieldset>

		<?php $this->close_wrapper( $field );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_checkboxes ( $field ) {
		$id = $field['id'];
		$name = $field['name'];
		$title = $field['title'] ?? $id;
		$desc = $field['description'];
		$options = $field['options'];
		$value = $this->get_field_value( $field );
		$value = is_array( $value ) ? $value : [ $value ];

		$this->open_wrapper( $field );
		?>

		<fieldset>
			<legend class="screen-reader-text"><span><?= esc_html( strip_tags( $title ) ); ?></span></legend>

			<?php foreach ( $options as $key => $label ) :
				$option_id = esc_attr( $id . '_' . $key );
				$checked = in_array( $key, $value ) ? 'checked="checked"' : '' ?>
				<label for="<?= $option_id ?>">
					<input name="<?= esc_attr( $name ) . '[]' ?>" type="checkbox" id="<?= esc_attr( $option_id ) ?>" value="<?= esc_attr( $key ) ?>" <?= $checked ?>>
					<?= $label ?>
				</label>
				<br>
			<?php endforeach ?>

			<?php do_action( $this->hook_prefix . 'after_field_input', $field ); ?>

			<?php if ( $desc ) : ?>
			<p class="description"><?= $desc ?></p>
			<?php endif ?>
		</fieldset>

		<?php $this->close_wrapper( $field );
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_title ( $field ) {
		$id = $this->field_prefix . $field['id'];
		$icon = $this->get_icon( $field['title_icon'] );
		$desc = $field['description'];
		?>
		<h1 id="<?= esc_attr( $id ) ?>"><?= esc_html( $field['title'] ) . $icon ?></h1>
		<?php if ( $desc ) : ?>
		<p><?= $desc ?></p>
		<?php endif ?>
		<?php
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_subtitle ( $field ) {
		$id = $this->field_prefix . $field['id'];
		$icon = $this->get_icon( $field['title_icon'] );
		$desc = $field['description'];
		?>
		<h1 id="<?= esc_attr( $id ) ?>"><?= esc_html( $field['title'] ) . $icon ?></h1>
		<?php if ( $desc ) : ?>
		<p><?= $desc ?></p>
		<?php endif ?>
		<?php
	}

	/**
	 * @param array $field
	 * @return void
	 */
	protected function render_field_submit ( $field ) {
		$title = $field['title'] ?? __( 'Save Changes' );
		$class = $field['class'] ?? 'button button-primary';
		?>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="<?= esc_attr( $class ) ?>" value="<?= esc_attr( $title ) ?>">
		</p>
		<?php
	}
}
