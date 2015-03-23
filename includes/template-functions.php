<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'template_include', 'solidres_call_template' );
function solidres_call_template( $template ) {
	$options = get_option( 'solidres_pages' );
	$find_paths = array();
	$file = '';

	if ( ! empty( $options['asset'] ) && is_page( $options['asset'] ) ) {
		$file = 'single-asset.php';
		$find_paths[] = $file;
		$find_paths[] = solidres()->template_path() . $file;
	}

	if ( ! empty( $options['reservationcompleted'] ) && is_page( $options['reservationcompleted'] ) ) {
		$file = 'final.php';
		$find_paths[] = $file;
		$find_paths[] = solidres()->template_path() . $file;
	}

	if ( ! empty( $file ) ) {
		$template = locate_template( array_unique( $find_paths ) );

		if ( ! $template ) {
			$template = solidres()->plugin_path() . '/templates/' . $file;
		}
	}

	return $template;
}

if ( ! function_exists( 'solidres_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function solidres_output_content_wrapper() {
		solidres_get_template( 'global/wrapper-start.php' );
	}
}

if ( ! function_exists( 'solidres_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function solidres_output_content_wrapper_end() {
		solidres_get_template( 'global/wrapper-end.php' );
	}
}

if ( ! function_exists( 'solidres_get_sidebar' ) ) {

	/**
	 * Get the shop sidebar template.
	 *
	 */
	function solidres_get_sidebar() {
		solidres_get_template( 'global/sidebar.php' );
	}
}

if ( ! function_exists( 'solidres_breadcrumb' ) ) {

	/**
	 * Output the Solidres Breadcrumb
	 */
	function solidres_breadcrumb( $args = array() ) {
		$args = wp_parse_args( $args, apply_filters( 'solidres_breadcrumb_defaults', array(
			'delimiter'   => ' &#47; ',
			'wrap_before' => '<nav class="solidres-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'solidres' )
		) ) );

		$breadcrumbs = new SR_Breadcrumb();

		if ( $args['home'] ) {
			$breadcrumbs->add_crumb( $args['home'], home_url() );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		solidres_get_template( 'global/breadcrumb.php', $args );
	}
}