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

function solidres_install(){
	global  $wpdb;
	require_once(ABSPATH. 'wp-admin/includes/upgrade.php' );
	$solidres_installed_version = get_option( 'solidres_db_version' );

	if ( $solidres_installed_version != solidres()->version ) {
		$tables = array();
		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_categories (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(200) NOT NULL,
			slug VARCHAR(200) NOT NULL,
			state TINYINT(1) NOT NULL DEFAULT 0,
			parent_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY(id))
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_countries (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(45) NOT NULL,
			code_2 VARCHAR(10) NOT NULL,
			code_3 VARCHAR(10) NOT NULL,
			state TINYINT(11) NOT NULL DEFAULT 0,
			checked_out INT(11) UNSIGNED NOT NULL DEFAULT 0,
			checked_out_time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			created_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			created_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			modified_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			modified_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY(id))
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_geo_states (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			country_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
			name VARCHAR(45) NOT NULL,
			code_2 VARCHAR(10) NOT NULL,
			code_3 VARCHAR(10) NOT NULL,
			state TINYINT(3) NOT NULL DEFAULT 0,
			PRIMARY KEY(id),
			INDEX fk_sr_geo_states_sr_countries1_idx(country_id ASC),
			CONSTRAINT fk_sr_geo_states_sr_countries1
			FOREIGN KEY(country_id)
			REFERENCES {$wpdb->prefix}sr_countries (id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_customer_groups (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			state TINYINT(3) NOT NULL DEFAULT 0,
			PRIMARY KEY(id))
		ENGINE = InnoDB;";

		/*$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_customers (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			customer_group_id INT(11) UNSIGNED NULL DEFAULT NULL,
			user_id INT(11) UNSIGNED NOT NULL COMMENT 'The Joomla User Id',
			customer_code VARCHAR(255) NULL,
			firstname VARCHAR(255) NULL,
			middlename VARCHAR(255) NULL,
			lastname VARCHAR(255) NULL,
			vat_number VARCHAR(255) NULL DEFAULT NULL,
			company VARCHAR(255) NULL,
			phonenumber VARCHAR(45) NULL,
			address1 VARCHAR(255) NULL,
			address2 VARCHAR(255) NULL,
			city VARCHAR(45) NULL,
			zipcode VARCHAR(45) NULL,
			country_id INT(11) UNSIGNED NULL DEFAULT NULL,
			geo_state_id INT(11) UNSIGNED NULL DEFAULT NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_customers_sr_customer_groups1_idx(customer_group_id ASC),
			INDEX fk_sr_customers_sr_countries1_idx(country_id ASC),
			INDEX fk_sr_customers_sr_geo_states1_idx(geo_state_id ASC),
			CONSTRAINT fk_sr_customers_sr_customer_groups1
			FOREIGN KEY(customer_group_id)
			REFERENCES {$wpdb->prefix}sr_customer_groups(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_customers_sr_countries1
			FOREIGN KEY(country_id)
			REFERENCES {$wpdb->prefix}sr_countries(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_customers_sr_geo_states1
			FOREIGN KEY(geo_state_id)
			REFERENCES {$wpdb->prefix}sr_geo_states(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";*/

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_currencies(
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			currency_name VARCHAR(45) NOT NULL,
			currency_code VARCHAR(10) NOT NULL,
			state TINYINT(3) NOT NULL DEFAULT 0,
			exchange_rate FLOAT UNSIGNED NOT NULL DEFAULT 0,
			sign VARCHAR(10) NOT NULL,
			filter_range VARCHAR(255) NULL,
			PRIMARY KEY(id))
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_taxes (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			rate FLOAT NOT NULL,
			state TINYINT(3) NOT NULL,
			country_id INT(11) UNSIGNED NULL,
			geo_state_id INT(11) UNSIGNED NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_taxes_sr_countries1_idx(country_id ASC),
			INDEX fk_sr_taxes_sr_geo_states1_idx(geo_state_id ASC),
			CONSTRAINT fk_sr_taxes_sr_countries1
			FOREIGN KEY(country_id)
			REFERENCES {$wpdb->prefix}sr_countries(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_taxes_sr_geo_states1
			FOREIGN KEY(geo_state_id)
			REFERENCES {$wpdb->prefix}sr_geo_states(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_assets (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`asset_id` INT(11) UNSIGNED NULL DEFAULT NULL,
			`category_id` INT(11) UNSIGNED NULL DEFAULT NULL,
			`name` VARCHAR(255) NOT NULL,
			`alias` VARCHAR(255) NOT NULL,
			`address_1` VARCHAR(255) NOT NULL,
			`address_2` VARCHAR(255) NOT NULL,
			`city` VARCHAR(45) NOT NULL,
			`postcode` VARCHAR(45) NOT NULL,
			`phone` VARCHAR(30) NOT NULL,
			`description` TEXT NOT NULL,
			`email` VARCHAR(50) NOT NULL,
			`website` VARCHAR(255) NOT NULL,
			`featured` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			`fax` VARCHAR(45) NOT NULL,
			`rating` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			`geo_state_id` INT(11) UNSIGNED NULL DEFAULT NULL,
			`country_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`modified_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`state` TINYINT(3) NOT NULL DEFAULT 0,
			`checked_out` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`ordering` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`archived` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
			`approved` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
			`access` INT(11) UNSIGNED NOT NULL DEFAULT 1,
			`params` TEXT NOT NULL,
			`language` VARCHAR(10) NOT NULL,
			`hits` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`metakey` TEXT NOT NULL,
			`metadesc` TEXT NOT NULL,
			`metadata` TEXT NOT NULL,
			`xreference` VARCHAR(50) NOT NULL,
			`partner_id` INT(11) UNSIGNED NULL,
			`lat` FLOAT(10,6) NULL DEFAULT 0,
			`lng` FLOAT(10,6) NULL DEFAULT 0,
			`default` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			`deposit_required` TINYINT(3) UNSIGNED NULL DEFAULT 0,
			`deposit_is_percentage` TINYINT(3) UNSIGNED NULL DEFAULT 1,
			`deposit_amount` DECIMAL(12,2) UNSIGNED NULL,
			`currency_id` INT(11) UNSIGNED NOT NULL,
			`tax_id` INT(11) UNSIGNED NULL,
			PRIMARY KEY (`id`),
			INDEX `fk_sr_reservation_assets_sr_countries1_idx` (`country_id` ASC),
			INDEX `fk_sr_reservation_assets_sr_geo_states1_idx` (`geo_state_id` ASC),
			/*INDEX `fk_sr_reservation_assets_sr_customers1_idx` (`partner_id` ASC),*/
			INDEX `fk_sr_reservation_assets_sr_currencies1_idx` (`currency_id` ASC),
			INDEX `fk_jos_sr_reservation_assets_jos_sr_taxes1_idx` (`tax_id` ASC),
			CONSTRAINT `fk_sr_reservation_assets_sr_countries1`
			FOREIGN KEY (`country_id` )
			REFERENCES {$wpdb->prefix}sr_countries(`id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT `fk_sr_reservation_assets_sr_geo_states1`
			FOREIGN KEY (`geo_state_id` )
			REFERENCES {$wpdb->prefix}sr_geo_states(`id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			/*CONSTRAINT `fk_sr_reservation_assets_sr_customers1`
			FOREIGN KEY (`partner_id` )
			REFERENCES {$wpdb->prefix}sr_customers(`id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,*/
			CONSTRAINT `fk_sr_reservation_assets_sr_currencies1`
			FOREIGN KEY (`currency_id`)
			REFERENCES {$wpdb->prefix}sr_currencies(`id`)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT `fk_jos_sr_reservation_assets_jos_sr_taxes1`
			FOREIGN KEY (`tax_id`)
			REFERENCES {$wpdb->prefix}sr_taxes(`id`)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_room_types (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			reservation_asset_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
			name VARCHAR(255) NOT NULL,
			alias VARCHAR(255) NOT NULL,
			description TEXT NOT NULL,
			state TINYINT(3) NOT NULL DEFAULT 0,
			checked_out INT(11) UNSIGNED NOT NULL DEFAULT 0,
			checked_out_time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			created_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			created_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			modified_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			modified_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			language VARCHAR(10) NOT NULL,
			params TEXT NOT NULL,
			featured TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			ordering INT(11) NOT NULL DEFAULT 0,
			occupancy_adult TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
			occupancy_child TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
			smoking TINYINT(2) NOT NULL DEFAULT 0,
			PRIMARY KEY(id),
			INDEX fk_sr_room_types_sr_reservation_assets1_idx(reservation_asset_id ASC),
			CONSTRAINT fk_sr_room_types_sr_reservation_assets1
			FOREIGN KEY(reservation_asset_id)
			REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_coupons (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			state TINYINT(3) NOT NULL DEFAULT 0,
			coupon_name VARCHAR(255) NOT NULL,
			coupon_code VARCHAR(15) NOT NULL,
			amount INT(11) NOT NULL,
			is_percent TINYINT(1) NOT NULL DEFAULT 0,
			valid_from DATE NOT NULL DEFAULT '0000-00-00',
			valid_to DATE NOT NULL DEFAULT '0000-00-00',
			customer_group_id INT(11) UNSIGNED NULL,
			reservation_asset_id INT(11) UNSIGNED NOT NULL,
			valid_from_checkin DATE NULL DEFAULT '0000-00-00',
			valid_to_checkin DATE NULL DEFAULT '0000-00-00',
			quantity INT(11) UNSIGNED NULL DEFAULT NULL,
			params TEXT NOT NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_coupons_sr_customer_groups1_idx(customer_group_id ASC),
			INDEX fk_sr_coupons_sr_reservation_assets1_idx(reservation_asset_id ASC),
			CONSTRAINT fk_sr_coupons_sr_customer_groups1
			FOREIGN KEY(customer_group_id)
			REFERENCES {$wpdb->prefix}sr_customer_groups(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_coupons_sr_reservation_assets1
			FOREIGN KEY(reservation_asset_id)
			REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservations (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			state TINYINT(3) NOT NULL DEFAULT 0,
			customer_id INT(11) UNSIGNED NULL DEFAULT NULL,
			created_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			modified_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			modified_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			created_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			payment_method_id VARCHAR(255) NOT NULL DEFAULT 0,
			payment_method_txn_id VARCHAR(255) NULL DEFAULT NULL,
			payment_status TINYINT(2) NULL DEFAULT 0 COMMENT '0 is Unpaid, 1 is Completed, 2 is Cancelled, 3 is Pending',
			code VARCHAR(255) NOT NULL,
			coupon_id INT(11) UNSIGNED NULL,
			coupon_code VARCHAR(15) NULL DEFAULT NULL,
			customer_title VARCHAR(45) NULL,
			customer_firstname VARCHAR(255) NULL,
			customer_middlename VARCHAR(255) NULL,
			customer_lastname VARCHAR(255) NULL,
			customer_email VARCHAR(255) NULL,
			customer_phonenumber VARCHAR(45) NULL,
			customer_company VARCHAR(45) NULL,
			customer_address1 VARCHAR(45) NULL,
			customer_address2 VARCHAR(45) NULL,
			customer_city VARCHAR(45) NULL,
			customer_zipcode VARCHAR(45) NULL,
			customer_country_id INT(11) NULL,
			customer_geo_state_id INT(11) NULL,
			customer_vat_number VARCHAR(255) NULL DEFAULT NULL,
			checkin DATE NOT NULL DEFAULT '0000-00-00',
			checkout DATE NOT NULL DEFAULT '0000-00-00',
			invoice_number VARCHAR(255) NULL,
			currency_id INT(11) UNSIGNED NULL,
			currency_code VARCHAR(10) NULL,
			total_price DECIMAL(12,2) UNSIGNED NULL,
			total_price_tax_incl DECIMAL(12,2) UNSIGNED NULL,
			total_price_tax_excl DECIMAL(12,2) UNSIGNED NULL,
			total_extra_price DECIMAL(12,2) UNSIGNED NULL,
			total_extra_price_tax_incl DECIMAL(12,2) UNSIGNED NULL,
			total_extra_price_tax_excl DECIMAL(12,2) UNSIGNED NULL,
			total_discount DECIMAL(12,2) UNSIGNED NULL,
			note TEXT NULL DEFAULT NULL,
			reservation_asset_id INT(11) UNSIGNED NULL DEFAULT NULL,
			reservation_asset_name VARCHAR(255) NULL DEFAULT NULL,
			deposit_amount DECIMAL(12,2) UNSIGNED NULL DEFAULT NULL,
			total_paid DECIMAL(12,2) UNSIGNED NULL,
			PRIMARY KEY(id),
			UNIQUE INDEX code_UNIQUE(code ASC),
			INDEX fk_sr_reservations_sr_coupons1_idx(coupon_id ASC),
			INDEX fk_sr_reservations_sr_reservation_assets1_idx(reservation_asset_id ASC),
			CONSTRAINT fk_sr_reservations_sr_coupons1
			FOREIGN KEY(coupon_id)
			REFERENCES {$wpdb->prefix}sr_coupons(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_reservations_sr_reservation_assets1
			FOREIGN KEY(reservation_asset_id)
			REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_media (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			type VARCHAR(45) NOT NULL,
			value TEXT NOT NULL,
			name VARCHAR(255) NOT NULL,
			created_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			modified_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			created_by INT(11) NOT NULL DEFAULT 0,
			modified_by INT(11) NOT NULL DEFAULT 0,
			mime_type VARCHAR(255) NOT NULL,
			size INT(11) NOT NULL DEFAULT 0,
			PRIMARY KEY(id))
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_extras (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			state TINYINT(3) NOT NULL DEFAULT 0,
			description TEXT NOT NULL,
			created_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			modified_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			created_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			modified_by INT(11) UNSIGNED NOT NULL DEFAULT 0,
			price DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0,
			ordering INT(11) UNSIGNED NOT NULL DEFAULT 0,
			max_quantity INT(11) UNSIGNED NOT NULL DEFAULT 0,
			daily_chargable TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			reservation_asset_id INT(11) UNSIGNED NOT NULL,
			mandatory TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			charge_type TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
			tax_id INT(11) UNSIGNED NULL,
			params TEXT NOT NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_extras_sr_reservation_assets1_idx(reservation_asset_id ASC),
			INDEX fk_sr_extras_sr_taxes1_idx(tax_id ASC),
			CONSTRAINT fk_sr_extras_sr_reservation_assets1
			FOREIGN KEY(reservation_asset_id)
			REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_extras_sr_taxes1
			FOREIGN KEY(tax_id)
			REFERENCES {$wpdb->prefix}sr_taxes(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_tariffs (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			currency_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
			customer_group_id INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'price for each user groups',
			valid_from DATE NOT NULL,
			valid_to DATE NOT NULL,
			room_type_id INT(11) UNSIGNED NULL,
			title VARCHAR(45) NULL,
			description VARCHAR(255) NULL,
			d_min TINYINT NULL,
			d_max TINYINT NULL,
			p_min TINYINT NULL,
			p_max TINYINT NULL,
			type TINYINT UNSIGNED NOT NULL DEFAULT 0,
			limit_checkin VARCHAR(45) NOT NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_prices_sr_currencies1_idx(currency_id ASC),
			INDEX fk_sr_prices_sr_room_types1_idx(room_type_id ASC),
			INDEX fk_sr_prices_sr_customer_groups1_idx(customer_group_id ASC),
			CONSTRAINT fk_sr_prices_sr_currencies1
			FOREIGN KEY(currency_id)
			REFERENCES {$wpdb->prefix}sr_currencies(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_prices_sr_room_types1
			FOREIGN KEY(room_type_id)
			REFERENCES {$wpdb->prefix}sr_room_types(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_prices_sr_customer_groups1
			FOREIGN KEY(customer_group_id)
			REFERENCES {$wpdb->prefix}sr_customer_groups(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_rooms (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			label VARCHAR(255) NOT NULL,
			room_type_id INT(11) UNSIGNED NOT NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_rooms_sr_room_types1_idx(room_type_id ASC),
			CONSTRAINT fk_sr_rooms_sr_room_types1
			FOREIGN KEY(room_type_id)
			REFERENCES {$wpdb->prefix}sr_room_types(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_room_xref (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			reservation_id INT(11) UNSIGNED NOT NULL,
			room_id INT(11) UNSIGNED NULL,
			room_label VARCHAR(255) NULL,
			adults_number TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
			children_number TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
			guest_fullname VARCHAR(500) NULL,
			room_price DECIMAL(12,2) UNSIGNED NULL DEFAULT NULL,
			room_price_tax_incl DECIMAL(12,2) UNSIGNED NULL,
			room_price_tax_excl DECIMAL(12,2) UNSIGNED NULL,
			tariff_id INT(11) UNSIGNED NULL,
			tariff_title VARCHAR(45) NULL DEFAULT NULL,
			tariff_description VARCHAR(255) NULL DEFAULT NULL,
			PRIMARY KEY(id),
			INDEX fk_reservations_rooms_xref_reservations1_idx(reservation_id ASC),
			INDEX fk_sr_reservation_room_coupon_extra_xref_sr_rooms1_idx(room_id ASC),
			INDEX fk_sr_reservation_room_xref_sr_tariffs1_idx(tariff_id ASC),
			CONSTRAINT fk_reservations_rooms_xref_reservations1
			FOREIGN KEY(reservation_id)
			REFERENCES {$wpdb->prefix}sr_reservations(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_reservation_room_coupon_extra_xref_sr_rooms1
			FOREIGN KEY(room_id)
			REFERENCES {$wpdb->prefix}sr_rooms(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_reservation_room_xref_sr_tariffs1
			FOREIGN KEY(tariff_id)
			REFERENCES {$wpdb->prefix}sr_tariffs(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB
		COMMENT = '\nit include extra optionaly.\n';";

		/*$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_media_reservation_assets_xref (
				media_id INT(11) UNSIGNED NOT NULL,
				reservation_asset_id INT(11) UNSIGNED NOT NULL,
				weight INT(11) UNSIGNED NOT NULL DEFAULT 0,
				PRIMARY KEY(media_id, reservation_asset_id),
				INDEX fk_sr_media_ref_reservation_assets_sr_media1_idx(media_id ASC),
				INDEX fk_sr_media_ref_reservation_assets_sr_reservation1_idx(reservation_asset_id ASC),
				CONSTRAINT fk_sr_media_ref_reservation_assets_sr_media1
				FOREIGN KEY(media_id)
				REFERENCES {$wpdb->prefix}sr_media(id)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
				CONSTRAINT fk_sr_media_ref_reservation_assets_sr_reservation1
				FOREIGN KEY(reservation_asset_id)
				REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
			ENGINE = InnoDB;";*/

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_media_reservation_assets_xref (
			media_id INT(11) UNSIGNED NOT NULL,
			reservation_asset_id INT(11) UNSIGNED NOT NULL,
			weight INT(11) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY(media_id, reservation_asset_id),
			INDEX fk_sr_media_ref_reservation_assets_sr_reservation1_idx(reservation_asset_id ASC),
			CONSTRAINT fk_sr_media_ref_reservation_assets_sr_reservation1
			FOREIGN KEY(reservation_asset_id)
			REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		/*$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_media_roomtype_xref (
				media_id INT(11) UNSIGNED NOT NULL,
				room_type_id INT(11) UNSIGNED NOT NULL,
				weight INT(11) UNSIGNED NOT NULL DEFAULT 0,
				INDEX fk_sr_media_ref_roomtype_sr_media1_idx(media_id ASC),
				INDEX fk_sr_media_ref_roomtype_sr_room_types1_idx(room_type_id ASC),
				CONSTRAINT fk_sr_media_ref_roomtype_sr_media1
				FOREIGN KEY(media_id)
				REFERENCES {$wpdb->prefix}sr_media(id)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
				CONSTRAINT fk_sr_media_ref_roomtype_sr_room_types1
				FOREIGN KEY(room_type_id)
				REFERENCES {$wpdb->prefix}sr_room_types(id)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
			ENGINE = InnoDB;";*/

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_media_roomtype_xref (
			media_id INT(11) UNSIGNED NOT NULL,
			room_type_id INT(11) UNSIGNED NOT NULL,
			weight INT(11) UNSIGNED NOT NULL DEFAULT 0,
			INDEX fk_sr_media_ref_roomtype_sr_room_types1_idx(room_type_id ASC),
			CONSTRAINT fk_sr_media_ref_roomtype_sr_room_types1
			FOREIGN KEY(room_type_id)
			REFERENCES {$wpdb->prefix}sr_room_types(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_asset_fields (
			reservation_asset_id INT(11) UNSIGNED NOT NULL,
			field_key VARCHAR(100) NOT NULL,
			field_value TEXT NULL,
			ordering INT(11) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY(field_key, reservation_asset_id),
			INDEX fk_sr_reservation_asset_fields_sr_reservation_assets1_idx(reservation_asset_id ASC),
			CONSTRAINT fk_sr_reservation_asset_fields_sr_reservation_assets1
			FOREIGN KEY(reservation_asset_id)
			REFERENCES {$wpdb->prefix}sr_reservation_assets(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		/*$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_customer_fields (
			user_id INT(11) UNSIGNED NOT NULL,
			field_key VARCHAR(100) NOT NULL,
			field_value VARCHAR(255) NULL,
			ordering INT(11) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY(field_key),
			INDEX fk_sr_customer_fields_sr_customers1_idx(user_id ASC),
			CONSTRAINT fk_sr_customer_fields_sr_customers1
			FOREIGN KEY(user_id)
			REFERENCES {$wpdb->prefix}sr_customers(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";*/

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_room_type_coupon_xref (
			room_type_id INT(11) UNSIGNED NOT NULL,
			coupon_id INT(11) UNSIGNED NOT NULL,
			PRIMARY KEY(room_type_id, coupon_id),
			INDEX fk_sr_room_type_coupon_xref_sr_coupons1_idx(coupon_id ASC),
			INDEX fk_sr_room_type_coupon_xref_sr_room_types1_idx(room_type_id ASC),
			CONSTRAINT fk_sr_room_type_coupon_xref_sr_coupons1
			FOREIGN KEY(coupon_id)
			REFERENCES {$wpdb->prefix}sr_coupons(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_room_type_coupon_xref_sr_room_types1
			FOREIGN KEY(room_type_id)
			REFERENCES {$wpdb->prefix}sr_room_types(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_room_type_extra_xref (
			room_type_id INT(11) UNSIGNED NOT NULL,
			extra_id INT(11) UNSIGNED NOT NULL,
			PRIMARY KEY(room_type_id, extra_id),
			INDEX fk_sr_room_type_extra_xref_sr_extras1_idx(extra_id ASC),
			INDEX fk_sr_room_type_extra_xref_sr_room_types1_idx(room_type_id ASC),
			CONSTRAINT fk_sr_room_type_extra_xref_sr_extras1
			FOREIGN KEY(extra_id)
			REFERENCES {$wpdb->prefix}sr_extras(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_room_type_extra_xref_sr_room_types1
			FOREIGN KEY(room_type_id)
			REFERENCES {$wpdb->prefix}sr_room_types(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_room_extra_xref (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			reservation_id INT(11) UNSIGNED NOT NULL,
			room_id INT(11) UNSIGNED NULL,
			room_label VARCHAR(255) NULL,
			extra_id INT(11) UNSIGNED NULL,
			extra_name VARCHAR(255) NULL,
			extra_quantity INT(11) UNSIGNED NULL,
			extra_price DECIMAL(12,2) UNSIGNED NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_reservation_room_extra_xref_sr_reservations1_idx(reservation_id ASC),
			INDEX fk_sr_reservation_room_extra_xref_sr_rooms1_idx(room_id ASC),
			INDEX fk_sr_reservation_room_extra_xref_sr_extras1_idx(extra_id ASC),
			CONSTRAINT fk_sr_reservation_room_extra_xref_sr_reservations1
			FOREIGN KEY(reservation_id)
			REFERENCES {$wpdb->prefix}sr_reservations(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_reservation_room_extra_xref_sr_rooms1
			FOREIGN KEY(room_id)
			REFERENCES {$wpdb->prefix}sr_rooms(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_reservation_room_extra_xref_sr_extras1
			FOREIGN KEY(extra_id)
			REFERENCES {$wpdb->prefix}sr_extras(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_room_type_fields (
			room_type_id INT(11) UNSIGNED NOT NULL,
			field_key VARCHAR(100) NOT NULL,
			field_value TEXT NULL,
			ordering INT(11) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY(room_type_id, field_key),
			INDEX fk_sr_room_type_fields_sr_room_types1_idx(room_type_id ASC),
			CONSTRAINT fk_sr_room_type_fields_sr_room_types1
			FOREIGN KEY(room_type_id)
			REFERENCES {$wpdb->prefix}sr_room_types(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_notes (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			reservation_id INT(11) UNSIGNED NULL DEFAULT NULL,
			text TEXT NULL DEFAULT NULL,
			created_date DATETIME NULL DEFAULT '0000-00-00 00:00:00',
			created_by INT(11) UNSIGNED NULL DEFAULT NULL,
			notify_customer TINYINT(3) UNSIGNED NULL DEFAULT 0,
			visible_in_frontend TINYINT(3) UNSIGNED NULL DEFAULT 0,
			PRIMARY KEY(id),
			INDEX fk_jos_sr_reservation_notes_jos_sr_reservations1_idx(reservation_id ASC),
			CONSTRAINT fk_jos_sr_reservation_notes_jos_sr_reservations1
			FOREIGN KEY(reservation_id)
			REFERENCES {$wpdb->prefix}sr_reservations(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_config_data(
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			scope_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
			data_key VARCHAR(255) NOT NULL,
			data_value TEXT NULL DEFAULT NULL,
			PRIMARY KEY(id))
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_tariff_details (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			tariff_id INT(11) UNSIGNED NOT NULL,
			price DECIMAL(12,2) UNSIGNED NULL,
			w_day TINYINT UNSIGNED NULL,
			guest_type VARCHAR(10) NULL,
			from_age TINYINT UNSIGNED NULL DEFAULT NULL,
			to_age TINYINT UNSIGNED NULL DEFAULT NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_tariff_details_sr_tariffs1_idx(tariff_id ASC),
			CONSTRAINT fk_sr_tariff_details_sr_tariffs1
			FOREIGN KEY(tariff_id)
			REFERENCES {$wpdb->prefix}sr_tariffs(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_room_details (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			reservation_room_id INT(11) UNSIGNED NOT NULL,
			`key` VARCHAR(255) NULL,
			`value` TEXT NULL,
			PRIMARY KEY (id),
			INDEX `fk_sr_reservation_room_details_sr_reservation_room_xr1_idx` (`reservation_room_id` ASC),
			CONSTRAINT `fk_sr_reservation_room_details_sr_reservation_room_xr1`
			FOREIGN KEY (`reservation_room_id` )
			REFERENCES {$wpdb->prefix}sr_reservation_room_xref(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		$tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sr_reservation_extra_xref(
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			reservation_id INT(11) UNSIGNED NOT NULL,
			extra_id INT(11) UNSIGNED NULL,
			extra_name VARCHAR(255) NULL,
			extra_quantity INT(11) NULL,
			extra_price DECIMAL(12,2) NULL,
			PRIMARY KEY(id),
			INDEX fk_sr_reservation_extra_xref_sr_reservations1_idx(reservation_id ASC),
			INDEX fk_sr_reservation_extra_xref_sr_extras1(extra_id ASC),
			CONSTRAINT fk_sr_reservation_extra_xref_sr_reservations1
			FOREIGN KEY(reservation_id)
			REFERENCES {$wpdb->prefix}sr_reservations(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
			CONSTRAINT fk_sr_reservation_extra_xref_sr_sr_extras1
			FOREIGN KEY(extra_id)
			REFERENCES {$wpdb->prefix}sr_extras(id)
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB;";

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}
		add_option( 'solidres_db_version', solidres()->version );
	}
}