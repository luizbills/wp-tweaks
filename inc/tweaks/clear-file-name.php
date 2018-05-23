<?php
/**
 * Produces clean filenames for uploads
 * https://github.com/WPArtisan/wpartisan-filename-sanitizer
 *
 * @author https://wpartisan.me/
 * @version 0.0.6
 *
 * @package wp-tweaks
 */

add_filter( 'sanitize_file_name', 'wp_tweaks_clear_file_name' );
function wp_tweaks_clear_file_name ( $filename ) {
	$sanitized_filename = remove_accents( $filename ); // Convert to ASCII

	// Standard replacements
	$invalid = [
		' '   => '-',
		'%20' => '-',
		'_'   => '-',
	];

	$sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
	$sanitized_filename = preg_replace( '/[^A-Za-z0-9-\. ]/', '', $sanitized_filename ); // Remove all non-alphanumeric except .
	$sanitized_filename = preg_replace( '/\.(?=.*\.)/', '', $sanitized_filename ); // Remove all but last .
	$sanitized_filename = preg_replace( '/-+/', '-', $sanitized_filename ); // Replace any more than one - in a row
	$sanitized_filename = str_replace( '-.', '.', $sanitized_filename ); // Remove last - if at the end
	$sanitized_filename = strtolower( $sanitized_filename ); // Lowercase

	/**
	 * Apply any more sanitization using this filter
	 *
	 * @var string $sanitized_filename The sanitized filename
	 * @var string $filename           Original filename
	 */
	$sanitized_filename = apply_filters( 'wp_tweaks_sanitize_file_name', $sanitized_filename, $filename );

	return $sanitized_filename;
}
