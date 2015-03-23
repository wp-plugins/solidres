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

if ( isset( $id ) ) {
	$payment_data = new SR_Config( array( 'scope_id' => (int) $id ) );
	$payments     = array();
	$payments     = array_merge( $payments, array(
		'paylater_enabled'          => $payment_data->get( 'payments/paylater/paylater_enabled' ),
		'paylater_is_default'       => $payment_data->get( 'payments/paylater/paylater_is_default' ),
		'paylater_frontend_message' => $payment_data->get( 'payments/paylater/paylater_frontend_message' ),
		'bankwire_enabled'          => $payment_data->get( 'payments/bankwire/bankwire_enabled' ),
		'bankwire_is_default'       => $payment_data->get( 'payments/bankwire/bankwire_is_default' ),
		'bankwire_frontend_message' => $payment_data->get( 'payments/bankwire/bankwire_frontend_message' ),
		'bankwire_accountname'      => $payment_data->get( 'payments/bankwire/bankwire_accountname' ),
		'bankwire_accountdetails'   => $payment_data->get( 'payments/bankwire/bankwire_accountdetails' ),
	) );
} ?>

<div id="asset_payments" class="postbox closed open">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Payments', 'solidres' ); ?></span></h3>

	<div class="inside">
		<div id="payments_tab">
			<ul>
				<li>
					<a href="#pay_later"><?php _e( 'Pay Later', 'solidres' ); ?></a>
				</li>
				<li>
					<a href="#bank_wire"><?php _e( 'Bank Wire', 'solidres' ); ?></a>
				</li>
			</ul>
			<div id="pay_later">
				<table class="form-table">
					<tbody>
					<tr>
						<td class="first"><label for="srform_paylater_enabled"
						                         title="<?php _e( "Enable Pay Later payment method. If your client choose this payment method, the reservation's status will be changed to 'Pending'. You will need to manually confirm the reservation when the client go to your hotel", 'solidres' ); ?>"><?php _e( 'Enable Pay Later', 'solidres' ); ?></label>
						</td>
						<td>
							<select name="srform[payments][paylater_enabled]" class="srform_selected"
							        id="srform_paylater_enabled">
								<option value="0" <?php if ( isset( $payments['paylater_enabled'] ) ) {
									echo $payments['paylater_enabled'] == 0 ? 'selected' : '';
								} ?> ><?php _e( 'No', 'solidres' ); ?></option>
								<option value="1" <?php if ( isset( $payments['paylater_enabled'] ) ) {
									echo $payments['paylater_enabled'] == 1 ? 'selected' : '';
								} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="first"><label for="srform_paylater_is_default"
						                         title="<?php _e( 'Specify whether this payment method is the default one (pre-selected).', 'solidres' ); ?>"><?php _e( 'Default', 'solidres' ); ?></label>
						</td>
						<td>
							<select name="srform[payments][paylater_is_default]" class="srform_selected"
							        id="srform_paylater_is_default">
								<option value="0" <?php if ( isset( $payments['paylater_is_default'] ) ) {
									echo $payments['paylater_is_default'] == 0 ? 'selected' : '';
								} ?> ><?php _e( 'No', 'solidres' ); ?></option>
								<option value="1" <?php if ( isset( $payments['paylater_is_default'] ) ) {
									echo $payments['paylater_is_default'] == 1 ? 'selected' : '';
								} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="first"><label for="srform_paylater_frontend_message"
						                         title="<?php _e( 'Enter the message that will be show in the front end, this field can be used to explain this payment method to your clients', 'solidres' ); ?>"><?php _e( 'Front-end message', 'solidres' ); ?></label>
						</td>
						<td><textarea class="srform_textarea" rows="5"
						              name="srform[payments][paylater_frontend_message]"
						              id="srform_paylater_frontend_message"><?php echo isset( $payments['paylater_frontend_message'] ) ? $payments['paylater_frontend_message'] : ''; ?></textarea>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div id="bank_wire">
				<table class="form-table">
					<tbody>
					<tr>
						<td class="first"><label for="srform_bankwire_enabled"
						                         title="<?php _e( "Enable Bank Wire payment method. If your client choose this payment method, the reservation's status will be changed to 'Pending'. You will need to manually confirmed the reservation upon receiving the bank wire", 'solidres' ); ?>"><?php _e( 'Enable Bank Wire', 'solidres' ); ?></label>
						</td>
						<td>
							<select name="srform[payments][bankwire_enabled]" class="srform_selected"
							        id="srform_bankwire_enabled">
								<option value="0" <?php if ( isset( $payments['bankwire_enabled'] ) ) {
									echo $payments['bankwire_enabled'] == 0 ? 'selected' : '';
								} ?> ><?php _e( 'No', 'solidres' ); ?></option>
								<option value="1" <?php if ( isset( $payments['bankwire_enabled'] ) ) {
									echo $payments['bankwire_enabled'] == 1 ? 'selected' : '';
								} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="first"><label for="srform_bankwire_is_default"
						                         title="<?php _e( 'Specify whether this payment method is the default one (pre-selected).', 'solidres' ); ?>"><?php _e( 'Default', 'solidres' ); ?></label>
						</td>
						<td>
							<select name="srform[payments][bankwire_is_default]" class="srform_selected"
							        id="srform_bankwire_is_default">
								<option value="0" <?php if ( isset( $payments['bankwire_is_default'] ) ) {
									echo $payments['bankwire_is_default'] == 0 ? 'selected' : '';
								} ?> ><?php _e( 'No', 'solidres' ); ?></option>
								<option value="1" <?php if ( isset( $payments['bankwire_is_default'] ) ) {
									echo $payments['bankwire_is_default'] == 1 ? 'selected' : '';
								} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="first"><label for="srform_bankwire_accountname"
						                         title="<?php _e( 'Enter your bank account name to be used with the Bank Wire payment method', 'solidres' ); ?>"><?php _e( 'Account name', 'solidres' ); ?></label>
						</td>
						<td><input type="text" name="srform[payments][bankwire_accountname]" size="30"
						           value="<?php echo isset( $payments['bankwire_accountname'] ) ? $payments['bankwire_accountname'] : ''; ?>"
						           id="srform_bankwire_accountname"></td>
					</tr>
					<tr>
						<td class="first"><label for="srform_bankwire_accountdetails"
						                         title="<?php _e( 'Enter your bank account details like Bank name, Bank branch, Bank address, Bank account number, CIF, Code etc. Please contact your bank if you do not know these information.', 'solidres' ); ?>"><?php _e( 'Account details', 'solidres' ); ?></label>
						</td>
						<td><textarea class="srform_textarea" rows="5" name="srform[payments][bankwire_accountdetails]"
						              id="srform_bankwire_accountdetails"><?php echo isset( $payments['bankwire_accountdetails'] ) ? $payments['bankwire_accountdetails'] : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td class="first"><label for="srform_bankwire_frontend_message"
						                         title="<?php _e( 'Enter the message that will be show in the front end, this field can be used to explain this payment method to your clients', 'solidres' ); ?>"><?php _e( 'Front-end message', 'solidres' ); ?></label>
						</td>
						<td><textarea class="srform_textarea" rows="5"
						              name="srform[payments][bankwire_frontend_message]"
						              id="srform_bankwire_frontend_message"><?php echo isset( $payments['bankwire_frontend_message'] ) ? $payments['bankwire_frontend_message'] : ''; ?></textarea>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>