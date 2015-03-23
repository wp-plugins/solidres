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

class SR_Assets_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $wpdb, $status, $state, $string_search, $query_default;
		$assets        = new SR_Asset();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$asset_id      = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $asset_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;

		$query_default = "SELECT t1.id, t1.name, t1.state, t2.name as categoryname, t3.name as countryname,
			( SELECT COUNT(*) FROM {$wpdb->prefix}sr_room_types WHERE {$wpdb->prefix}sr_room_types.reservation_asset_id = t1.id ) as ofroomtype, access, hits
			FROM {$wpdb->prefix}sr_reservation_assets t1 LEFT JOIN {$wpdb->prefix}sr_categories t2 ON t1.category_id = t2.id LEFT JOIN {$wpdb->prefix}sr_countries t3 ON t1.country_id = t3.id";

		$state         = SR_Helper::get_listview_state( $status );
		if ( isset( $action ) && $action == 'edit' && isset( $asset_id ) && $asset_id != null ) {
			sr_edit_asset_item( $asset_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$assets->update_states( $action, $asset_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $asset_id ) && $asset_id != null ) {
			foreach ( $ids as $id ) {
				$asset_name = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}sr_reservation_assets WHERE id = %d", $id ) );
				$return     = $assets->delete( $id );
				if ( $return === false ) {
					$message = __( 'Error, can not delete <span class="bold"> ' . $asset_name->name . ' </span> because it is containing room types. You must delete all its room types first.', 'solidres' );
					SR_Helper::error_message( $message );
				} else {
					$message = __( '<span class="bold"> ' . $asset_name->name . ' </span> permanently deleted.', 'solidres' );
					SR_Helper::update_message( $message );
				}
			}
		}

		$filter_asset       = isset( $_GET['filter_asset'] ) ? $_GET['filter_asset'] : null;
		$filter_published   = isset( $_GET['filter_published'] ) ? $_GET['filter_published'] : '';
		$filter_category_id = isset( $_GET['filter_category_id'] ) ? $_GET['filter_category_id'] : null;
		$filter_country_id  = isset( $_GET['filter_country_id'] ) ? $_GET['filter_country_id'] : null;
		echo $filter_published;
		$query_filter = array();
		if ( is_numeric( $filter_published ) ) {
			$query_filter[] = ' t1.state = ' . $filter_published;
		}
		if ( $filter_category_id > 0 ) {
			$query_filter[] = ' t1.category_id = ' . $filter_category_id;
		}
		if ( $filter_country_id > 0 ) {
			$query_filter[] = ' t1.country_id = ' . $filter_country_id;
		}

		$results = '';
		if ( ( $string_search != '' && is_null( $filter_asset ) ) || ( $string_search != '' && isset( $filter_asset ) && $filter_published == '' && $filter_category_id == '' && $filter_country_id == '' ) ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$query_default = $query_default . ' WHERE t1.id = ' . (int) substr( $string_search, 3 );
				$results       = $wpdb->get_results( $query_default );
			} else {
				$query_default = $query_default . ' WHERE t1.name LIKE "%' . $string_search . '%"';
				$results       = $wpdb->get_results( $query_default );
			}
		} else if ( ( is_null( $state ) && is_null( $filter_asset ) ) || ( isset ( $filter_asset ) && $filter_published == '' && $filter_category_id == '' && $filter_country_id == '' && $string_search == '' ) ) {
			$results = $wpdb->get_results( $query_default . " WHERE t1.state = 0 OR t1.state = 1" );
		} else if ( $state != null && is_null( $filter_asset ) ) {
			$results = $wpdb->get_results( $wpdb->prepare( $query_default . " WHERE t1.state = %d", $state ) );
		} else if ( isset ( $filter_asset ) && ( $filter_published != '' || $filter_category_id != '' || $filter_country_id != '' ) && $string_search == '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter );
			$results       = $wpdb->get_results( $query_default );
		} else if ( isset ( $filter_asset ) && ( $filter_published != '' || $filter_category_id != '' || $filter_country_id != '' ) && $string_search != '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter ) . ' AND t1.name LIKE "%' . $string_search . '%"';
			$results       = $wpdb->get_results( $query_default );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$this->datatable [] = array( 'id'         => $result->id,
			                             'name'       => $result->name,
			                             'published'  => $published,
			                             'category'   => $result->categoryname,
			                             'ofroomtype' => $result->ofroomtype,
			                             'country'    => $result->countryname,
			                             'hits'       => $result->hits
			);
		}
		parent::__construct( array(
			'singular' => __( 'asset' ),
			'plural'   => __( 'assets' ),
			'ajax'     => false,
		) );
	}

	function no_items() {
		_e( 'No asset found.', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'published':
			case 'category':
			case 'ofroomtype':
			case 'country':
			case 'hits':
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
			'category'   => array( 'category', false ),
			'ofroomtype' => array( 'ofroomtype', false ),
			'country'    => array( 'country', false ),
			'hits'       => array( 'hits', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'id'         => __( 'ID', 'solidres' ),
			'name'       => __( 'Name', 'solidres' ),
			'published'  => __( 'Published', 'solidres' ),
			'category'   => __( 'Category', 'solidres' ),
			'ofroomtype' => __( '# of Room type', 'solidres' ),
			'country'    => __( 'Country', 'solidres' ),
			'hits'       => __( 'Hits', 'solidres' ),
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
			<select name="filter_category_id" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by categories', 'solidres' ); ?></option>
				<?php echo SR_Helper::get_asset_caterogy_selected( $_GET['filter_category_id'] ); ?>
			</select>
			<select name="filter_country_id" id="srform_filter_dropdow">
				<option value=""><?php _e( 'Filter by countries', 'solidres' ); ?></option>
				<?php echo SR_Helper::get_country_selected( $_GET['filter_country_id'] ); ?>
			</select>
			<?php submit_button( __( 'Filter', 'solidres' ), 'button', 'filter_asset', false ); ?>
		</div>
	<?php }
}

function sr_assets() {
	global $status, $ListTableData, $string_search;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Assets_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_reservation_assets', $action, $string_search, $status, $ListTableData );
}