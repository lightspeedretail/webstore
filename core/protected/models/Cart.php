<?php

/**
 * This is the model class for table "{{cart}}".
 *
 * @package application.models
 * @name Cart
 *
 */
class Cart extends BaseCart
{
	public $blnStorePickup = false;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Cart the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	// String representation of the object
	public function __toString()
	{
		return sprintf('Cart Object %s', $this->id);
	}

	/* Define some specialized query scopes to make searching for specific db info easier */
	public function scopes()
	{
		return array(
			'complete' => array(
				'condition' => 'cart_type='.CartType::order,
				'order' => 'id_str DESC',
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cart_type', 'numerical', 'integerOnly' => true, 'min' => CartType::order, 'max' => CartType::order, 'on' => 'manual', 'tooSmall' => 'Status must be set to Paid', 'tooBig' => 'Status must be set to Paid'),
			array('downloaded', 'safe'),
		) + parent::rules();
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAdmin()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		$criteria->compare('id_str', $this->id_str, true, 'OR');
		$criteria->compare('datetime_cre', $this->datetime_cre, true, 'OR');
		$criteria->compare('downloaded', $this->downloaded, false, 'AND');
		$criteria->compare('cart_type', $this->cart_type);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'id_str DESC',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}


	/**
	 * Create and return a cart object with some prepopulated values.
	 * We pass in and set the customerId to facilitate setting the tax code
	 *
	 * @param Customer $objCustomer
	 * @return Cart
	 */
	public static function initialize(Customer $objCustomer = null)
	{
		$objCart = new Cart();
		$objCart->cart_type = CartType::cart;

		if ($objCustomer instanceof Customer)
		{
			$objCart->customer_id = $objCustomer->id;
		}

		$objCart->datetime_cre = new CDbExpression('NOW()');;
		$objCart->datetime_due = new CDbExpression('now() + INTERVAL '._xls_get_conf('CART_LIFE', 7).' DAY');
		$objCart->ResetTaxIncFlag();
		$objCart->setTaxCodeByDefaultShippingAddress();

		return $objCart;
	}

	public static function initializeAndSave()
	{
		$objCart = self::initialize();
		if(!$objCart->save())
		{
			Yii::log("Error initializing cart ".print_r($objCart->getErrors(), true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		return $objCart;
	}

	public function getDataProvider()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		if ($this->id === null)
		{
			return new CArrayDataProvider(array());     // WS-2265 - The Edit Cart modal may display products when the cart is empty
		}

		$criteria = new CDbCriteria;
		$criteria->compare('cart_id',$this->id);

		return new CActiveDataProvider(new CartItem(), array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'id ASC',
			),
			'pagination' => false,
		));
	}

	/**
	 * Initialize if needed and return the current Cart
	 * This is used by unit tests to get a cart to work with for testing
	 * Normal use will use the Cart component
	 * @return Cart
	 */
	public static function GetCart()
	{
		if (is_null(Yii::app()->user->getState('cartid')))
		{
			$objCart = Cart::initialize();
			return $objCart;
		}
		else
		{
			$objCart = Cart::model()->findByPk(Yii::app()->user->getState('cartid'));

			if ($objCart === null)
			{
				//something has happened to the database object
				Yii::log("Could not find cart ".Yii::app()->user->getState('cartid').", creating new one.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->user->setState('cartid', null);
				$objCart = Cart::initialize();
			}

			return $objCart;
		}
	}

	/**
	 * Function called by AJAX
	 * Initialize cart if needed, and return Cart Items
	 */
	public static function GetCartItems() {
		$objCart = Cart::GetCart();
		$objCart->SaveUpdatedCartItems();
		return $objCart->cartItems;
	}

	/**
	 * Create a cloned copy of the Cart
	 * @return newcart[]
	 */
	public static function CloneCart($objCart = null){

		if (is_null($objCart))
			$objCart = Cart::GetCart();

		$objNewCart = new Cart;
		$objNewCart->attributes = $objCart->attributes;
		$objNewCart->linkid = null;
		$objNewCart->id_str = null;
		$objNewCart->datetime_cre = new CDbExpression('NOW()');
		$objNewCart->save();

		$arrItems = $objCart->cartItems;

		foreach($arrItems as $item){
			$newitem = new CartItem;
			$newitem->attributes = $item->attributes;
			$newitem->cart_id = $objNewCart->id;
			$newitem->datetime_added = new CDbExpression('NOW()');
			$newitem->datetime_mod = new CDbExpression('NOW()');
			$newitem->save();
		}

		return $objNewCart;
	}

	/**
	 * Update the cart's taxcode based on the current customer's
	 * default shipping destination, or on the ANY/ANY destination
	 * if the customer doesn't have a default shipping address
	 * configured.
	 *
	 * @throws CHttpException From @see TaxCode::getTaxCodeByAddress when Web
	 * Store is not configured correctly.
	 */
	public function setTaxCodeByDefaultShippingAddress()
	{

		if (is_null($this->customer_id))
		{
			return;
		}

		$objCustomer = $this->customer;

		if ($objCustomer instanceof Customer === false)
		{
			return;
		}

		$country = null;
		$state = null;
		$postal = null;

		if(isset($objCustomer->defaultShipping))
		{
			$country = $objCustomer->defaultShipping->country;
			$state = $objCustomer->defaultShipping->state;
			$postal = $objCustomer->defaultShipping->postal;
		}

		$taxcode = TaxCode::getTaxCodeByAddress(
			$country,
			$state,
			$postal
		);

		$this->tax_code_id = $taxcode->lsid;

		if ($this->item_count > 0)
		{
			$this->recalculateAndSave();
		}
	}

	public static function GetCartLastIdStr() {
		// Since id_str is a text field, we have to read in and strip out nonnumeric
		$intIdStr = Yii::app()->db->createCommand('SELECT SUBSTRING(id_str, 4)
				AS id_num
				FROM xlsws_cart
				WHERE id_str LIKE "WO-%"
				ORDER BY (id_num + 0) DESC
				LIMIT 1;')->queryScalar();


		if (empty($intIdStr))
			return 0;
		else
			return $intIdStr;
	}

	public function GetCartNextIdStr($blnUseDb = true) {

		if (!is_null($this->id_str)) return $this->id_str;

		$strNextId = _xls_get_conf('NEXT_ORDER_ID', false);

		if ($blnUseDb && $strNextId) {
			$intNextId = preg_replace("/[^0-9]/", "", $strNextId);
			return 'WO-' . $intNextId;
		}
		else {
			$intLastId = preg_replace("/[^0-9]/", "", Cart::GetCartLastIdStr());
			$intDocLastId = preg_replace("/[^0-9]/", "", Document::GetCartLastIdStr());
			if ($intDocLastId >$intLastId) $intLastId= $intDocLastId;
			$intNextId = intval($intLastId) + 1;
			$strNextId = 'WO-' . $intNextId;
			return $strNextId;
		}

	}

	/** For any cart items, recalculate the inventory available. Always needs to be done after
	 * an order is completed
	 *
	 */
	public function RecalculateInventoryOnCartItems() {

		$arrItems = $this->cartItems;
		foreach($arrItems as $objItem)
		{
			$objItem->product->SetAvailableInventory();

			foreach($objItem->product->xlswsCategories as $objCategory)
			{
				$objCategory->UpdateChildCount();
			}

			// Since products belong to one family we can call updateChildCount on the
			// product's family
			if (is_null($objItem->product->family) === false)
			{
				$objItem->product->family->UpdateChildCount();
			}

			$objEvent = new CEventProduct(get_class($this), 'onUpdateInventory', $objItem->product);
			_xls_raise_events('CEventProduct', $objEvent);
		}
	}

	public function SetIdStr() {
		$strQueryFormat = 'SELECT COUNT(id) FROM xlsws_cart WHERE '.
			'`id_str` = "%s" AND `id` != "%s";';

		if (!$this->id_str)
			$this->id_str = Cart::GetCartNextIdStr();

		$strQuery = sprintf($strQueryFormat, $this->id_str, $this->id);

		while(Yii::app()->db->createCommand($strQuery)->queryScalar() != '0') {
			$this->id_str++;
			$strQuery = sprintf($strQueryFormat, $this->id_str, $this->id);
		}

		try {
			$this->Save();
		}
		catch (Exception $objExc) {
			Yii::log('Failed to save cart with : ' . $objExc, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		}

		$objConf = Configuration::LoadByKey('NEXT_ORDER_ID');
		$objConf->key_value = intval(preg_replace("/[^0-9]/", "", $this->id_str)) + 1;
		$objConf->save();
	}

	/**
	 * Update the Quantity of an Item in the cart
	 * Then force recalculation of Cart values
	 * @param int $intItemId
	 * @param int $intQuantity
	 * @return string[]|true|void|CartItem
	 */
	public function UpdateItemQuantity($objItem, $intQuantity)
	{
		if ($intQuantity <= 0)
		{
			if($objItem->wishlist_item > 0)
			{
				WishlistItem::model()->updateByPk($objItem->wishlist_item, array('cart_item_id' => null));
			}

			$objItem->delete();
			return true;
		}

		if ($intQuantity == $objItem->qty)
		{
			return;
		}

		if (_xls_get_conf('PRICE_REQUIRE_LOGIN', 0) == 1 && Yii::app()->user->isGuest)
		{
			return array(
				'errorId' => 'notLoggedIn',
				'errorMessage' => Yii::t('cart', 'You must log in before Adding to Cart.')
			);
		}

		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD', 0) < Product::InventoryAllowBackorders &&
			$intQuantity > $objItem->qty &&
			$objItem->product->inventoried &&
			$objItem->product->inventory_avail < $intQuantity)
		{
			if (_xls_get_conf('INVENTORY_DISPLAY', 0) == 0)
			{
				$availQty = null;
			} else {
				$availQty = $objItem->product->inventory_avail;
			}

			return array(
				'errorId' => 'invalidQuantity',
				'errorMessage' => Yii::t('cart', 'Your chosen quantity is not available for ordering. Please come back and order later.'),
				'availQty' => $availQty
			);
		}

		// qty discount?
		$arrtmp = ProductQtyPricing::model()->findAllByAttributes(
			array('product_id' => $objItem->product_id, 'pricing_level' => 1),
			array('order' => 'qty ASC')
		);

		$tmpprice = 0;

		foreach ($arrtmp as $tmp)
		{
			$tmpprice = ($intQuantity >= $tmp->qty ? $tmp->price : $tmpprice);
		}

		$objItem->discount = ($tmpprice > 0 ? $objItem->sell_base - $tmpprice : 0);

		$objItem->qty = $intQuantity;
		$objItem->save();

		$this->recalculateAndSave();
		return $objItem;
	}

	/**
	 * Update Cart by removing discounts if the Cart is expired
	 */
	public function UpdateDiscountExpiry() {
		foreach ($this->cartItems as $objItem) {
			if ($this->IsExpired() && $objItem->Discounted) {
				$objItem->discount = 0;
				$objItem->sell_discount = 0;
				$objItem->sell_total = $objItem->sell_base*$objItem->qty;
				$objItem->save();
			}
		}
	}

	/**
	 * Remove discounts from all items in the cart.
	 */
	public function removeDiscounts()
	{
		foreach ($this->cartItems as $obj)
		{
			$objItem = CartItem::model()->findByPk($obj->id);
			if ($objItem->Discounted)
			{
				$objItem->discount = 0;
				$objItem->sell_discount = 0;
				$objItem->sell_total = $objItem->sell_base * $objItem->qty;
				$objItem->save();
			}
		}
	}
	/**
	 * Update Cart by removing Products which no longer exist or are unavailable
	 */
	public function updateMissingProducts()
	{
		$wasCartModified = false;

		foreach ($this->cartItems as $key => $objItem)
		{
			if (is_null($objItem->product) || $objItem->product->web != 1)
			{
				Yii::app()->user->addFlash(
					'warning',
					Yii::t(
						'cart',
						'The product {product} is no longer available on this site and has been removed from your cart.',
						array('{product}' => "<strong>" . $objItem->description . "</strong>")
					)
				);
				$objItem->delete();
				$wasCartModified = true;
			}

			if (is_numeric($this->document_id))
			{
				// TODO: Figure out why we do this because it's not exactly WS-221 as the commit suggests
				// TODO: Refactor this to go outside for loop if possible
				// Ignore carts that originate from quotes
				continue;
			}

			if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD', 0) == Product::InventoryAllowBackorders)
			{
				// TODO: Refactor this to go outside for loop if possible
				// Backorders are allowed.
				// We continue in the loop to satisfy currently written unit test CartAndCartItemTest::testUpdateMissingProducts()
				continue;
			}

			if ($objItem->product->inventoried != 1)
			{
				// Current item is non-inventoried
				// Move on to the next item
				continue;
			}

			if ($objItem->product->Inventory <= 0)
			{
				Yii::app()->user->addFlash(
					'warning',
					Yii::t(
						'cart',
						'The product {product} is now out of stock and has been removed from your cart.',
						array('{product}' => "<strong>".$objItem->description."</strong>")
					)
				);
				$objItem->delete();
				$wasCartModified = true;
			}
			elseif ($objItem->qty > $objItem->product->Inventory)
			{
				Yii::app()->user->addFlash(
					'warning',
					Yii::t(
						'cart',
						'The product {product} now has less stock available than the amount you requested. Your cart quantity has been reduced to match what is available.',
						array('{product}' => "<strong>".$objItem->description."</strong>")
					)
				);
				$objItem->qty = $objItem->product->Inventory;
				$objItem->save();
				$wasCartModified = true;
			}
		}

		if ($wasCartModified)
		{
			$this->recalculateAndSave();
			Yii::app()->shoppingcart->wasCartModified = $wasCartModified;
		}
	}


	/**
	 * Perform all Cart Update mechanisms
	 * This is used to ensure that the Cart data remains consistent after
	 * additions and modifications of Products, updates to the Customer
	 * record and Tax Code.
	 */
	public function recalculateAndSave()
	{
		// TODO: Investigate why we save() and refresh() here. This is
		// possibly related to the models/Cart.php beforeValidate magic
		// that we're doing to set things like tax_code_id.
		$this->save();
		$this->refresh();

		$this->updatePromoCode();
		$this->updateTaxInclusive();
		$this->updateTaxExclusive();
		$this->updateTaxShipping();
		$this->updateCountAndSubtotal();
		$this->updateTotal();

		$this->save();
		$this->refresh();
	}

	/**
	 * Update cart by applying promo code
	 *
	 * @return bool
	 */
	public function updatePromoCode() {
		if (!$this->fk_promo_id)
		{
			return false;
		}

		$objPromoCode = PromoCode::model()->findByPk($this->fk_promo_id);

		if (!($objPromoCode instanceof PromoCode) || !$objPromoCode->enabled)
		{
			return false;
		}

		// In dollar amount discount, exit if there are
		// any item with sell_discount value set
		if ($objPromoCode->type == PromoCodeType::Flat)
		{
			foreach ($this->cartItems as $objItem)
			{
				if ($objItem->sell_discount > 0)
				{
					return false;
				}
			}
		}

		// Sort array by High Price to Low Price, reset discount to 0 to evaluate from the beginning
		$arrSorted = array();
		$intOriginalSubTotal = 0;
		foreach ($this->cartItems as $objItem)
		{
			$objItem->discount = 0;
			$arrSorted[] = $objItem;
			$intOriginalSubTotal += $objItem->qty * $objItem->sell;
		}

		if (is_null($objPromoCode->threshold))
		{
			$objPromoCode->threshold = 0; //for calculation purposes
		}

		// If none of the products are affected by the promo code anymore, remove it
		$isAnyProductAffected = false;
		foreach ($arrSorted as $objItem)
		{
			if ($objPromoCode->IsProductAffected($objItem))
			{
				$isAnyProductAffected = true;
				break;
			}
		}

		if (($objPromoCode->threshold > $intOriginalSubTotal && $this->fk_promo_id !== NULL) || $isAnyProductAffected === false)
		{
			$this->fk_promo_id = NULL;
			Yii::app()->user->setFlash(
				'error',
				Yii::t(
					'cart',
					'Promo Code {promocode} no longer applies to your cart and has been removed.',
					array('{promocode}' => "<strong > ".$objPromoCode->code."</strong > ")
				)
			);
			$this->removeDiscounts();
			return false;
		}

		if ($objPromoCode->type == PromoCodeType::Flat)
		{
			$intDiscount = $objPromoCode->amount;
		}
		else if ($objPromoCode->type == PromoCodeType::Percent)
		{
			$intDiscount = $objPromoCode->amount/100;
		}
		else
		{
			Yii::log('Invalid PromoCode type ' . $objPromoCode->type, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		$blnApplied = false;

		usort($arrSorted, array(get_class($this), 'CompareByPrice'));

		foreach ($arrSorted as $objItem)
		{
			if (!$objPromoCode->IsProductAffected($objItem))
			{
				continue;
			}

			$intItemDiscount = 0;

			if ($objPromoCode->type == PromoCodeType::Flat) {
				if ($intDiscount == 0) {
					$objItem->discount=0;
					break;
				}

				$intItemPrice = $objItem->sell;
				$intTotalPrice = $objItem->sell * $objItem->qty;

				if ($intDiscount >= $intTotalPrice) {
					$intItemDiscount = $intItemPrice;
					$intDiscount -= $intTotalPrice;
				}
				else {
					$intItemDiscount = $intDiscount / $objItem->qty;
					$intDiscount = 0;
				}
			}
			else if ($objPromoCode->type == PromoCodeType::Percent) {
				$intItemDiscount = $intDiscount * $objItem->sell;
			}

			if ($intItemDiscount > 0)
			{
				$objItem->discount = $intItemDiscount;
				$objItem->save();
				$blnApplied = true;
			}
		}

		return $blnApplied;
	}

	/**
	 * Update tax values on the cart and cart items for tax inclusive stores.
	 */
	protected function updateTaxInclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') != '1')
		{
			return;
		}

		$taxDecimal = _xls_get_conf('TAX_DECIMAL', 2);

		// Reset taxes
		$this->tax1 = 0;
		$this->tax2 = 0;
		$this->tax3 = 0;
		$this->tax4 = 0;
		$this->tax5 = 0;

		// If we are in tax inclusive mode, the only case when we don't
		//  want tax inclusive prices is:
		//   1. Our tax code is NOTAX AND
		//   2. We did NOT select in-store-pickup.
		if ($this->taxCode->IsNoTax() && $this->blnStorePickup === false)
		{
			foreach ($this->cartItems as $objItem)
			{
				$objTaxCode = TaxCode::GetDefault();

				// For quote to cart, we have to remove prices manually
				if ($objItem->cart_type == CartType::quote && $objItem->tax_in)
				{
					$taxes = $objItem->product->CalculateTax(
						$objTaxCode->id,
						$objItem->sell
					);

					// Taxes are deducted from cart for Lightspeed
					$this->tax1 -= $taxes[1];
					$this->tax2 -= $taxes[2];
					$this->tax3 -= $taxes[3];
					$this->tax4 -= $taxes[4];
					$this->tax5 -= $taxes[5];

					$objItem->sell -= round(array_sum($taxes), $taxDecimal);
					$objItem->sell_base = $objItem->sell;
					$objItem->sell_total = $objItem->sell * $objItem->qty;
					$objItem->tax_in = 0;
					$objItem->save();
				}
				elseif ($objItem->cart_type == CartType::cart && $objItem->tax_in)
				{
					// Set Tax Exclusive price
					$qty = 1;
					$taxIn = 0;
					$objItem->sell = $objItem->sell_base = $objItem->product->getPriceValue($qty, $taxIn);
					$objItem->sell_total = $objItem->sell_base * $objItem->qty;
					$objItem->tax_in = 0;
					$objItem->save();
				}
			}
		}
		else
		{
			// Tax Inclusive && Want taxes, so set prices back to
			// inclusive if needed
			foreach ($this->cartItems as $objItem)
			{
				// Set back tax inclusive prices
				if ($objItem->tax_in == 0)
				{
					$qty = 1;
					$taxIn = 1;
					$objItem->sell = $objItem->product->getPriceValue($qty, $taxIn);
					$objItem->sell_base = $objItem->sell;
					$objItem->sell_total = $objItem->sell_base * $objItem->qty;
					$objItem->tax_in = 1;
					$objItem->save();
				}
			}
		}
	}

	/**
	 * Update tax values on the cart and cart items for tax exclusive stores.
	 */
	public function updateTaxExclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
		{
			return;
		}

		// Reset taxes
		$this->tax1 = 0;
		$this->tax2 = 0;
		$this->tax3 = 0;
		$this->tax4 = 0;
		$this->tax5 = 0;

		// Get the rowid for "No Tax"
		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) {
			$intNoTax = $objNoTax->lsid;
		}

		// Dont want taxes, so return
		if (is_null($this->tax_code_id) || $this->tax_code_id == $intNoTax)
		{
			return;
		}

		foreach($this->cartItems as $objItem) {
			$taxes = $objItem->product->CalculateTax($this->tax_code_id, $objItem->sell_total);

			$this->tax1 += $taxes[1];
			$this->tax2 += $taxes[2];
			$this->tax3 += $taxes[3];
			$this->tax4 += $taxes[4];
			$this->tax5 += $taxes[5];
		}
	}

	/**
	 * Update shipping price and shipping tax price on the cart.
	 */
	protected function updateTaxShipping() {

		if (isset($this->shipping->shipping_sell) === false)
		{
			return;
		}

		if (Yii::app()->params['SHIPPING_TAXABLE'] != '1')
		{
			$this->shipping->shipping_sell_taxed = $this->shipping->shipping_sell;
			$this->shipping->shipping_taxable = 0;
			$this->shipping->save();
			return;
		}

		$this->shipping->shipping_taxable = 1;
		$this->shipping->save();

		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax instanceof TaxCode)
		{
			$intNoTax = $objNoTax->lsid;
		}

		if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '0' && $this->tax_code_id == $intNoTax)
		{
			$this->shipping->shipping_sell_taxed = $this->shipping->shipping_sell;
			$this->shipping->save();
			return;
		}

		$objShipProduct = Product::LoadByCode($this->shipping->shipping_method);

		$intTaxStatus = 0;
		if ($objShipProduct)
		{
			$intTaxStatus = $objShipProduct->taxStatus->lsid;
		}

		// Check if the tax status is set to no tax for it, if so, make it
		// default, otherwise leave it alone.
		if (Yii::app()->getComponent('storepickup')->IsStorePickup && $intTaxStatus)
		{
			$objTaxStatus = $objShipProduct->taxStatus;

			if ($objTaxStatus && $objTaxStatus->IsNoTax())
			{
				$intTaxStatus = 0;
			}
		}

		$nprice_taxes = Tax::calculatePricesWithTax($this->shipping->shipping_sell, $this->tax_code_id, $intTaxStatus);

		$taxes = $nprice_taxes['arrTaxValues'];

		if ($this->tax_code_id == $intNoTax)
		{
			$this->shipping->shipping_sell_taxed = $this->shipping->shipping_sell;
		}

		else
		{
			$this->shipping->shipping_sell_taxed = $nprice_taxes['fltSellTotalWithTax'];

			if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] != '1')
			{
				$this->tax1 += $taxes[1];
				$this->tax2 += $taxes[2];
				$this->tax3 += $taxes[3];
				$this->tax4 += $taxes[4];
				$this->tax5 += $taxes[5];
			}
		}

		$this->shipping->shipping_taxable = 1;
		$this->shipping->save();
	}

	/**
	 * Update the cart total.
	 */
	protected function updateTotal() {
		$this->total = $this->getTotalWithShipping($this->shipping_sell);
	}

	/**
	 * Update item count and subtotal on the cart.
	 */
	public function updateCountAndSubtotal() {

		$this->item_count = 0;
		$this->subtotal = 0;

		foreach ($this->cartItems as $objItem) {
			$this->item_count += 1; //How many rows in cart_items
			$this->subtotal += $objItem->sell_total;
		}

	}

	/**
	 * Iterate through the Cart Items and Save those that are Updated
	 */
	public function SaveUpdatedCartItems() {
		foreach ($this->cartItems as $objItem)
			$objItem->save();
	}

	/**
	 * Attempt to add product to cart. If product cannot be added, the error string is returned. Otherwise, the row id is returned.
	 * @param $objProduct
	 * @param int $intQuantity
	 * @param int $mixCartType
	 * @param null $intGiftItemId
	 * @param bool $strDescription
	 * @param bool $fltSell
	 * @param bool $fltDiscount
	 * @return bool|string
	 */
	public function AddProduct($objProduct,
		$intQuantity = 1,
		$mixCartType = 0,
		$intGiftItemId = null,
		$strDescription = false,
		$fltSell = false,
		$fltDiscount = false)
	{

		if ($mixCartType==0)
			$mixCartType = CartType::cart;


		if (_xls_get_conf('PRICE_REQUIRE_LOGIN') && Yii::app()->user->isGuest) {
			return Yii::t('cart',"You must log in to {button}",
				array('{button}'=>Yii::t('product', 'Add to Cart')));
		}

		if ($objProduct->IsMaster) {
			return Yii::t('cart',"Please choose options before selecting {button}",
				array('{button}'=>Yii::t('product', 'Add to Cart')));
		}

		// Verify inventory
		if (!$objProduct->getIsAddable() && $mixCartType==CartType::cart) {
				return Yii::t('cart',_xls_get_conf('INVENTORY_ZERO_NEG_TITLE', 'This item is not currently available'));
		}

		// Ensure product is Saleable
		if (!$objProduct->web && $mixCartType==CartType::cart) {
			return Yii::t('cart',
				'Selected product is no longer available for ordering. Thank you for your understanding.');
		}

		//Todo Replace with CEvent
		if(function_exists('_custom_before_add_to_cart'))
			_custom_before_add_to_cart($objProduct , $intQuantity);

		$objItem = false;


		//Items to use
		$intTaxIn = ($this->tax_code_id>0 && _xls_get_conf('TAX_INCLUSIVE_PRICING') ? 1 : 0);
		if ($strDescription==false) $strDescription = $objProduct->Title;
		if ($fltSell==false) $fltSell = $objProduct->getPriceValue(1,$intTaxIn);
		if ($fltDiscount==false) $fltDiscount=0;

		foreach ($this->cartItems as $item) {

			if ($item->product_id == $objProduct->id &&
				$item->code == $objProduct->OriginalCode &&
				$item->description == $strDescription  &&
				$item->sell_discount == $fltDiscount  &&
				$item->cart_type == $mixCartType &&
				$item->wishlist_item == $intGiftItemId) {
				$objItem = $item;
				break;
			}
		}

		// If our Cart isn't saved to the db at this point, save it
		if (is_null($this->id))
		{
			if (!$this->save())
			{
				throw new Exception(
					sprintf(
						"Unable to save cart before adding this first product: %s\n%s",
						$objProduct->code,
						print_r($this->getErrors(), true)
					)
				);
			}

			Yii::app()->user->setState('cartid', $this->id);
		}

		if (!$objItem) {
			$objItem = new CartItem();

			if ($objProduct->id)
				$objItem->product_id = $objProduct->id;

			$objItem->cart_id = $this->id;
			$objItem->code = $objProduct->OriginalCode;
			$objItem->cart_type = $mixCartType;
			$objItem->datetime_added = new CDbExpression('NOW()');
			$objItem->sell_base = $fltSell;
			$objItem->sell_discount = $fltDiscount;

			$objItem->description = $strDescription;
			$objItem->tax_in= $intTaxIn;

			if ($intGiftItemId > 0)
				$objItem->wishlist_item = $intGiftItemId;

		}

		$objItem->qty = ($objItem->qty ? $objItem->qty : 0);
		$objItem->sell_total = $objItem->sell_base * $objItem->qty;

		if ($objItem->save() === false)
		{
			throw new Exception('Unable to save item: ' . print_r($objItem->getErrors(), true));
		}

		$retVal = $this->UpdateItemQuantity($objItem, $intQuantity + $objItem->qty);
		if (!($retVal instanceof CartItem))
			return $retVal;

		$objItem->cart_id = $this->id;

		$this->recalculateAndSave();

		//Todo change to CEvent
		if(function_exists('_custom_after_add_to_cart'))
			_custom_after_add_to_cart($objProduct , $intQuantity);

		return $objItem->id;
	}

	/**
	 * Checks if current taxcode should be tax inclusive or not..
	 * @return
	 */
	public function ResetTaxIncFlag(){
		$this->tax_inclusive = 0;

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == 1) {
			$objTaxCode = TaxCode::GetDefault();
			if ($objTaxCode instanceof TaxCode)
			{
				$this->tax_code_id = $objTaxCode->lsid;
				$this->tax_inclusive = 1;
			}
		}
	}

	public function IsExpired() {
		if ($this->datetime_due && strtotime($this->datetime_due) < strtotime('now'))
			return true;
		return false;
	}

	/**
	 * Return a unique identifier for this cart, if this cart
	 * doesn't already have a unique identifier, then generate one, save it
	 * to the db, and return it to the caller.
	 *
	 * @return string a unique identifier for this cart
	 */
	public function GenerateLink() {
		if (empty($this->linkid))
		{
			$this->linkid = _xls_seo_url(_xls_truncate(_xls_encrypt(md5(date("YmdHis"))), 31, ''));
			$this->save();
			return $this->linkid;
		}

		return $this->linkid;
	}

	public function getLink() {

		return Yii::app()->createAbsoluteUrl('cart/receipt',array('getuid'=>$this->GenerateLink()));

	}

	/**
	 * Combines weight of each product to give total weight of all items
	 * @return int
	 */
	protected function GetWeight(){
		$items = $this->cartItems;

		$weight = 0;
		foreach($items as $item){
			$product = $item->product;
			$weight += $item->qty * $product->product_weight;
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
		$items = $this->cartItems;

		$length = 0;
		foreach($items as $item){
			$product = $item->product;
			$length += $item->qty * $product->product_length;
		}

		return $length;
	}

	/**
	 * Find the widest product out of all your cart items to use as box width
	 * @return int
	 */
	protected function GetWidth(){
		$items = $this->cartItems;

		$width = 0;
		foreach($items as $item) {
			$product = $item->product;

			if ($product->product_width > $width)
				$width = $product->product_width;
		}

		return $width;
	}

	/**
	 * Find the tallest product out of all your cart items to use as box height
	 * @return int
	 */
	protected function GetHeight(){
		$items = $this->cartItems;

		$height = 0;
		foreach($items as $item){
			$product = $item->product;

			if ($product->product_height > $height)
				$height = $product->product_height;
		}

		return $height;
	}

	/**
	 * Get shipping charge for different webstore modes.
	 *
	 * @return shipping charge for the cart
	 */
	protected function getShippingCharge()
	{
		return $this->shipping->getShippingSell();
	}


	/**
	 * Calculate how many pending orders are waiting to be downloaded
	 * @return int
	 */
	public static function GetPending() {
		return Cart::model()->countByAttributes(array('downloaded'=>0,'cart_type'=>CartType::order));
	}

	/**
	 * Load Cart by the Id String (i.e. WO- number)
	 * @param $strIdStr
	 * @return array|CActiveRecord|mixed|null
	 */
	public static function LoadByIdStr($strIdStr) {
		return Cart::model()->findByAttributes(array('id_str'=>$strIdStr));
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
		$cart->recalculateAndSave();

		// TODO Carts that have been disabled or expired
		//Cart::SaveCart($cart);

		// Clone the cart so we don't have modified carts everywhere...
		if ($clone){
			$newcart = Cart::CloneCart();
			Cart::SaveCart($newcart);
		}
	}

	/**
	 * Load an array of Cart objects,
	 * by CustomerId Index(es)
	 * @param integer $intCustomerId
	 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
	 * @return Cart[]
	 */
	public static function LoadLastCartInProgress($intCustomerId, $intBelowCart = 0)
	{
		// if $intBelowCart is null it means the active cart hasn't been saved to the db yet.
		// This most likely means it is empty i.e. no products have been added.
		// However, in order for the db query to work as intended, we need to set it to an integer.
		if (is_null($intBelowCart))
		{
			$intBelowCart = 0;
		}

		$strConditionCurrentCart = 'id > :currentcart';
		if ($intBelowCart > 0)
		{
			$strConditionCurrentCart = 'id < :currentcart';
		}

		$criteria = new CDbCriteria();
		$criteria->condition =
			'customer_id = :id AND '.
			'cart_type = :type AND '.
			'item_count > 0 AND '.
			$strConditionCurrentCart;
		$criteria->order = 'id DESC';
		$criteria->limit = 1;
		$criteria->params = array(
			'id' => $intCustomerId,
			'type' => CartType::cart,
			'currentcart' => $intBelowCart
		);

		$arrCarts = Cart::model()->findAll($criteria);

		if (count($arrCarts) > 0)
		{
			return $arrCarts[0];
		}
		else
		{
			return null;
		}
	}


	/**
	 * Delete cart items from database
	 * @return
	 */
	public function FullDelete() {
		$arrItems = $this->cartItems;

		foreach($arrItems as $objItem)
			$objItem->delete();

		if ($this->id)
			$this->delete();

		$this->refresh();
	}

	public function UpdateWishList()
	{


		foreach($this->cartItems as $item)
		{
			if (!is_null($item->wishlist_item))
			{
				//See if we have delete set
				if ($item->wishlistItem->registry->after_purchase == Wishlist::DELETEFROMLIST)
				{
					$item->wishlist_item = null;
					$item->save();
					$item->wishlistItem->cart_item_id = null;
					$item->wishlistItem->save();
					$item->wishlistItem->delete();

					$this->refresh();
				} else {
					//Mark which customer purchased the item
					$item->wishlistItem->purchased_by = Yii::app()->user->id;
					$item->wishlistItem->save();
				}

			}
		}
	}

	public function HasShippableGift()
	{

		foreach ($this->cartItems as $item)
			if(isset($item->wishlist_item))
				if ($item->wishlistItem->registry->customer_id != Yii::app()->user->id &&
					$item->wishlistItem->registry->ship_option > 0)
					return true;

		return false;

	}

	public function GiftAddress()
	{
		foreach ($this->cartItems as $item)
			if(isset($item->wishlist_item))
				if ($item->wishlistItem->registry->customer_id != Yii::app()->user->id &&
					$item->wishlistItem->registry->ship_option > 0)
					return CustomerAddress::model()->findAllByPk($item->wishlistItem->registry->ship_option);
		return null;

	}

	/**
	 * During the Cart completion process, update (decrement) the usage quantity remaining
	 * and add the promo code information to the order notes.
	 *
	 * @return void
	 */

	public function completeUpdatePromoCode()
	{
		$objPromo = null;

		if ($this->fk_promo_id > 0)
		{
			$objPromo = PromoCode::model()->findByPk($this->fk_promo_id);

			$this->printed_notes = implode("\n\n", array(
				$this->printed_notes,
				sprintf("%s: %s", _sp('Promo Code'), $objPromo->code)
			));

			foreach ($this->cartItems as $objItem)
			{
				if ($objItem->discount > 0)
				{
					$this->printed_notes = implode("\n", array(
						$this->printed_notes,
						sprintf(
							"%s discount: %.2f",
							$objItem->code,
							$objItem->discount
						)
					));
				}
			}

			if ($objPromo->qty_remaining > 0)
			{
				$objPromo->qty_remaining--;
				$objPromo->save();
			}
		}

		$this->save();
	}

	public function getTotalDiscount()
	{
		$total=0;
		foreach($this->cartItems as $item)
		{
			$total += $item->discount * $item->qty;
		}
		return $total;
	}

	/**
	 * Format the totalDiscount string by adding the relevant
	 * currency to it.
	 * @return string
	 */
	public function getTotalDiscountFormatted()
	{
		return _xls_currency($this->totalDiscount);
	}

	public function getTotalItemCount()
	{
		$total=0;
		foreach($this->cartItems as $item)
		{
			$total += $item->qty;
		}
		return $total;
	}

	public function getPromoCode()
	{
		if($this->fk_promo_id)
		{
			$obj = PromoCode::model()->findByPk($this->fk_promo_id);
			if ($obj) return $obj->code;
			return "unknown";
		}
		return null;
	}

	public function getHasPromoCode()
	{
		if ($this->fk_promo_id === null)
		{
			return false;
		}

		return true;
	}

	/**
	 * Erases all carts and cart items that are older than CART_LIFE days and
	 * have no customer_id assocaited with them.
	 *
	 * Called by legacySoapController when doing a document_flush(),
	 * document_flush() is called by onsite when the user initiates a
	 * "Reset Carts and Documents" from the Tools->eCommerce->Documents tab
	 *
	 * @return int The number of carts + items deleted by the
	 * query
	 */
	public static function garbageCollect()
	{
		return ShoppingCart::eraseExpired();
	}


	/**
	 * Adds taxes to cart
	 *
	 * @param $arr - array of 5 tax values
	 */

	public function addTaxes($arr)
	{
		if (count($arr) !== 5)
			Yii::log(
				'Taxes not added, incorrect size of passed array. Expecting 5, received '.count($arr),
				'error',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
		else
		{
			$arr = array_values($arr);  // normalize array keys
			$this->tax1 += $arr[0];
			$this->tax2 += $arr[1];
			$this->tax3 += $arr[2];
			$this->tax4 += $arr[3];
			$this->tax5 += $arr[4];
		}

	}

	/**
	 * Subtract taxes from cart
	 *
	 * @param $arr - array of 5 tax values
	 */

	public function subtractTaxes($arr)
	{
		if (count($arr) !== 5)
			Yii::log(
				'Taxes not subtracted, incorrect size of passed array. Expecting 5, received '.count($arr),
				'error',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
		else
		{
			$arr = array_values($arr);  // normalize array keys
			$this->tax1 -= $arr[0];
			$this->tax2 -= $arr[1];
			$this->tax3 -= $arr[2];
			$this->tax4 -= $arr[3];
			$this->tax5 -= $arr[4];
		}

	}

	/**
	 * Determine if the current cart has a tax code
	 * @return boolean true if the cart has a tax code
	 */
	public function hasTaxCode() {
		return isset($this->taxCode);
	}

	/**
	 * Determine if the current cart is taxable based on it's taxcode
	 * @return boolean true if the carts tax code is taxable
	 * @throws Exception
	 * @see Customer::defaultShippingIsTaxIn The logic is very similar.
	 */
	public function getIsTaxIn() {

		if ($this->hasTaxCode() === false)
		{
			throw new Exception("No tax code.");
		}

		// Tax-exclusive stores never have tax inclusive carts.
		if (CPropertyValue::ensureBoolean(_xls_get_conf('TAX_INCLUSIVE_PRICING', 0)) === false)
		{
			return false;
		}

		// Tax-inclusive stores only have 2 tax codes: their tax inclusive tax
		// code and a no-tax tax code.
		if ($this->taxCode->IsNoTax() === true)
		{
			return false;
		}

		return true;
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate
	 * requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
		{
			$this->datetime_cre = new CDbExpression('NOW()');
		}

		$this->modified = new CDbExpression('NOW()');

		if (empty($this->tax_inclusive))
		{
			$this->tax_inclusive = _xls_get_conf('TAX_INCLUSIVE_PRICING', 0);
		}

		if (empty($this->cart_type))
		{
			$this->cart_type = CartType::cart;
		}

		if (empty($this->linkid))
		{
			$this->linkid = $this->GenerateLink();
		}

		return parent::beforeValidate();
	}

	protected function afterValidate() {
		$arrErrors = $this->GetErrors();

		return parent::afterValidate();
	}

	protected function afterSave() {

		Yii::app()->user->setState('cartid', $this->id);

		return parent::afterSave();
	}

	public function __get($strName) {
		switch ($strName) {

			case 'completed':
				return "nothing";
				return $this->carts(array('scopes'=>array('complete')));


			case 'DatetimeCreated':
				return date(_xls_get_conf('DATE_FORMAT','Y-m-d'),strtotime($this->datetime_cre));

			case 'cartitems':
				return $this->cartItems;

			case 'Length':
				return $this->GetLength();

			case 'Height':
				return $this->GetHeight();

			case 'Width':
				return $this->GetWidth();

			case 'Weight':
				return $this->GetWeight();

			case 'HasShippableGift':
				return $this->HasShippableGift();

			case 'GiftAddress':
				return $this->GiftAddress();

			case 'SubTotalTaxIncIfSet':
				QApplication::Log(E_USER_NOTICE, 'legacy', $strName);
				return $this->Subtotal;

			case 'tax1name':
			case 'tax1Name':
				return Tax::TaxByLsid(1);

			case 'tax2name':
			case 'tax2Name':
				return Tax::TaxByLsid(2);

			case 'tax3name':
			case 'tax3Name':
				return Tax::TaxByLsid(3);

			case 'tax4name':
			case 'tax4Name':
				return Tax::TaxByLsid(4);

			case 'tax5name':
			case 'tax5Name':
				return Tax::TaxByLsid(5);

			case 'tax_total':
			case 'TaxTotal':
				return round(round($this->tax1,2)+round($this->tax2,2)+
					round($this->tax3,2)+round($this->tax4,2)+round($this->tax5,2),2);

			case 'tax_code':
				if (isset($this->taxCode->code))
					return $this->taxCode->code;
				else
					return '';

			case 'payment':
				if (isset($this->payment))
					return parent::__get($strName);
				else
					return new CartPayment();

			case 'Taxes':
				$arrTaxes = Tax::model()->findAll(array('order'=>'id'));

				return array(
					$arrTaxes[0]->tax=>round($this->tax1,2),
					$arrTaxes[1]->tax=>round($this->tax2,2),
					$arrTaxes[2]->tax=>round($this->tax3,2),
					$arrTaxes[3]->tax=>round($this->tax4,2),
					$arrTaxes[4]->tax=>round($this->tax5,2)
				);

			case 'Pending':
				return $this->GetPending();

			case 'shipping_sell':
				if (isset($this->shipping->shipping_sell))
					return $this->shipping->getShippingSell();
				else
					return 0;

			default:
				return parent::__get($strName);
		}
	}


	public static function CompareByPrice($objA, $objB) {
		if ($objA->sell_base == $objB->sell_base)
			return 0;

		return ($objA->sell_base < $objB->sell_base) ? +1 : -1;
	}

	/**
	 * Return an array of pending orders containing 'id_str' and 'datetime_cre' fields.
	 *
	 * @return array pending orders
	 */
	public static function getPendingOrders()
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'id_str,datetime_cre';
		$criteria->condition = 'cart_type = :cart_type AND downloaded = :downloaded';
		$criteria->params = array('cart_type' => CartType::order, 'downloaded' => 0);
		$pendingOrders = Cart::model()->findAll($criteria);

		$returnOrders = array();

		foreach ($pendingOrders as $order)
		{
			array_push($returnOrders, array('id_str' => $order['id_str'], 'datetime_cre' => $order['datetime_cre']));
		}

		return $returnOrders;
	}

	/**
	 * Check to see if the cart contains items that have quantity discounts
	 * applied to them. If there is one element that those this method will
	 * return true.
	 *
	 * @return bool If the cart has a discount it returns true
	 * false otherwise.
	 */
	public function hasQtyDiscount()
	{
		foreach ($this->cartItems as $item)
		{
			if ($item->discount > 0)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Check that the promo code applied to the cart is still valid.
	 * If the promo code is no longer valid, remove it and return an error
	 * message.
	 *
	 * @return array|null Null if the promo code is still valid. If the promo
	 * code is invalid an array with the following keys will be returned:
	 *	code => The promo code.
	 *	reason => The reason that the promo code is invalid.
	 */
	public function revalidatePromoCode()
	{
		if ($this->fk_promo_id === null)
		{
			// No promo code applied.
			return null;
		}

		$hasInvalidPromoCode = false;

		$objPromoCode = PromoCode::model()->findByPk($this->fk_promo_id);
		if ($objPromoCode === null)
		{
			// The promo code has been deleted from Web Store.
			$hasInvalidPromoCode = true;
		} else {
			$objPromoCode->validatePromocode('code', null);
			$arrErrors = $objPromoCode->getErrors();

			if (count($arrErrors) > 0)
			{
				// After validating the promo code, there were errors.
				$hasInvalidPromoCode = true;
			}
		}

		if ($hasInvalidPromoCode === false)
		{
			return null;
		}

		if ($objPromoCode === null)
		{
			$promoCodeCode = '';
			$reason = 'This Promo Code has been disabled.';
		} else {
			$promoCodeCode = $objPromoCode->code;
			$reason = _xls_convert_errors_display(_xls_convert_errors($arrErrors));
		}

		return array(
			'code' => $promoCodeCode,
			'reason' => $reason
		);
	}

	/**
	 * Given a specific shipping price, calculate the total of the cart and return
	 * the value. Because this is a precalculation, we don't actually save this
	 * information.
	 * @param double $fltShipping The price of the shipping.
	 * @return double The total price of the cart.
	 */
	public function getTotalWithShipping($fltShipping)
	{
		$taxDecimal = _xls_get_conf('TAX_DECIMAL', 2);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
		{
			return round($this->subtotal, $taxDecimal) +
				round($fltShipping, $taxDecimal);
		}

		return round($this->subtotal, $taxDecimal) +
			round($this->tax1, $taxDecimal) +
			round($this->tax2, $taxDecimal) +
			round($this->tax3, $taxDecimal) +
			round($this->tax4, $taxDecimal) +
			round($this->tax5, $taxDecimal) +
			round($fltShipping, $taxDecimal);
	}
}
