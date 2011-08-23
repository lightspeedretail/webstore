<?php
	/**
	 * The CartType class defined here contains
	 * code for the CartType enumerated type.  It represents
	 * the enumerated values found in the "xlsws_cart_type" table
	 * in the database.
	 * 
	 * To use, you should use the CartType subclass which
	 * extends this CartTypeGen class.
	 * 
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the CartType class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 */
	abstract class CartTypeGen extends QBaseClass {
		const cart = 1;
		const giftregistry = 2;
		const quote = 3;
		const order = 4;
		const invoice = 5;
		const saved = 6;
		const awaitpayment = 7;
		const sro = 8;

		const MaxId = 8;

		public static $NameArray = array(
			1 => 'cart',
			2 => 'giftregistry',
			3 => 'quote',
			4 => 'order',
			5 => 'invoice',
			6 => 'saved',
			7 => 'awaitpayment',
			8 => 'sro');

		public static $TokenArray = array(
			1 => 'cart',
			2 => 'giftregistry',
			3 => 'quote',
			4 => 'order',
			5 => 'invoice',
			6 => 'saved',
			7 => 'awaitpayment',
			8 => 'sro');

		public static function ToString($intCartTypeId) {
			switch ($intCartTypeId) {
				case 1: return 'cart';
				case 2: return 'giftregistry';
				case 3: return 'quote';
				case 4: return 'order';
				case 5: return 'invoice';
				case 6: return 'saved';
				case 7: return 'awaitpayment';
				case 8: return 'sro';
				default:
					throw new QCallerException(sprintf('Invalid intCartTypeId: %s', $intCartTypeId));
			}
		}

		public static function ToToken($intCartTypeId) {
			switch ($intCartTypeId) {
				case 1: return 'cart';
				case 2: return 'giftregistry';
				case 3: return 'quote';
				case 4: return 'order';
				case 5: return 'invoice';
				case 6: return 'saved';
				case 7: return 'awaitpayment';
				case 8: return 'sro';
				default:
					throw new QCallerException(sprintf('Invalid intCartTypeId: %s', $intCartTypeId));
			}
		}

	}
?>