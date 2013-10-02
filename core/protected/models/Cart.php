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
	/**
	 * Returns the static model of the specified AR class.
	 * @return Cart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public $blnStorePickup = false;

	// String representation of the object
	public function __toString() {
		return sprintf('Cart Object %s',  $this->id);
	}

	/* Define some specialized query scopes to make searching for specific db info easier */
	public function scopes()
	{
		return array(
			'complete'=>array(
				'condition'=>'cart_type='.CartType::order,
				'order'=>'id_str DESC',
			),
		);
	}

//	public function __construct($config=null)
//	{
//		if(is_array($config))
//			foreach ($config as $key=>$value)
//					$this->$key = $value;
//
//	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cart_type', 'numerical', 'integerOnly'=>true, 'min'=>CartType::order,'max'=>CartType::order, 'on'=>'manual','tooSmall'=>'Status must be set to Paid','tooBig'=>'Status must be set to Paid'),

		)+parent::rules();
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAdmin()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id_str',$this->id_str,true,'OR');
		$criteria->compare('datetime_cre',$this->datetime_cre,true,'OR');
		$criteria->compare('downloaded',$this->downloaded,false,'AND');
		$criteria->compare('cart_type',$this->cart_type);

//		$criteria->with=array(
//			'customer.first_name',
//			'customer.last_name',
//		);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id_str DESC',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));


	}


	/** Create a new cart object and prepopulate some values
	 * @return Cart
	 */
	public static function InitializeCart() {

			$objCart = new Cart();
			$objCart->cart_type = CartType::cart;

			$objCart->datetime_cre = new CDbExpression('NOW()');;
			$objCart->datetime_due = new CDbExpression('now() + INTERVAL '._xls_get_conf('CART_LIFE', 7).' DAY');
			$objCart->ResetTaxIncFlag();
			if(!$objCart->save())
				Yii::log("Error initializing cart ".print_r($objCart->getErrors(),true),
					'error', 'application.'.__CLASS__.".".__FUNCTION__);

			return $objCart;

	}

	/**
	 * Initialize if needed and return the current Cart
	 * This is used by unit tests to get a cart to work with for testing
	 * Normal use will use the Cart component
	 * @return
	 */
	public static function GetCart() {

		if(is_null(Yii::app()->user->getState('cartid'))) {
			$objCart = Cart::InitializeCart();
			Yii::app()->user->setState('cartid',$objCart->id);
			//Cart::UpdateCartCustomer();
			return $objCart;
		} else {

			$objCart = Cart::model()->findByPk(Yii::app()->user->getState('cartid'));
			if (!$objCart) {
				//something has happened to the database object
				Yii::log("Could not find cart ".Yii::app()->user->getState('cartid').", creating new one.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->user->setState('cartid',null);
				$objCart = Cart::InitializeCart();
				Yii::app()->user->setState('cartid',$objCart->id);
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
	 * Update the customer in the cart
	 * Used to update if you log in with a cart in progress
	 * @return
	 */
	public function UpdateCartCustomer() {
		if(is_null($this->customer_id))
			return false;


		$objCustomer = $this->customer;

		if ($objCustomer instanceof Customer) {
			if(isset($objCustomer->defaultShipping))
			{
				$objDestination = Destination::LoadMatching(
					$objCustomer->defaultShipping->country,
					$objCustomer->defaultShipping->state,
					$objCustomer->defaultShipping->postal);

				if(!$objDestination)
					$objDestination = Destination::LoadDefault();

				if(!$objDestination)
					throw new CHttpException(500,'Web Store missing destination setup. Cannot continue.');

				$this->tax_code_id = $objDestination->taxcode;

				if(!isset($objDestination->taxcode0))
					throw new CHttpException(500,'Web Store error, destination has invalid tax code. Cannot continue.');

				if ($objDestination->taxcode0->IsNoTax() && Yii::app()->params['TAX_INCLUSIVE_PRICING'])
					Yii::app()->user->setFlash('warning',Yii::t('global','Note: Because of your default shipping address, prices will be displayed without tax.'));


			} else {
				$objTax = TaxCode::GetDefault();
				$this->tax_code_id = $objTax->lsid;
			}
		}

		if ($this->item_count > 0)
			$this->UpdateCart();
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
		foreach($arrItems as $objItem) {
			$objItem->product->SetAvailableInventory();

			$objEvent = new CEventProduct(get_class($this),'onUpdateInventory',$objItem->product);
			_xls_raise_events('CEventProduct',$objEvent);

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
		$objConf->key_value = intval(preg_replace("/[^0-9]/", "", $this->id_str))+1;
		$objConf->save();
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
			if($objItem->wishlist_item>0)
				WishlistItem::model()->updateByPk($objItem->wishlist_item,array('cart_item_id'=>null));
			$objItem->delete();
			return true;
		}

		if ($intQuantity == $objItem->qty)
			return;

		if (_xls_get_conf('PRICE_REQUIRE_LOGIN',0) == 1 && Yii::app()->user->isGuest) {
			return Yii::t('cart','You must log in before Adding to Cart.');
		}

		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0) < Product::InventoryAllowBackorders &&
			$intQuantity > $objItem->qty &&
			$objItem->product->inventoried &&
			$objItem->product->inventory_avail < $intQuantity) {
				return Yii::t('cart','Your chosen quantity is not available for ordering. Please come back and order later.');
		}

		$objItem->qty = $intQuantity;
		$objItem->save();
		$this->UpdateCart();
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
	 * Update Cart by removing discounts if the Cart is expired
	 */
	public function ResetDiscounts() {
		foreach ($this->cartItems as $obj) {
			$objItem = CartItem::model()->findByPk($obj->id);
			if ($objItem->Discounted) {
				$objItem->discount = 0;
				$objItem->sell_discount = 0;
				$objItem->sell_total = $objItem->sell_base*$objItem->qty;
				$objItem->save();
			}
		}
	}
	/**
	 * Update Cart by removing Products which no longer exist or are unavailable
	 */
	public function UpdateMissingProducts() {

		$blnResult=false;
		foreach ($this->cartItems as $objItem) {

			if (!$objItem->product || $objItem->product->web != 1) {
				Yii::app()->user->setFlash('warning',
					Yii::t('cart','The product {product} is no longer available on this site and has been removed from your cart.',
						array('{product}'=>"<strong>".$objItem->description."</strong>")));
				$objItem->delete();
				$blnResult = true;
			}

			//This is a cart that has not originated as a document i.e quote
			if(is_null($this->document_id))
			{

				if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0) != Product::InventoryAllowBackorders) { //IOW, unless we allow backordering
					if ($objItem->product->inventoried) {
						if ($objItem->product->Inventory==0) {
							Yii::app()->user->setFlash('warning',
								Yii::t('cart','The product {product} is now out of stock and has been removed from your cart.',
									array('{product}'=>"<strong>".$objItem->description."</strong>")));
							$objItem->delete();
							$blnResult = true;

						}
						elseif ($objItem->qty > $objItem->product->Inventory) {
							Yii::app()->user->setFlash('warning',
								Yii::t('cart','The product {product} now has less stock available than the amount you requested. Your cart quantity has been reduced to match what is available.',
									array('{product}'=>"<strong>".$objItem->description."</strong>")));
							$objItem->qty=$objItem->product->Inventory;
							$objItem->save();
							$blnResult = true;
						}
					}
				}
			}


		}
		if ($blnResult) $this->UpdateCart();
		return $blnResult;
	}

	/**
	 * Update Cart by applying a Promo Code
	 * dryRun is deprecated, we shouldn't run this as a validation test
	 */
	public function UpdatePromoCode($dryRun = false) {
		if (!$this->fk_promo_id)
			return;

		$objPromoCode = PromoCode::model()->findByPk($this->fk_promo_id);

		if (!($objPromoCode instanceof PromoCode))
			return;

		if (!$objPromoCode->enabled)
			return;


		// Sort array by High Price to Low Price, reset discount to 0 to evaluate from the beginning
		$arrSorted = array();
		$intOriginalSubTotal=0;
		foreach ($this->cartItems as $objItem) {
			if (!$dryRun)
				$objItem->discount = 0;
			$arrSorted[] = $objItem;
			$intOriginalSubTotal += $objItem->qty*$objItem->sell;
		}

		if (is_null($objPromoCode->threshold)) $objPromoCode->threshold=0; //for calculation purposes

		if ($objPromoCode->threshold > $intOriginalSubTotal && $this->fk_promo_id != NULL) {
			$this->fk_promo_id = NULL;
			Yii::app()->user->setFlash('error',
				Yii::t('cart','Promo Code {promocode} no longer applies to your cart and has been removed.',
					array('{promocode}'=>"<strong>".$objPromoCode->code."</strong>")));
			$this->ResetDiscounts();
			return;
		}


		if ($objPromoCode->type == PromoCodeType::Flat)
			$intDiscount = $objPromoCode->amount;
		else if ($objPromoCode->type == PromoCodeType::Percent)
			$intDiscount = $objPromoCode->amount/100;
		else {
			Yii::log('Invalid PromoCode type ' . $objPromoCode->type, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return;
		}

		$bolApplied = false;

		usort($arrSorted, array(get_class($this), 'CompareByPrice'));


		foreach ($arrSorted as $objItem) {
			if (!$objPromoCode->IsProductAffected($objItem))
				continue;

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

			if (!$dryRun) {
				$objItem->discount = $intItemDiscount;
				$objItem->save();
			}

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
		$this->tax1 = 0;
		$this->tax2 = 0;
		$this->tax3 = 0;
		$this->tax4 = 0;
		$this->tax5 = 0;

		// Get the rowid for "No Tax"
		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->lsid;

		// Dont want taxes, so return
		if (is_null($this->tax_code_id) || $this->tax_code_id == $intNoTax)
			return;

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
	 * Update Cart by setting taxes when in Tax Inclusive
	 */
	public function UpdateTaxInclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') != '1')
			return;

		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		// Reset taxes
		$this->tax1 = 0;
		$this->tax2 = 0;
		$this->tax3 = 0;
		$this->tax4 = 0;
		$this->tax5 = 0;

		// Tax Inclusive && Don't want taxes
		if ($this->taxCode->IsNoTax()) {

			foreach ($this->cartItems as $obj) {
				$objItem = CartItem::model()->findByPk($obj->id);

				// For quote to cart, we have to remove prices manually
				if ($objItem->cart_type == CartType::quote && $objItem->tax_in) {
					$taxes = $objItem->product->CalculateTax(
						TaxCode::GetDefault(), $objItem->sell);

					// Taxes are deducted from cart for LightSpeed
					$this->tax1 -= $taxes[1];
					$this->tax2 -= $taxes[2];
					$this->tax3 -= $taxes[3];
					$this->tax4 -= $taxes[4];
					$this->tax5 -= $taxes[5];

					$objItem->sell -= round(array_sum($taxes), $TAX_DECIMAL);
					$objItem->sell_base = $objItem->sell;
					$objItem->sell_total =  $objItem->sell * $objItem->qty;
					$objItem->tax_in=0;
					$objItem->save();
				}
				elseif ($objItem->cart_type == CartType::cart && $objItem->tax_in)
				{
					// Set Tax Exclusive price
					$objItem->sell = $objItem->sell_base = $objItem->product->PriceValue;
					$objItem->sell_total = $objItem->sell_base*$objItem->qty;
					$objItem->tax_in = 0;
					$objItem->save();
				}
			}
		}
		else
		{
			//Tax Inclusive && Want taxes, so return and set prices back to inclusive if needed
			foreach ($this->cartItems as $obj) {
				$objItem = CartItem::model()->findByPk($obj->id);

				// Set back tax inclusive prices
				if ($objItem->tax_in ==0)
				{
					$objItem->sell = $objItem->sell_base = $objItem->product->PriceValue;
					$objItem->sell_total = $objItem->sell_base*$objItem->qty; //$objItem->product->getPriceValue($objItem->qty,true);
					$objItem->tax_in=1;
					$objItem->save();
				}
			}

		}

		$this->refresh();
	}

	/**
	 * Update Cart by setting taxes for Shipping if applicable
	 */
	public function UpdateTaxShipping() {
		if(Yii::app()->params['SHIPPING_TAXABLE'] != '1')
			return;

		if (!isset($this->shipping->shipping_sell))
			return;

		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->lsid;

		if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '0')
			if ($this->tax_code_id == $intNoTax)
				return;

		$objShipProduct = Product::LoadByCode('SHIPPING');

		$intTaxStatus = 0;
		if ($objShipProduct)
			$intTaxStatus = $objShipProduct->taxStatus->lsid;

		//
		// Check if the tax status is set to no tax for it, if so, make it
		// default, otherwise leave it alone.
		//
		if (Yii::app()->getComponent('storepickup')->IsStorePickup && $intTaxStatus) {
			$objTaxStatus = $objShipProduct->taxStatus;

			if ($objTaxStatus && $objTaxStatus->IsNoTax())
				$intTaxStatus = 0;
		}

		$nprice_taxes = Tax::CalculatePricesWithTax($this->shipping->shipping_sell, $this->tax_code_id, $intTaxStatus);

		$taxes = $nprice_taxes[1];

		$this->tax1 += $taxes[1];
		$this->tax2 += $taxes[2];
		$this->tax3 += $taxes[3];
		$this->tax4 += $taxes[4];
		$this->tax5 += $taxes[5];

		//
		// Legacy behavior assumes that the ShippingSell price does
		// not already contain taxes and that they must be added.
		//
		if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '1' && $this->tax_code_id != $intNoTax) {
			$this->shipping->shipping_sell += array_sum($taxes);
		}


	}

	/**
	 * Update Cart by counting products and setting the Subtotal
	 */
	public function UpdateCountAndSubtotal() {

		$this->item_count = 0;
		$this->subtotal = 0;

		foreach ($this->cartItems as $objItem) {
			$this->item_count += 1; //How many rows in cart_items
			$this->subtotal += $objItem->sell_total;
		}

	}

	/**
	 * Update Cart by setting the cart Total
	 */
	public function UpdateTotal() {
		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			$this->total = round($this->subtotal, $TAX_DECIMAL) +
				round($this->shipping_sell, $TAX_DECIMAL);
		else
			$this->total = round($this->subtotal, $TAX_DECIMAL) +
				round($this->tax1, $TAX_DECIMAL) +
				round($this->tax2, $TAX_DECIMAL) +
				round($this->tax3, $TAX_DECIMAL) +
				round($this->tax4, $TAX_DECIMAL) +
				round($this->tax5, $TAX_DECIMAL) +
				round($this->shipping_sell, $TAX_DECIMAL);
	}

	/**
	 * Iterate through the Cart Items and Save those that are Updated
	 */
	public function SaveUpdatedCartItems() {
		foreach ($this->cartItems as $objItem)
			$objItem->save();
	}

	/**
	 * Perform all Cart Update mechanisms
	 * This is used to ensure that the Cart data remains consistent after
	 * additions and modifications of Products, updates to the Customer
	 * record and Tax Code.
	 */
	public function UpdateCart()
	{ 
		$this->save();
		$this->refresh();

		$this->UpdatePromoCode();
		$this->UpdateCountAndSubtotal();


		$this->UpdateTaxInclusive();
		$this->UpdateTaxExclusive();
		$this->UpdateTaxShipping();

		$this->UpdateCountAndSubtotal();
		$this->UpdateTotal();

		$this->save();
		$this->refresh();
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
		$objItem->sell_total = $objItem->sell_base*$objItem->qty;
		if (!$objItem->save())
			print_r($objItem->getErrors());

		if (!$retVal = $this->UpdateItemQuantity($objItem, $intQuantity + $objItem->qty))
			return $retVal;

		$objItem->cart_id = $this->id;

		$this->UpdateCart();

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
	 * Return link for current cart
	 * @return string
	 */
	public function GenerateLink() {
		if (empty($this->linkid)) {
			$this->linkid = _xls_seo_url(_xls_truncate(_xls_encrypt(md5(date("YmdHis"))),31,''));
			$this->save();
			return $this->linkid;
		}
		else
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
	 * Load an array of Cart objects,
	 * by CustomerId Index(es)
	 * @param integer $intCustomerId
	 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
	 * @return Cart[]
	 */
	public static function LoadLastCartInProgress($intCustomerId, $intBelowCart = 0) {
		// Call Cart::QueryArray to perform the LoadArrayByCustomerId query
		try {

			$arrCarts=Cart::model()->findAll(array(
				'condition'=>'customer_id=:id AND cart_type=:type AND item_count > 0 '. ($intBelowCart>0 ? 'AND id<:currentcart' : 'AND id>:currentcart'),
				'params'=>array(':id'=>$intCustomerId, ':type'=>CartType::cart, ':currentcart'=>$intBelowCart),
				'order'=>'id DESC',
				'limit'=>1,
			));

			if(count($arrCarts)>0) {

				return $arrCarts[0];
			}
			else return null;
		} catch (Exception $objExc) {
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

	/**
	 * Called by session management to periodically sweep away old carts, based on config cart life
	 */
	public static function GarbageCollect() {
		//Delete any carts older than our timeout that don't have a customer ID attached (since those can always be restored)
		$objCarts = Cart::model()->findAll("cart_type = :type AND customer_id IS NULL AND modified<:date",
			array(':type'=>CartType::cart,
				':date'=>date("Y-m-d H:i:s",strtotime("-"._xls_get_conf('CART_LIFE',30)."days"))
			));
		foreach ($objCarts as $objCart)
		{
			foreach ($objCart->cartItems as $item)
				$item->delete();
			$objCart->delete();
		}
	}


	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->datetime_cre = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		if (empty($this->tax_inclusive))
				$this->tax_inclusive = _xls_get_conf('TAX_INCLUSIVE_PRICING',0);

		if (empty($this->cart_type))
				$this->cart_type = CartType::cart;

		if (is_null($this->tax_code_id))
		{
			$objTax = TaxCode::GetNoTaxCode();
			if ($objTax instanceof TaxCode)
				$this->tax_code_id = $objTax->lsid;
		}

		if (empty($this->linkid)) {
			$this->linkid = $this->GenerateLink();
		}

		return parent::beforeValidate();
	}

	protected function afterValidate() {
		$arrErrors = $this->GetErrors();

		return parent::afterValidate();
	}

	protected function afterSave() {


		Yii::app()->user->setState('cartid',$this->id);

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
					return $this->shipping->shipping_sell;
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
}