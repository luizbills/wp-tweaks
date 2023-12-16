<?php
/**
 * Hide annoying notices in WordPress admin
 *
 * @package wp-tweaks
 */

add_action( 'admin_head', 'wp_tweaks_hide_admin_notices', 20 );
function wp_tweaks_hide_admin_notices () {
	global $pagenow;
	$ignored_pages = [ 'plugins.php', 'post.php', 'post-new.php' ];
	if ( in_array( $pagenow, $ignored_pages, true ) ) return;

	$display = apply_filters(
		'wp_tweaks_hide_admin_notices_css_display',
		'block!important'
	);

	ob_start();	?>

	<style>
		.notice:not(.hidden) {
			display: none!important;
		}

		/* Notices with this selectors, will always appears */
		#setting-error-settings_updated,
		.notice-success.settings-error,
		.notice.error,
		.notice.updated,
		.notice.update-message,
		.notice[id^="message"],
		.notice.inline:not(.hidden) {
			display: <?php echo esc_html( $display ); ?>;
		}
	</style>

	<?php echo apply_filters(
		'wp_tweaks_hide_admin_notices_css_rules',
		ob_get_clean()
	);
}

