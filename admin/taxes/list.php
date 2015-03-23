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

class SR_Taxes_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $wpdb, $state, $status, $string_search, $query_default;
		$taxes         = new SR_Tax();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$tax_id        = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $tax_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$query_default = "SELECT * FROM {$wpdb->prefix}sr_taxes";
		$state         = SR_Helper::get_listview_state( $status );

		if ( isset( $action ) && $action == 'edit' && isset( $tax_id ) && $tax_id != null ) {
			sr_edit_tax_item( $tax_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$taxes->update_states( $action, $tax_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $tax_id ) && $tax_id != null ) {
			foreach ( $ids as $id ) {
				$tax_name = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}sr_taxes WHERE id = %d", $id ) );
				$return   = $taxes->delete( $id );
				if ( $return === false ) {
					$message = __( 'Error, can not delete <span class="bold"> ' . $tax_name->name . ' </span> because it is containing states. You must delete all its states first.', 'solidres' );
					SR_Helper::error_message( $message );
				} else {
					$message = __( '<span class="bold"> ' . $tax_name->name . ' </span> permanently deleted.', 'solidres' );
					SR_Helper::update_message( $message );
				}
			}
		}

		$filter_taxes     = isset( $_GET['filter_taxes'] ) ? $_GET['filter_taxes'] : null;
		$filter_published = isset( $_GET['filter_published'] ) ? $_GET['filter_published'] : '';
		$query_filter     = array();
		$results          = '';
		if ( is_numeric( $filter_published ) ) {
			$query_filter[] = ' state = ' . $filter_published;
		}
		if ( ( $string_search != '' && is_null( $filter_taxes ) ) || ( $string_search != '' && isset( $filter_taxes ) && $filter_published == '' ) ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$query_default = $query_default . ' WHERE id = ' . (int) substr( $string_search, 3 );
				$results       = $wpdb->get_results( $query_default );
			} else {
				$query_default = $query_default . ' WHERE name LIKE "%' . $string_search . '%"';
				$results       = $wpdb->get_results( $query_default );
			}
		} else if ( ( is_null( $state ) && is_null( $filter_taxes ) ) || ( isset ( $filter_taxes ) && $filter_published == '' && $string_search == '' ) ) {
			$results = $wpdb->get_results( $query_default . " WHERE state = 0 OR state = 1" );
		} else if ( $state != null && is_null( $filter_taxes ) ) {
			$results = $wpdb->get_results( $wpdb->prepare( $query_default . " WHERE state = %d", $state ) );
		} else if ( isset ( $filter_taxes ) && ( $filter_published != '' ) && $string_search == '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter );
			$results       = $wpdb->get_results( $query_default );
		} else if ( isset ( $filter_taxes ) && ( $filter_published != '' ) && $string_search != '' ) {
			$query_default = $query_default . ' WHERE ' . implode( ' AND', $query_filter ) . ' AND name LIKE "%' . $string_search . '%"';
			$results       = $wpdb->get_results( $query_default );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$this->datatable [] = array( 'id'        => $result->id,
			                             'name'      => $result->name,
			                             'rate'      => $result->rate,
			                             'published' => $published
			);
		}
		parent::__construct( array(
			'singular' => __( 'tax' ),
			'plural'   => __( 'taxes' ),
			'ajax'     => false,
		) );
	}

	function no_items() {
		_e( 'No tax found!', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'rate':
			case 'published':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'        => array( 'id', false ),
			'name'      => array( 'name', false ),
			'rate'      => array( 'rate', false ),
			'published' => array( 'published', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'id'        => __( 'ID', 'solidres' ),
			'name'      => __( 'Name', 'solidres' ),
			'rate'      => __( 'Rate', 'solidres' ),
			'published' => __( 'Published', 'solidres' ),
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
			<?php submit_button( __( 'Filter', 'solidres' ), 'button', 'filter_taxes', false ); ?>
		</div>
	<?php }
}

function sr_taxes() {
	global $ListTableData, $status, $string_search;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Taxes_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_taxes', $action, $string_search, $status, $ListTableData );
}