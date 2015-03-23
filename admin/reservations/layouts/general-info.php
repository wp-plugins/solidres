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
$checkin_time  = strtotime( $sr_form_data->checkin );
$checkout_time = strtotime( $sr_form_data->checkout );
$sum_secs      = $checkout_time - $checkin_time;
$numofnights   = $sum_secs / 86400;
$pament_status = '';
switch ( $sr_form_data->payment_status ) {
	case '0':
		$pament_status = __( 'Unpaid', 'solidres' );
		break;
	case '1':
		$pament_status = __( 'Completed', 'solidres' );
		break;
	case '2':
		$pament_status = __( 'Cancelled', 'solidres' );
		break;
	case '3':
		$pament_status = __( 'Pending', 'solidres' );
		break;
}
$status = '';
switch ( $sr_form_data->state ) {
	case '0':
		$status = __( 'Pending arrival', 'solidres' );
		break;
	case '1':
		$status = __( 'Checked-in', 'solidres' );
		break;
	case '2':
		$status = __( 'Checked-out', 'solidres' );
		break;
	case '3':
		$status = __( 'Closed', 'solidres' );
		break;
	case '4':
		$status = __( 'Canceled', 'solidres' );
		break;
	case '5':
		$status = __( 'Confirmed', 'solidres' );
		break;
	case '-2':
		$status = __( 'Trashed', 'solidres' );
		break;
}

$payment_type = '';
switch ( $sr_form_data->payment_method_id ) {
	case 'paylater':
		$payment_type = __( 'Pay Later', 'solidres' );
		break;
	case 'bankwire':
		$payment_type = __( 'Bank Wire', 'solidres' );
		break;
}

$total_price_tax_excl = clone $baseCurrency;
$total_price_tax_excl->setValue( isset ( $sr_form_data->total_price_tax_excl ) ? $sr_form_data->total_price_tax_excl : 0 );

$room_cost_tax = $sr_form_data->total_price_tax_incl - $sr_form_data->total_price_tax_excl;
$roomcosttax   = clone $baseCurrency;
$roomcosttax->setValue( isset ( $room_cost_tax ) ? $room_cost_tax : 0 );

$total_extra_price_tax_excl = clone $baseCurrency;
$total_extra_price_tax_excl->setValue( isset ( $sr_form_data->total_extra_price_tax_excl ) ? $sr_form_data->total_extra_price_tax_excl : 0 );

$extra_tax = $sr_form_data->total_extra_price_tax_incl - $sr_form_data->total_extra_price_tax_excl;
$extratax  = clone $baseCurrency;
$extratax->setValue( isset ( $extra_tax ) ? $extra_tax : 0 );

$grand_total = $sr_form_data->total_price_tax_incl + $sr_form_data->total_extra_price_tax_incl;
$grandtotal  = clone $baseCurrency;
$grandtotal->setValue( isset ( $grand_total ) ? $grand_total : 0 );

$deposit_amount = clone $baseCurrency;
$deposit_amount->setValue( isset ( $sr_form_data->deposit_amount ) ? $sr_form_data->deposit_amount : 0 );

$total_paid = clone $baseCurrency;
$total_paid->setValue( isset ( $sr_form_data->total_paid ) ? $sr_form_data->total_paid : 0 );
?>

