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

class SR_Room_Types_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $wpdb, $status, $state, $string_search, $query_default;
		$room_types    = new SR_Room_Type();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$room_type_id  = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $room_type_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$query_default = "SELECT t1.id, t1.name, t1.state, t2.name as reservation_assets_name, (SELECT COUNT(*) FROM {$wpdb->prefix}sr_rooms WHERE t1.id = {$wpdb->prefix}sr_rooms.room_type_id) as numofroom, occupancy_adult, occupancy_child FROM {$wpdb->prefix}sr_room_types t1 LEFT JOIN {$wpdb->prefix}sr_reservation_assets t2 ON t1.reservation_asset_id = t2.id";
		$state         = SR_Helper::get_listview_state( $status );

		if ( isset( $action ) && $action == 'edit' && isset( $room_type_id ) && $room_type_id != null ) {
			sr_edit_room_type_item( $room_type_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$room_types->update_states( $action, $room_type_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $room_type_id ) && $room_type_id != null ) {
			foreach ( $ids as $id ) {
				$room_types->delete( $id );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 room types permanently deleted.', 'solidres' );
				SR_Helper::error_message( $message );
			} else {
				$message = __( count( $ids ) . ' rooms types permanently deleted.', 'solidres' );
				SR_Helper::update_message( $message );
			}
		}

		$filter_published            = isset( $_GET['filter_published'] ) ? $_GET['filter_published'] : '';
		$filter_reservation_asset_id = isset( $_GET['filter_reservation_asset_id'] ) ? $_GET['filter_reservation_asset_id'] : null;
		$filter_roomtypes            = isset( $_GET['filter_roomtypes'] ) ? $_GET['filter_roomtypes'] : null;
		$query_filter                = array();
		$results                     = '';
		if ( is_numeric( $filter_published ) ) {
			$query_filter[] = ' t1.state = ' . $filter_published;
		}
		if ( $filter_reservation_asset_id > 0 ) {
			$query_filter[] = ' t1.reservation_asset_id = ' . $filter_reservation_asset_id;
		}
		if ( ( $string_search != '' && is_null( $filter_roomtypes ) ) || ( $string_search != '' && isset( $filter_roomtypes ) && $filter_published == '' && $filter_reservation_asset_id == '' ) ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$query_default = $query_default . ' WHERE t1.id = ' . (int) substr( $string_search, 3 );
				$results       = $wpdb->get_results( $query_default );
			} else {
				$query_default = $query_default . ' WHERE t1.name LIKE "%%' . $string_search . '%%"';
				$results       = $wpdb->get_results( $query_default );
			}
		} else if ( ( is_null( $state ) && is_null( $filter_roomtypes ) ) || ( isset ( $filter_roomtypes ) && $filter_published == '' && $filter_reservation_asset_id == '' && $string_search == '' ) ) {
			$results = $wpdb->get_results( $query_default . " WHERE t1.state = 0 OR t1.state = 1" );
		} else if ( $state != null && is_null( $filter_roomtypes ) ) {
			$results = $wpdb->get_results( $wpdb->prepare( $query_default . " WHERE t1.state = %d", $state ) );
		} else if ( isset ( $filter_roomtypes ) && ( $filter_published != '' || $filter_reservation_asset_id != '' ) && $string_search == '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter );
			$results       = $wpdb->get_results( $query_default );
		} else if ( isset ( $filter_roomtypes ) && ( $filter_published != '' || $filter_reservation_asset_id != '' ) && $string_search != '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter ) . ' AND t1.name LIKE "%%' . $string_search . '%%"';
			$results       = $wpdb->get_results( $query_default );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$this->datatable [] = array( 'id'        => $result->id,
			                             'name'      => $result->name,
			                             'published' => $published,
			                             'asset'     => $result->reservation_assets_name,
			                             'numofroom' => $result->numofroom,
			                             'adult'     => $result->occupancy_adult,
			                             'child'     => $result->occupancy_child
			);
		}
		parent::__construct( array(
			'singular' => __( 'roomtype' ),
			'plural'   => __( 'roomtypes' ),
			'ajax'     => false,
		) );
	}

	function no_items() {
		_e( 'No room type found!' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'published':
			case 'asset':
			case 'numofroom':
			case 'adult':
			case 'child':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'        => array( 'id', false ),
			'name'      => array( 'name', false ),
			'published' => array( 'published', false ),
			'asset'     => array( 'asset', false ),
			'numofroom' => array( 'numofroom', false ),
			'adult'     => array( 'adult', false ),
			'child'     => array( 'child', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'id'        => __( 'ID', 'solidres' ),
			'name'      => __( 'Name', 'solidres' ),
			'published' => __( 'Published', 'solidres' ),
			'asset'     => __( 'Asset', 'solidres' ),
			'numofroom' => __( 'Number of rooms', 'solidres' ),
			'adult'     => __( '#Adult', 'solidres' ),
			'child'     => __( '#Child(ren)', 'solidres' ),
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
			</select>
			<select name="filter_reservation_asset_id" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by assets', 'solidres' ); ?></option>
				<?php echo SR_Helper::get_reservation_asset_selected( $_GET['filter_reservation_asset_id'] ); ?>
			</select>
			<?php submit_button( __( 'Filter', 'solidres' ), 'button', 'filter_roomtypes', false ); ?>
		</div>
	<?php }
}

function sr_room_types() {
	global $status, $string_search, $ListTableData;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Room_Types_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_room_types', $action, $string_search, $status, $ListTableData );
}
