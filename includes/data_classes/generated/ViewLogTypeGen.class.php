<?php
	/**
	 * The ViewLogType class defined here contains
	 * code for the ViewLogType enumerated type.  It represents
	 * the enumerated values found in the "xlsws_view_log_type" table
	 * in the database.
	 * 
	 * To use, you should use the ViewLogType subclass which
	 * extends this ViewLogTypeGen class.
	 * 
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the ViewLogType class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 */
	abstract class ViewLogTypeGen extends QBaseClass {
		const index = 1;
		const categoryview = 2;
		const productview = 3;
		const pageview = 4;
		const productcartadd = 5;
		const search = 6;
		const registration = 7;
		const giftregistryview = 8;
		const giftregistryadd = 9;
		const customerlogin = 10;
		const customerlogout = 11;
		const checkoutcustomer = 12;
		const checkoutshipping = 13;
		const checkoutpayment = 14;
		const checkoutfinal = 15;
		const unknown = 16;
		const invalidcreditcard = 17;
		const failcreditcard = 18;
		const familyview = 19;

		const MaxId = 19;

		public static $NameArray = array(
			1 => 'index',
			2 => 'categoryview',
			3 => 'productview',
			4 => 'pageview',
			5 => 'productcartadd',
			6 => 'search',
			7 => 'registration',
			8 => 'giftregistryview',
			9 => 'giftregistryadd',
			10 => 'customerlogin',
			11 => 'customerlogout',
			12 => 'checkoutcustomer',
			13 => 'checkoutshipping',
			14 => 'checkoutpayment',
			15 => 'checkoutfinal',
			16 => 'unknown',
			17 => 'invalidcreditcard',
			18 => 'failcreditcard',
			19 => 'familyview');

		public static $TokenArray = array(
			1 => 'index',
			2 => 'categoryview',
			3 => 'productview',
			4 => 'pageview',
			5 => 'productcartadd',
			6 => 'search',
			7 => 'registration',
			8 => 'giftregistryview',
			9 => 'giftregistryadd',
			10 => 'customerlogin',
			11 => 'customerlogout',
			12 => 'checkoutcustomer',
			13 => 'checkoutshipping',
			14 => 'checkoutpayment',
			15 => 'checkoutfinal',
			16 => 'unknown',
			17 => 'invalidcreditcard',
			18 => 'failcreditcard',
			19 => 'familyview');

		public static function ToString($intViewLogTypeId) {
			switch ($intViewLogTypeId) {
				case 1: return 'index';
				case 2: return 'categoryview';
				case 3: return 'productview';
				case 4: return 'pageview';
				case 5: return 'productcartadd';
				case 6: return 'search';
				case 7: return 'registration';
				case 8: return 'giftregistryview';
				case 9: return 'giftregistryadd';
				case 10: return 'customerlogin';
				case 11: return 'customerlogout';
				case 12: return 'checkoutcustomer';
				case 13: return 'checkoutshipping';
				case 14: return 'checkoutpayment';
				case 15: return 'checkoutfinal';
				case 16: return 'unknown';
				case 17: return 'invalidcreditcard';
				case 18: return 'failcreditcard';
				case 19: return 'familyview';
				default:
					throw new QCallerException(sprintf('Invalid intViewLogTypeId: %s', $intViewLogTypeId));
			}
		}

		public static function ToToken($intViewLogTypeId) {
			switch ($intViewLogTypeId) {
				case 1: return 'index';
				case 2: return 'categoryview';
				case 3: return 'productview';
				case 4: return 'pageview';
				case 5: return 'productcartadd';
				case 6: return 'search';
				case 7: return 'registration';
				case 8: return 'giftregistryview';
				case 9: return 'giftregistryadd';
				case 10: return 'customerlogin';
				case 11: return 'customerlogout';
				case 12: return 'checkoutcustomer';
				case 13: return 'checkoutshipping';
				case 14: return 'checkoutpayment';
				case 15: return 'checkoutfinal';
				case 16: return 'unknown';
				case 17: return 'invalidcreditcard';
				case 18: return 'failcreditcard';
				case 19: return 'familyview';
				default:
					throw new QCallerException(sprintf('Invalid intViewLogTypeId: %s', $intViewLogTypeId));
			}
		}

	}
?>