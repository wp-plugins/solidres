<?php
/**
* Plugin Name: 	Solidres
* Plugin URI: 	http://www.solidres.com
* Description: 	Solidres - Hotel booking plugin for WordPress
* Author: 		Solidres Team
* Author URI: 	http://www.solidres.com
* Version: 		0.1.0
* Text Domain: 	solidres
* License   	GNU General Public License version 3, or later
* Copyright 	Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Solidres' ) ) :

	final class Solidres {

		public $version;

		public $nav = array();

		public $session = null;

		public $locale;

		public static function get_instance( $config = array() )
		{
			static $instance = null;

			if ( null === $instance ) {
				$instance = new Solidres( $config );
				$instance->define_constants();
				$instance->setup_globals();
				$instance->includes();
				$instance->setup_actions();
				$instance->setup_media();
			}

			return $instance;
		}

		private function __construct( $config = array() ) {

		}

		private function define_constants() {

			$relatives = $this->find_relatives();

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			foreach ( $relatives as $relative ) {
				$relative_key = strtoupper( substr( $relative, 9 ) );
				if ( is_plugin_active( $relative . '/' . $relative . '.php' ) ) {
					define( 'SR_PLUGIN_' . $relative_key . '_ENABLED', true );
				} else {
					define( 'SR_PLUGIN_' . $relative_key . '_ENABLED', false );
				}
			}

			define( 'SR_PLUGIN_COMPLEXTARIFF_ENABLED' , false ); // TODO remove this hard code when we have Complex Tariff plugin
			define( 'SR_PLUGIN_LIMITBOOKING_ENABLED' , false ); // TODO remove this hard code when we have Complex Tariff plugin
		}

		private function setup_globals() {
			$this->version = '0.1.0';
			$this->locale = get_locale();
		}

		private function includes() {

			include_once( 'includes/core-functions.php' );

			if ( $this->is_request( 'ajax' ) ) {
				include_once( 'libraries/ajax/ajax.php' );
			}

			if ( $this->is_request( 'frontend' ) ) {
				include_once( 'includes/template-hooks.php' );
				include_once( 'includes/breadcrumb.php' );
			}

			include_once( 'libraries/asset/asset.php' );
			include_once( 'libraries/calendar/calendar.php' );
			include_once( 'libraries/category/category.php' );
			include_once( 'libraries/config/config.php' );
			include_once( 'libraries/country/country.php' );
			include_once( 'libraries/coupon/coupon.php' );
			include_once( 'libraries/currency/currency.php' );
			include_once( 'libraries/customfield/customfield.php' );
			include_once( 'libraries/extra/extra.php' );
			include_once( 'libraries/media/media.php' );
			include_once( 'libraries/reservation/reservation.php' );
			include_once( 'libraries/roomtype/roomtype.php' );
			include_once( 'libraries/state/state.php' );
			include_once( 'libraries/tariff/tariff.php' );
			include_once( 'libraries/tax/tax.php' );
			include_once( 'libraries/utilities/utilities.php' );

			include_once( 'includes/activate.php' );
			include_once( 'includes/install-data.php' );
			include_once( 'includes/install-sampledata.php' );
			include_once( 'includes/uninstall.php' );
			include_once( 'includes/class-sr-helper.php' );
			include_once( 'includes/widgets/class-sr-widget-check-availability.php' );
			include_once( 'includes/widgets/class-sr-widget-currency.php' );
			include_once( 'includes/class-sr-form-handler.php' );

			if ( $this->is_request( 'admin' ) ) {

				include_once( 'includes/class-solidres-list-table.php' );
				include_once( 'admin/register-menu.php' );
				include_once( 'includes/top-menu.php' );
				include_once( 'admin/includes/assets.php' );
				include_once( 'admin/includes/customers.php' );
				include_once( 'admin/includes/reservations.php' );
				include_once( 'admin/includes/coupons_extras.php' );
				include_once( 'admin/includes/options.php' );
			}
		}

		private function setup_actions() {
			add_action( 'init', array($this, 'include_assets') );
			add_action( 'init', array($this, 'init_session') );
			add_action( 'plugins_loaded', array($this, 'solidres_load_language') );
			add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
			register_activation_hook( __FILE__, 'solidres_install' );
			register_activation_hook( __FILE__, 'solidres_install_data' );
			register_uninstall_hook( __FILE__, 'solidres_uninstall' );
		}

		public function init_session() {

			if ( $this->is_request( 'frontend' ) ) {

				// let users change the session cookie name
				if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
					define( 'WP_SESSION_COOKIE', '_wp_session' );
				}

				if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
					include 'libraries/session/class-recursive-arrayaccess.php';
				}

				// Include utilities class
				if ( ! class_exists( 'WP_Session_Utils' ) ) {
					include 'libraries/session/class-wp-session-utils.php';
				}

				// Include WP_CLI routines early
				/*if ( defined( 'WP_CLI' ) && WP_CLI ) {
					include 'libraries/session/wp-cli.php';
				}*/

				// Only include the functionality if it's not pre-defined.
				if ( ! class_exists( 'WP_Session' ) ) {
					include 'libraries/session/class-wp-session.php';
					include 'libraries/session/wp-session.php';
				}

				$this->session = WP_Session::get_instance();
			}
		}

		/**
		 * Todo: move this method into better locations to avoid too many files including
		 *
		 */
		public function include_assets() {
			global $wp_scripts;
			$options = get_option( 'solidres_plugin' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'jquery-ui-button' );

			wp_register_script( 'solidres_main_script', solidres()->plugin_url() . '/assets/js/main.min.js' , array('jquery'), $this->version, true );

			if ( $this->is_request( 'frontend' ) ) {
				wp_register_script( 'solidres_site_script', solidres()->plugin_url() . '/assets/js/site.min.js' , array('jquery'), $this->version, true );
			}

			wp_localize_script( 'solidres_main_script', 'solidres', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'ajaxnonce' => wp_create_nonce( 'ajax_post_validation' ),
				'nonce_load_states' => wp_create_nonce( 'load-states' ),
				'nonce_load_taxes' => wp_create_nonce( 'load-taxes' ),
				'nonce_load_coupons' => wp_create_nonce( 'load-coupons' ),
				'nonce_load_extras' => wp_create_nonce( 'load-extras' ),
				'nonce_edit_reservation' => wp_create_nonce( 'edit-reservation' ),
				'nonce_save_note' => wp_create_nonce( 'save-note' ),
				'nonce_delete_room' => wp_create_nonce( 'delete-room' ),
				'nonce_confirm_delete_room' => wp_create_nonce( 'confirm-delete-room' ),
				'plugin_url' => solidres()->plugin_url(),
			) );

			wp_localize_script( 'solidres_site_script', 'solidres', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'ajaxnonce' => wp_create_nonce( 'ajax_post_validation' ),
				'nonce_load_states' => wp_create_nonce( 'load-states' ),
				'nonce_load_taxes' => wp_create_nonce( 'load-taxes' ),
				'nonce_load_calendar' => wp_create_nonce( 'load-calendar' ),
				'nonce_process_reservation' => wp_create_nonce( 'process-reservation' ),
				'nonce_load_room_form' => wp_create_nonce( 'load-room-form' ),
				'nonce_load_date_form' => wp_create_nonce( 'load-date-form' ),
				'nonce_cal_tariff' => wp_create_nonce( 'cal-tariff' ),
				'child_max_age_limit' => isset( $options['child_max_age_limit'] ) ? $options['child_max_age_limit'] : 17,
				'nonce_set_currency' => wp_create_nonce( 'set-currency' ),
			) );

			$lang = get_bloginfo( 'language' );

			wp_register_script( 'solidres_datepicker_lang', solidres()->plugin_url() . '/assets/lib/datePicker/localization/jquery.ui.datepicker-'.$lang.'.js' );
			wp_enqueue_script( 'solidres_datepicker_lang' );

			wp_enqueue_script( 'solidres_main_script' );
			if ( $this->is_request( 'frontend' ) ) {
				wp_enqueue_script( 'solidres_site_script' );
			}

			wp_register_style( 'solidres_styles' , solidres()->plugin_url() . '/assets/css/style.css' );
			wp_enqueue_style( 'solidres_styles' );

			wp_register_script( 'solidres_validate', solidres()->plugin_url() . '/assets/lib/validate/jquery.validate.min.js' );
			wp_enqueue_script( 'solidres_validate' );

			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

			wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css', array() );

			wp_register_script( 'solidres_editable', solidres()->plugin_url() . '/assets/lib/editable/js/jqueryui-editable.min.js', array( 'jquery-ui-tooltip' ), false, true  );
			wp_enqueue_script( 'solidres_editable' );

			wp_register_style( 'solidres_editable', solidres()->plugin_url() . '/assets/lib/editable/css/jqueryui-editable.css' );
			wp_enqueue_style( 'solidres_editable', false );
			wp_enqueue_media();

			wp_enqueue_script( 'custom-header' );

			if ( $this->is_request( 'frontend' ) ) {
				wp_register_script( 'solidres_bootstrap', solidres()->plugin_url() . '/assets/lib/bootstrap/js/bootstrap.min.js' );
				wp_enqueue_script( 'solidres_bootstrap' );

				wp_register_style( 'solidres_bootstrap', solidres()->plugin_url() . '/assets/lib/bootstrap/css/bootstrap.min.css' );
				wp_enqueue_style( 'solidres_bootstrap' );

				wp_register_style( 'solidres_bootstrap_responsive', solidres()->plugin_url() . '/assets/lib/bootstrap/css/bootstrap-responsive.min.css' );
				wp_enqueue_style( 'solidres_bootstrap_responsive' );

				wp_register_style( 'solidres_colorbox', solidres()->plugin_url() . '/assets/lib/colorbox/colorbox.css' );
				wp_register_script( 'solidres_colorbox', solidres()->plugin_url() . '/assets/lib/colorbox/jquery.colorbox.min.js' );
				$allowed_language_tags = array(
					'ar-AA', 'bg-BG', 'ca-ES', 'cs-CZ', 'da-DK', 'de-DE', 'el-GR', 'es-ES', 'et-EE',
					'fa-IR', 'fi-FI', 'fr-FR', 'he-IL', 'hr-HR', 'hu-HU', 'it-IT', 'ja-JP', 'ko-KR',
					'lv-LV', 'nb-NO', 'nl-NL', 'pl-PL', 'pt-BR', 'ro-RO', 'ru-RU', 'sk-SK', 'sr-RS',
					'sv-SE', 'tr-TR', 'uk-UA', 'zh-CN', 'zh-TW',
				);
				$locale = str_replace( '_', '-', $this->locale );
				if ( in_array( $locale, $allowed_language_tags ) ) {
					wp_register_script( 'solidres_colorbox_lc', solidres()->plugin_url() . '/assets/lib/colorbox/i18n/jquery.colorbox-' . $allowed_language_tags . '.js' );
				}

				wp_enqueue_style( 'solidres_colorbox' );
				wp_enqueue_script( 'solidres_colorbox' );
				wp_enqueue_script( 'solidres_colorbox_lc' );

				$protocol = 'https';
				wp_register_script( 'solidres_site_map', $protocol . '://maps.google.com/maps/api/js?sensor=true', __FILE__ );
				wp_enqueue_script( 'solidres_site_map' );
			}
		}

		public function solidres_load_language() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'solidres' );

			if ( $this->is_request( 'admin' ) ) {
				load_textdomain( 'solidres', WP_LANG_DIR . '/solidres/solidres-admin-' . $locale . '.mo' );
				load_textdomain( 'solidres', WP_LANG_DIR . '/plugins/solidres-admin-' . $locale . '.mo' );
			}

			load_textdomain( 'solidres', WP_LANG_DIR . '/solidres/solidres-' . $locale . '.mo' );
			load_plugin_textdomain( 'solidres', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'solidres_template_path', 'solidres/' );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Get plugin url
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		public function setup_media() {
			$options = get_option( 'solidres_plugin' );
			$small_thumbnail_width = ( ! empty( $options['sr_small_thumbnail_width'] ) ) ? $options['sr_small_thumbnail_width'] : 75;
			$small_thumbnail_height = ( ! empty( $options['sr_small_thumbnail_width'] ) ) ? $options['sr_small_thumbnail_height'] : 75;
			$medium_thumbnail_width = ( ! empty( $options['sr_medium_thumbnail_width'] ) ) ? $options['sr_medium_thumbnail_width'] : 300;
			$medium_thumbnail_height = ( ! empty( $options['sr_medium_thumbnail_height'] ) ) ? $options['sr_medium_thumbnail_height'] : 250;

			add_image_size( 'sr_small_thumbnail', $small_thumbnail_width, $small_thumbnail_height, true );
			add_image_size( 'sr_medium_thumbnail', $medium_thumbnail_width, $medium_thumbnail_height, true );
		}

		private function find_relatives() {
			$plugins = scandir( WP_PLUGIN_DIR );
			$relatives = array();

			foreach ( $plugins as $plugin ) {
				if ( $plugin === '.' OR $plugin === '..' OR substr( $plugin, 0, 8 ) !== 'solidres') continue;

				if ( is_dir( WP_PLUGIN_DIR . '/' . $plugin ) ) {
					$relatives[] = $plugin;
				}
			}

			return $relatives;
		}

		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		public function include_template_functions() {
			if ( $this->is_request( 'frontend' ) ) {
				include_once( 'includes/template-functions.php' );
			}
		}
	}

	function solidres() {
		return Solidres::get_instance();
	}

	/**
	 * Hook Solidres early onto the 'plugins_loaded' action..
	 *
	 * This gives all other plugins the chance to load before Solidres, to get
	 * their actions, filters, and overrides setup without Solidres being in the
	 * way.
	 */
	if ( defined( 'SOLIDRES_LATE_LOAD' ) ) {
		add_action( 'plugins_loaded', 'solidres', (int) SOLIDRES_LATE_LOAD );
	} else {
		$GLOBALS['solidres'] = solidres();
	}

endif;