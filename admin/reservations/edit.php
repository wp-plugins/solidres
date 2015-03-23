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

function sr_edit_reservation_item( $id ) {
	global $wpdb;
	$reservations = new SR_Reservation();
	$sr_form_data = $reservations->load( $id );
	$baseCurrency = new SR_Currency( 0, $sr_form_data->currency_id );
	?>
	<div class="wrap">
		<div id="wpbody">
			<h2><?php _e( 'Edit Reservation', 'solidres' ); ?></h2>

			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="edit-form-section edit_reservation_table">
					<?php require( 'layouts/general-info.php' ); ?>
					<?php require( 'layouts/customer-info.php' ); ?>
					<?php require( 'layouts/room-extra-info.php' ); ?>
					<?php require( 'layouts/invoice.php' ); ?>
					<?php require( 'layouts/other-information.php' ); ?>
					<?php require( 'layouts/reservation-note.php' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php }