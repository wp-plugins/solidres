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

class SR_Coupons_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $status, $state, $string_search, $query_default, $wpdb;
		$coupons       = new SR_Coupon();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$coupon_id     = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $coupon_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$query_default = "SELECT t1.*, t2.name as assetname FROM {$wpdb->prefix}sr_coupons t1  LEFT JOIN {$wpdb->prefix}sr_reservation_assets t2 ON t1.reservation_asset_id = t2.id";
		$state         = SR_Helper::get_listview_state( $status );

		if ( isset( $action ) && $action == 'edit' && isset( $coupon_id ) && $coupon_id != null ) {
			sr_edit_coupon_item( $coupon_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$coupons->update_states( $action, $coupon_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $coupon_id ) && $coupon_id != null ) {
			foreach ( $ids as $id ) {
				$coupons->delete( $id );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 coupon permanently deleted.', 'solidres' );
				SR_Helper::error_message( $message );
			} else {
				$message = __( count( $ids ) . ' coupons permanently deleted.', 'solidres' );
				SR_Helper::update_message( $message );
			}
		}

		$filter_coupons              = isset( $_GET['filter_coupons'] ) ? $_GET['filter_coupons'] : null;
		$filter_published            = isset( $_GET['filter_published'] ) ? $_GET['filter_published'] : '';
		$filter_reservation_asset_id = isset( $_GET['filter_reservation_asset_id'] ) ? $_GET['filter_reservation_asset_id'] : null;
		$query_filter                = array();
		$results                     = '';
		if ( is_numeric( $filter_published ) ) {
			$query_filter[] = ' t1.state = ' . $filter_published;
		}
		if ( $filter_reservation_asset_id > 0 ) {
			$query_filter[] = ' t1.reservation_asset_id = ' . $filter_reservation_asset_id;
		}
		if ( ( $string_search != '' && is_null( $filter_coupons ) ) || ( $string_search != '' && isset( $filter_coupons ) && $filter_published == '' && $filter_reservation_asset_id == '' ) ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$query_default = $query_default . ' WHERE t1.id = ' . (int) substr( $string_search, 3 );
				$results       = $wpdb->get_results( $query_default );
			} else {
				$query_default = $query_default . ' WHERE t1.coupon_name LIKE "%%' . $string_search . '%%"';
				$results       = $wpdb->get_results( $query_default );
			}
		} else if ( ( is_null( $state ) && is_null( $filter_coupons ) ) || ( isset ( $filter_coupons ) && $filter_published == '' && $filter_reservation_asset_id == '' && $string_search == '' ) ) {
			$results = $wpdb->get_results( $query_default . " WHERE t1.state = 0 OR t1.state = 1" );
		} else if ( $state != null && is_null( $filter_coupons ) ) {
			$results = $wpdb->get_results( $wpdb->prepare( $query_default . " WHERE t1.state = %d", $state ) );
		} else if ( isset ( $filter_coupons ) && ( $filter_published != '' || $filter_reservation_asset_id != '' ) && $string_search == '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter );
			$results       = $wpdb->get_results( $query_default );
		} else if ( isset ( $filter_coupons ) && ( $filter_published != '' || $filter_reservation_asset_id != '' ) && $string_search != '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter ) . ' AND t1.coupon_name LIKE "%%' . $string_search . '%%"';
			$results       = $wpdb->get_results( $query_default );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$quantity           = ( $result->quantity == '' ) ? __( 'Unlimited', 'solidres' ) : $result->quantity;
			$percent            = ( $result->is_percent == 1 ) ? __( 'Yes', 'solidres' ) : __( 'No', 'solidres' );
			$this->datatable [] = array( 'id'         => $result->id,
			                             'name'       => $result->coupon_name,
			                             'published'  => $published,
			                             'asset'      => $result->assetname,
			                             'couponcode' => '<span class="couponcode">' . $result->coupon_code . '</span>',
			                             'amount'     => $result->amount,
			                             'percent'    => $percent,
			                             'quantity'   => $quantity,
			                             'validfrom'  => $result->valid_from_checkin,
			                             'validto'    => $result->valid_to_checkin
			);
		}
		parent::__construct( array(
			'singular' => __( 'coupon' ),
			'plural'   => __( 'coupons' ),
			'ajax'     => false,
		) );
	}

	function no_items() {
		_e( 'No coupon found!', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'published':
			case 'asset':
			case 'couponcode':
			case 'amount':
			case 'percent':
			case 'quantity':
			case 'validfrom':
			case 'validto':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'         => array( 'id', false ),
			'name'       => array( 'name', false ),
			'published'  => array( 'published', false ),
			'asset'      => array( 'asset', false ),
			'couponcode' => array( 'couponcode', false ),
			'amount'     => array( 'amount', false ),
			'percent'    => array( 'percent', false ),
			'quantity'   => array( 'quantity', false ),
			'validfrom'  => array( 'validfrom', false ),
			'validto'    => array( 'validto', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'id'         => __( 'ID', 'solidres' ),
			'name'       => __( 'Coupon name', 'solidres' ),
			'published'  => __( 'Published', 'solidres' ),
			'asset'      => __( 'Asset', 'solidres' ),
			'couponcode' => __( 'Coupon code', 'solidres' ),
			'amount'     => __( 'Amount', 'solidres' ),
			'percent'    => __( 'Percent', 'solidres' ),
			'quantity'   => __( 'Quantity', 'solidres' ),
			'validfrom'  => __( 'Valid from', 'solidres' ),
			'validto'    => __( 'Valid to', 'solidres' ),
		);

		return $columns;
	}

	function extra_tablenav( $which ) {
		if ( 'top' != $which ) {
			return;
		} ?>
		<div class="alignleft actions bulkactions">
			<select name="filter_published" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by status', 'solidres' ); ?></option>
				<option value="1" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 1 ) {
					echo 'selected';
				} ?>><?php _e( 'Published', 'solidres' ); ?></option>
				<option value="0" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == 0 ) {
					echo 'selected';
				} ?>><?php _e( 'Unpublished', 'solidres' ); ?></option>
				<option value="-2" <?php if ( isset( $_GET['filter_published'] ) && $_GET['filter_published'] == - 2 ) {
					echo 'selected';
				} ?>><?php _e( 'Trashed', 'solidres' ); ?></option>
			</select>
			<select name="filter_reservation_asset_id" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by assets', 'solidres' ); ?></option>
				<?php echo SR_Helper::get_reservation_asset_selected( $_GET['filter_reservation_asset_id'] ); ?>
			</select>
			<?php submit_button( __( 'Filter', 'solidres' ), 'button', 'filter_coupons', false ); ?>
		</div>
	<?php }
}

function sr_coupons() {
	global $ListTableData, $string_search, $status;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Coupons_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_coupons', $action, $string_search, $status, $ListTableData );
}