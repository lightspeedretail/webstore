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

/**
 * XLSZipField
 * Extension of XLSTextBox which provides custom validation for
 * zip / postal code fields
 */

class XLSZipFieldControl extends XLSTextControl {

    protected $strLabelForInvalid = 'Invalid Zip/Postal Code';
    private $strRegex;

    public function ValidateValue() {
        if (!parent::ValidateValue()) return false;

        $this->strText = str_replace(' ', '', $this->strText);
        $this->strText = strtoupper($this->strText);

        if ($this->strRegex)
            if (!preg_match($this->strRegex, $this->strText)) { 
                $this->ValidationError = _sp($this->strLabelForInvalid);
                return false;
            }

        return true;
    }

	public function __get($strName) {
		switch ($strName) {
            case 'Regex':
                return $this->strRegex;

			default:
				try {
					return parent::__get($strName);
				}
				catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
    }

    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'Regex':
                return ($this->strRegex = $mixValue);

            default:
                try { 
                    return parent::__set($strName, $mixValue);
                }
                catch (QCallerException $objExc) {
                    $objExc->IncremnetOffset();
                    throw $objExc;
                }
        }
    }
}

