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

require(__DATAGEN_CLASSES__ . '/ProductQtyPricingGen.class.php');

/**
 * The ProductQtyPricing class defined here contains any
 * customized code for the ProductQtyPricing class in the
 * Object Relational Model.
 *
 * @package My Application
 * @subpackage DataObjects
 */
class ProductQtyPricing extends ProductQtyPricingGen {
	// Default "to string" handler
	public function __toString() {
		return sprintf('ProductQtyPricing Object %s',  $this->intRowid);
	}

	public function GetPrice($intTaxCode, $intTaxStatus,
		$taxExclusive = false) {

		if ($taxExclusive)
			return $this->fltPrice;
		else if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '') == '1') {
			$arrPrice = _xls_calculate_price_tax_price(
				$this->fltPrice, $intTaxCode, $intTaxStatus);
			$intPrice = round($arrPrice[0], 2);
			return $intPrice;
		}
		else
			return $this->fltPrice;
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Price':
				return $this->GetPrice(
					_xls_tax_default_taxcode(),
					$this->Product->FkTaxStatusId
				);
			case 'PriceExclusive':
				return $this->GetPrice(
					_xls_tax_default_taxcode(),
					$this->Product->FkTaxStatusId,
					true
				);
			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
}
