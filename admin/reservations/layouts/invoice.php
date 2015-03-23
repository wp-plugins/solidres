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
} ?>

<div id="asset_general_infomation" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Invoice', 'solidres' ); ?></span></h3>

	<div class="inside">
		<?php
		$invoice = 'solidres-invoice/solidres-invoice.php';
		$check_plugin_invoice = solidres_check_plugin( $invoice );
		if ( $check_plugin_invoice['status'] == 2 ) {
			$message_update = __( 'This feature allow you to select one or multiple themes for your reservation asset and they can be used in front end search and filtering.', 'solidres' );
			$message_trash  = __( '<strong>Notice:</strong> Solidres Invoice plugin is not installed. <a target="blank" href="https://www.solidres.com/subscribe/levels">Become a subscriber and download it now.</a>', 'solidres' );
			SR_Helper::update_message( $message_update );
			SR_Helper::trash_message( $message_trash );
		} else if ( $check_plugin_invoice['status'] == 0 ) {
			$message_trash = __( '<strong>Notice:</strong> please active <strong>Solidres Invoice plugin</strong>. <a target="blank" href="' . admin_url() . 'plugins.php"><strong>Active now</strong></a>', 'solidres' );
			SR_Helper::trash_message( $message_trash );
		} else {
			include_once( WP_PLUGIN_DIR.'/solidres-invoice/admin/edit.php' );
		}
		?>
	</div>
</div>