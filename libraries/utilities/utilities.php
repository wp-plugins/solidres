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
 * Utilities handler class
 * @package 	Solidres
 * @subpackage	Utilities
 */
class SR_Utilities {
	public static function translateDayWeekName( $inputs ) {
		$dayMapping = array(
			'0' => __( 'sun', 'solidres' ),
			'1' => __( 'mon', 'solidres' ),
			'2' => __( 'tue', 'solidres' ),
			'3' => __( 'wed', 'solidres' ),
			'4' => __( 'thu', 'solidres' ),
			'5' => __( 'fri', 'solidres' ),
			'6' => __( 'sat', 'solidres' )
		);
		foreach ( $inputs as $input ) {
			$input->w_day_name = $dayMapping[$input->w_day];
		}
		return $inputs;
	}

	public static function translateText( $text ) {
		if ( strpos( $text, '{lang' ) !== false ) {
			$text = self::filterText( $text );
		}
		return $text;
	}

	public static function getTariffDetailsScaffoldings( $config = array() ) {
		$scaffoldings = array();
		// If this is package per person or package per room
		if ( $config['type'] == 2 || $config['type'] == 3 ) {
			$scaffoldings[0] = new stdClass();
			$scaffoldings[0]->id = null;
			$scaffoldings[0]->tariff_id = $config['tariff_id'];
			$scaffoldings[0]->price = null;
			$scaffoldings[0]->w_day = 8;
			$scaffoldings[0]->guest_type = $config['guest_type'];
			$scaffoldings[0]->from_age = null;
			$scaffoldings[0]->to_age = null;
		}
		else // For normal complex tariff
		{
			for ( $i = 0; $i < 7; $i++ ) {
				$scaffoldings[$i] = new stdClass();
				$scaffoldings[$i]->id = null;
				$scaffoldings[$i]->tariff_id = $config['tariff_id'];
				$scaffoldings[$i]->price = null;
				$scaffoldings[$i]->w_day = $i;
				$scaffoldings[$i]->guest_type = $config['guest_type'];
				$scaffoldings[$i]->from_age = null;
				$scaffoldings[$i]->to_age = null;
			}
		}
		return $scaffoldings;
	}

	/* Translate custom field by using language tag. Author: isApp.it Team */
	public static function getLagnCode() {
		$lang_codes = JLanguageHelper::getLanguages('lang_code');
		$lang_code 	= $lang_codes[JFactory::getLanguage()->getTag()]->sef;
		return $lang_code;
	}

	/* Translate custom field by using language tag. Author: isApp.it Team */
	public static function filterText( $text ) {
		if ( strpos( $text, '{lang' ) === false ) return $text;
		$lang_code = self::getLagnCode();
		$regex = "#{lang ".$lang_code."}(.*?){\/lang}#is";
		$text = preg_replace($regex,'$1', $text);
		$regex = "#{lang [^}]+}.*?{\/lang}#is";
		$text = preg_replace($regex,'', $text);
		return $text;
	}

	/**
	 * This simple function return a correct javascript date format pattern based on php date format pattern
	 **/
	public static function convertDateFormatPattern( $input ){
		$mapping = array(
			'd-m-Y' => 'dd-mm-yy',
			'd/m/Y' => 'dd/mm/yy',
			'd M Y' => 'dd M yy',
			'd F Y' => 'dd MM yy',
			'D, d M Y' => 'D, dd M yy',
			'l, d F Y' => 'DD, dd MM yy',
			'Y-m-d' => 'yy-mm-dd',
			'm-d-Y' => 'mm-dd-yy',
			'm/d/Y' => 'mm/dd/yy',
			'M d, Y' => 'M dd, yy',
			'F d, Y' => 'MM dd, yy',
			'D, M d, Y' => 'D, M dd, yy',
			'l, F d, Y' => 'DD, MM dd, yy',
			'F j, Y' => 'MM d, yy',
		);
		return $mapping[$input];
	}
}