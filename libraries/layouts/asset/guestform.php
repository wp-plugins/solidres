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

global $current_user;
get_currentuserinfo();

?>
<form enctype="multipart/form-data"
	  id="sr-reservation-form-guest"
	  class="sr-reservation-form form-stacked sr-validate"
	  action="index.php"
	  method="POST">

	<input type="hidden" name="action" value="solidres_reservation_process"/>
	<input type="hidden" name="step" value="guestinfo"/>
	<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'process-reservation' ) ?>" />

	<div class="row-fluid button-row button-row-top">
		<div class="span8">
			<div class="inner">
				<p><?php _e( 'Enter your information and payment method', 'solidres' ) ?></p>
			</div>
		</div>
		<div class="span4">
			<div class="inner">
				<div class="btn-group">
					<button data-step="guestinfo" type="submit" class="btn btn-success">
						<i class="icon-arrow-right uk-icon-arrow-right fa-arrow-right"></i> <?php _e( 'Next', 'solidres' ) ?>
					</button>
					<button type="button" class="btn reservation-navigate-back" data-step="guestinfo"
							data-prevstep="room">
						<i class="icon-arrow-left uk-icon-arrow-left fa-arrow-left"></i> <?php _e( 'Back', 'solidres' ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="inner">
				<h3><?php _e( 'Guest information', 'solidres' ) ?></h3>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span6">
			<div class="inner">

				<fieldset>
					<label for="firstname">
						<?php _e( 'Your title (Optional)', 'solidres' ) ?>
					</label>

					<?php echo SR_Helper::get_generic_list(
						$displayData['customer_titles'],
						array( 'name' => 'srform[customer_title]', 'class' => 'span12', 'required' => 'required' ),
						$displayData['reservation_details_guest']["customer_title"]
					) ?>

					<label for="firstname">
						<?php _e( 'First name', 'solidres' ) ?>
					</label>
					<input id="firstname"
						   required
						   name="srform[customer_firstname]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_firstname"] ) ? $displayData['reservation_details_guest']["customer_firstname"] : "" ) ?>"/>

					<label for="middlename">
						<?php _e( 'Middlename (Optional)', 'solidres' ) ?>
					</label>
					<input id="middlename"
						   name="srform[customer_middlename]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_middlename"] ) ? $displayData['reservation_details_guest']["customer_middlename"] : "" ) ?>"/>

					<label for="lastname">
						<?php _e( 'Last name', 'solidres' ) ?>
					</label>
					<input id="lastname"
						   required
						   name="srform[customer_lastname]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_lastname"] ) ? $displayData['reservation_details_guest']["customer_lastname"] : "" ) ?>"/>

					<label for="email">
						<?php _e( 'Email', 'solidres' ) ?>
					</label>
					<input id="email"
						   required
						   name="srform[customer_email]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_email"] ) ? $displayData['reservation_details_guest']["customer_email"] : "" ) ?>"/>

					<label for="phonenumber">
						<?php _e( 'Phone number', 'solidres' ) ?>
					</label>
					<input id="phonenumber"
						   required
						   name="srform[customer_phonenumber]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_phonenumber"] ) ? $displayData['reservation_details_guest']["customer_phonenumber"] : "" ) ?>"/>

					<label for="company">
						<?php _e( 'Company (Optional)', 'solidres' ) ?>
					</label>
					<input id="company"
						   name="srform[customer_company]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_company"] ) ? $displayData['reservation_details_guest']["customer_company"] : "" ) ?>"/>

					<label for="address1">
						<?php _e( 'Address 1', 'solidres' ) ?>
					</label>
					<input id="address1"
						   required
						   name="srform[customer_address1]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_address1"] ) ? $displayData['reservation_details_guest']["customer_address1"] : "" ) ?>"/>

					<label for="address2">
						<?php _e( 'Address 2 (Optional)', 'solidres' ) ?>
					</label>
					<input id="address2"
						   name="srform[customer_address2]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_address2"] ) ? $displayData['reservation_details_guest']["customer_address2"] : "" ) ?>"/>

					<label for="address_2">
						<?php _e( 'VAT Number (Optional)', 'solidres' ) ?>
					</label>
					<input id="address_2"
						   name="srform[customer_vat_number]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_vat_number"] ) ? $displayData['reservation_details_guest']["customer_vat_number"] : "" ) ?>"/>
				</fieldset>
			</div>
		</div>

		<div class="span6">
			<div class="inner">
				<fieldset>
					<label for="city"><?php _e( 'City', 'solidres' ) ?></label>
					<input id="city"
						   required
						   name="srform[customer_city]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_city"] ) ? $displayData['reservation_details_guest']["customer_city"] : "" ) ?>"/>

					<label for="zip"><?php _e( 'Zip/Postal code (Optional)', 'solidres' ) ?></label>
					<input id="zip"
						   name="srform[customer_zipcode]"
						   type="text"
						   class="span12"
						   value="<?php echo( isset( $displayData['reservation_details_guest']["customer_zipcode"] ) ? $displayData['reservation_details_guest']["customer_zipcode"] : "" ) ?>"/>

					<label for="srform[country_id]"><?php _e( 'Country', 'solidres' ) ?></label>

					<select name="srform[customer_country_id]" class="country_select span12" required>
						<?php echo $displayData['countries'] ?>
					</select>

					<label for="srform[customer_geo_state_id]"><?php _e( 'State/Province (Optional)', 'solidres' ) ?></label>
					<select name="srform[customer_geo_state_id]" class="state_select span12">
						<?php echo $displayData['geo_states'] ?>
					</select>

					<label for="note"><?php _e( 'Note (Optional)', 'solidres' ) ?></label>
				<textarea id="note" name="srform[note]" rows="10" cols="30"
						  class="span12"><?php echo( isset( $displayData['reservation_details_guest']["note"] ) ? $displayData['reservation_details_guest']["note"] : "" ) ?></textarea>

					<p class="help-block"><?php _e( 'Enter any information you wish to attach to your reservation. The staff cannot guarantee additional requests or comments. Please avoid the use of special characters.', 'solidres' ) ?></p>

					<?php if ( defined( 'SR_PLUGIN_USER_ENABLED' ) && true == SR_PLUGIN_USER_ENABLED && $current_user->ID <= 0 ) : ?>
						<label class="checkbox">
							<input id="register_an_account_form"
								   type="checkbox"> <?php _e( 'Register with us for future convenience: fast and easy booking. Please enter your desired username and password in the following fields.', 'solidres' ) ?>
						</label>
						<div class="register_an_account_form" style="display: none">
							<label for="username">
								<?php _e( 'Username', 'solidres' ) ?>
							</label>
							<input id="username"
								   name="srform[customer_username]"
								   type="text"
								   class="span12"
								   value=""/>

							<label for="password">
								<?php _e( 'Password', 'solidres' ) ?>
							</label>
							<input id="password"
								   name="srform[customer_password]"
								   type="password"
								   class="span12"
								   value=""
								   autocomplete="off"
								/>
						</div>
					<?php endif ?>
				</fieldset>
			</div>
		</div>
	</div>

	<?php
	// Show Per Booking Extras
	if (count( $displayData['extras'] )) :
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="inner">
				<h3><?php _e( 'Enhance your stay', 'solidres' ) ?></h3>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="inner">
				<ul class="unstyled">
					<?php
					foreach ( $displayData['extras'] as $extra ) :
						$extraInputCommonName = 'srform[extras][' . $extra->id . ']';
						$checked              = '';
						$disabledCheckbox     = '';
						$disabledSelect       = 'disabled="disabled"';
						$alreadySelected      = false;
						if ( isset( $displayData['reservation_details_guest']['extras'] ) ) :
							$alreadySelected = array_key_exists( $extra->id, (array) $displayData['reservation_details_guest']['extras'] );
						endif;

						if ( $extra->mandatory == 1 || $alreadySelected ) :
							$checked = 'checked="checked"';
						endif;

						if ( $extra->mandatory == 1 ) :
							$disabledCheckbox = 'disabled="disabled"';
							$disabledSelect   = '';
						endif;

						if ( $alreadySelected && $extra->mandatory == 0 ) :
							$disabledSelect = '';
						endif;
						?>
						<li>
							<input <?php echo $checked ?> <?php echo $disabledCheckbox ?> type="checkbox"
																						  data-target="guest_extra_<?php echo $extra->id ?>"/>

							<?php
							if ( $extra->mandatory == 1 ) :
								?>
								<input type="hidden" name="<?php echo $extraInputCommonName ?>[quantity]" value="1"/>
							<?php
							endif;
							?>
							<select class="span3 guest_extra_<?php echo $extra->id ?>"
									name="<?php echo $extraInputCommonName ?>[quantity]"
								<?php echo $disabledSelect ?>>
								<?php
								for ( $quantitySelection = 1; $quantitySelection <= $extra->max_quantity; $quantitySelection ++ ) :
									$checked = '';
									if ( isset( $displayData['reservation_details_guest']['extras'][ $extra->id ]['quantity'] ) ) :
										$checked = ( $displayData['reservation_details_guest']['extras'][ $extra->id ]['quantity'] == $quantitySelection ) ? 'selected="selected"' : '';
									endif;
									?>
									<option <?php echo $checked ?>
										value="<?php echo $quantitySelection ?>"><?php echo $quantitySelection ?></option>
								<?php
								endfor;
								?>
							</select>
									<span data-content="<?php echo $extra->description ?>" class="extra_desc_tips"
										  title="<?php echo $extra->name ?>">
										<?php echo $extra->name . ' (' . $extra->currency->format() . ')' ?>
										<i class="icon-question-sign uk-icon-question-circle fa-question-circle"></i>
									</span>
						</li>
					<?php
					endforeach;
					endif;
					?>
				</ul>
			</div>
		</div>
	</div>
	<?php
	// Show available payment methods
	$solidresPaymentConfigData = new SR_Config( array( 'scope_id' => $displayData['assetId'] ) );
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="inner">
				<h3><?php _e( 'Payment information', 'solidres' ) ?></h3>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="inner">
				<ul class="unstyled payment_method_list">
					<?php
					//$solidresUtilities = SRFactory::get( 'solidres.utilities.utilities' );

					if ( $solidresPaymentConfigData->get( 'payments/paylater/paylater_enabled' ) ) :
						$checkPayLater = '';
						if ( isset( $displayData['reservation_details_guest']["payment_method_id"] ) ) :
							if ( $displayData['reservation_details_guest']["payment_method_id"] == "paylater" ) :
								$checkPayLater = "checked";
							endif;
						else :
							if ( $solidresPaymentConfigData->get( 'payments/paylater/paylater_is_default' ) == 1 ):
								$checkPayLater = "checked";
							endif;
						endif;
						?>
						<li>
							<input id="payment_method_paylater" type="radio" class="payment_method_radio"
								   name="srform[payment_method_id]"
								   value="paylater" <?php echo $checkPayLater ?>/>
					<span class="popover_payment_methods"
						  data-content="<?php echo $solidresPaymentConfigData->get( 'payments/paylater/paylater_frontend_message' ) ?>"
						  data-title="<?php _e( 'Pay Later', 'solidres' ) ?>">
						<?php _e( 'Pay Later', 'solidres' ) ?>

						<i class="icon-question-sign icon-question-sign uk-icon-question-circle fa-question-cirlce "></i>
					</span>
						</li>
					<?php
					endif;

					if ( $solidresPaymentConfigData->get( 'payments/bankwire/bankwire_enabled' ) ) :
						$checkBankWire = '';
						if ( isset( $displayData['reservation_details_guest']["payment_method_id"] ) ) :
							if ( $displayData['reservation_details_guest']["payment_method_id"] == "bankwire" ) :
								$checkBankWire = "checked";
							endif;
						else :
							if ( $solidresPaymentConfigData->get( 'payments/bankwire/bankwire_is_default' ) == 1 ):
								$checkBankWire = "checked";
							endif;
						endif;
						?>
						<li>
							<input id="payment_method_bankwire" class="payment_method_radio" type="radio"
								   name="srform[payment_method_id]"
								   value="bankwire" <?php echo $checkBankWire ?> />
					<span class="popover_payment_methods"
						  data-content="<?php echo $solidresPaymentConfigData->get( 'payments/bankwire/bankwire_frontend_message' ) ?>"
						  data-title="<?php _e( 'Bank Wire', 'solidres' ) ?>">
						<?php _e( 'Bank Wire', 'solidres' ) ?>
						<i class="icon-question-sign icon-question-sign uk-icon-question-circle fa-question-cirlce"></i>
					</span>
						</li>
					<?php
					endif;

					// For extra payment methods provide via plugins
					foreach ( $displayData['solidresPaymentPlugins'] as $paymentPlugin ) :
						$paymentPluginId = $paymentPlugin->element;

						if ( $solidresPaymentConfigData->get( 'payments/' . $paymentPluginId . '/' . $paymentPluginId . '_enabled' ) ) :
							$checked = '';
							if ( isset( $displayData['reservation_details_guest']["payment_method_id"] ) ) :
								if ( $displayData['reservation_details_guest']["payment_method_id"] == $paymentPluginId ) :
									$checked = "checked";
								endif;
							else :
								if ( $solidresPaymentConfigData->get( "payments/$paymentPluginId/{$paymentPluginId}_is_default" ) == 1 ):
									$checked = "checked";
								endif;
							endif;

							// Load custom payment plugin field template if it is available, otherwise just render it normally
							$fieldTemplatePath = JPATH_PLUGINS . '/solidrespayment/' . $paymentPluginId . '/form/field.php';
							if ( file_exists( $fieldTemplatePath ) ) :
								@ob_start();
								include $fieldTemplatePath;
								echo @ob_get_clean();
							else :
								?>
								<li>
									<input id="payment_method_<?php echo $paymentPluginId ?>"
										   type="radio"
										   name="srform[payment_method_id]"
										   value="<?php echo $paymentPluginId ?>"
										   class="payment_method_radio"
										<?php echo $checked ?>
										/>
							<span class="popover_payment_methods"
								  data-content="<?php echo $solidresUtilities::translateText( $solidresPaymentConfigData->get( 'payments/' . $paymentPluginId . '/' . $paymentPluginId . '_frontend_message' ) ) ?>"
								  data-title="<?php _e( "SR_PAYMENT_METHOD_" . $paymentPluginId ) ?>">
								<?php _e( "SR_PAYMENT_METHOD_" . $paymentPluginId ) ?>
								<i class="icon-question-sign icon-question-sign uk-icon-question-circle fa-question-cirlce"></i>
							</span>
								</li>
							<?php
							endif;

						endif;
					endforeach;
					?>
				</ul>
			</div>
		</div>
	</div>

	<div class="row-fluid button-row button-row-bottom">
		<div class="span8">
			<div class="inner">
				<p><?php _e( 'Enter your information and payment method', 'solidres' ) ?></p>
			</div>
		</div>
		<div class="span4">
			<div class="inner">
				<div class="btn-group">
					<button data-step="guestinfo" type="submit" class="btn btn-success">
						<i class="icon-arrow-right uk-icon-arrow-right fa-arrow-right"></i> <?php _e( 'Next', 'solidres' ) ?>
					</button>
					<button type="button" class="btn reservation-navigate-back" data-step="guestinfo"
							data-prevstep="room">
						<i class="icon-arrow-left uk-icon-arrow-left fa-arrow-left"></i> <?php _e( 'Back', 'solidres' ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="srform[next_step]" value="confirmation"/>
</form>