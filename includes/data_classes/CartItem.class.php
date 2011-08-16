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

	require(__DATAGEN_CLASSES__ . '/CartItemGen.class.php');

	/**
	 * The CartItem class defined here contains any
	 * customized code for the CartItem class in the
     * Object Relational Model.
     *
     * Sell :: Qty modified Sell Price
     * SellBase :: Product's unmodified Sell price
     * SellDiscount :: Discounted SellBase price
     * SellTotal :: Discounted qty modified Sell Price
     *
	 */
    class CartItem extends CartItemGen {
        // Define the Object Manager for semi-persistent storage
        public static $Manager;

        // Product represented by the CartItem
        protected $objProduct;

        // Boolean detailing if object was updated
        public $Updated = false;

        //Boolean to check to see if an item's tax has already been removed from tax inclusive pricing
        public $blnWebTaxRemoved = false;

        // String representation of the object
		public function __toString() {
			return sprintf('CartItem Object %s',  $this->intRowid);
		}

        // Initialize the Object Manager on the class
        public static function InitializeManager() {
            if (!CartItem::$Manager)
                CartItem::$Manager = 
                    XLSCartItemManager::Singleton('XLSCartItemManager');
        }

        protected function IsDiscounted() {
            if ($this->Discount > 0)
                return true;
            return false;
        }

        public function GetPriceField() {
            if ($this->IsDiscounted())
                return 'SellDiscount';
            return 'Sell';
            return 'SellDiscount';
        }

        protected function GetPriceValue() {
            $strPriceField = $this->GetPriceField();
            return $this->$strPriceField;
        }

        public function Delete() {
            if (CartItem::$Manager)
                CartItem::$Manager->Remove($this);
            parent::Delete();
        }

        public function Save($blnForceInsert = false, $blnForceUpdate = false) {
            $update = false;
            if ($this->Rowid)
                $update = true;

            if ($this->Updated || $blnForceInsert)
                parent::Save($blnForceInsert, $blnForceUpdate);

            if (CartItem::$Manager)
                if (!$update)
                    CartItem::$Manager->Add($this);

            $this->Updated = false;
        }

		public function __get($strName) {
			switch ($strName) {
				case 'Prod': 
                    //QApplication::Log(E_USER_NOTICE, 'legacy', $strName);
					if(!$this->objProduct)
						$this->objProduct = Product::Load($this->ProductId);
					return $this->objProduct;

                case 'Discounted':
                    return $this->IsDiscounted();

                case 'Price':
                    return $this->GetPriceValue();

				case 'SellTotalTaxIncIfSet': 
					return $this->SellTotal;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
        }

        public function __set($strName, $mixValue) {
            $mixReturn = '';

            try { 
                switch ($strName) { 
                    case 'Discount':
                        $mixValue = round($mixValue, 2);
                        parent::__set($strName, $mixValue);
                        $this->SellDiscount = $this->Sell - $mixValue;
                        break;

                    case 'Qty':
                        parent::__set($strName, $mixValue);
                        $this->Discount = 0;
                        if ($this->Product)
                            $this->Sell = $this->Product->GetPrice($mixValue);
                        break;

                    case 'Sell':
                    case 'SellDiscount': 
                        $mixValue = round($mixValue, 2);
                        parent::__set($strName, $mixValue);
                        $this->SellTotal = $this->GetPriceValue() * $this->Qty;
                        break;

                    default:
                        parent::__set($strName, $mixValue);
                        break;
                }
            }
            catch (QCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }

            $this->Updated = true;
            return $mixReturn;
        }
    }

	
?>
