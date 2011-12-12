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

require(__DATAGEN_CLASSES__ . '/ProductGen.class.php');

/**
 * The Product class defined here contains any customized code for the Product
 * class in the Object Relational Model.
 *
 * Prices with are affected by the global Tax Inclusive Pricing toggle in the
 * following manager. If Tax Inclusive Pricing is disabled, then prices never
 * include any taxes.
 *
 * - Sell :: This is the tax exclusive Sell price for qty of 1
 *
 * - SellTaxInclusve :: This is the tax inclusive Sell price for qty of 1
 *
 * - SellWeb :: This is the tax inclusive Web price for qty of 1. If none was
 * defined, then this defaults to the tax exclusive Sell price.
 *
 */
class Product extends ProductGen {
	// Define the Object Manager for semi-persistent storage
	public static $Manager;

	// Boolean detaining if object was updated
	public $Updated = false;

	// Default "to string" handler
	public function __toString() {
		return sprintf('Product Object %s',  $this->intRowid);
	}

	// Initialize the Object Manager on the class
	public static function InitializeManager() {
		if (!Product::$Manager)
			Product::$Manager =
				XLSProductManager::Singleton('XLSProductManager');
	}

	/**
	 * Checks if the product is a matrix master product
	 *
	 * @return boolean true or false based on whether the item is a master or not
	 */
	protected function IsMaster() {
		if ($this->blnMasterModel)
			return true;
		return false;
	}

	/**
	 * Checks if the product is a child product of a matrix master product
	 *
	 * @return boolean true or false based on whether the item is a child product or not
	 */
	 protected function IsChild() {
		if (!$this->blnMasterModel && (!empty($this->intFkProductMasterId)))
			return true;
		return false;
	}

	/**
	 * Checks if the product is an independent item, not tied into a size/color matrix
	 *
	 * @return boolean true or false based on whether the item is an indepdendent product or not
	 */
	protected function IsIndependent() {
		if (!$this->blnMasterModel && (empty($this->intFkProductMasterId)))
			return true;
		return false;
	}


	protected function IsAvailable() {
		if ($this->Web && $this->HasInventory(true))
			return true;
		return false;
	}

	/**
	 * Gets the URL encoded version of the product's code for SEO purposes
	 *
	 * @return string SEO version of the product code
	 */
	protected function GetSlug() {
		return str_replace("%2F","/",urlencode($this->strCode));
	}

	/**
	 * Gets the URL referring to the Product image
	 * @param string $type :: Image size constant
	 * @return string
	 */
	protected function GetImageLink($type = ImagesType::normal) {
		return Images::GetImageLink($this->intImageId, $type);
	}

	/**
	 * Gets the URL for this Product
	 * @return string
	 */
	protected function GetLink() {
		if ($this->IsChild)
			if ($prod = $this->FkProductMaster)
				return $prod->Link;

		if(_xls_get_conf('ENABLE_SEO_URL' , false))
			return $this->Slug . ".html";

		return 'index.php?product=' . $this->Slug .
			(isset($_GET['c'])?"&c=$_GET[c]":'');
	}

