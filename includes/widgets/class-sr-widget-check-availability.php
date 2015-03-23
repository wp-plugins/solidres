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

/**
 * Creating the widget
 *
 * Class SR_Widget_Check_Availability
 */
class SR_Widget_Check_Availability extends WP_Widget {
	function __construct() {
		parent::__construct(
			'SR_Widget_Check_Availability',
			__('Solidres - Module check availability', 'solidres'),
			array( 'description' => __( 'Solidres - Module check availability', 'solidres' ), )
		);
	}

	/**
	 * Creating widget front-end
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$checkin = null;
		$checkout = null;

		$reservationassets = new SR_Asset();
		$default = $reservationassets->load_by_default( 1 );

		$tzoffset = get_option( 'timezone_string' );
		$tzoffset = $tzoffset == '' ? 'UTC' : $tzoffset;
		$timezone = new DateTimeZone( $tzoffset );

		$dateCheckIn = new DateTime();
		$dateCheckOut = new DateTime();

		$options = get_option( 'solidres_plugin' );
		$minDaysBookInAdvance = ! empty ( $options['min_days_book_in_advance'] ) ? $options['min_days_book_in_advance'] : 0;
		$maxDaysBookInAdvance = ! empty ( $options['max_days_book_in_advance'] ) ? $options['max_days_book_in_advance'] : 0;
		$minLengthOfStay = ! empty( $options['min_length_of_stay'] ) ? $options['min_length_of_stay'] : 1;
		$datePickerMonthNum = ! empty( $options['datepicker_month_number'] ) ? $options['datepicker_month_number'] : 1;
		$weekStartDay = ! empty( $options['week_start_day'] ) ? $options['week_start_day'] : 1;
		$dateFormat = get_option( 'date_format' );
		$dateFormat = ! empty( $dateFormat ) ? $dateFormat : 'd-m-Y';

		$jsDateFormat = SR_Utilities::convertDateFormatPattern( $dateFormat );

		$checkin_new = new DateTime( $checkin, $timezone );
		$checkin_format = $checkin_new->format('Y-m-d');
		$checkout_new = new DateTime( $checkout, $timezone );
		$checkout_format = $checkout_new->format('Y-m-d');

		$defaultCheckinDate = isset($checkin) ? $checkin_format : '';
		$defaultCheckoutDate = isset($checkout) ? $checkout_format : '';

		if (!empty($defaultCheckinDate)) :
			$defaultCheckinDateArray = explode('-', $defaultCheckinDate);
			$defaultCheckinDateArray[1] -= 1;
		endif;

		if (!empty($defaultCheckoutDate)) :
			$defaultCheckoutDateArray = explode('-', $defaultCheckoutDate);
			$defaultCheckoutDateArray[1] -= 1;
		endif;

		echo '<script>
			jQuery(function($) {
			var minLengthOfStay = '.$minLengthOfStay.';
			var checkout = $(".checkout_datepicker_inline_module").datepicker({
				minDate : "+' . ( $minDaysBookInAdvance + $minLengthOfStay ). '",
				numberOfMonths : '.$datePickerMonthNum.',
				showButtonPanel : true,
				dateFormat : "'.$jsDateFormat.'",
				firstDay: '.$weekStartDay.',
				' . (isset($checkout) ? 'defaultDate: new Date(' . implode(',' , $defaultCheckoutDateArray) .'),' : '') . '
				onSelect: function() {
					$("#sr-checkavailability-form input[name=\'checkout\']").val($.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")));
					$("#sr-checkavailability-form .checkout_module").text($.datepicker.formatDate("'.$jsDateFormat.'", $(this).datepicker("getDate")));
					$(".checkout_datepicker_inline_module").slideToggle();
					$(".checkin_module").removeClass("disabledCalendar");
				}
			});
			var checkin = $(".checkin_datepicker_inline_module").datepicker({
				minDate : "+' .  $minDaysBookInAdvance . 'd",
				'.($maxDaysBookInAdvance > 0 ? 'maxDate: "+'. ($maxDaysBookInAdvance) . '",' : '' ).'
				numberOfMonths : '.$datePickerMonthNum.',
				showButtonPanel : true,
				dateFormat : "'.$jsDateFormat.'",
				'. (isset($checkin) ? 'defaultDate: new Date(' . implode(',' , $defaultCheckinDateArray) .'),' : '') . '
				onSelect : function() {
					var currentSelectedDate = $(this).datepicker("getDate");
					var checkoutMinDate = $(this).datepicker("getDate", "+1d");
					checkoutMinDate.setDate(checkoutMinDate.getDate() + minLengthOfStay);
					checkout.datepicker( "option", "minDate", checkoutMinDate );
					checkout.datepicker( "setDate", checkoutMinDate);

					$("#sr-checkavailability-form input[name=\'checkin\']").val($.datepicker.formatDate("yy-mm-dd", currentSelectedDate));
					$("#sr-checkavailability-form input[name=\'checkout\']").val($.datepicker.formatDate("yy-mm-dd", checkoutMinDate));

					$("#sr-checkavailability-form .checkin_module").text($.datepicker.formatDate("'.$jsDateFormat.'", currentSelectedDate));
					$("#sr-checkavailability-form .checkout_module").text($.datepicker.formatDate("'.$jsDateFormat.'", checkoutMinDate));
					$(".checkin_datepicker_inline_module").slideToggle();
					$(".checkout_module").removeClass("disabledCalendar");
				},
				firstDay: '.$weekStartDay.'
			});
			$(".ui-datepicker").addClass("notranslate");
			$(".checkin_module").click(function() {
				if (!$(this).hasClass("disabledCalendar")) {
					$(".checkin_datepicker_inline_module").slideToggle("slow", function() {
						if ($(this).is(":hidden")) {
							$(".checkout_module").removeClass("disabledCalendar");
						} else {
							$(".checkout_module").addClass("disabledCalendar");
						}
					});
				}
			});
		
			$(".checkout_module").click(function() {
				if (!$(this).hasClass("disabledCalendar")) {
					$(".checkout_datepicker_inline_module").slideToggle("slow", function() {
						if ($(this).is(":hidden")) {
							$(".checkin_module").removeClass("disabledCalendar");
						} else {
							$(".checkin_module").addClass("disabledCalendar");
						}
					});
				}
			});
	    });
	</script>'; ?>

	<div class="row-fluid">
	    <form id="sr-checkavailability-form" action="#" method="GET" class="form-stacked sr-validate">
	    	<fieldset>
	    		<input name="id" value="<?php echo $default->id ?>" type="hidden" />
				<div class="span12">
					<label for="checkin">
						<?php _e( 'Arrival Date', 'solidres' )?>
					</label>
					<div class="checkin_module datefield">
						<?php
						$checkin_newform = new DateTime( $checkin, $timezone );
						$checkin_newform_format = $checkin_newform->format( $dateFormat );
						$checkin_mindays_format = $dateCheckIn->add(new DateInterval('P'.($minDaysBookInAdvance).'D'))->setTimezone($timezone)->format($dateFormat);
						echo isset($checkin) ? $checkin_newform_format : $checkin_mindays_format; ?>
					</div>
					<div class="checkin_datepicker_inline_module datepicker_inline" style="display: none"></div>
					<?php
					$checkin_newfm = new DateTime( $checkin, $timezone );
					$checkin_newfm_format = $checkin_newfm->format( 'Y-m-d' );
					$checkin_fm_mindays_format = $dateCheckIn->add(new DateInterval('P'.($minDaysBookInAdvance).'D'))->setTimezone($timezone)->format('Y-m-d');
					?>
					<input type="hidden" name="checkin" value="<?php echo isset($checkin) ? $checkin_newfm_format : $checkin_fm_mindays_format; ?>" />
	            </div>

	            <div class="span12">
					<label for="checkout">
						<?php _e( 'Departure Date', 'solidres' ); ?>
					</label>
					<div class="checkout_module datefield">
						<?php
						$checkout_newform = new DateTime( $checkout, $timezone );
						$checkout_newform_format = $checkout_newform->format( $dateFormat );
						$checkout_mindays_format = $dateCheckOut->add(new DateInterval('P'.($minDaysBookInAdvance + $minLengthOfStay).'D'))->setTimezone($timezone)->format($dateFormat);
						echo isset($checkout) ? $checkout_newform_format : $checkout_mindays_format; ?>
					</div>
					<div class="checkout_datepicker_inline_module datepicker_inline" style="display: none"></div>
					<?php
					$checkout_newfm = new DateTime( $checkout, $timezone );
					$checkout_newfm_format = $checkout_newfm->format( 'Y-m-d' );
					$checkout_fm_mindays_format = $dateCheckOut->add(new DateInterval('P'.($minDaysBookInAdvance + $minLengthOfStay).'D'))->setTimezone($timezone)->format('Y-m-d');
					?>
					<input type="hidden" name="checkout" value="<?php echo isset($checkout) ? $checkout_newfm_format : $checkout_fm_mindays_format; ?>" />

				</div>

	            <div class="span12">
					<div class="action">
						<button class="btn primary" type="submit"><i class="icon-search"></i> <?php _e( 'Check', 'solidres' ); ?></button>
					</div>
	            </div>

	    	</fieldset>

	    	<input type="hidden" name="option" value="com_solidres" />
	    	<input type="hidden" name="task" value="reservationasset.checkavailability" />
			<input type="hidden" name="Itemid" value="" />
	    </form>
	</div>

	<?php
		echo $args['after_widget'];
	}

	/**
	 * Widget Backend
	 *
	 * @param array $instance
	 * @return mixed
	 */

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Solidres - Module check availability', 'solidres' );
		} ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'solidres' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<?php
	}

	/**
	 * Updating widget replacing old instances with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}

/**
 * Register and load the widget
 */
function register_widget_checkavailability() {
	register_widget( 'SR_Widget_Check_Availability' );
}
add_action( 'widgets_init', 'register_widget_checkavailability' );
