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


solidres()->session[ 'sr_currency_id' ] = $currency->id ;
solidres()->session[ 'sr_currency_code' ] = $currency->currency_code ;
solidres()->session[ 'sr_deposit_required' ] = $asset->deposit_required ;
solidres()->session[ 'sr_deposit_is_percentage' ] = $asset->deposit_is_percentage ;
solidres()->session[ 'sr_deposit_amount' ] = $asset->deposit_amount ;
solidres()->session[ 'sr_tax_id' ] = $asset->tax_id ;

$imposed_tax_types = array();
if ( ! empty( $asset->tax_id ) ) {
	$solidres_tax = new SR_Tax;
	$imposed_tax_types[] = $solidres_tax->load( $asset->tax_id );
}

$room_types = $solidres_room_type->load_by_asset_id( $asset->id );
$selectedTariffs = array();
if ( isset( solidres()->session[ 'sr_current_selected_tariffs' ] ) ) {
	$selectedTariffs = solidres()->session[ 'sr_current_selected_tariffs' ]->toArray();
}

$selectedRoomTypes = solidres()->session[ 'sr_room' ];

$solidres_currency = new SR_Currency( 0, $asset->currency_id );
$checkin = isset( $_GET['checkin'] ) ? $_GET['checkin'] : '';
$checkout = isset( $_GET['checkout'] ) ? $_GET['checkout'] : '';

$msg = '';

if ( ! empty ( $checkin ) && ! empty( $checkout ) ) :

	$conditions                             = array();
	$conditions['min_days_book_in_advance'] = $options['min_days_book_in_advance'];
	$conditions['max_days_book_in_advance'] = $options['max_days_book_in_advance'];
	$conditions['min_length_of_stay']       = $options['min_length_of_stay'];
	$showPriceWithTax                       = $options['show_price_with_tax'];

	solidres()->session[ 'sr_checkin' ] = $checkin ;
	solidres()->session[ 'sr_checkout'] =  $checkout ;

	try {
		$isCheckInCheckOutValid = $solidres_reservation->isCheckInCheckOutValid( $checkin, $checkout, $conditions );
	} catch ( Exception $e ) {
		switch ( $e->getCode() ) {
			case 50001:
				$msg = __( $e->getMessage(), 'solidres' );
				break;
			case 50002:
				$msg = sprintf( __( $e->getMessage(), 'solidres' ), $conditions['min_length_of_stay'] );
				break;
			case 50003:
				$msg = sprintf( __( $e->getMessage(), 'solidres' ), $conditions['min_days_book_in_advance'] );
				break;
			case 50004:
				$msg = sprintf( __( $e->getMessage(), 'solidres' ), $conditions['max_days_book_in_advance'] );
				break;
		}
	}

	$tariffs = solidres()->session[ 'sr_current_selected_tariffs' ];
endif;
$is_fresh = empty( $checkin ) && empty( $checkout );
?>

<?php if ( ! empty( $msg ) ) : ?>
	<div class="alert alert-info">
		<?php echo $msg ?>
	</div>
<?php endif ?>

<?php
if ( ! isset( $asset_params['enable_coupon'] ) ) :
	$asset_params['enable_coupon'] = 0;
endif;

if ( $asset_params['enable_coupon'] == 1 ) :
	$coupon = solidres()->session[ 'sr_coupon' ];

	if ( ! $is_fresh ) :
		?>
		<div class="coupon">
			<input type="text" name="coupon_code" class="span12" id="coupon_code"
				   placeholder="<?php _e( 'Enter coupon code (Optional)', 'solidres' ) ?>"/>
			<?php if ( isset( $coupon ) ) : ?>
				<?php _e( 'Applied coupon', 'solidres' ) ?>
				<span class="label label-success">
			<?php echo $coupon['coupon_name'] ?>
		</span>&nbsp;
				<a id="sr-remove-coupon" href="javascript:void(0)"
				   data-couponid="<?php echo $coupon['coupon_id'] ?>">
					<?php _e( 'Remove', 'solidres' ) ?>
				</a>
			<?php endif ?>
		</div>
	<?php
	endif;
endif;
?>
<a name="form"></a>

