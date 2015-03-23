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
} ?>

<div id="reservation_other_infomation" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Other infomartion', 'solidres' ); ?></span></h3>

	<div class="inside">
		<?php
		$extras = $reservations->load_extras( $id );
		if ( isset( $extras ) ) :
			echo '
						<table class="form-table ">
							<thead>
								<th>' . __( 'Name', 'solidres' ) . '</th>
								<th>' . __( 'Quantity', 'solidres' ) . '</th>
								<th>' . __( 'Price', 'solidres' ) . '</th>
							</thead>
							<tbody>
											';
			foreach ( $extras as $extra ) :
				echo '<tr>';
				?>
				<td><?php echo $extra->extra_name ?></td>
				<td><?php echo $extra->extra_quantity ?></td>
				<td>
					<?php
					$extraPriceCurrencyPerBooking = clone $baseCurrency;
					$extraPriceCurrencyPerBooking->setValue( $extra->extra_price );
					echo $extraPriceCurrencyPerBooking->format();
					?>
				</td>
				<?php
				echo '</tr>';
			endforeach;
			echo '
							</tbody>
						</table>';
		endif;
		?>
	</div>
</div>