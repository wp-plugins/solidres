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

function sr_add_new_currency() {
	global $wpdb;
	if ( isset( $_POST['add_new_currency'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$columns      = array(
			'currency_name' => $sr_form_data->currency_name,
			'currency_code' => $sr_form_data->currency_code,
			'state'         => $sr_form_data->state,
			'exchange_rate' => $sr_form_data->exchange_rate,
			'sign'          => $sr_form_data->sign,
		);
		if ( $sr_form_data->filter_range != null ) {
			$columns['filter_range'] = $sr_form_data->filter_range;
		}
		$add_currency = $wpdb->insert( $wpdb->prefix . 'sr_currencies', $columns );
		if ( $add_currency == true ) {
			$last_id = $wpdb->insert_id;
			$message = 1;
			wp_redirect( admin_url( 'admin.php?page=sr-currencies&action=edit&id=' . $last_id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Add currency failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	} ?>

	<div id="wpbody">
		<h2><?php _e( 'Add new currency', 'solidres' ); ?></h2>

		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="edit-form-section">
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

					<div class="inside">
						<form name="srform_add_new_currency" action="" method="post" id="srform">
							<?php require( 'layouts/general-infomation.php' ); ?>
							<input type="submit" name="add_new_currency" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_currency">
						</form>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }