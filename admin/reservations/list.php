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

class SR_Reservations_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $wpdb, $status, $state, $string_search, $query_default;
		$reservations   = new SR_Reservation();
		$status         = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search  = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$reservation_id = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids            = (array) $reservation_id;
		$action         = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$query_default  = "SELECT id, code, reservation_asset_name, state, payment_status, customer_firstname, customer_middlename, customer_lastname, checkin, checkout, created_date FROM {$wpdb->prefix}sr_reservations";
		$state          = SR_Helper::get_listview_state( $status );
		if ( isset( $action ) && $action == 'edit' && isset( $reservation_id ) && $reservation_id != null ) {
			sr_edit_reservation_item( $reservation_id );
		}
		if ( $action == 'trash' || $action == 'untrash' ) {
			$reservations->update_states( $action, $reservation_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $reservation_id ) && $reservation_id != null ) {
			foreach ( $ids as $id ) {
				$reservations->delete( $id );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 reservation permanently deleted.', 'solidres' );
				SR_Helper::error_message( $message );
			} else {
				$message = __( count( $ids ) . ' reservations permanently deleted.', 'solidres' );
				SR_Helper::update_message( $message );
			}
		}
		$filter_reservation          = isset( $_GET['filter_reservation'] ) ? $_GET['filter_reservation'] : null;
		$filter_reservation_asset_id = isset( $_GET['filter_reservation_asset_id'] ) ? $_GET['filter_reservation_asset_id'] : null;
		$filter_published            = isset( $_GET['filter_published'] ) ? $_GET['filter_published'] : '';
		$filter_payment_status       = isset( $_GET['filter_payment_status'] ) ? $_GET['filter_payment_status'] : null;
		$filter_checkin_from         = isset( $_GET['filter_checkin_from'] ) ? $_GET['filter_checkin_from'] : null;
		$filter_checkin_to           = isset( $_GET['filter_checkin_to'] ) ? $_GET['filter_checkin_to'] : '';
		$filter_checkout_from        = isset( $_GET['filter_checkout_from'] ) ? $_GET['filter_checkout_from'] : null;
		$filter_checkout_to          = isset( $_GET['filter_checkout_to'] ) ? $_GET['filter_checkout_to'] : null;
		$query_filter                = array();
		$results                     = '';
		if ( is_numeric( $filter_published ) ) {
			$query_filter[] = ' state = ' . $filter_published;
		}
		if ( $filter_reservation_asset_id > 0 ) {
			$query_filter[] = ' reservation_asset_id = ' . $filter_reservation_asset_id;
		}
		if ( is_numeric( $filter_payment_status ) ) {
			$query_filter[] = ' payment_status = ' . $filter_payment_status;
		}
		if ( $filter_checkin_from != '' && $filter_checkin_to != '' ) {
			$query_filter[] = ' checkin >= "' . date( 'Y-m-d', strtotime( $filter_checkin_from ) ) . '" AND checkin <= "' . date( 'Y-m-d', strtotime( $filter_checkin_to ) ) . '"';
		}
		if ( $filter_checkout_from != '' && $filter_checkout_to != '' ) {
			$query_filter[] = ' checkout >= "' . date( 'Y-m-d', strtotime( $filter_checkout_from ) ) . '" AND checkout <= "' . date( 'Y-m-d', strtotime( $filter_checkout_to ) ) . '"';
		}
		if ( ( $string_search != '' && is_null( $filter_reservation ) ) || ( $string_search != '' && isset( $filter_reservation ) && $filter_reservation_asset_id == '' && $filter_published == '' && $filter_payment_status == '' && $filter_checkin_from == '' && $filter_checkin_to == '' && $filter_checkout_from == '' && $filter_checkout_to == '' ) ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$query_default = $query_default . ' WHERE id = ' . (int) substr( $string_search, 3 );
				$results       = $wpdb->get_results( $query_default );
			} else {
				$query_default = $query_default . ' WHERE code LIKE "%' . $string_search . '%"';
				$results       = $wpdb->get_results( $query_default );
			}
		} else if ( ( is_null( $state ) && is_null( $filter_reservation ) ) || ( isset ( $filter_reservation ) && $filter_reservation_asset_id == '' && $filter_published == '' && $filter_payment_status == '' && $filter_checkin_from == '' && $filter_checkin_to == '' && $filter_checkout_from == '' && $filter_checkout_to == '' ) ) {
			$results = $wpdb->get_results( $query_default . " WHERE state = 0 OR state = 1 OR state = 2 OR state = 3 OR state = 4 OR state = 5" );
		} else if ( $state != null && is_null( $filter_reservation ) ) {
			$results = $wpdb->get_results( $wpdb->prepare( $query_default . " WHERE state = %d", $state ) );
		} else if ( isset ( $filter_reservation ) && ( $filter_reservation_asset_id != '' || $filter_published != '' || $filter_payment_status != '' || $filter_checkin_from != '' || $filter_checkin_to != '' || $filter_checkout_from != '' || $filter_checkout_to != '' ) && $string_search == '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter );
			$results       = $wpdb->get_results( $query_default );
		} else if ( isset ( $filter_reservation ) && ( $filter_reservation_asset_id != '' || $filter_published != '' || $filter_payment_status != '' || $filter_checkin_from != '' || $filter_checkin_to != '' || $filter_checkout_from != '' || $filter_checkout_to != '' ) && $string_search != '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter ) . ' AND code LIKE "%' . $string_search . '%"';
			$results       = $wpdb->get_results( $query_default );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$reservations       = SR_Reservation::view_status( $result->state, $result->code );
			$paymentstatus      = SR_Reservation::payment_status( $result->payment_status );
			$customerfullname   = $result->customer_firstname . ' ' . $result->customer_middlename . ' ' . $result->customer_lastname;
			$this->datatable [] = array( 'id'            => $result->id,
			                             'codename'      => $reservations[1],
			                             'asset'         => $result->reservation_asset_name,
			                             'status'        => $reservations[0],
			                             'paymentstatus' => $paymentstatus,
			                             'customer'      => $customerfullname,
			                             'checkin'       => $result->checkin,
			                             'checkout'      => $result->checkout,
			                             'createdate'    => $result->created_date
			);
		}
		parent::__construct( array(
			'singular' => __( 'reservation' ),
			'plural'   => __( 'reservations' ),
			'ajax'     => false,
		) );
	}

	function no_items() {
		_e( 'No reservation found!', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'codename':
			case 'asset':
			case 'status':
			case 'paymentstatus':
			case 'customer':
			case 'checkin':
			case 'checkout':
			case 'createdate':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'            => array( 'id', false ),
			'codename'      => array( 'codename', false ),
			'asset'         => array( 'asset', false ),
			'status'        => array( 'status', false ),
			'paymentstatus' => array( 'paymentstatus', false ),
			'customer'      => array( 'customer', false ),
			'checkin'       => array( 'checkin', false ),
			'checkout'      => array( 'checkout', false ),
			'createdate'    => array( 'createdate', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'id'            => __( 'ID', 'solidres' ),
			'codename'      => __( 'Code Name', 'solidres' ),
			'asset'         => __( 'Asset', 'solidres' ),
			'status'        => __( 'Status', 'solidres' ),
			'paymentstatus' => __( 'Payment status', 'solidres' ),
			'customer'      => __( 'Customer', 'solidres' ),
			'checkin'       => __( 'Check-in', 'solidres' ),
			'checkout'      => __( 'Check-out', 'solidres' ),
			'createdate'    => __( 'Craete Date', 'solidres' ),
		);

		return $columns;
	}

	function column_codename( $item ) {
		global $status;
		if ( $status == 'trash' ) {
			$actions = array(
				'untrash' => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Restore</a>', 'solidres' ), $_REQUEST['page'], 'untrash', $item['id'] ),
				'delete'  => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Delete Permanently</a>', 'solidres' ), $_REQUEST['page'], 'delete', $item['id'] ),
			);
		} else {
			$actions = array(
				'edit'  => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Edit</a>', 'solidres' ), $_REQUEST['page'], 'edit', $item['id'] ),
				'trash' => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Trash</a>', 'solidres' ), $_REQUEST['page'], 'trash', $item['id'] ),
			);
		}

		return sprintf( '%1$s %2$s', $item['codename'], $this->row_actions( $actions ) );
	}

	function get_bulk_actions() {
		global $status;
		if ( $status == 'trash' ) {
			$actions = array(
				'untrash' => __( 'Restore', 'solidres' ),
				'delete'  => __( 'Delete Permanently', 'solidres' ),
			);
		} else {
			$actions = array(
				'trash' => __( 'Move to Trash', 'solidres' ),
			);
		}

		return $actions;
	}

	function extra_tablenav( $which ) {
		if ( 'top' != $which ) {
			return;
		}
		$filter_checkin_from  = isset( $_GET['filter_checkin_from'] ) ? $_GET['filter_checkin_from'] : null;
		$filter_checkin_to    = isset( $_GET['filter_checkin_to'] ) ? $_GET['filter_checkin_to'] : null;
		$filter_checkout_from = isset( $_GET['filter_checkout_from'] ) ? $_GET['filter_checkout_from'] : null;
		$filter_checkout_to   = isset( $_GET['filter_checkout_to'] ) ? $_GET['filter_checkout_to'] : null;
		?>
		<div class="alignleft actions bulkactions">
			<select name="filter_reservation_asset_id" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by assets', 'solidres' ); ?></option>
				<?php echo SR_Helper::get_reservation_asset_selected( $_GET['filter_reservation_asset_id'] ); ?>
			</select>
			<select name="filter_published" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by status', 'solidres' ); ?></option>
				<option value="0" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 0 ) {
					echo 'selected';
				} ?>><?php _e( 'Pending arrival', 'solidres' ); ?></option>
				<option value="1" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 1 ) {
					echo 'selected';
				} ?>><?php _e( 'Checked-in', 'solidres' ); ?></option>
				<option value="2" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 2 ) {
					echo 'selected';
				} ?>><?php _e( 'Checked-out', 'solidres' ); ?></option>
				<option value="3" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 3 ) {
					echo 'selected';
				} ?>><?php _e( 'Closed', 'solidres' ); ?></option>
				<option value="4" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 4 ) {
					echo 'selected';
				} ?>><?php _e( 'Canceled', 'solidres' ); ?></option>
				<option value="5" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 5 ) {
					echo 'selected';
				} ?>><?php _e( 'Confirmed', 'solidres' ); ?></option>
				<option value="-2" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == - 2 ) {
					echo 'selected';
				} ?>><?php _e( 'Trashed', 'solidres' ); ?></option>
			</select>
			<select name="filter_payment_status" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by payment status', 'solidres' ); ?></option>
				<option
					value="0" <?php if ( isset( $_GET['filter_payment_status'] ) && $_GET['filter_payment_status'] == 0 && $_GET['filter_payment_status'] != null ) {
					echo 'selected';
				} ?>><?php _e( 'Unpaid', 'solidres' ); ?></option>
				<option
					value="1" <?php if ( isset( $_GET['filter_payment_status'] ) && $_GET['filter_payment_status'] == 1 ) {
					echo 'selected';
				} ?>><?php _e( 'Completed', 'solidres' ); ?></option>
				<option
					value="2" <?php if ( isset( $_GET['filter_payment_status'] ) && $_GET['filter_payment_status'] == 2 ) {
					echo 'selected';
				} ?>><?php _e( 'Cancelled', 'solidres' ); ?></option>
				<option
					value="3" <?php if ( isset( $_GET['filter_payment_status'] ) && $_GET['filter_payment_status'] == 3 ) {
					echo 'selected';
				} ?>><?php _e( 'Pending', 'solidres' ); ?></option>
			</select>

			<div class="checkin_group">
				<table>
					<tr>
						<td><label for="checkin_from"><?php _e( 'From', 'solidres' ); ?></label></td>
						<td><input type="text" name="filter_checkin_from"
						           value="<?php echo isset( $filter_checkin_from ) ? $filter_checkin_from : '' ?>"
						           id="filter_checkin_from" class="srform_datepicker filter_checkin_checkout"
						           placeholder="<?php _e( 'Check-in from', 'solidres' ); ?>"></td>
					</tr>
					<tr>
						<td><label for="checkin_to"><?php _e( 'To', 'solidres' ); ?></label></td>
						<td><input type="text" name="filter_checkin_to"
						           value="<?php if ( isset( $filter_checkin_to ) ) {
							           echo $filter_checkin_to;
						           } ?>" id="filter_checkin_to" class="srform_datepicker filter_checkin_checkout"
						           placeholder="<?php _e( 'Check-in to', 'solidres' ); ?>"></td>
					</tr>
				</table>
				<div class="clr"></div>
			</div>
			<div class="checkout_group">
				<table>
					<tr>
						<td><label for="checkout_from"><?php _e( 'From', 'solidres' ); ?></label></td>
						<td><input type="text" name="filter_checkout_from"
						           value="<?php echo isset( $filter_checkout_from ) ? $filter_checkout_from : '' ?>"
						           id="filter_checkout_from" class="srform_datepicker filter_checkin_checkout"
						           placeholder="<?php _e( 'Check-out from', 'solidres' ); ?>"></td>
					</tr>
					<tr>
						<td><label for="checkout_to"><?php _e( 'To', 'solidres' ); ?></label></td>
						<td><input type="text" name="filter_checkout_to"
						           value="<?php echo isset( $filter_checkout_to ) ? $filter_checkout_to : '' ?>"
						           id="filter_checkout_to" class="srform_datepicker filter_checkin_checkout"
						           placeholder="<?php _e( 'Check-out to', 'solidres' ); ?>"></td>
					</tr>
				</table>
				<div class="clr"></div>
			</div>
			<?php submit_button( __( 'Filter', 'solidres' ), 'button', 'filter_reservation', false ); ?>
		</div>
	<?php }
}

function sr_reservations() {
	global $status, $ListTableData, $string_search;
	$reservations  = new SR_Reservation();
	$ListTableData = new SR_Reservations_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$reservations->listview( $action, $string_search, $status, $ListTableData );
}