<div id="reservation_general_info" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'General info', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="reservation_label"><?php _e( 'Code', 'solidres' ); ?></td>
				<td class="reservation_code_row"><?php
					$statuses = SR_Reservation::view_status( $sr_form_data->state, $sr_form_data->code );
					echo $statuses[1];
					?></td>
				<td class="reservation_label"><?php _e( 'Room cost (excl tax)', 'solidres' ); ?></td>
				<td><?php echo $total_price_tax_excl->format(); ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Asset name', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->reservation_asset_name ) ? $sr_form_data->reservation_asset_name : ''; ?></td>
				<td class="reservation_label"><?php _e( 'Room cost tax', 'solidres' ); ?></td>
				<td><?php echo $roomcosttax->format(); ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Checkin', 'solidres' ); ?></td>
				<td><?php echo solidres_valid_date_format( '-', $sr_form_data->checkin ); ?></td>
				<td class="reservation_label"><?php _e( 'Extra cost (exl tax)', 'solidres' ); ?></td>
				<td><?php echo $total_extra_price_tax_excl->format(); ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Checkout', 'solidres' ); ?></td>
				<td><?php echo solidres_valid_date_format( '-', $sr_form_data->checkout ); ?></td>
				<td class="reservation_label"><?php _e( 'Extra tax', 'solidres' ); ?></td>
				<td><?php echo $extratax->format(); ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Number of nights', 'solidres' ); ?></td>
				<td><?php echo $numofnights; ?></td>
				<td class="reservation_label"><?php _e( 'Grand total', 'solidres' ); ?></td>
				<td><?php echo $grandtotal->format(); ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Created date', 'solidres' ); ?></td>
				<td><?php echo solidres_valid_date_format( '-', solidres_split_date_data( $sr_form_data->created_date, 10 ) ); ?></td>
				<td class="reservation_label"><?php _e( 'Deposit amount', 'solidres' ); ?></td>
				<td><?php echo $deposit_amount->format(); ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Payment type', 'solidres' ); ?></td>
				<td><?php echo $payment_type; ?></td>
				<td class="reservation_label"><?php _e( 'Total paid', 'solidres' ); ?></td>
				<?php if ( current_user_can( 'solidres_user' ) ) { ?>
					<td><?php echo $total_paid->format(); ?></td>
				<?php } else { ?>
					<td><?php echo '<a href="" id="total_paid" data-type="text" data-value="' . $sr_form_data->total_paid . '" data-pk="' . $id . '">' . $total_paid->format() . '</a>'; ?></td>
				<?php } ?>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Status', 'solidres' ); ?></td>
				<?php if ( current_user_can( 'solidres_user' ) ) { ?>
					<td>
						<?php
						echo '<span class="reservation_status_user">' . $status . '</span>';
						if ( $sr_form_data->state != 4 ) {
							$nonce = wp_create_nonce( 'cancel_reservation_nonce' );

							$current_user = wp_get_current_user();
							$author_id    = $current_user->ID;
							?>
							<form id="cancel_reservation_form" action="" method="POST">
								<input type="hidden" name="reservation_id" value="<?php echo $id; ?>"
								       id="reservation_id">
								<input type="hidden" name="customer_id" value="<?php echo $author_id; ?>"
								       id="customer_id">
								<button type="submit" name="cancel_reservation"
								        class="srform_button button button-primary button-large cancel_reservation_btn"
								        data-nonce="<?php echo $nonce; ?>"><?php _e( ' Cancel this reservation', 'solidres' ); ?></button>
							</form>
						<?php }
						?>
					</td>
				<?php } else { ?>
					<td><?php echo '<a href="" id="state" data-type="select" data-value="' . $sr_form_data->state . '" data-pk="' . $id . '">' . $status . '</a>'; ?></td>
				<?php } ?>
				<td class="reservation_label"><?php _e( 'Coupon code', 'solidres' ); ?></td>
				<td><?php echo $sr_form_data->coupon_code == null ? 'N/A' : $sr_form_data->coupon_code; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Payment status', 'solidres' ); ?></td>
				<?php if ( current_user_can( 'solidres_user' ) ) { ?>
					<td><?php echo $pament_status; ?></td>
				<?php } else { ?>
					<td><?php echo '<a href="" id="payment_status" data-type="select" data-value="' . $sr_form_data->payment_status . '" data-pk="' . $id . '">' . $pament_status . '</a>'; ?></td>
				<?php } ?>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Notes', 'solidres' ); ?></td>
				<td><?php echo $sr_form_data->note; ?></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>