<div class="wizard">
	<ul class="steps">
		<li data-target="#step1" class="active reservation-tab reservation-tab-room span4"><span
				class="badge badge-info">1</span><?php _e( 'Room &amp; Rates', 'solidres' ); ?><span
				class="chevron"></span></li>
		<li data-target="#step2" class="reservation-tab reservation-tab-guestinfo span4"><span
				class="badge">2</span><?php _e( 'Guest info &amp; Payment', 'solidres' ); ?><span
				class="chevron"></span></li>
		<li data-target="#step3" class="reservation-tab reservation-tab-confirmation span4"><span class="badge">3</span><?php _e( 'Confirmation', 'solidres' ); ?>
		</li>
	</ul>
</div>
<div class="step-content">
	<div class="step-pane active" id="step1">
		<!-- Tab 1 -->
		<div class="reservation-single-step-holder room">

			<?php require( 'searchinfo.php' ); ?>

			<form enctype="multipart/form-data"
				  id="sr-reservation-form-room"
				  class="sr-reservation-form"
				  action="index.php"
				  method="POST">

				<?php if ( count( $room_types ) > 0 ) : ?>
					<?php if ( ! $is_fresh ) : ?>
						<div class="row-fluid button-row button-row-top">
							<div class="span9">
								<div class="inner">
									<p><?php _e( 'Select your room type, review the prices and click Next to continue', 'solidres' ) ?></p>
								</div>
							</div>
							<div class="span3">
								<div class="inner">
									<div class="btn-group">
										<button data-step="room" type="submit" class="btn btn-success">
											<i class="icon-arrow-right uk-icon-arrow-right fa-arrow-right"></i> <?php _e( 'Next', 'solidres' ) ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					<?php endif ?>

					<?php
					$count = 1;
					foreach ( $room_types as $room_type ) :
						$rowCSSClass = ( $count % 2 ) ? ' even' : ' odd';
						$rowCSSClass .= $room_type->featured == 1 ? ' featured' : '';
						$currentSelectedRoomNumberPerTariff = array();
						$room_type_media                    = $solidres_media->load_by_room_type_id( $room_type->id );

						// Tariff loading
						$standard_tariff   = null;
						$room_type_tariffs = array();
						if ( ! SR_PLUGIN_COMPLEXTARIFF_ENABLED ) {
							$standard_tariff           = $solidres_tariff->load_by_room_type_id( $room_type->id );
							$room_type_standard_tariff = null;
							if ( isset( $standard_tariff[0]->id ) ) {
							}
							{
								$room_type_tariffs[] = $solidres_tariff->load( $standard_tariff[0]->id );
							}
						} else {

						}

						$list_available_room  = $solidres_room_type->getListAvailableRoom( $room_type->id, $checkin, $checkout );
						$total_available_room = is_array( $list_available_room ) ? count( $list_available_room ) : 0;
						?>
						<div class="row-fluid <?php echo esc_attr( $rowCSSClass ) ?>"
							 id="room_type_row_<?php echo (int) $room_type->id; ?>">
							<div class="span12">
								<div class="row-fluid">
									<div class="span12">
										<div class="inner">
											<h4 class="roomtype_name"
												id="room_type_details_handler_<?php echo (int) $room_type->id; ?>">
										<span class="label label-info">
											<?php echo (int ) $room_type->occupancy_adult + (int) $room_type->occupancy_child; ?>
											<i class="icon-user uk-icon-user fa-user"></i>
										</span>

												<?php echo esc_attr( $room_type->name ); ?>
												<?php if ( $room_type->featured == 1 ) : ?>
													<span
														class="label label-success"><?php _e( 'Featured', 'solidres' ) ?></span>
												<?php endif ?>
											</h4>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<div class="inner">
											<?php
											if ( ! empty( $room_type_media ) ) :
												echo '<div id="carousel' . (int) $room_type->id . '" class="carousel slide">';
												echo '<div class="carousel-inner">';
												$count_media = 0;
												$active      = '';
												foreach ( $room_type_media as $media ) :
													$active     = ( $count_media == 0 ) ? 'active' : '';
													$media_attr = wp_get_attachment_image_src( $media->media_id, 'full' );
													?>
													<div class="item <?php echo esc_attr( $active ) ?>">
														<a class="room_type_details sr-photo-<?php echo (int) $room_type->id ?>"
														   href="<?php echo esc_url( $media_attr[0] ); ?>">
															<?php echo wp_get_attachment_image( $media->media_id, array( 300, 250 ) ); ?>
														</a>
													</div>
													<?php
													$count_media ++;
												endforeach;
												echo '</div>';
												echo '<a class="carousel-control left" href="#carousel' . (int) $room_type->id . '" data-slide="prev">&lsaquo;</a>';
												echo '<a class="carousel-control right" href="#carousel' . (int) $room_type->id . '" data-slide="next">&rsaquo;</a>';
												echo '</div>';
											endif;
											?>
										</div>
									</div>
									<div class="span8">
										<div class="inner">
											<div class="roomtype_desc">
												<?php echo $room_type->description; ?>
											</div>
											<?php if ( $total_available_room > 0 ) : ?>
											<p>
										<span class="num_rooms_available_msg" id="num_rooms_available_msg_<?php echo (int) $room_type->id; ?>"
											  data-original-text="<?php printf( _n( 'Last chance! Only 1 room left', 'Only %d rooms left', $total_available_room, 'solidres' ), $total_available_room )  ?>">
											<?php printf( _n( 'Last chance! Only 1 room left', 'Only %d rooms left', $total_available_room, 'solidres' ), $total_available_room )  ?>
										</span>
											</p>
											<?php endif; ?>

											<div class="btn-group">
												<button type="button" class="btn toggle_more_desc"
														data-target="<?php echo (int) $room_type->id; ?>">
													<i class="icon-eye-open uk-icon-eye fa-eye"></i>
													<?php _e( 'More info', 'solidres' ) ?>
												</button>

												<?php if ( $options['availability_calendar_enable'] == 1 ) : ?>
													<button type="button"
															data-roomtypeid="<?php echo (int) $room_type->id ?>"
															class="btn load-calendar">
														<i class="icon-calendar uk-icon-calendar fa-calendar"></i> <?php _e( 'View calendar', 'solidres' ) ?>
													</button>
												<?php endif ?>

											</div>
											<div class="unstyled more_desc"
												 id="more_desc_<?php echo (int) $room_type->id ?>"
												 style="display: none">
												<?php
												$roomtype_field_data      = new SR_Custom_Field( array(
													'id'   => (int) $room_type->id,
													'type' => 'room_type'
												) );
												$load_roomtype_field_data = $roomtype_field_data->create_array_group();
												$roomtype_field_data_view = '';
												foreach ( $load_roomtype_field_data as $group_name => $fields ) {
													foreach ( $fields as $field ) {
														$roomtype_field_data_view .= '<p><strong>' . ucfirst( $roomtype_field_data->split_field_name( solidres_convertslugtostring( $field[0] ) ) ) . ':</strong> ' . $field[1] . '</p>';
													}
												}
												echo $roomtype_field_data_view;
												?>
											</div>
										</div>
									</div>
									<!-- end of span8 -->
								</div>
								<!-- end of row-fluid -->

								<?php if ( $options['availability_calendar_enable'] == 1 ) { ?>
									<div class="row-fluid">
										<div class="span12 availability-calendar"
											 id="availability-calendar-<?php echo (int) $room_type->id; ?>"
											 style="display: none"></div>
									</div>
								<?php } ?>

								<div class="row-fluid">
									<div class="span12">
										<div class="inner">
											<?php
											$hasMatchedTariffs = true;
											if ( SR_PLUGIN_COMPLEXTARIFF_ENABLED &&
												 count( $room_type->tariffs ) == 0 &&
												 ( ! $is_fresh )
											) :
												$hasMatchedTariffs = false;
												// Special case: join tariffs
												if ( ! $hasMatchedTariffs && ! empty( $room_type->availableTariffs ) && ! is_null( $room_type->availableTariffs[0]['val'] ) ) :
													foreach ( $room_type->availableTariffs as $tariffKey => $tariffInfo ) :
														?>

														<div class="row-fluid">
															<div
																id="tariff-box-<?php echo $room_type->id ?>-<?php echo $tariffKey ?>"
																class="span12 tariff-box <?php //echo $tariffIsSelected
																?>">
																<div class="row-fluid">
																	<div class="span5">
																		<strong>
																			<?php echo JText::plural( 'SR_PRICE_IS_FOR_X_NIGHT', $this->numberOfNights ) ?>
																		</strong>
																	</div>
																	<div
																		class="span5 align-right normal_tariff">
																		<div class="inner">
																			<?php echo $tariffInfo['val']->format() ?>
																		</div>
																	</div>
																	<div class="span2">
																		<div class="inner">
																			<?php
																			if ( isset ( $room_type->totalAvailableRoom ) ) :
																				if ( $room_type->totalAvailableRoom == 0 ) :
																					echo JText::_( 'SR_NO_ROOM_AVAILABLE' );
																				else :
																					?>
																					<select
																						name="solidres[ign<?php echo rand() ?>]"
																						data-raid="<?php echo $this->item->id ?>"
																						data-rtid="<?php echo $room_type->id ?>"
																						data-tariffid="<?php echo $tariffKey ?>"
																						data-totalroomsleft="<?php echo $room_type->totalAvailableRoom ?>"
																						class="span12 roomtype-quantity-selection quantity_<?php echo $room_type->id ?>">
																						<option
																							value="0"><?php echo JText::_( 'SR_ROOMTYPE_QUANTITY' ) ?></option>
																						<?php
																						for ( $i = 1; $i <= $room_type->totalAvailableRoom; $i ++ ) :
																							$selected = '';
																							if ( isset( $this->selectedRoomTypes['room_types'][ $room_type->id ][ $tariffKey ] ) ) :
																								$selected = ( $i == count( $this->selectedRoomTypes['room_types'][ $room_type->id ][ $tariffKey ] ) ) ? 'selected="selected"' : '';
																							endif;

																							echo '<option ' . $selected . ' value="' . $i . '">' . JText::plural( 'SR_SELECT_ROOM_QUANTITY', $i ) . '</option>';
																						endfor;
																						?>
																					</select>
																					<input type="hidden"
																						   name="srform[selected_tariffs][<?php echo $room_type->id ?>][]"
																						   value="<?php echo $tariffKey ?>"
																						   id="selected_tariff_<?php echo $room_type->id ?>_<?php echo $tariffKey ?>"
																						   class="selected_tariff_hidden_<?php echo $room_type->id ?>"
																						   disabled
																						/>
																					<div class="processing"
																						 style="display: none"></div>
																				<?php
																				endif;
																			endif;
																			?>
																		</div>
																	</div>
																</div>

																<!-- check in form -->
																<div class="row-fluid">
																	<div class="span12 checkinoutform"
																		 id="checkinoutform-<?php echo $room_type->id ?>-<?php echo $tariffKey ?>"
																		 style="display: none">

																	</div>
																</div>
																<!-- /check in form -->


																<div class="row-fluid">
																	<div
																		class="span12 room-form room-form-<?php echo $room_type->id ?>-<?php echo $tariffKey ?>"
																		id="room-form-<?php echo $room_type->id ?>-<?php echo $tariffKey ?>"
																		style="display: none">

																	</div>
																</div>


															</div>
															<!-- end of span12 -->
														</div> <!-- end of row-fluid -->
													<?php
													endforeach;
												else :
													$link = JRoute::_( 'index.php?option=com_solidres&view=reservationasset&id=' . $this->item->id . '#form' );
													echo '<div class="alert alert-notice">' . JText::sprintf( 'SR_NO_TARIFF_MATCH_CHECKIN_CHECKOUT', $link ) . '</div>';
												endif;
											else:

												if ( isset( $room_type_tariffs ) && is_array( $room_type_tariffs ) ) :
													foreach ( $room_type_tariffs as $tariff ) :
														$tariffIsSelected = '';

														if ( isset( $selectedTariffs[ $room_type->id ] ) ) :
															$tariffIsSelected = in_array( $tariff->id, $selectedTariffs[ $room_type->id ] ) ? 'selected' : '';
														endif;

														if ( isset( $selectedRoomTypes['room_types'][ $room_type->id ][ $tariff->id ] ) ) :
															$currentSelectedRoomNumberPerTariff[ $tariff->id ] = count( $selectedRoomTypes['room_types'][ $room_type->id ][ $tariff->id ] );
														endif;

														$min = 0;
														?>
														<div class="row-fluid">
															<div
																id="tariff-box-<?php echo $room_type->id ?>-<?php echo $tariff->id ?>"
																class="span12 tariff-box <?php echo $tariffIsSelected ?>">
																<div class="row-fluid">
																	<div class="span5">
																		<strong><?php echo empty( $tariff->title ) ? __( 'Standard rate', 'solidres' ) : $tariff->title ?></strong>

																		<p><?php echo $tariff->description ?></p>
																	</div>
																	<div class="span5 align-right ">
																		<?php echo $solidres_room_type->get_min_price( $tariff, $solidres_currency, $options['show_price_with_tax'], $imposed_tax_types ) ?>
																	</div>
																	<div class="span2">
																		<div class="inner">
																			<?php if ( $is_fresh ) : ?>
																				<button
																					class="btn btn-block trigger_checkinoutform"
																					type="button"
																					data-roomtypeid="<?php echo $room_type->id ?>"
																					data-itemid="<?php //echo $this->itemid ?>"
																					data-assetid="<?php echo $asset->id ?>"
																					data-tariffid="<?php echo $tariff->id ?>"
																					><?php _e( 'Select', 'solidres' ) ?></button>
																			<?php else :
																				if ( isset ( $total_available_room ) ) :
																					if ( $total_available_room == 0 ) :
																						_e( 'Sold out!', 'solidres' );
																					else :
																						?>
																						<select
																							name="solidres[ign<?php echo $tariff->id ?>]"
																							data-raid="<?php echo $asset->id ?>"
																							data-rtid="<?php echo $room_type->id ?>"
																							data-tariffid="<?php echo $tariff->id ?>"
																							data-totalroomsleft="<?php echo $total_available_room ?>"
																							class="span12 roomtype-quantity-selection quantity_<?php echo $room_type->id ?>">
																							<option
																								value="0"><?php _e( 'Quantity', 'solidres' ) ?></option>
																							<?php
																							for ( $i = 1; $i <= $total_available_room; $i ++ ) :
																								$selected = '';
																								if ( isset( $currentSelectedRoomNumberPerTariff[ $tariff->id ] ) ) :
																									$selected = ( $i == $currentSelectedRoomNumberPerTariff[ $tariff->id ] ) ? 'selected' : '';
																								endif;

																								echo '<option ' . $selected . ' value="' . $i . '">' . sprintf( _n( '1 room', '%d rooms', $i, 'solidres' ), $i ) . '</option>';
																							endfor;
																							?>
																						</select>
																						<input type="hidden"
																							   name="srform[selected_tariffs][<?php echo $room_type->id ?>][]"
																							   value="<?php echo $tariff->id ?>"
																							   id="selected_tariff_<?php echo $room_type->id ?>_<?php echo $tariff->id ?>"
																							   class="selected_tariff_hidden_<?php echo $room_type->id ?>"
																							   disabled
																							/>
																						<div class="processing"
																							 style="display: none"></div>
																					<?php
																					endif;
																				endif;
																			endif;
																			?>
																		</div>
																	</div>
																</div>

																<!-- check in form -->
																<div class="row-fluid">
																	<div class="span12 checkinoutform"
																		 id="checkinoutform-<?php echo $room_type->id ?>-<?php echo $tariff->id ?>"
																		 style="display: none">

																	</div>
																</div>
																<!-- /check in form -->


																<div class="row-fluid">
																	<div
																		class="span12 room-form room-form-<?php echo $room_type->id ?>-<?php echo $room_type->id ?>"
																		id="room-form-<?php echo $room_type->id ?>-<?php echo $tariff->id ?>"
																		style="display: none">

																	</div>
																</div>


															</div>
															<!-- end of span12 -->
														</div> <!-- end of row-fluid -->
													<?php
													endforeach; // end foreach of complex tariffs
												endif;
											endif // end if in line 274
											?>
										</div>
									</div>
									<!-- end of span12 -->
								</div>
								<!-- end of row-fluid -->
							</div>
							<!-- end of span12 -->
						</div> <!-- end of row-fluid -->

						<?php
						$count ++;
					endforeach; ?>
				<?php
				else :
					?>
					<div class="alert alert-warning">
						<?php _e( 'SR_NO_ROOM_TYPES_MATCHED_SEARCH_CONDITIONS' ); ?>
					</div>
				<?php
				endif;
				?>

				<input type="hidden" name="srform[customer_id]" value="" />
				<input type="hidden" name="srform[raid]" value="<?php echo $asset->id ?>" />
				<input type="hidden" name="srform[state]" value="0" />
				<input type="hidden" name="srform[next_step]" value="guestinfo" />
				<input type="hidden" name="step" value="room" />
				<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'process-reservation' ) ?>" />
				<input type="hidden" name="action" value="solidres_reservation_process" />
				<input type="hidden" name="srform[bookingconditions]" value="<?php //echo $this->item->params['termsofuse'] ?>" />
				<input type="hidden" name="srform[privacypolicy]" value="<?php //echo $this->item->params['privacypolicy'] ?>" />

			</form>
		</div>
		<!-- /Tab 1 -->
	</div>
	<div class="step-pane" id="step2">
		<!-- Tab 2 -->
		<div class="reservation-single-step-holder guestinfo nodisplay"></div>
		<!-- /Tab 2 -->
	</div>
	<div class="step-pane" id="step3">
		<!-- Tab 3 -->
		<div class="reservation-single-step-holder confirmation nodisplay"></div>
		<!-- /Tab 3 -->
	</div>
</div>