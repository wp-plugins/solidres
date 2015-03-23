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

function sr_edit_currency_item( $id ) {
	$message = isset( $_GET['message'] ) ? $_GET['message'] : '';
	global $wpdb;
	$currencies = new SR_Currency();
	if ( ! isset( $_POST['edit_currency'] ) ) {
		$sr_form_data = $currencies->load( $id );
	} else {
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
		} else {
			$columns['filter_range'] = 'NULL';
		}
		add_filter( 'query', 'solidres_wp_db_null_value' );
		$update_currency = $wpdb->update( $wpdb->prefix . 'sr_currencies', $columns, array( 'id' => $id ) );
		remove_filter( 'query', 'solidres_wp_db_null_value' );
		if ( $update_currency == true ) {
			$message = 2;
			wp_redirect( admin_url( 'admin.php?page=sr-currencies&action=edit&id=' . $id . '&message=' . $message ) );
			exit;
		}
	}
	?>
	<div class="wrap">
		<div id="wpbody">
			<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Currency published.', 'solidres' ); ?></p></div>
			<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Currency updated.', 'solidres' ); ?></p></div>
			<h2><?php _e( 'Edit currency', 'solidres' ); ?><a
					href="<?php echo admin_url( 'admin.php?page=sr-add-new-currency' ); ?>"
					class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="edit-form-section">
					<div id="namediv" class="stuffbox">
						<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

						<div class="inside">
							<form name="srform_edit_currency" action="" method="post" id="srform">
								<?php require( 'layouts/general-infomation.php' ); ?>
								<input type="submit" name="edit_currency" value="<?php _e( 'Save', 'solidres' ); ?>"
								       class="srform_button button button-primary button-large edit_currency">
							</form>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}