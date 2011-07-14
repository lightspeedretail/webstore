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

	require(__DATAGEN_CLASSES__ . '/TaxCodeGen.class.php');

	/**
     * The TaxCode class defined here contains any customized code for the 
     * TaxCode class in the Object Relational Model.
	 */
    class TaxCode extends TaxCodeGen {
        // String representation of the object
		public function __toString() {
			return sprintf('TaxCode Object %s',  $this->intRowid);
		}

        public static function GetDefault() {
            $objCode = TaxCode::QuerySingle(
                QQ::AndCondition(QQ::All()),
                QQ::Clause(QQ::OrderBy(QQN::TaxCode()->ListOrder,
                    QQN::TaxCode()->Rowid)));
            return $objCode;
        }

        public static function GetNoTaxCode() {
            foreach (TaxCode::LoadAll() as $objTax)
                if ($objTax->IsNoTax())
                    return $objTax;
            return;
        }

        public function IsNoTax() {
            if (strtolower($this->Code) == 'no tax') 
                return true;

            $total = $this->Tax1Rate + 
                $this->Tax2Rate + 
                $this->Tax3Rate + 
                $this->Tax4Rate + 
                $this->Tax5Rate;

            if ($total == 0)
                return true;

            return false;
        }
	}
?>
