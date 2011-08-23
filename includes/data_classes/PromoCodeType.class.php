<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

abstract class PromoCodeType extends QBaseClass {
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
