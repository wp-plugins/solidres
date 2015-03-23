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

get_header( 'booking' ); ?>

<?php
	/**
	 * solidres_before_main_content hook
	 *
	 * @hooked solidres_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked solidres_breadcrumb - 20
	 */
	do_action( 'solidres_before_main_content' );
?>
	<div id="solidres">
		<div class="alert alert-success">
			<p>
				<?php printf( __( 'Your reservation number %s has been completed. A confirmation email has been sent to %s', 'solidres' ), solidres()->session[ 'sr_code' ], solidres()->session[ 'sr_customeremail' ] ) ?>
			</p>
		</div>
	</div>
<?php
	/**
	 * solidres_after_main_content hook
	 *
	 * @hooked solidres_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'solidres_after_main_content' );
?>

<?php
	/**
	 * solidres_sidebar hook
	 *
	 * @hooked solidres_get_sidebar - 10
	 */
	do_action( 'solidres_sidebar' );
?>

<?php get_footer( 'booking' );
