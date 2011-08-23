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

require(__DATAGEN_CLASSES__ . '/CartGen.class.php');

/**
 * The Cart class defined here contains any customized code for the Cart
 * class in the Object Relational Model.
 *
 * XLSWS_CART session variable contains contains the current user cart
 */
class Cart extends CartGen {
	/**
	 * Initialize a cart and inject in SESSION
	 * @return
	 */

	public $blnStorePickup = false;

	public static function InitializeCart() {
		if (!isset($_SESSION['XLSWS_CART']) ||
			!$_SESSION['XLSWS_CART'] ||
			is_null($_SESSION['XLSWS_CART'])) {

			$cart = new Cart();
			$cart->Type = CartType::cart;

			$due = QDateTime::Now();
			$cart->DatetimeCre = QDateTime::Now();
			$cart->DatetimeDue =
				$due->AddDays(_xls_get_conf('CART_LIFE', 7));
			$cart->FkTaxCodeId = -1;

			if ($cart->FkTaxCodeId == -1)
				$cart->ResetTaxIncFlag();

			$_SESSION['XLSWS_CART'] = $cart;

			Cart::UpdateCartCustomer();
		}
	}

	/**
	 * Initialize if needed and return the current Cart
	 * @return
	 */
	public static function GetCart(){
		Cart::InitializeCart();
		return $_SESSION['XLSWS_CART'];
	}

	/**
	 * Function called by AJAX
	 * Initialize cart if needed, and return Cart Items
	 */
	public static function GetCartItems() {
		$objCart = Cart::GetCart();
		$objCart->SaveUpdatedCartItems();
		return $objCart->GetCartItemArray();
	}

	/**
	 * Create a cloned copy of the Cart
	 * @return newcart[]
	 */
	public static function CloneCart(){
		$objCart = Cart::GetCart();
		$arrItems = $objCart->GetCartItemArray();

		$objNewCart = $objCart;
		$objNewCart->Linkid = $objNewCart->IdStr = NULL ;
		$objNewCart->Save(true);

		// Copy over each items
		foreach($arrItems as $item){
			$item->CartId = $objNewCart->Rowid;
			$item->Save(true);
		}

		return $objNewCart;
	}

	/**
	 * Remove cart items from session
	 * @return
	 */
	public static function ClearCart(){
		$objCart = Cart::GetCart();

		// if it is a cart then delete it..
		if ($objCart->Type == CartType::cart  ||
			$objCart->Type == CartType::giftregistry) {
			$objCart->FullDelete();
		}

		unset($_SESSION['XLSWS_CART']);
	}

	/**
	 * Save cart to database
	 * @return
	 */
	public static function SaveCart($objCart){
		if ($objCart->intRowid) {
			$objCart->SaveUpdatedCartItems();
			$objCart->Save(false, true);
		}
		else
			$objCart->Save(true);

		$_SESSION['XLSWS_CART'] = $objCart;
	}

	/**
	 * Update the customer in the cart
	 * Used to update if you log in with a cart in progress
	 * @return
	 */
	public static function UpdateCartCustomer() {
		$customer = Customer::GetCurrent();
		$cart = Cart::GetCart();

		if($customer){
			$cart->Customer = $customer;
			$dest = Destination::LoadMatching(
				$customer->Country2,
				$customer->State2,
				$customer->Zip2);

			if($dest){
				$cart->FkTaxCodeId = $dest->Taxcode;
			}
		}

		if ($cart->Count > 0)
			$cart->UpdateCart();
	}

	/**
	 * Update the quantity of an existing Product in the Cart
	 * @param int $intItemId
	 * @param int $intQuantity
	 * @return
	 */
	public static function UpdateCartQuantity($intItemId, $intQuantity) {
		$objCart = Cart::GetCart();
		foreach ($objCart->GetCartItemArray() as $objItem) {
			if ($objItem->Rowid != $intItemId)
				continue;

			if ($objCart->UpdateItemQuantity($objItem, $intQuantity))
				$objCart->UpdateCart();
		}
	}