	/**
	 * Get and optionally load the Master Product
	 * @return string
	 */
	protected function GetMaster() {
		if ($this->IsChild()) {
			if (!$this->objFkProductMaster)
				try {
					$this->objFkProductMaster =
						Product::Load($this->intFkProductMasterId);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				return $this->objFkProductMaster;
		}
	}

	/**
	 * Return a boolean representing whether the Product has available Inv.
	 * @return bool
	 */
	public function HasInventory($bolExtended = false) {
		if ($bolExtended)
			if (!$this->Inventoried)
				return true;
			else if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD' , true))
				return true;

		if ($this->GetInventory() > 0)
			return true;

		return false;
	}

	/**
	 * Get the inventory count for all Product types.
	 * This is INVENTORY_FIELD_TOTAL aware.
	 * @return intenger
	 */
	protected function GetInventory() {
		$strField = $this->GetInventoryField();
		$intInventory = $this->$strField;

		return $this->$strField;
	}

	/**
	 * Return the property name representing the inventory field
	 * @return string
	 */
	protected function GetInventoryField() {
		$invType = _xls_get_conf('INVENTORY_FIELD_TOTAL','0');
		if ($invType == '1')
			return 'fltInventoryTotal';
		else
			return 'fltInventory';
	}

	/**
	 * Return the property name representing the inventory databse field
	 * @return string
	 */
	protected function GetInventorySqlField() {
		$invType = _xls_get_conf('INVENTORY_FIELD_TOTAL','0');
		if ($invType == '1')
			return 'inventory_total';
		else
			return 'inventory';
	}

	/**
	 * Gets the inventory message for the product from the Admin Panel
	 * based on the item's current inventory level
	 *
	 * @return string inventory message to show to the client for the
	 * product's availability
	 */
	public function InventoryDisplay() {
		$intValue = $this->Inventory;

		if (!$this->Inventoried)
			return _sp(_xls_get_conf('INVENTORY_NON_TITLE' , ''));

		// Do not display master inventory levels
		if ($this->IsMaster()) {
			return '';
		}

		if (_xls_get_conf('INVENTORY_DISPLAY_LEVEL' , 0) == 1) {
			if($intValue <= 0)
				return _sp(
				_xls_get_conf('INVENTORY_ZERO_NEG_TITLE', 'Please Call'));
			elseif ($intValue < _xls_get_conf('INVENTORY_LOW_THRESHOLD' , 0))
				return _sp(
				_xls_get_conf('INVENTORY_LOW_TITLE', 'Low'));
			else
				return _sp(
				_xls_get_conf('INVENTORY_AVAILABLE', 'Available'));
		}

		return $intValue . " " . _sp("Available");
	}

	/**
	 * Determine whether the Product was a Web Price set.
	 * LEGACY :: Workaround for uploader product save handler
	 * @return boolean
	 */
	public function HasWebPrice() {
		if ($this->fltSellWeb != $this->fltSell)
			return true;
		return false;
	}

	/**
	 * Return the TaxInclusive/Regular pricing field name
	 * @return string
	 */
	public function GetPriceField($taxExclusive = false) {
		if ($taxExclusive && _xls_get_conf('TAX_INCLUSIVE_PRICING', '') == '1')
			return 'fltSell';
		else if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '') == '1' &&
			!$this->HasWebPrice())
				return 'fltSellTaxInclusive';
		else
			return 'fltSellWeb';
	}

	/**
	 * Return the value of the TaxInclusive/Regular pricing field
	 * @return float
	 */
	protected function GetPriceValue($taxExclusive = false) {
		$strPriceField = $this->GetPriceField($taxExclusive);
		return $this->$strPriceField;
	}

	/**
	 * Return an array of the applicable ProductQtyPrice objects
	 * @return array
	 */
	protected function GetQuantityPrices() {
		$customer = Customer::GetCurrent();
		$intCustomerLevel = 0;
		$arrPrices = false;

		if ($customer)
			$intCustomerLevel = $customer->PricingLevel;

		$arrPrices = ProductQtyPricing::LoadArrayByProductIdPricingLevel(
			$this->intRowid, $intCustomerLevel, QQ::Clause(
				QQ::OrderBy(QQN::ProductQtyPricing()->Qty, false)));

		foreach ($arrPrices as $objQtyPrice)
			$objQtyPrice->Product = $this;

		return $arrPrices;
	}

	// TODO convert _xls_tax_default_taxcode to TaxCode member
	// Part of refactoring TaxCode && Cart

	/**
	 * Given an amount of Products, return the applicable Qty Price
	 * @param integer
	 * @return float
	 */
	public function GetQuantityPrice($intQuantity = 1,
		$taxExclusive = false) {

		$arrPrices = false;

		if ($intQuantity > 1)
			$arrPrices = $this->GetQuantityPrices();

		if (!$arrPrices)
			return $this->GetPriceValue();

		foreach($arrPrices as $objPrice) {
			if ($intQuantity < $objPrice->Qty)
				continue;

			if ($objPrice->Qty == 0)
				continue;

			if ($taxExclusive)
				return $objPrice->PriceExclusive;
			else
				return $objPrice->Price;
		}

		return $this->GetPriceValue($taxExclusive);
	}

	/**
	 * Return the final TaxInclusive/Exclusive price for a given product,
	 * optionally modified by an amount of Products.
	 * @param integer defaults to 1
	 * @return float
	 */
	public function GetPrice($intQuantity = 1, $taxExclusive = false) {
		$intPrice = $this->GetPriceValue($taxExclusive);
		if ($intQuantity == 1)
			return $intPrice;

		$intQtyPrice = $this->GetQuantityPrice($intQuantity, $taxExclusive);

		if ($intPrice < $intQtyPrice)
			return $intPrice;
		else
			return $intQtyPrice;
	}

	/**
	 * Like GetPrice, this will return the final price, with the exception
	 * that it will optionally return a message for Master products.
	 * @param integer defaults to 1
	 * @return float or string
	 */
	public function GetPriceDisplay($intQuantity = 1,
		$taxExclusive = false) {

		if ($this->IsMaster() && _xls_get_conf('MATRIX_PRICE') == 1)
			return _sp("Click for pricing");

		return $this->GetPrice($intQuantity, $taxExclusive);
	}

	/**
	 * Calculates the tax on an item
	 * @param obj|int $taxCode      :: TaxCode or Rowid to apply
	 * @param float [$fltPrice]     :: Price to calculate on
	 * @return array([1] => .... [5]=>))  all the tax components
	 */
	public function CalculateTax($taxCode, $fltPrice = false) {
		if ($fltPrice === false)
			$fltPrice = $this->GetPrice();

		list($fltTaxedPrice, $arrTaxes) = _xls_calculate_price_tax_price(
			$fltPrice, $taxCode, $this->intFkTaxStatusId);

		return $arrTaxes;
	}

	public function calculate_tax($price , $taxcode) {
		QApplication::Log(E_USER_NOTICE,'legacy',__FUNCTION__);
		$arrPrices = _xls_calculate_price_tax_price(
			$fltPrice, $taxcode, $this->intFkTaxStatusId);
		return $arrPrices[1];
	}

	/**
	 * Given the price of current object (may have been determined by qty/level), and current
	 * product's status calculate the new price and tax
	 *
	 * @param float $price
	 * @param TaxCode|int $taxcode
	 * @return array([1] => .... [5]=>))  all the tax components
	 */
	public function calculate_tax_inclusive($price, $taxcode) {
		QApplication::Log(E_USER_NOTICE,'legacy',__FUNCTION__);
		$arrPrices = _xls_calculate_price_tax_price(
			$fltPrice, $intTaxCode, $this->intFkTaxStatusID);
		return $arrPrices[0];
	}

	public function PriceQtyClac($qty=1,$forceExclusive = false) {
		// TODO :: $forceExclusive is the wrong way to do things (TM)
		// in only applies in TaxInclusive when the taxcode isn't yet
		// set on the cart
		QApplication::Log(E_USER_NOTICE,'legacy',__FUNCTION__);
		return $this->GetPrice($qty, $forceExclusive);
	}

	public function PriceQtyClacIncTaxIfset($qty=1){
		QApplication::Log(E_USER_NOTICE,'legacy',__FUNCTION__);
		$p = $this->GetPrice($qty);
		return $p;
	}

	public function __get($strName) {
		switch ($strName) {
			case 'IsMaster':
				return $this->IsMaster();

			case 'IsChild':
				return $this->IsChild();

			case 'IsIndependent':
				return $this->IsIndependent();

			case 'IsAvailable':
				return $this->IsAvailable();

			case 'Slug':
				return $this->GetSlug();

			case 'Code':
				if ($this->IsChild())
					if ($prod = $this->GetMaster())
						return $prod->Code;
				return $this->strCode;

			case 'FkProductMaster':
				return $this->GetMaster();

			case 'Link':
				return $this->GetLink();

			case 'ListingImage':
				return $this->GetImageLink(ImagesType::listing);

			case 'MiniImage':
				return $this->GetImageLink(ImagesType::mini);

			case 'PDetailImage':
				return $this->GetImageLink(ImagesType::pdetail);

			case 'SmallImage':
				return $this->GetImageLink(ImagesType::small);

			case 'Image':
				return $this->GetImageLink(ImagesType::normal);

			case 'OriginalCode':
				return $this->strCode;

			case 'Price':
				return $this->GetPriceDisplay();

			case 'PriceValue':
				return $this->GetPrice();

			case 'SizeLabel':
					return _xls_get_conf('PRODUCT_SIZE_LABEL' , _sp('Size'));
			case 'ColorLabel':
					return _xls_get_conf('PRODUCT_COLOR_LABEL' , _sp('Color'));
			case 'Inventory':
				return $this->GetInventory();

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
		switch ($strName) {
			case 'Rowid':
				if (defined('XLSWS_SOAP'))
					try {
						return ($this->intRowid =
							QType::Cast($mixValue, QType::Integer));
					}
					catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				else
					QApplication::Log(E_ERROR, 'uploader',
						'You may only update the Product Rowid during' .
						' SOAP operations');
				break;

			default:
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	protected function GetAggregateInventory($intRowid = false) {
		if (!$intRowid)
			$intRowid = $this->intRowid;

		$strQuery = <<<EOS
		SELECT SUM(inventory) AS inv, SUM(inventory_total) AS inv_total
		FROM xlsws_product
		WHERE web=1
		AND fk_product_master_id='{$intRowid}';
EOS;

		$objQuery = _dbx($strQuery, 'Query');
		$arrTotal = $objQuery->FetchArray();

		$intInv = $arrTotal['inv'];
		$intInvTotal = $arrTotal['inv_total'];

		if (!$intInv)
			$intInv = 0;

		if (!$intInvTotal)
			$intInvTotal = 0;

		return array($intInv, $intInvTotal);
	}

	/**
	 * If this is a Master Product, update it's inventory to be the
	 * aggregate of it's Children.
	 * @return integer :: Rowid of any Product modified
	 */
	protected function UpdateMasterInventory() {
		if ($this->IsIndependent() || !$this->Inventoried)
			return false;

		$objProduct = $this;
		if ($this->IsChild())
			$objProduct = $this->FkProductMaster;

		if (!$objProduct)
			return false;

		list($intInv, $intInvTotal) = $this->GetAggregateInventory();

		$objProduct->Inventory = $intInv;
		$objProduct->InventoryTotal = $intInvTotal;

		return $objProduct;
	}

	protected function UpdateMasterAvailability() {
		// Non inventoried Products may be added to cart
		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD', 0) == 1)
			return false;

		if ($this->IsIndependent() || !$this->Inventoried)
			return false;

		$objProduct = $this;

		if ($this->IsChild())
			$objProduct = $this->FkProductMaster;

		if (!$objProduct)
			return false;

		$blnWeb = $objProduct->Web;

		if ($objProduct->GetInventory() < 1) $objProduct->Web = 0;
		else $objProduct->Web = 1;

		if ($blnWeb != $objProduct->Web)
			return $objProduct;
	}

	public function PreSaveHandler() {
		if ($this->IsMaster()) {
			$this->UpdateMasterInventory();
			$this->UpdateMasterAvailability();
		}
	}

	public function PostSaveHandler() {
		if ($this->IsChild()) {
			$this->UpdateMasterInventory();
			$this->UpdateMasterAvailability();

			if ($this->FkProductMaster)
				$this->FkProductMaster->Save();
		}
	}

	/**
	 * Load an array of Product objects,
	 * by FkProductMasterId Index(es) ordered by rowid
	 * @param integer $intFkProductMasterId
	 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
	 * @return Product[]
	*/
	public static function LoadArrayByFkProductMasterId($intFkProductMasterId, $objOptionalClauses = null) {
		// Call Product::QueryArray to perform the LoadArrayByFkProductMasterId query
		try {
			return Product::QueryArray(
				QQ::Equal(QQN::Product()->FkProductMasterId, $intFkProductMasterId),
				QQ::Clause(
					QQ::OrderBy(QQN::Product()->Rowid)
			 ));
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}
	}

	/**
	 * Overload the generated Save handler for the Product model.
	 *
	 * With this version, we provide a facility so as to insure that
	 * Products are saved with the Rowid of our choice.
	 *
	 * We also ensure that no Products have a Duplicate Product Code.
	 *
	 * Lastly, we provide facilities for PreSave and PostSave handlers which
	 * may be triggered to update related Products.
	 */
	public function Save($blnForceInsert = false, $blnForceUpdate = false, $blnSoapSave = false) {
        $this->PreSaveHandler();

        if (!$this->Created)
            $this->Created = new QDatetime(QDateTime::Now);

		if ($blnForceInsert)
			$this->InsertWithRowid();
		else
			parent::Save($blnForceInsert, $blnForceUpdate);

		$this->PostSaveHandler();
		return true;
	}

	/**
	 * Internal Save handler which is primarily used to insert new Products
	 * with the Rowid which LightSpeed expects them to have. This code
	 * should always match the insert query as defined in ProductGen::Save.
	 */
	protected function InsertWithRowid() {
		$objDatabase = Product::GetDatabase();

		$strSql = '
		INSERT INTO `xlsws_product` (
			`rowid`,
			`name`,
			`image_id`,
			`class_name`,
			`code`,
			`current`,
			`description`,
			`description_short`,
			`family`,
			`gift_card`,
			`inventoried`,
			`inventory`,
			`inventory_total`,
			`master_model`,
			`fk_product_master_id`,
			`product_size`,
			`product_color`,
			`product_height`,
			`product_length`,
			`product_width`,
			`product_weight`,
			`fk_tax_status_id`,
			`sell`,
			`sell_tax_inclusive`,
			`sell_web`,
			`upc`,
			`web`,
			`web_keyword1`,
			`web_keyword2`,
			`web_keyword3`,
			`meta_desc`,
			`meta_keyword`,
			`featured`,
			`created`
		) VALUES (
			' . $objDatabase->SqlVariable($this->intRowid) . ',
			' . $objDatabase->SqlVariable($this->strName) . ',
			' . $objDatabase->SqlVariable($this->intImageId) . ',
			' . $objDatabase->SqlVariable($this->strClassName) . ',
			' . $objDatabase->SqlVariable($this->strCode) . ',
			' . $objDatabase->SqlVariable($this->blnCurrent) . ',
			' . $objDatabase->SqlVariable($this->strDescription) . ',
			' . $objDatabase->SqlVariable($this->strDescriptionShort) . ',
			' . $objDatabase->SqlVariable($this->strFamily) . ',
			' . $objDatabase->SqlVariable($this->blnGiftCard) . ',
			' . $objDatabase->SqlVariable($this->blnInventoried) . ',
			' . $objDatabase->SqlVariable($this->fltInventory) . ',
			' . $objDatabase->SqlVariable($this->fltInventoryTotal) . ',
			' . $objDatabase->SqlVariable($this->blnMasterModel) . ',
			' . $objDatabase->SqlVariable($this->intFkProductMasterId) . ',
			' . $objDatabase->SqlVariable($this->strProductSize) . ',
			' . $objDatabase->SqlVariable($this->strProductColor) . ',
			' . $objDatabase->SqlVariable($this->fltProductHeight) . ',
			' . $objDatabase->SqlVariable($this->fltProductLength) . ',
			' . $objDatabase->SqlVariable($this->fltProductWidth) . ',
			' . $objDatabase->SqlVariable($this->fltProductWeight) . ',
			' . $objDatabase->SqlVariable($this->intFkTaxStatusId) . ',
			' . $objDatabase->SqlVariable($this->fltSell) . ',
			' . $objDatabase->SqlVariable($this->fltSellTaxInclusive) . ',
			' . $objDatabase->SqlVariable($this->fltSellWeb) . ',
			' . $objDatabase->SqlVariable($this->strUpc) . ',
			' . $objDatabase->SqlVariable($this->blnWeb) . ',
			' . $objDatabase->SqlVariable($this->strWebKeyword1) . ',
			' . $objDatabase->SqlVariable($this->strWebKeyword2) . ',
			' . $objDatabase->SqlVariable($this->strWebKeyword3) . ',
			' . $objDatabase->SqlVariable($this->strMetaDesc) . ',
			' . $objDatabase->SqlVariable($this->strMetaKeyword) . ',
			' . $objDatabase->SqlVariable($this->blnFeatured) . ',
			' . $objDatabase->SqlVariable($this->dttCreated) . '
		)';
		$objDatabase->NonQuery($strSql);
		$this->intRowid = $objDatabase->InsertId('xlsws_product', 'rowid');
	}

	public function DeleteImages() {
		if (is_null($this->intRowid)) {
			QApplication::Log(E_ERROR, 'uploader',
				'Cannot unassociate unsaved Products');
			return;
		}

		foreach (Images::LoadArrayByProductAsImage($this->intRowid)
			as $objImage ) $objImage->Delete();

		$objDatabase = Product::GetDatabase();
		$strQuery = '
			DELETE FROM `xlsws_product_image_assn`
			WHERE `product_id` = ' .
				$objDatabase->SqlVariable($this->Rowid);
		$objDatabase->NonQuery($strQuery);

		if ($this->ImageId) {
			$objImage = Images::LoadByRowid($this->ImageId);
			if ($objImage) $objImage->Delete();
		}
	}
}
