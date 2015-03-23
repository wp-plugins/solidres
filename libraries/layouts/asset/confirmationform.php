<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

?>

<form
	id="sr-reservation-form-confirmation"
	class=""
	action="<?php echo get_site_url() . '/' . get_page_uri( $displayData['page_id'] ) ?>"
	method="POST">

	<div class="row-fluid button-row button-row-top">
		<div class="span8">
			<div class="inner">
				<p><?php _e( 'Please review the your reservation details and click on the below button to finish your reservation. A confirmation email will be sent to your given email address.', 'solidres' ) ?></p>
			</div>
		</div>
		<div class="span4">
			<div class="inner">
				<div class="btn-group">
					<button disabled data-step="confirmation" type="submit" class="btn btn-success">
						<i class="icon-checkmark icon-ok uk-icon-check fa-check"></i> <?php _e( 'Finish', 'solidres' ) ?>
					</button>
					<button type="button" class="btn reservation-navigate-back" data-step="confirmation"
							data-prevstep="guestinfo">
						<i class="icon-arrow-left uk-icon-arrow-left fa-arrow-left"></i> <?php _e( 'Back', 'solidres' ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="inner">
				<div id="reservation-confirmation-box">
					<p>
						<strong>
							<?php
							$checkin = new DateTime( $displayData['checkin'], $displayData['timezone'] );
							echo __( 'Checkin:', 'solidres' ) . ' ' . $checkin->format( $displayData['dateFormat'] ) ?>
						</strong>
					</p>
					<p>
						<strong>
							<?php
							$checkout = new DateTime( $displayData['checkout'], $displayData['timezone'] );
							echo __( 'Checkout:', 'solidres' ) . ' ' . $checkout->format( $displayData['dateFormat'] )  ?>
						</strong>
					</p>

					<table class="table table-bordered">
						<tbody>
						<?php
						// Room cost
						foreach ($displayData['roomTypes'] as $roomTypeId => $roomTypeDetails) :
							foreach ($roomTypeDetails['rooms'] as $tariffId => $roomDetails) :
								foreach ($roomDetails as $roomIndex => $cost) :
									?>
									<tr>
										<td>
											<?php _e( 'Room', 'solidres' ) . ': ' . $roomTypeDetails["name"] ?>
											<p><?php echo !empty($cost['currency']['title']) ? '(' . $cost['currency']['title'] . ')' : ''  ?></p>
										</td>
										<td>
											<?php printf( _n( '%d nights', '%d nights', $displayData['numberOfNights'], 'solidres'), $displayData['numberOfNights']) ?>
										</td>
										<td class="sr-align-right">
											<?php echo $cost['currency']['total_price_tax_excl_formatted']->format() ?>
										</td>
									</tr>
								<?php
								endforeach;
							endforeach;
						endforeach;

						// Total room cost
						$totalRoomCost = new SR_Currency($displayData['cost']['total_price_tax_excl'], $displayData['currency_id']);
						?>
						<tr>
							<td colspan="2">
								<?php _e( 'Total room cost (exclude taxes)', 'solidres' ) ?>
							</td>
							<td class="sr-align-right">
								<?php echo $totalRoomCost->format() ?>
							</td>
						</tr>
						<?php
						// Imposed taxes
						$imposedTaxTypes = array();
						$taxId = $displayData['tax_id'];
						if (!empty($taxId)) :
							//$taxModel = JModelLegacy::getInstance('Tax', 'SolidresModel', array('ignore_request' => true));
							$solidres_tax = new SR_Tax();
							$imposedTaxTypes[] = $solidres_tax->load( $taxId );
						endif;

						$totalImposedTax = 0;
						foreach ($imposedTaxTypes as $taxType) :
							$imposedAmount = $taxType->rate * $displayData['cost']['total_price_tax_excl'];
							$totalImposedTax += $imposedAmount;
							$displayData['currency']->setValue($imposedAmount);
							$taxItem = new SR_Currency($imposedAmount, $displayData['currency_id']);
							?>
							<tr>
								<td colspan="2">
									<?php _e( 'Total room tax', 'solidres' ) ?>
								</td>
								<td class="sr-align-right">
									<?php echo $taxItem->format() ?>
								</td>
							</tr>
						<?php
						endforeach;

						// Extra cost
						$totalExtraCostTaxExcl = new SR_Currency($displayData['totalRoomTypeExtraCostTaxExcl'], $displayData['currency_id']);
						$totalExtraCostTaxAmount = new SR_Currency($displayData['totalRoomTypeExtraCostTaxIncl'] - $displayData['totalRoomTypeExtraCostTaxExcl'], $displayData['currency_id']);
						?>
						<tr>
							<td colspan="2">
								<?php _e( 'Total extra cost (exclude taxes)', 'solidres' ) ?>
							</td>
							<td id="total-extra-cost" class="sr-align-right">
								<?php echo $totalExtraCostTaxExcl->format() ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<?php _e( 'Total extra tax', 'solidres' ) ?>
							</td>
							<td id="total-extra-cost" class="sr-align-right">
								<?php echo $totalExtraCostTaxAmount->format() ?>
							</td>
						</tr>

						<?php
						// Grand total cost
						$grandTotal = new SR_Currency($displayData['cost']['total_price_tax_incl'] + $displayData['totalRoomTypeExtraCostTaxIncl'], $displayData['currency_id']);
						?>
						<tr>
							<td colspan="2">
								<strong><?php _e( 'Grand Total', 'solidres' ) ?></strong>
							</td>
							<td class="sr-align-right gra">
								<strong><?php echo $grandTotal->format() ?></strong>
							</td>
						</tr>

						<?php
						// Deposit amount, if enabled
						$isDepositRequired = $displayData['deposit_required'];

						if ($isDepositRequired) :
							$depositAmountTypeIsPercentage = $displayData['deposit_is_percentage'];
							$depositAmount = $displayData['deposit_amount'];
							$depositTotal = $depositAmount;
							if ($depositAmountTypeIsPercentage) :
								$depositTotal = ($displayData['cost']['total_price_tax_incl'] + $displayData['totalRoomTypeExtraCostTaxIncl']) * ($depositAmount / 100);
							endif;
							$depositTotalAmount = new SR_Currency($depositTotal, $displayData['currency_id']);
							?>
							<tr>
								<td colspan="2">
									<strong><?php _e( 'Deposit amount', 'solidres' ) ?></strong>
								</td>
								<td class="sr-align-right gra">
									<strong><?php echo $depositTotalAmount->format() ?></strong>
								</td>
							</tr>
							<?php
							solidres()->session[ 'sr_deposit' ] = array( 'deposit_amount' => $depositTotal );
						endif;

						// Terms and conditions
						$bookingConditionsLink = get_permalink( $displayData['asset_params']['termsofuse']);
						$privacyPolicyLink = get_permalink( $displayData['asset_params']['privacypolicy']);
						?>
						<tr>
							<td colspan="3">
								<p>
									<input type="checkbox" id="termsandconditions" data-target="finalbutton"/>
									<?php _e( 'I agree with ', 'solidres' ) ?>
									<a target="_blank"
									   href="<?php echo $bookingConditionsLink ?>"><?php _e( 'Booking conditions', 'solidres' ) ?></a> <?php _e( 'and', 'solidres' ) ?>
									<a target="_blank"
									   href="<?php echo $privacyPolicyLink ?>"><?php _e( 'Privacy Policy', 'solidres' ) ?></a>
								</p>
							</td>
						</tr>

						</tbody>
					</table>
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $displayData['assetId'] ?>"/>
		</div>
	</div>

	<div class="row-fluid button-row button-row-bottom">
		<div class="span8">
			<div class="inner">
				<p><?php _e( 'Please review the your reservation details and click on the below button to finish your reservation. A confirmation email will be sent to your given email address.', 'solidres' ) ?></p>
			</div>
		</div>
		<div class="span4">
			<div class="inner">
				<div class="btn-group">
					<button disabled data-step="confirmation" type="submit" name="save_reservation" value="<?php _e( 'Finish', 'solidres' ) ?>" class="btn btn-success">
						<i class="icon-checkmark icon-ok uk-icon-check fa-check"></i> <?php _e( 'Finish', 'solidres' ) ?>
					</button>
					<button type="button" class="btn reservation-navigate-back" data-step="confirmation"
							data-prevstep="guestinfo">
						<i class="icon-arrow-left uk-icon-arrow-left fa-arrow-left"></i> <?php _e('Back', 'solidres' ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>