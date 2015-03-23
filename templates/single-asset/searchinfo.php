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

?>

<div id="availability-search">
	<?php if ( $checkin && $checkout ) : ?>
		<div class="alert alert-info availability-search-info">
			<?php
			/*echo JText::sprintf('SR_ROOM_AVAILABLE_FROM_TO',
				  JDate::getInstance($checkin, $this->timezone)->format($this->dateFormat, true) ,
				  JDate::getInstance($checkout, $this->timezone)->format($this->dateFormat, true)
			  );*/

			echo sprintf( __( 'Available rooms from %s to %s', 'solidres' ), $checkin, $checkout );
			?>
			<a class="btn"
			   href="<?php echo esc_attr( home_url( '/' . $post->post_name ) ) ?>"><i
					class="icon-remove uk-icon-refresh fa-refresh"></i> <?php _e( 'Reset', 'solidres' ) ?></a>
		</div>
	<?php endif; ?>

	<form id="sr-checkavailability-form-component"
		  action="<?php echo esc_url( home_url( $post->post_name ) ) ?>"
		  method="GET"
		>
		<input name="id" value="<?php echo $asset->id ?>" type="hidden"/>
		<!--<input name="Itemid" value="<?php /*echo $this->itemid */ ?>" type="hidden"/>-->

		<input type="hidden"
			   name="checkin"
			   value="<?php //echo isset( $checkin ) ? $checkin : $dateCheckIn->add( new DateInterval( 'P' . ( $this->minDaysBookInAdvance ) . 'D' ) )->setTimezone( $this->timezone )->format( 'd-m-Y', true ) ?>"
			/>

		<input type="hidden"
			   name="checkout"
			   value="<?php //echo isset( $checkout ) ? $checkout : $dateCheckOut->add( new DateInterval( 'P' . ( $this->minDaysBookInAdvance + $this->minLengthOfStay ) . 'D' ) )->setTimezone( $this->timezone )->format( 'd-m-Y', true ) ?>"
			/>
		<input type="hidden" name="ts" value=""/>
	</form>
</div>