	/**
	 * Update the Quantity of an Item in the cart
	 * Then force recalculation of Cart values
	 * @param int $intItemId
	 * @param int $intQuantity
	 * @return
	 */
	public function UpdateItemQuantity($objItem, $intQuantity) {
		if ($intQuantity <= 0) {
			$objItem->Delete();
			return true;
		}

		if ($intQuantity == $objItem->Qty)
			return;

		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD','0') != '1' &&
			$intQuantity > $objItem->Qty &&
			$objItem->Product->Inventoried &&
			$objItem->Product->Inventory < $intQuantity) {
				_qalert(_sp('Your chosen quantity is not available' .
				' for ordering. Please come back and order later.'));
				return false;
			}

		$objItem->Qty = $intQuantity;
		return $objItem;
	}

	/**
	 * Update Cart by removing discounts if the Cart is expired
	 */
	public function UpdateDiscountExpiry() {
		foreach ($this->GetCartItemArray() as $objItem) {
			if ($this->IsExpired() && $objItem->Discounted) {
				$objItem->Discount = 0;
				$objItem->SellDiscount = 0;
			}
		}
	}

	/**
	 * Update Cart by removing Products which no longer exist
	 */
	public function UpdateMissingProducts() {
		foreach ($this->GetCartItemArray() as $objItem) {
			if (!$objItem->Product) {
				QApplication::Log(E_WARNING, 'cart',
					'Removing missing product code cart : ' . $this->Rowid);
				$objItem->Delete();
			}
		}
	}

	/**
	 * Update Cart by applying a Promo Code
	 */
	public function UpdatePromoCode($dryRun = false) {
		if (!$this->FkPromoId)
			return;

		$objPromoCode = PromoCode::Load($this->FkPromoId);

		if (!$objPromoCode)
			return;

		if (!$objPromoCode->Active)
			return;

		$intDiscount = 0;
		if ($objPromoCode->Type == PromoCodeType::Flat)
			$intDiscount = $objPromoCode->Amount;
		else if ($objPromoCode->Type == PromoCodeType::Percent)
			$intDiscount = $objPromoCode->Amount/100;
		else {
			QApplication::Log(E_WARNING, 'checkout'.
				'Invalid PromoCode type ' . $objPromoCode->Type);
			return;
		}

		$bolApplied = false;

		// Sort array by High Price to Low Price
		$arrSorted = array();
		foreach ($this->GetCartItemArray() as $objItem)
			$arrSorted[] = $objItem;
		usort($arrSorted, array('XLSCartItemManager', 'CompareByPrice'));

		foreach ($arrSorted as $objItem) {
			if (!$objPromoCode->IsProductAffected($objItem))
				continue;

			$intItemDiscount = 0;

			if ($objPromoCode->Type == PromoCodeType::Flat) {
				if ($intDiscount == 0) {
					$objItem->Discount=0;
					break;
				}

				$intItemPrice = $objItem->Sell;
				$intTotalPrice = $objItem->Sell * $objItem->Qty;

				if ($intDiscount >= $intTotalPrice) {
					$intItemDiscount = $intItemPrice;
					$intDiscount -= $intTotalPrice;
				}
				else {
					$intItemDiscount = $intDiscount / $objItem->Qty;
					$intDiscount = 0;
				}
			}
			else if ($objPromoCode->Type == PromoCodeType::Percent) {
				$intItemDiscount = $intDiscount * $objItem->Sell;
			}

			if (!$dryRun)
				$objItem->Discount = $intItemDiscount;

			$bolApplied = true;
		}
		return $bolApplied;
	}

	/**
	 * Update Cart by setting taxes when in Tax Exclusive
	 */
	public function UpdateTaxExclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			return;

		// Reset taxes
		$this->Tax1 = 0;
		$this->Tax2 = 0;
		$this->Tax3 = 0;
		$this->Tax4 = 0;
		$this->Tax5 = 0;

		// Get the rowid for "No Tax"
		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->Rowid;

		// Dont want taxes, so return
		if ($this->FkTaxCodeId == $intNoTax)
			return;

		foreach($this->GetCartItemArray() as $objItem) {
			$taxes = $objItem->Product->CalculateTax(
				$this->FkTaxCodeId, $objItem->SellTotal);

			$this->Tax1 += $taxes[1];
			$this->Tax2 += $taxes[2];
			$this->Tax3 += $taxes[3];
			$this->Tax4 += $taxes[4];
			$this->Tax5 += $taxes[5];
		}
	}

	/**
	 * Update Cart by setting taxes when in Tax Inclusive
	 */
	public function UpdateTaxInclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') != '1')
			return;

		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		// Reset taxes
		$this->Tax1 = 0;
		$this->Tax2 = 0;
		$this->Tax3 = 0;
		$this->Tax4 = 0;
		$this->Tax5 = 0;

		// Get the rowid for "No Tax"
		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->Rowid;

		// Tax Inclusive && Want taxes, so return and set prices back to inclusive if needed
		if ($this->FkTaxCodeId != $intNoTax) {
			if (!$this->TaxInclusive) { //if the last destination was exclusive, and we have inclusive now, we need to reset the line items
				$this->TaxInclusive = true;
				foreach ($this->GetCartItemArray() as $objItem) {
					 // Set back tax inclusive prices
					$objItem->Sell = $objItem->Product->GetPrice($objItem->Qty);
					$objItem->SellBase = $objItem->Sell;
					$objItem->Updated = true;
				}
			}
			return;
		}

		$this->TaxInclusive = false;

		// Tax Inclusive && Dont want taxes
		foreach ($this->GetCartItemArray() as $objItem) {
			// The Web Price is the only forced Tax Inclusive price
			if ($objItem->Product->HasWebPrice() && !$objItem->blnWebTaxRemoved) {
				$taxes = $objItem->Product->CalculateTax(
					_xls_tax_default_taxcode(), $objItem->Sell);

				// Taxes are deducted from cart for LightSpeed
				$this->Tax1 -= $taxes[1];
				$this->Tax2 -= $taxes[2];
				$this->Tax3 -= $taxes[3];
				$this->Tax4 -= $taxes[4];
				$this->Tax5 -= $taxes[5];

				$objItem->Sell -= round(array_sum($taxes), $TAX_DECIMAL);
				$objItem->SellBase = $objItem->Sell;
				$objItem->SellTotal =  $objItem->Sell * $objItem->Qty;
				$objItem->blnWebTaxRemoved = true;
				$objItem->Updated = true;
			} else if (!$objItem->blnWebTaxRemoved) {
				// Set Tax Exclusive price
				$objItem->Sell = $objItem->Product->GetPrice($objItem->Qty,true);
				$objItem->SellBase = $objItem->Sell;
				$objItem->Updated = true;
			}
		}
	}

	/**
	 * Update Cart by setting taxes for Shipping if applicable
	 */
	public function UpdateTaxShipping() {
		if (_xls_get_conf('SHIPPING_TAXABLE', '0') != '1')
			return;

		if (!$this->ShippingSell)
			return;

		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->Rowid;

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '0')
			if ($this->FkTaxCodeId == $intNoTax)
				return;

		$objShipProduct = Product::LoadByCode($this->ShippingMethod);

		$intTaxStatus = 0;
		if ($objShipProduct)
			$intTaxStatus = $objShipProduct->FkTaxStatusId;

		//
		// Check if the tax status is set to no tax for it, if so, make it
		// default, otherwise leave it alone.
		//
		if ($this->blnStorePickup && $intTaxStatus) {
			$objTaxStatus = $objShipProduct->FkTaxStatus;
			if ($objTaxStatus && $objTaxStatus->IsNoTax())
				$intTaxStatus = 0;
		}

		$nprice_taxes = _xls_calculate_price_tax_price(
			$this->ShippingSell, $this->FkTaxCodeId, $intTaxStatus);

		$taxes = $nprice_taxes[1];

		$this->Tax1 += $taxes[1];
		$this->Tax2 += $taxes[2];
		$this->Tax3 += $taxes[3];
		$this->Tax4 += $taxes[4];
		$this->Tax5 += $taxes[5];

		//
		// Legacy behavior assumes that the ShippingSell price does
		// not already contain taxes and that they must be added.
		//
		if ((_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1') &&
			($this->FkTaxCodeId != $intNoTax)) {
				$this->ShippingSell += array_sum($taxes);
		}
	}

	/**
	 * Update Cart by counting products and setting the Subtotal
	 */
	public function UpdateSubtotal() {
		$this->Count = 0;
		$this->Subtotal = 0;

		foreach ($this->GetCartItemArray() as $objItem) {
			$this->Count += $objItem->Qty;
			$this->Subtotal += $objItem->SellTotal;
		}
	}

	/**
	 * Update Cart by setting the cart Total
	 */
	public function UpdateTotal() {
		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			$this->Total = round($this->Subtotal, $TAX_DECIMAL) +
				round($this->ShippingSell, $TAX_DECIMAL);
		else
			$this->Total = round($this->Subtotal, $TAX_DECIMAL) +
				round($this->Tax1, $TAX_DECIMAL) +
				round($this->Tax2, $TAX_DECIMAL) +
				round($this->Tax3, $TAX_DECIMAL) +
				round($this->Tax4, $TAX_DECIMAL) +
				round($this->Tax5, $TAX_DECIMAL) +
				round($this->ShippingSell, $TAX_DECIMAL);
	}

	/**
	 * Iterate through the Cart Items and Save those that are Updated
	 */
	public function SaveUpdatedCartItems() {
		foreach ($this->GetCartItemArray() as $objItem)
			if ($objItem->Updated)
				$objItem->Save();
	}

	/**
	 * Perform all Cart Update mechanisms
	 * This is used to ensure that the Cart data remains consistent after
	 * additions and modifications of Products, updates to the Customer
	 * record and Tax Code.
	 */
	public function UpdateCart(
		$UpdateTax = true,
		$UpdateDiscount = true,
		$UpdateShipping = true,
		$SaveCart = true)
	{
		$this->UpdateMissingProducts();

		// TODO : Legacy code
		//if ($this->IsExpired())
		//    $this->UpdateDiscountExpiry();

		$this->UpdateSubtotal();

		if ($UpdateDiscount)
			$this->UpdatePromoCode();

		if ($UpdateTax) {
			$this->UpdateTaxInclusive();
			$this->UpdateTaxExclusive();
		}

		if ($UpdateShipping) {
			$this->UpdateTaxShipping();
		}

		$this->UpdateSubtotal();
		$this->UpdateTotal();

		if ($SaveCart) {
			$this->SyncSave();
		}
	}

	/**
	 * Stripped down version of AddItemToCart for use with the SOAP uploader
	 */
	public function AddSoapProduct($objProduct,
		$intQty = 1, $strDescription = false,
		$fltSell = false, $fltDiscount = 0,
		$mixCartType = false, $intGiftItemId = 0) {

		if (!$mixCartType)
			$mixCartType = CartType::cart;

		// Preload categories prior to calculations
		if (!CartItem::$Manager->Populated)
			$arrItems = $this->GetCartItemArray();

		$objItem = new CartItem();

		$objItem->Qty = abs($intQty);

		if ($objProduct->Rowid)
			$objItem->ProductId = $objProduct->Rowid;

		$objItem->CartType = $mixCartType;
		$objItem->Description = $strDescription;
		$objItem->GiftRegistryItem = $intGiftItemId;
		$objItem->Sell = $fltSell;
		$objItem->SellDiscount = $fltDiscount;
		$objItem->SellBase = $objProduct->SellWeb;
		$objItem->Code = $objProduct->OriginalCode;
		$objItem->Discount = "";
		$objItem->DatetimeAdded = QDateTime::Now();

		// If cart unsaved, Save it to get Rowid
		if (!$this->Rowid)
			$this->Save();

		$objItem->CartId = $this->Rowid;
		$objItem->Save();

		$this->UpdateCart(true,false,false,true);

		return $objItem->Rowid;
	}

	public function AddProduct($objProduct,
		$intQuantity, $mixCartType = false) {

		if (!$mixCartType)
			$mixCartType = CartType::cart;

		// Verify inventory
		if (!$objProduct->HasInventory(true)) {
			_qalert(_sp(_xls_get_conf('INVENTORY_ZERO_NEG_TITLE', 'Please Call')));
				return null;
		}

		// Ensure product is Sellable
		if (!$objProduct->Web) {
			_qalert(_sp('Selected product is no longer available' .
			   ' for ordering. Thank you for your understanding.'));
			return null;
		}

		if(function_exists('_custom_before_add_to_cart'))
			_custom_before_add_to_cart($objProduct , $intQuantity);

		$objItem = false;

		foreach ($this->GetCartItemArray() as $item) {
			if ($item->ProductId == $objProduct->Rowid &&
				$item->Code == $objProduct->OriginalCode &&
				$item->Description == $objProduct->Name  &&
				$item->CartType == $mixCartType) {
					$objItem = $item;
					break;
			}
		}

		if ($objItem) {
			$intTotalQty = $intQuantity + ($objItem->Qty?$objItem->Qty:0);

			if ($this->UpdateItemQuantity($objItem, $intTotalQty))
				$this->UpdateCart(false,true,false,true);

			return $objItem->Rowid;
		}
		$objItem = new CartItem();

		if ($objProduct->Rowid)
			$objItem->ProductId = $objProduct->Rowid;

		$objItem->Code = $objProduct->OriginalCode;
		$objItem->Qty = $intQuantity;
		$objItem->CartType = $mixCartType;
		$objItem->DatetimeAdded = QDateTime::Now();
		$objItem->SellBase = $objProduct->GetPrice(1);
		$objItem->Description = $objProduct->Name;
		$objItem->Sell = $objProduct->GetPrice($objItem->Qty);

		// If cart unsaved, Save it to get Rowid
		if (!$this->Rowid)
			$this->Save();

		$objItem->CartId = $this->Rowid;
		$objItem->Save();

		$this->UpdateCart(false,true,false,true);

		if(function_exists('_custom_after_add_to_cart'))
			_custom_after_add_to_cart($objProduct , $intQuantity);

		return $objItem->Rowid;
	}

	/**
	 * Add item to cart (in session and database)
	 *
	 * @param $product array
	 * @param $qty int optional
	 * @param $description optional
	 * @param $sell optional
	 * @param $discount optional
	 * @param $carttype optional
	 * @param $gift_item_id optional
	 * @return int (row id)
	 */
	public static function AddToCart($objProduct,
		$intQty = 1, $strDescription = false,
		$fltSell = false, $fltDiscount = 0,
		$mixCartType = false, $intGiftItemId = 0) {

		$objCart = Cart::GetCart();

		if (defined('XLSWS_SOAP'))
			return $objCart->AddSoapProduct($objProduct,
				$intQty = 1, $strDescription = false,
				$fltSell = false, $fltDiscount = 0,
				$mixCartType = false, $intGiftItemId = 0);

		return $objCart->AddProduct($objProduct, $intQty, $mixCartType);
	}

	/**
	 * function called by AJAX, syncs from the cart as stored in the
	 * database with what is in session
	 * @return
	 */
	public function SyncSave() {
		Cart::SaveCart($this);
	}

	/**
	 * Checks if current taxcode should be tax inclusive or not..
	 * @return
	 */
	public function ResetTaxIncFlag(){
		$this->TaxInclusive = false;

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1') {
			$objTaxCode = TaxCode::GetDefault();
			$this->FkTaxCodeId = $objTaxCode->Rowid;
			$this->TaxInclusive = true;
		}
	}

	public function IsExpired() {
		if ($this->DatetimeDue &&
			$this->DatetimeDue->IsEarlierThan(QDateTime::Now()))
				return true;
		return false;
	}

	/**
	 * Return link for current cart
	 * @return string
	 */
	protected function GetLink($blnTracking = false) {
		if ($this->Linkid == '' || is_null($this->Linkid)) {
			$this->Linkid = md5(date('U') . "_" . $this->intRowid);
			$this->Save();
		}

		$strUrl = _xls_site_dir() . '/index.php?xlspg=';

		if ($blnTracking) $strUrl = $strUrl . 'order_track&getuid=';
		else $strUrl = $strUrl . 'cart&getcart=';

		$strUrl = $strUrl . $this->Linkid;
		return $strUrl;
	}

	/**
	 * Combines weight of each product to give total weight of all items
	 * @return int
	 */
	protected function GetWeight(){
		$items = $this->GetCartItemArray();

		$weight = 0;
		foreach($items as $item){
			$product = $item->Product;
			$weight += $item->Qty * $product->ProductWeight;
		}

		if ($weight == 0)
			$weight = 1;

		return $weight;
	}

	/**
	 * Combines length of each product to give total length of all items
	 * @return int
	 */
	protected function GetLength(){
		$items = $this->GetCartItemArray();

		$length = 0;
		foreach($items as $item){
			$product = $item->Product;
			$length += $item->Qty * $product->ProductLength;
		}

		return $length;
	}

	/**
	 * Findest the widest product out of all your cart items to use as box width
	 * @return int
	 */
	protected function GetWidth(){
		$items = $this->GetCartItemArray();

		$width = 0;
		foreach($items as $item) {
			$product = $item->Product;

			if ($product->ProductWidth > $width)
				$width = $product->ProductWidth;
		}

		return $width;
	}

	/**
	 * Findest the tallest product out of all your cart items to use as box height
	 * @return int
	 */
	protected function GetHeight(){
		$items = $this->GetCartItemArray();

		$height = 0;
		foreach($items as $item){
			$product = $item->Product;

			if ($product->ProductHeight > $height)
				$height = $product->ProductHeight;
		}

		return $height;
	}

	/**
	 * from an emailed cart, load the cart by link
	 * @return
	 */
	public static function LoadCartByLink($strLinkId, $clone = true){
		$carts = Cart::LoadArrayByLinkid($strLinkId);

		if(!$carts)
			throw new Exception(_sp("Cart not found!"));

		$cart = current($carts);
		$cart->UpdateCart();

		// TODO Carts that have been disabled or expired
		//Cart::SaveCart($cart);

		// Clone the cart so we don't have modified carts everywhere...
		if ($clone){
			$newcart = Cart::CloneCart();
			Cart::SaveCart($newcart);
		}
	}

	/**
	 * loads cart by a given promo code id, if it exists
	 *
	 * @param $intRowid integer
	 * @param $cart_id integer
	 * @param $email string
	 * @return Cart object
	 */
	public static function LoadByFkPromoId($intRowid, $cart_id, $email) {
		// Use QuerySingle to Perform the Query
		return Cart::QuerySingle(
		  QQ::OrCondition(
			QQ::AndCondition(
				QQ::Equal(QQN::Cart()->FkPromoId, $intRowid),
				QQ::Equal(QQN::Cart()->Rowid, $cart_id)
			),
			QQ::AndCondition(
				QQ::Equal(QQN::Cart()->FkPromoId, $intRowid),
				QQ::Equal(QQN::Cart()->Type, CartType::order)
			)
		  )
		);
	}

	/**
	 * Delete cart items from database
	 * @return
	 */
	public function FullDelete() {
		$arrItems = $this->GetCartItemArray();

		foreach($arrItems as $objItem)
			$objItem->Delete();

		if ($this->intRowid)
			$this->Delete();
	}

	// String representation of the object
	public function __toString() {
		return sprintf('Cart Object %s',  $this->intRowid);
	}

	public static function initiate_cart() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::InitializeCart();
	}

	public static function get_cart() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::GetCart();
	}

	public static function clone_cart() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::CloneCart();
	}

	public function get_link() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->GetLink(false);
	}

	public static function clear_cart() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::ClearCart();
	}

	public static function save_cart($cart) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::SaveCart($cart);
	}

	public static function update_customer() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::UpdateCartCustomer();
	}

	public function update_tax() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->UpdateCart();
	}

	public function total_weight() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->GetWeight();
	}

	public function total_length() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->GetLength();
	}

	public function total_width() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->GetWidth();
	}

	public function total_height() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->GetHeight();
	}

	public static function add_to_cart($product , $qty = 1 , $description = FALSE , $sell = FALSE , $discount = 0 , $carttype = false , $gift_item_id = 0){
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::AddToCart($product, $qty, $description, $sell, $discount, $carttype, $gift_item_id);
	}

	public static function load_by_link($linkid , $clone = true){
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::LoadCartByLink($linkid, $clone = true);
	}

	public static function update_cart_qty($itemid, $qty) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return $this->UpdateCartQuantity($itemid, $qty);
	}

	/**
	 * calls save cart
	 * @return
	 */
	public function ssave(){
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Cart::SaveCart($this);
	}

	// Overload GetCartItemArray to provide to use the CartItem manager
	public function GetCartItemArray($objOptionalClauses = null) {
		if (CartItem::$Manager) {
			if (!CartItem::$Manager->HasAssociation($this->Rowid))
				CartItem::$Manager->AddArray(parent::GetCartItemArray());
			return CartItem::$Manager->GetByAssociation($this->Rowid);
		}
		return parent::GetCartItemArray($objOptionalClauses);
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Link':
				return $this->GetLink(true);

			case 'Order':
				return $this->GetLink(false);

			case 'Length':
				return $this->GetLength();

			case 'Height':
				return $this->GetHeight();

			case 'Width':
				return $this->GetWidth();

			case 'Weight':
				return $this->GetWeight();

			case 'SubTotalTaxIncIfSet':
				QApplication::Log(E_USER_NOTICE, 'legacy', $strName);
				return $this->Subtotal;

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
