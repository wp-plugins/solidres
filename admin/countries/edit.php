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

function sr_edit_country_item( $id ) {
	$message      = isset( $_GET['message'] ) ? $_GET['message'] : '';
	$today        = date( 'Y-m-d H:i:s' );
	$current_user = wp_get_current_user();
	$author_id    = $current_user->ID;
	global $wpdb;
	$countries = new SR_Country();
	if ( ! isset( $_POST['edit_country'] ) ) {
		$sr_form_data = $countries->load( $id );
	} else {
		$sr_form_data   = (object) $_POST['srform'];
		$update_country = $wpdb->update( $wpdb->prefix . 'sr_countries',
			array(
				'name'          => $sr_form_data->name,
				'code_2'        => $sr_form_data->code_2,
				'code_3'        => $sr_form_data->code_3,
				'state'         => $sr_form_data->state,
				'modified_by'   => $author_id,
				'modified_date' => $today,
			),
			array(
				'id' => $id,
			)
		);
		if ( $update_country == true ) {
			$message = 2;
			wp_redirect( admin_url( 'admin.php?page=sr-countries&action=edit&id=' . $id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Update country failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	}
	?>

	<div class="wrap">
		<div id="wpbody">
			<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Country published.', 'solidres' ); ?></p></div>
			<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Country updated.', 'solidres' ); ?></p></div>
			<h2><?php _e( 'Edit country', 'solidres' ); ?><a
					href="<?php echo admin_url( 'admin.php?page=sr-add-new-country' ); ?>"
					class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="edit-form-section">
					<div id="namediv" class="stuffbox">
						<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

						<div class="inside">
							<form name="srform_edit_country" action="" method="post" id="srform">
								<?php require( 'layouts/general_infomation.php' ); ?>
								<input type="submit" name="edit_country" value="<?php _e( 'Save', 'solidres' ); ?>"
								       class="srform_button button button-primary button-large edit_country">
							</form>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }