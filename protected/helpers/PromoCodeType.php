<?php
/**
 * PromoCodeType definitions
 *
 * @category   Helpers
 * @package    Promocode
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2013-05-14

 */


class PromoCodeType {
	const Flat = 0;
	const Percent = 1;

	const MaxId = 1;

	public static $NameArray = array(
		0 => '%',
		1 => '$',
	);

	public static $TokenArray = array(
		0 => '%',
		1 => '$',
	);

	public static function Display($intPromoCodeTypeId, $intAmount) {
		switch ($intPromoCodeTypeId) {
		case 0:
			return _xls_currency($intAmount);
		case 1:
			return sprintf('%s%s', $intAmount, '%');
		default:
			throw new QCallerException(
				sprintf(_sp('Invalid intPromoCodeTypeId') .
						': %s', $intPromoCodeTypeId));
		}
	}

	public static function ToString($intPromoCodeTypeId) {
		switch ($intPromoCodeTypeId) {
		case 0:
			$locale = localeconv();
			return $locale['currency_symbol'];
		case 1: return '%';
		default:
			throw new QCallerException(
				sprintf(_sp('Invalid intPromoCodeTypeId') .
						': %s', $intPromoCodeTypeId));
		}
	}

	public static function ToToken($intPromoCodeTypeId) {
		switch ($intPromoCodeTypeId) {
		case 0:
			$locale = localeconv();
			return $locale['currency_symbol'];
		case 1: return 'Percent';
		default:
			throw new QCallerException(
				sprintf(_sp('Invalid intPromoCodeTypeId') .
						': %s', $intPromoCodeTypeId));
		}
	}
}