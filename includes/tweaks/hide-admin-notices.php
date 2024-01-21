<?php
/**
 * Hide annoying notices in WordPress admin
 *
 * @package wp-tweaks
 */

add_action( 'admin_head', 'wp_tweaks_hide_admin_notices', 20 );
function wp_tweaks_hide_admin_notices () {
	global $pagenow;
	$ignored_pages = apply_filters(
		'wp_tweaks_hide_admin_notices_ignored_pages',
		[ 'plugins.php', 'site-health.php' ]
	);

	if ( in_array( $pagenow, $ignored_pages, true ) ) return;

	$css_display = apply_filters(
		'wp_tweaks_hide_admin_notices_css_display',
		'block'
	);

	ob_start();	?>

	<style id="wp_tweaks_hide_admin_notices">
		.notice:not(.inline) {
			display: none;
		}

		/* allowlist */
		#wpwrap .wrap .updated,
		#wpwrap .wrap .notice.update-message,
		#wpwrap .wrap .notice[id="message"],
		#wpwrap .wrap .notice[id="message1"],
		#wpwrap .wrap .notice[id="message2"],
		#wpwrap .wrap .notice[id="message3"],
		#wpwrap .wrap .notice[id="message4"],
		#wpwrap .wrap .notice[id="message5"],
		#wpwrap .wrap .notice[id="message6"],
		#wpwrap .wrap .notice[id="message7"],
		#wpwrap .wrap .notice[id="message8"],
		#wpwrap .wrap .notice[id^="setting-error"] {
			display: <?= esc_html( $css_display ) ?>;
		}
	</style>

	<?php echo apply_filters(
		'wp_tweaks_hide_admin_notices_css_rules',
		ob_get_clean()
	);
}

// don't hide WP Crontrol notices
add_filter( 'wp_tweaks_hide_admin_notices_css_rules', function ( $css ) {
	if ( defined( "Crontrol\\WP_CRONTROL_VERSION" ) ) {
		$custom_css = "
		#wpwrap .wrap .notice#crontrol-late-message,
		#wpwrap .wrap .notice#crontrol-status-notice,
		#wpwrap .wrap .notice#crontrol-status-error,
		#wpwrap .wrap .notice#crontrol-timezon-warning,
		#wpwrap .wrap .notice#crontrol-event-not-found,
		#wpwrap .wrap .notice#crontrol-message,
		";
		$find = '/* allowlist */';
		$css = str_replace( $find, "{$find}{$custom_css}", $css );
	}
	return $css;
} );
