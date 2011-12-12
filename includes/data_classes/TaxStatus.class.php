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

require(__DATAGEN_CLASSES__ . '/TaxStatusGen.class.php');

/**
 * The TaxStatus class defined here contains any customized code for the
 * TaxStatus class in the Object Relational Model.
 */
class TaxStatus extends TaxStatusGen {
	// String representation of this object
	public function __toString() {
		return sprintf('TaxStatus Object %s',  $this->intRowid);
	}

	public static function GetNoTaxStatus() {
		foreach (TaxStatus::LoadAll() as $objTax)
			if ($objTax->IsNoTax())
				return $objTax;
		return;
	}

	public function IsNoTax() {
		if (strtolower($this->Status) == 'no tax' or
			strtolower($this->Status) == 'exempt')
				return true;

		for ($i = 1; $i <= 5; $i++) {
			$strField = 'Tax' . $i . 'Status';

			if ($this->$strField == '0')
				return false;
		}

		return true;
	}
}
