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

$roomTypeId = $displayData['roomTypeId'];
$roomType   = $displayData['roomType'];
for ( $i = 0; $i < $displayData['quantity']; $i ++ ) :
	$currentRoomIndex = null;
	if ( isset( $displayData['reservation_details_room']['room_types'][ $roomTypeId ][ $displayData['tariffId'] ][ $i ] ) ) :
		$currentRoomIndex = $displayData['reservation_details_room']['room_types'][ $roomTypeId ][ $displayData['tariffId'] ][ $i ];
	endif;

	// Html for adult selection
	$htmlAdultSelection = '';
	$htmlAdultSelection .= '<option value="">' . __( 'Adult', 'solidres' ) . '</option>';

	for ( $j = 1; $j <= $displayData['roomType']->occupancy_adult; $j ++ ) :
		$selected = '';
		if ( isset( $currentRoomIndex['adults_number'] ) ) :
			$selected = $currentRoomIndex['adults_number'] == $j ? 'selected' : '';
		else :
			if ( $j == 1 ) :
				$selected = 'selected';
			endif;
		endif;
		$htmlAdultSelection .= '<option ' . $selected . ' value="' . $j . '">' . sprintf( _n( '1 adult', '%s adults', $j, 'solidres' ), $j ) . '</option>';
	endfor;

	// Html for children selection
	$htmlChildSelection = '';
	$htmlChildrenAges   = '';
	if ( ! isset( $displayData['room_type_params']['show_child_option'] ) ) :
		$displayData['room_type_params']['show_child_option'] = 1;
	endif;

	// Only show child option if it is enabled and the child quantity > 0
	if ( $displayData['room_type_params']['show_child_option'] == 1 && $displayData['roomType']->occupancy_child > 0 ) :
		$htmlChildSelection .= '';
		$htmlChildSelection .= '<option value="">' . __( 'Child', 'solidres' ) . '</option>';

		for ( $j = 1; $j <= $displayData['roomType']->occupancy_child; $j ++ ) :
			if ( isset( $currentRoomIndex['children_number'] ) ) :
				$selected = $currentRoomIndex['children_number'] == $j ? 'selected' : '';
			endif;
			$htmlChildSelection .= '
				<option ' . $selected . ' value="' . $j . '">' . sprintf( _n( '1 child', '%s children', $j, 'solidres' ), $j ). '</option>
			';
		endfor;

		// Html for children ages
		if ( isset( $currentRoomIndex['children_ages'] ) ) :
			for ( $j = 0; $j < count( $currentRoomIndex['children_ages'] ); $j ++ ) :
				$htmlChildrenAges .= '
					<li>
						' . __( 'Child', 'solidres' ) . ' ' . ( $j + 1 ) . '
						<select name="srform[room_types][' . $roomTypeId . '][' . $displayData['tariffId'] . '][' . $i . '][children_ages][]"
							data-raid="' . $displayData['assetId'] . '"
							data-roomtypeid="' . $roomTypeId . '"
							data-tariffid="' . $displayData['tariffId'] . '"
							data-roomindex="' . $i . '"
							class="span6 child_age_' . $roomTypeId . '_' . $displayData['tariffId'] . '_' . $i . '_' . $j . ' trigger_tariff_calculating"
							required
						>';
				$htmlChildrenAges .= '<option value=""></option>';
				for ( $age = 1; $age <= $displayData['childMaxAge']; $age ++ ) :
					$selectedAge = '';
					if ( $age == $currentRoomIndex['children_ages'][ $j ] ) :
						$selectedAge = 'selected';
					endif;
					$htmlChildrenAges .= '<option ' . $selectedAge . ' value="' . $age . '">' . sprintf( _n( '1 year old', '%s years old', $age, 'solidres' ), $age ) . '</option>';
				endfor;

				$htmlChildrenAges .= '
						</select>
					</li>';
			endfor;
		endif;
	endif;

	// Smoking
	$htmlSmokingOption = '';
	if ( ! isset( $displayData['room_type_params']['show_smoking_option'] ) ) :
		$displayData['room_type_params']['show_smoking_option'] = 1;
	endif;

	if ( $displayData['room_type_params']['show_smoking_option'] == 1 ) :
		$selectedNonSmoking = '';
		$selectedSmoking    = '';
		if ( isset( $currentRoomIndex['preferences']['smoking'] ) ) :
			if ( $currentRoomIndex['preferences']['smoking'] == 0 ) :
				$selectedNonSmoking = 'selected';
			else :
				$selectedSmoking = 'selected';
			endif;
		endif;
		$htmlSmokingOption = '
			<select class="span10" name="srform[room_types][' . $roomTypeId . '][' . $displayData['tariffId'] . '][' . $i . '][preferences][smoking]">
				<option value="">' . __( 'Select your smoking options', 'solidres' ) . '</option>
				<option ' . $selectedNonSmoking . ' value="0">' . __( 'Non smoking room', 'solidres' ) . '</option>
				<option ' . $selectedSmoking . ' value="1">' . __( 'Smoking room', 'solidres' ) . '</option>
			</select>
		';
	endif;
	?>

	<div class="row-fluid">
		<div class="span10 offset2">
			<div class="row-fluid room_index_form_heading">
				<div class="span12">
					<div class="inner">
						<h4><?php _e( 'Room', 'solidres' ) . ' ' . ( $i + 1 ) ?>: <span
								class="tariff_<?php echo $roomTypeId . '_' . $displayData['tariffId'] . '_' . $i ?>">0</span>
							<i class="icon-question-sign uk-icon-question-circle fa-question-circle complex_tariff_break_down_<?php echo $roomTypeId . '_' . $displayData['tariffId'] . '_' . $i ?>"></i>
						</h4>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span5">
					<div class="row-fluid">
						<div class="inner">
							<select
								data-raid="<?php echo $displayData['assetId'] ?>"
								data-roomtypeid="<?php echo $roomTypeId ?>"
								data-tariffid="<?php echo $displayData['tariffId'] ?>"
								data-roomindex="<?php echo $i ?>"
								name="srform[room_types][<?php echo $roomTypeId ?>][<?php echo $displayData['tariffId'] ?>][<?php echo $i ?>][adults_number]"
								required
								class="span5 occupancy_adult_<?php echo $roomTypeId . '_' . $displayData['tariffId'] . '_' . $i ?> trigger_tariff_calculating">
								<?php echo $htmlAdultSelection ?>
							</select>
							<?php if ( $displayData['room_type_params']['show_child_option'] == 1 && $displayData['roomType']->occupancy_child > 0 ) : ?>
								<select
									data-raid="<?php echo $displayData['assetId'] ?>"
									data-roomtypeid="<?php echo $roomTypeId ?>"
									data-roomindex="<?php echo $i ?>"
									data-tariffid="<?php echo $displayData['tariffId'] ?>"
									name="srform[room_types][<?php echo $roomTypeId ?>][<?php echo $displayData['tariffId'] ?>][<?php echo $i ?>][children_number]"
									class="span5 reservation-form-child-quantity trigger_tariff_calculating occupancy_child_<?php echo $roomTypeId . '_' . $displayData['tariffId'] . '_' . $i ?>">
									<?php echo $htmlChildSelection ?>
								</select>
							<?php endif ?>

							<div
								class="span12 child-age-details <?php echo( empty( $htmlChildrenAges ) ? 'nodisplay' : '' ) ?>">
								<p><?php _e( 'Age of child(ren) at checkout', 'solidres' ) ?></p>
								<ul class="unstyled"><?php echo $htmlChildrenAges ?></ul>
							</div>
						</div>
					</div>
				</div>

				<div class="span7">
					<div class="inner">
						<input
							name="srform[room_types][<?php echo $roomTypeId ?>][<?php echo $displayData['tariffId'] ?>][<?php echo $i ?>][guest_fullname]"
							required
							type="text"
							value="<?php echo( isset( $currentRoomIndex['guest_fullname'] ) ? $currentRoomIndex['guest_fullname'] : '' ) ?>"
							class="span10"
							placeholder="<?php _e( 'Guest name', 'solidres' ) ?>"/>
						<?php echo $htmlSmokingOption ?>
						<ul class="unstyled">
							<?php
							foreach ( $displayData['extras'] as $extra ) :
								$extraInputCommonName = 'srform[room_types][' . $roomTypeId . '][' . $displayData['tariffId'] . '][' . $i . '][extras][' . $extra->id . ']';
								$checked              = '';
								$disabledCheckbox     = '';
								$disabledSelect       = 'disabled="disabled"';
								$alreadySelected      = false;
								if ( isset( $currentRoomIndex['extras'] ) ) :
									$alreadySelected = array_key_exists( $extra->id, (array) $currentRoomIndex['extras']->toArray() );
								endif;

								if ( $extra->mandatory == 1 || $alreadySelected ) :
									$checked = 'checked="checked"';
								endif;

								if ( $extra->mandatory == 1 ) :
									$disabledCheckbox = 'disabled="disabled"';
									$disabledSelect   = 'disabled="disabled"';
								endif;

								if ( $alreadySelected && $extra->mandatory == 0 ) :
									$disabledSelect = '';
								endif;
								?>
								<li class="extras_row_roomtypeform">
									<input <?php echo $checked ?> <?php echo $disabledCheckbox ?> type="checkbox"
																								  data-target="extra_<?php echo $displayData['tariffId'] ?>_<?php echo $i ?>_<?php echo $extra->id ?>"/>
									<?php if ( $extra->mandatory == 1 ) : ?>
										<input type="hidden" name="<?php echo $extraInputCommonName ?>[quantity]"
											   value="1"/>
									<?php endif ?>

									<select
										class="span3 extra_<?php echo $displayData['tariffId'] ?>_<?php echo $i ?>_<?php echo $extra->id ?>"
										name="<?php echo $extraInputCommonName ?>[quantity]"
										<?php echo $disabledSelect ?>>
										<?php
										for ( $quantitySelection = 1; $quantitySelection <= $extra->max_quantity; $quantitySelection ++ ) :
											$checked = '';
											if ( isset( $currentRoomIndex['extras'][ $extra->id ]['quantity'] ) ) :
												$checked = ( $currentRoomIndex['extras'][ $extra->id ]['quantity'] == $quantitySelection ) ? 'selected' : '';
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
							?>
						</ul>


					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span7 offset5">
					<button data-step="room" type="submit" class="btn span10 btn-success btn-block">
						<i class="icon-arrow-right uk-icon-arrow-right fa-arrow-right"></i>
						<?php _e( 'Next', 'solidres' ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>
<?php
endfor;