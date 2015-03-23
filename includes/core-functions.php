<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', 'solidres_do_output_buffer' );
function solidres_do_output_buffer() {
	ob_start();
}

/**
 * Get plugin version
 *
 * @param $plugin_file
 * @return mixed
 */
function solidres_check_version( $plugin_file ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$plugin_data = get_plugins();
	return $plugin_data[$plugin_file]['Version'];
}

/**
 * Check plugin status
 *
 * @param $plugin_file
 * @return mixed
 */
function solidres_check_plugin( $plugin_file ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$file_path = WP_PLUGIN_DIR.'/'.$plugin_file;
	if( file_exists( $file_path ) ) {
		$version = solidres_check_version( $plugin_file );
		if ( is_plugin_active( $plugin_file ) ) {
			$result['status'] = 1;
			$result['message'] = __( '<span class="sr_enable">Version '.$version.' is enabled</span>', 'solidres' );
		}
		else{
			$result['status'] = 0;
			$result['message'] = __( '<span class="sr_warning">Version '.$version.' is not enabled</span>', 'solidres' );
		}
	}
	else {
		$result['status'] = 2;
		$result['message'] = __( '<span class="sr_disable">Not installed</span>', 'solidres' );
	}
	return $result;
}

/**
 * Convert date format for valid date of Coupon form
 *
 * @param $symbol
 * @param $string
 * @return string
 */
function solidres_valid_date_format( $symbol, $string ){
	$date_part = explode( $symbol, $string );
	return $date_part[2] . '-' . $date_part[1] . '-' . $date_part[0];
}

/**
 * Convert date format for valid date of Coupon form
 *
 * @param $string
 * @param $number
 * @return mixed
 */
function solidres_split_date_data( $string, $number ){
	$date_data = str_split( $string, $number );
	return $date_data[0];
}

/**
 * Replace the 'NULL' string with NULL
 *
 * @param $query
 * @return mixed
 */
function solidres_wp_db_null_value( $query ) {
	return str_replace( "'NULL'", 'NULL', $query );
}

/**
 * Convert slug to String
 *
 * @param $string
 * @return string
 */
function solidres_convertslugtostring( $string ) {
	$string = str_replace( '_', ' ', $string );
	return ucfirst( $string );
}

/**
 * Set html type for email content
 *
 * @return string
 */
function solidres_set_html_content_type() {
	return 'text/html';
}

/**
 * Get template part
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 * @return void
 */
function solidres_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/solidres/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", solidres()->template_path() . "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( solidres()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
		$template = solidres()->plugin_path() . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/solidres/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", solidres()->template_path() . "{$slug}.php" ) );
	}

	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( 'solidres_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function solidres_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$located = solidres_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin
	$located = apply_filters( 'solidres_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'solidres_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'solidres_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function solidres_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = solidres()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = solidres()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found
	return apply_filters('solidres_locate_template', $template, $template_name, $template_path);
}

function solidres_template_debug_mode() {
	if ( ! defined( 'SOLIDRES_TEMPLATE_DEBUG_MODE' ) ) {
		$tool_options = get_option( 'solidres_tools', array() );
		if ( ! empty( $tool_options['enable_template_debug'] ) && current_user_can( 'manage_options' ) ) {
			define( 'SOLIDRES_TEMPLATE_DEBUG_MODE', true );
		} else {
			define( 'SOLIDRES_TEMPLATE_DEBUG_MODE', false );
		}
	}
}
add_action( 'after_setup_theme', 'solidres_template_debug_mode', 20 );

/**
 * Add Additional Links To The WordPress Plugin Admin
 */
if ( ! function_exists ( 'solidres_register_plugin_links' ) ) {
	function solidres_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			$links[] = '<a href="admin.php?page=sr-option">' . __( 'Settings','solidres' ) . '</a>';
			$links[] = '<a href="http://www.solidres.com/support/frequently-asked-questions" target="_blank">' . __( 'FAQ','solidres' ) . '</a>';
			$links[] = '<a href="http://www.solidres.com" target="_blank">' . __( 'Support','solidres' ) . '</a>';
		}
		return $links;
	}
}
add_filter( 'plugin_row_meta', 'solidres_register_plugin_links', 10, 2 );