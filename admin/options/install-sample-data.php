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

function sr_install_sample_data() { ?>
	<div class="alert_block">
		<form name="srform_install_simple_date" action="" method="post" id="srform">
			<?php
			global $wpdb;
			$asset_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_reservation_assets" );
			if ( $asset_count > 0 ) {
				$message_error = __( 'Sample data installed before.', 'solidres' );
				SR_Helper::error_message( $message_error );
			} else { ?>
				<h4><?php _e( 'Warning', 'solidres' ); ?></h4>
				<?php _e( "You are about to install Solidres's sample data into your website. Sample data is the easiest way for you to get started and learn how to use Solidres. Before proceed please read the following notices:", 'solidres' ); ?>
				<ul>
					<li><?php _e( 'Always make a backup of your website first.', 'solidres' ); ?></li>
					<li><?php _e( 'Please make sure that you only install sample data right after the initial installation Solidres (when Solidres has no data).', 'solidres' ); ?></li>
					<li><?php _e( 'Do not install sample data twice because it will create duplicated entries in your databases.', 'solidres' ); ?></li>
				</ul>
				<input type="submit" name="install_simple_date" value="I understand and want to install the sample data"
				       class="srform_button install_simple_date">
			<?php }
			?>
		</form>
	</div>
	<?php
	if ( isset( $_POST['install_simple_date'] ) ) {
		solidres_install_simpledata();
		$message_update = __( 'Sample data installed success.', 'solidres' );
		SR_Helper::update_message( $message_update ); ?>
		<style type="text/css">.alert_block {
				display: none;
			}</style>
	<?php }
}