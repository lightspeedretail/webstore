<?php

/**
 * The Cart component in this version replaces what was the "Cart Object" in prior versions of Web Store.
 * The advantage here is we can treat the Cart as completely independent of the model and customizations addressing
 * the component should not break as we continue to refine the model.
 *
 * Basically, you should never have Cart:: anything directly in any custom code, it should always
 * just go through Yii::app()->shoppingcart instead
 *
 * @example
 * //Get current cart object
 * $objCurrentCart = Yii::app()->shoppingcart
 * //print cart item rows (field in table)
 * echo $objCurrentCart->item_count;
 * //loop through items
 * foreach ($objCurrentCart->cartItems as $item)
 *  echo $item->description; //This is now passing through to attached xlsws_cart_items table
 * if ($objCurrentCart->payment)
 *  echo $objCurrentCart->payment->payment_method //If exists, pass through to attached xlsws_cart_payment table
 */
class ShoppingCart extends CApplicationComponent
{
	/**
	 * @var null
	 */
	private $_model = null;

	/**
	 * @param $id
	 */
	public function setModelById($id)
	{
		$this->_model = Cart::model()->findByPk($id);
	}

	/**
	 * @param Cart $model A cart model to load.
	 */
	public function setModel(Cart $model)
	{
		$this->_model = $model;
	}

	/**
	 * @return CActiveRecord|Cart|null
	 */
	public function getModel()
	{

		if (!$this->_model)
		{
			$intCartId = Yii::app()->user->getState('cartid');

			if(empty($intCartId))
			{
				$objCustomer = Customer::GetCurrent();
				$intCustomerid = null;

				if ($objCustomer instanceof Customer)
				{
					$intCustomerid = $objCustomer->id;
				}

				$objCart = null;
				if (!is_null($intCustomerid))
				{
					$objCart = Cart::LoadLastCartInProgress($intCustomerid);
				}

				if (is_null($objCart))
				{
					$objCart = new Cart();
				}

				//Logged in customers get a "real" cart
				if(is_null($objCart) && !is_null($intCustomerid))
				{
					$objCart = Cart::InitializeCart();
					Yii::app()->user->setState('cartid', $objCart->id);
				}
			}
			else
			{
				$objCart = Cart::model()->findByPk($intCartId);
				if (!$objCart || ($objCart->cart_type != CartType::cart && $objCart->cart_type != CartType::awaitpayment))
				{
					//something has happened to the database object
					Yii::log("Could not find cart ".$intCartId.", creating new one.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					$objCart = Cart::InitializeCart();
					Yii::app()->user->setState('cartid', $objCart->id);
				}
			}

			$this->_model = $objCart;
		}

		return $this->_model;
	}


	/**
	 * Since calling our model doesn't necessarily really create a db record (to avoid blank carts all over the place),
	 * this function purposely creates a record when we need one (i.e. when adding a product to the cart)
	 */
	protected function createCart()
	{
		$intCartId = Yii::app()->user->getState('cartid');
		Yii::log("Creating cart, existing id is ".$intCartId, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		if(empty($intCartId) && !Yii::app()->isCommonSSL) {

			$objCustomer = Customer::GetCurrent();
			$intCustomerid = null;

			if ($objCustomer instanceof Customer)
				$intCustomerid = $objCustomer->id;

			$objCart = null;
			if (!is_null($intCustomerid))
				$objCart = Cart::LoadLastCartInProgress($intCustomerid);
			if (is_null($objCart))
				$objCart = Cart::InitializeCart();

			$this->_model = $objCart;
			Yii::app()->user->setState('cartid',$objCart->id);
		}
	}

	public function getIsActive()
	{
		if (!$this->_model)
			return false;
		else return true;

	}


	/**
	 * If the user had a cart in progress, merge the items into the current cart
	 * @param null $objCartToMerge
	 * @return bool
	 */
	public function loginMerge($objCartToMerge = null)
	{

		if(!is_null($objCartToMerge)) $objCartInProgress = $objCartToMerge;
		else
			$objCartInProgress = Cart::LoadLastCartInProgress(Yii::app()->user->id,$this->id);

		if ($objCartInProgress) {
			Yii::log("Found prior cart ".$objCartInProgress->id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			if(count($this->cartItems)==0)
			{
				$this->_model=$objCartInProgress;
				$arrPastItems=array();
			} else
				$arrPastItems = $objCartInProgress->cartItems;

			//Merge in any new items we had in our cart from this session
			if (count($arrPastItems)>0)
			{
				foreach($arrPastItems as $objItem) {
					$objProduct = Product::model()->findbyPk($objItem->product_id);
					//we strip any discount from another cart usu. promo code
					$retVal = $this->model->AddProduct($objProduct, $objItem->qty, $objItem->cart_type, $objItem->wishlist_item, $objItem->description,$objItem->sell,0);
					if($objItem->wishlist_item>0)
						WishlistItem::model()->updateByPk($objItem->wishlist_item,array('cart_item_id'=>$retVal));

					if(is_null($objCartToMerge)) $objItem->delete();
				}
				//If we aren't being passed a cart from a share (that we don't want to delete), then remove the old cart in progress
				if(is_null($objCartToMerge)) {
					Yii::app()->user->setFlash('success',Yii::t('cart','Your prior cart has been restored.'));
					$objCartInProgress->delete();
				}
				$this->model->UpdateMissingProducts();
			}

		}

		$this->model->customer_id=Yii::app()->user->id;
		$this->model->datetime_cre = new CDbExpression('NOW()'); //Reset time to current time
		if(!$this->model->save())
			Yii::log("Error saving cart ".print_r($this->model->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		$this->UpdateCart();
		$this->_model=Cart::model()->findByPk($this->id);
		Yii::app()->user->setState('cartid',$this->id);
		return true;
	}

	/**
	 * Merge a document (i.e. quote) into the current cart.
	 * @param null $objDocument
	 * @return bool
	 */
	public function loginQuote($objDocument= null)
	{

		if(is_null($objDocument)) return;

		$cartid=Yii::app()->user->getState('cartid');
		if (empty($cartid))
			$this->createCart();

		$arrPastItems = $objDocument->documentItems;

		//Merge in items from our quote to the cart
		if (count($arrPastItems)>0) {
			foreach($arrPastItems as $objItem) {
				$objProduct = Product::model()->findbyPk($objItem->product_id);
				$retVal = $this->model->AddProduct($objProduct, $objItem->qty, CartType::quote,
					$objItem->gift_registry_item, $objItem->description,$objItem->sell,$objItem->discount);
				if (strlen($retVal)>5)
					return $retVal;
			}

		}
		$this->model->document_id=$objDocument->id;

		$this->model->datetime_cre = new CDbExpression('NOW()'); //Reset time to current time
		if(!$this->model->save())
			Yii::log("Error saving cart ".print_r($this->model->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		$this->UpdateCart();
		$this->_model=Cart::model()->findByPk($this->id);

		$this->assignCustomer(Yii::app()->user->id);
		return true;
	}


	/**
	 * Compare cart prices with the original product prices and update if necessary
	 */
	public function verifyPrices()
	{

		if ($this->model->cart_type  != CartType::cart)
			return;

		$strFlash = '';
		$arrItems = array();

		foreach ($this->cartItems as $item)
		{
			//Make sure our object is current and not cached through our relations
			$objProduct = Product::model()->findByPk($item->product_id);

			if ($item->sell_base != $objProduct->PriceValue) {
			$strFlash .= Yii::t('cart','The item {item} in your cart has {updown} in price to {price}.',
				array('{item}'=>$item->description,
				  '{updown}'=>($item->sell_base > $objProduct->PriceValue ? Yii::t('cart','decreased') : Yii::t('cart','increased')),
				  '{price}'=>$objProduct->getPrice(1,$this->IsTaxIn))) . "<br>";

				$arrItem['product'] = $objProduct;
				$arrItem['qty'] = $item->qty;
				$arrItem['wishlist_item'] = $item->wishlist_item;
				$arrItems[] = $arrItem;
				$item->delete();



			}
		}

		if (!empty($strFlash))
			Yii::app()->user->setFlash('info',$strFlash);

		$this->Recalculate();
		foreach($arrItems as $arrItem)
			$this->addProduct($arrItem['product'],$arrItem['qty'],$arrItem['wishlist_item']);
		$this->Recalculate();

	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->model->id;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->model->name;
	}


	public function getCartQty()
	{

		$count=0;
		foreach ($this->cartItems as $item)
			$count += $item->qty;
		return $count;
	}

	public function setTaxCodeId($id)
	{
		$this->model->tax_code_id = $id;
		$this->model->save();
		$this->model->refresh();
		$this->Recalculate();
	}

	/**
	 * Forcibly assign a shopping cart to a specific cartid
	 * @param $id
	 */
	public function assign($id)
	{
		Yii::app()->user->setState('cartid',$id);
		$this->setModelById($id);
	}
	/**
	 * Add a product to the cart. Defaults to qty 1 unless specified
	 * @param $mixProduct
	 * @param int $intQuantity
	 * @param bool $mixCartType
	 * @param int $intGiftItemId
	 * @return bool
	 */
	public function addProduct($mixProduct,$intQuantity = 1,$intGiftItemId = null, $auto=null)
	{
		$cartid = Yii::app()->user->getState('cartid');
		if (empty($cartid))
		{
			$this->createCart();
		}

		if ($mixProduct instanceof Product)
		{
			$objProduct = $mixProduct;
		}
		else
		{
			$objProduct = Product::model()->findByPk($mixProduct);
		}

		if(!(_xls_get_conf('QTY_FRACTION_PURCHASE')))
		{
			$intQuantity = intval($intQuantity);
		}

		/*
		 *  public function AddProduct($objProduct,
			$intQuantity = 1,
			$mixCartType = 0,
			$intGiftItemId = null,
			$strDescription = false,
			$fltSell = false,
			$fltDiscount = false)
		 */
		if ($objProduct instanceof Product)
		{
			$this->clearCachedShipping();

			//Actually add product to cart
			$retVal =  $this->model->AddProduct($objProduct,$intQuantity,CartType::cart,$intGiftItemId);

			if (is_numeric($retVal) && is_null($auto)) //prevent circular logic adding
				foreach($objProduct->productRelateds as $objProductAdditional) {
					if(isset($objProductAdditional->related) &&
						$objProductAdditional->related->IsAddable &&
						$objProductAdditional->autoadd==1 &&
						$objProductAdditional->related->master_model==0) {
						$this->addProduct($objProductAdditional->related,$objProductAdditional->qty,null,true);
					}
			}
			return $retVal;

		}
		else return false;

	}

	/**
	 * Shortcut to return an array of cart items
	 * @return mixed
	 */
	public function cartItems()
	{
		return $this->model->cartItems;

	}

	public function getDataProvider()
	{
		return $this->model->dataProvider;
	}

	/**
	 * Shortcut to save cart
	 * @return mixed
	 */
	public function save()
	{
		$retVal = $this->model->save();
		if (!$retVal)
			Yii::log("Error saving cart ".print_r($this->model->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		return $retVal;
	}

	public function getErrors()
	{
		return $this->model->getErrors();
	}
	/**
	 * Create a Link to view the cart after checkout. Used for receipt viewing.
	 * @return mixed
	 */
	public function GenerateLink()
	{
		return $this->model->GenerateLink();
	}

	/**
	 * Remove all items from the cart
	 * @return bool
	 */
	public function clearCart()
	{

		if (!$this->model->cart_type == CartType::cart)
			return false;

		unset(Yii::app()->session['checkout.cache']);
		unset(Yii::app()->session[MultiCheckoutForm::$sessionKey]);
		$this->clearCachedShipping();

		foreach ($this->cartItems as $item) {
			if (!is_null($item->wishlist_item))
				WishlistItem::model()->updateByPk($item->wishlist_item,array('cart_item_id'=>null));
			$item->delete();
		}
		$this->model->fk_promo_id=null;
		$this->model->save();
		$this->model->refresh();
		$this->Recalculate();
		$this->model->refresh();

		return true;
	}

	public function UpdateMissingProducts()
	{
		if (Yii::app()->user->getState('cartid') > 0)
		{
			$this->model->UpdateMissingProducts();
		}
	}

	/**
	 * Removes the promo code and discounts that are applied to a shopping cart
	 */
	public function RemovePromoCode() {
		$this->model->fk_promo_id = NULL;
		$this->model->ResetDiscounts();
		$this->model->save();
		$this->model->refresh();
		$this->Recalculate();
		$this->model->refresh();
	}

	/**
	 * Make sure our promo code still applies
	 */
	public function RevalidatePromoCode()
	{
		if ($this->model->fk_promo_id)
		{
			$objPromo = PromoCode::model()->findByPk($this->model->fk_promo_id);
			$objPromo->validatePromocode('code',null);
			$arrErrors = $objPromo->getErrors();
			$errCount = count($arrErrors);
			if ($errCount>0)
			{
				Yii::app()->user->setFlash('error',
					Yii::t('cart','Promo Code {promocode} removed. {reason}',
						array('{promocode}'=>"<strong>".$objPromo->code."</strong>",
						'{reason}'=>_xls_convert_errors_display(_xls_convert_errors($arrErrors)))));
				$this->model->fk_promo_id = NULL;
				$this->model->ResetDiscounts();
			}
		}
	}


	/**
	 * Disassociate the cart with the current session. Should be done after payment
	 * is complete but before redirecting to receipt.
	 */
	public function releaseCart()
	{
		Yii::app()->user->setState('cartid', null);
		$this->_model = null;
	}

	public function UpdateItemQuantity($objItem,$qty)
	{
		$this->clearCachedShipping();
		return $this->model->UpdateItemQuantity($objItem, _xls_number_only($qty));

	}

	public function applyPromoCode($mixCode)
	{
		if ($mixCode instanceof PromoCode)
		{
			$objPromoCode = $mixCode;
		}
		else
		{
			$objPromoCode = PromoCode::LoadByCode($mixCode);
		}

		if ($objPromoCode instanceof PromoCode) {
			$this->model->fk_promo_id = $objPromoCode->id;
			$this->Recalculate();
		}
	}

	/**
	 * Given a specific shipping price, calculate the total of the cart and return
	 * the value. Because this is a precalculation, we don't actually save this
	 * information.
	 * @param $fltShipping
	 * @return float
	 */
	public function precalculateTotal($fltShipping)
	{
		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			return round($this->model->subtotal, $TAX_DECIMAL) +
				round($fltShipping, $TAX_DECIMAL);
		else
			return round($this->model->subtotal, $TAX_DECIMAL) +
				round($this->model->tax1, $TAX_DECIMAL) +
				round($this->model->tax2, $TAX_DECIMAL) +
				round($this->model->tax3, $TAX_DECIMAL) +
				round($this->model->tax4, $TAX_DECIMAL) +
				round($this->model->tax5, $TAX_DECIMAL) +
				round($fltShipping, $TAX_DECIMAL);
	}

	//These are pass-through functions which go currently to our cart object


	/* @return boolean whether the shopping cart uses tax inclusive pricing.
	 */
	public function getIsTaxIn()
	{
		if($this->model->id == 0)
		{
			return (bool)Yii::app()->params['TAX_INCLUSIVE_PRICING'];
		}

		// If the cart taxCode is not set, use the default.
		if(isset($this->model->taxCode) === false)
		{
			$objDestination = Destination::LoadDefault();

			// No default tax destination, default to not tax inclusive.
			if(is_null($objDestination))
			{
				return false;
			}

			$this->setTaxCodeId($objDestination->taxcode);
		}

		// taxCode is set now, check the taxCode setting.
		if($this->model->taxCode->IsNoTax())
		{
			return false;
		}

		return (bool)Yii::app()->params['TAX_INCLUSIVE_PRICING'];
	}

	public function getPromoCode()
	{
		return $this->model->PromoCode;
	}

	public function completeUpdatePromoCode()
	{
		$this->model->completeUpdatePromoCode();
	}

	public function SetIdStr()
	{
		$this->model->SetIdStr();
	}

	public function getIdStr()
	{
		return $this->model->id_str;
	}

	/**
	 * Assign a customer to a cart, which triggers a recalculate. Can pass a Customer #ID or a Customer object
	 * @param $mixCustomer
	 * @return bool
	 */
	public function assignCustomer($mixCustomer)
	{
		if(is_numeric($mixCustomer))
			$objCustomer = Customer::model()->findByPk($mixCustomer);
		else $objCustomer = $mixCustomer;

		if ($objCustomer instanceof Customer)
		{
			Yii::log("Assigning customer id #".$objCustomer->id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$this->model->customer_id = $objCustomer->id;
			$this->model->save();
			$this->model->UpdateCartCustomer();
			return true;

		} else return false;
	}

	public function UpdateCartCustomer()
	{
		$this->model->UpdateCartCustomer();
	}

	public function UpdateCart()
	{
		$this->model->UpdateCart();
	}

	public function RecalculateInventoryOnCartItems()
	{
		$this->model->RecalculateInventoryOnCartItems();
	}


	public function Recalculate()
	{
		$this->UpdateCart();
	}

	public function UpdateWishList()
	{
		$this->model->UpdateWishList();
	}

	public function addTaxes($arr)
	{
		$this->model->addTaxes($arr);
	}

	public function subtractTaxes($arr)
	{
		$this->model->subtractTaxes($arr);
	}

	public function clearCachedShipping()
	{
		unset(Yii::app()->session['ship.providerRadio.cache']);
		unset(Yii::app()->session['ship.htmlCartItems.cache']);
		unset(Yii::app()->session['ship.prices.cache']);
		unset(Yii::app()->session['ship.priorityRadio.cache']);
		unset(Yii::app()->session['ship.priorityLabels.cache']);
		unset(Yii::app()->session['ship.providerLabels.cache']);
		unset(Yii::app()->session['ship.taxes.cache']);
		unset(Yii::app()->session['ship.formattedCartTotals.cache']);
		unset(Yii::app()->session[Shipping::$cartScenariosSessionKey]);
	}

	/**
	 * @param string $strName
	 * @return int|mixed
	 */
	public function __get($strName) {

		switch ($strName) {

			case 'attributes':
				return $this->model->attributes;

			case 'CartItems':
			case 'cartItems':
				return $this->model->cartItems;

			case 'Link':
				return $this->model->Link;

			case 'ItemCount':
			case 'itemCount':
				return count($this->model->cartItems);

			case 'TotalItemCount':
			case 'totalItemCount':
				return $this->model->TotalItemCount;

			case 'tax1name':
			case 'tax1Name':
				return $this->model->tax1Name;

			case 'formattedCartTax1':
				return _xls_currency($this->model->tax1);

			case 'tax2name':
			case 'tax2Name':
				return $this->model->tax2Name;

			case 'formattedCartTax2':
				return _xls_currency($this->model->tax2);

			case 'tax3name':
			case 'tax3Name':
				return $this->model->tax3Name;

			case 'formattedCartTax3':
				return _xls_currency($this->model->tax3);

			case 'tax4name':
			case 'tax4Name':
				return $this->model->tax4Name;

			case 'formattedCartTax4':
				return _xls_currency($this->model->tax4);

			case 'tax5name':
			case 'tax5Name':
				return $this->model->tax5Name;

			case 'formattedCartTax5':
				return _xls_currency($this->model->tax5);

			case 'tax_total':
			case 'TaxTotal':
				return $this->model->TaxTotal;

			case 'taxTotalFormatted':
				return _xls_currency($this->model->TaxTotal);
			case 'subtotal':
				if (empty($this->model->subtotal)) {
					return 0;
				}

				return $this->model->subtotal;

			case 'subtotalFormatted':
				if (empty($this->model->subtotal))
					return '';
				else return _xls_currency($this->model->subtotal);

			case 'Taxes':
				return $this->model->Taxes;

			case 'Total':
				return $this->total;

			case 'TotalFormatted':
			case 'totalFormatted':
				return _xls_currency($this->total);

			case 'TotalDiscount':
			case 'totalDiscount':
				return $this->model->totalDiscount;

			case 'TotalDiscountFormatted':
			case 'totalDiscountFormatted':
				return _xls_currency($this->model->totalDiscount);


			case 'Length':
				return $this->model->Length;

			case 'Height':
				return $this->model->Height;

			case 'Width':
				return $this->model->Width;

			case 'Weight':
				return $this->model->Weight;

			case 'Pending':
				return $this->model->Pending;

			case 'HasShippableGift':
				return $this->model->HasShippableGift;

			case 'GiftAddress':
				return $this->model->GiftAddress;

			case 'shipping_sell':
				if ($this->model->shipping)
					return $this->model->shipping->shipping_sell;
				else return 0;

			case 'shippingCharge':
				return $this->model->shippingCharge;

			case 'customer':
				if ($this->model->customer)
					return $this->model->customer;
				else return null;

			case 'payment':
				if ($this->model->payment)
					return $this->model->payment;
				else return null;

			case 'shipping':
				if ($this->model->shipping)
					return $this->model->shipping;
				else return null;

			case 'billaddress':
				if ($this->model->billaddress)
					return $this->model->billaddress;
				else return null;

			case 'shipaddress':
				if ($this->model->shipaddress)
					return $this->model->shipaddress;
				else return null;

			case 'originalSubTotal':
				return self::calculateOriginalSubtotal();

			default:
				//As a clever trick to get to our model through the component,
				if ($strName != "model" && $this->model->hasAttribute($strName))
					return $this->model->$strName;
				else
					return parent::__get($strName);


		}

	}

	private function calculateOriginalSubtotal()
	{
		$originalSubTotal = 0;
		if ($this->model->cartItems)
		{
			foreach($this->cartItems as $objItem)
				$originalSubTotal += $objItem->sell_base * $objItem->qty;
		}

		return $originalSubTotal;
	}

	/**
	 * @param string $strName
	 * @param mixed $mixValue
	 * @return mixed
	 */
	public function __set($strName, $mixValue) {
		switch ($strName) {

			case 'cartItems':
				return $this->model->cartItems;



			default:
				//As a clever trick to get to our model through the component,
				if ($strName != "model" && $this->model->hasAttribute($strName))
					$this->model->__set($strName,$mixValue);
				else
					return parent::__set($strName,$mixValue);

		}

	}

	/* Largely copied form SoapController. */
	/* @return String The shopping cart as a JSON-encoded string.
	 */
	public function asJSON() {
		$attributeNames = array(
			'id',
			'billaddress',
			'billaddress.country',
			'billaddress.state',
			'cartItems',
			'cart_type',
			'currency',
			'customer',
			'id_str',
			'payment',
			'printed_notes',
			'shipaddress',
			'shipaddress.country',
			'shipaddress.state',
			'shipping',
			'status',
			'subtotal',
			'subtotalFormatted',
			'tax1',
			'formattedCartTax1',
			'tax2',
			'formattedCartTax2',
			'tax3',
			'formattedCartTax3',
			'tax4',
			'formattedCartTax4',
			'tax5',
			'formattedCartTax5',
			'taxCode',
			'taxTotalFormatted',
			'tax_inclusive',
			'total',
			'totalDiscount',
			'totalFormatted',
			'totalDiscountFormatted',
			'totalItemCount',
			'promoCode'
		);
		$response = array(); //you will be copying in model attribute values to this array

		foreach ($attributeNames as $name)
		{
			$name = trim($name); //in case of spaces around commas
			$response[$name] = CHtml::value($this->model, $name); //this function walks the relations
		}

		$response['taxTotalFormatted'] = $this->taxTotalFormatted;
		$response['subtotalFormatted'] = $this->subtotalFormatted;
		$response['totalFormatted'] = $this->totalFormatted;
		$response['totalDiscountFormatted'] = $this->totalDiscountFormatted;

		// Taxes
		$response['formattedCartTax1'] = $this->formattedCartTax1;
		$response['formattedCartTax2'] = $this->formattedCartTax2;
		$response['formattedCartTax3'] = $this->formattedCartTax3;
		$response['formattedCartTax4'] = $this->formattedCartTax4;
		$response['formattedCartTax5'] = $this->formattedCartTax5;

		$arrItems = $response['cartItems'];

		foreach ($arrItems as $itemKey => $objItem)
		{
			list($taxCharged, $taxesIndividual, $taxRates) = Tax::CalculatePricesWithTax(
				$objItem->sell_total,
				$this->model->tax_code_id,
				$objItem->product->tax_status_id
			);

			$arrTaxRatesForItem = array();

			foreach ($taxRates as $key => $value)
			{
				if ($value > 0)
				{
					$arrTaxRatesForItem['tax'.$key.'_rate'] = $value;
				}
			}

			// Convert to JSON in order to do merge of multi-dimension array.
			// See http://stackoverflow.com/questions/2476876/how-do-i-convert-an-object-to-an-array
			$arrItem = CJSON::decode(CJSON::encode($objItem), true);
			$arrItem = array_merge($arrItem, $arrTaxRatesForItem);
			$arrItems[$itemKey] = $arrItem;
		}

		$response['cartItems'] = $arrItems;
		return CJSON::encode($response);
	}

	/**
	 * Find the tax code associated with the provided address and update the
	 * shopping cart to use it.
	 *
	 * @param mixed $shippingCountry The 2-letter country code for the country or the country ID.
	 * @param mixed $shippingState The 2-letter code for the state or the state ID.
	 * @param string $shippingPostal The postal code with all spaces removed.
	 * @return void
	 * @throws CException If tax destinations are not configured.
	 */
	public static function setTaxCodeFromAddress(
		$shippingCountry,
		$shippingState,
		$shippingPostal)
	{
		// Calculate tax since that may change depending on shipping address.
		Yii::log(
			sprintf(
				"Attempting to match with a defined Destination to Country/State/Postal %s/%s/%s",
				$shippingCountry,
				$shippingState,
				$shippingPostal
			),
			'info',
			'application.'.__CLASS__.".".__FUNCTION__
		);

		$objDestination = Destination::LoadMatching(
			$shippingCountry,
			$shippingState,
			$shippingPostal
		);

		if ($objDestination !== null)
		{
			Yii::log("Matched Destination destination.id=".$objDestination->id." to tax code destination.taxcode=".$objDestination->taxcode, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		} else {
			Yii::log('Destination not matched, going with default (Any/Any)', 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$objDestination = Destination::LoadDefault();

			if ($objDestination === null)
			{
				throw new CException(
					Yii::t(
						'checkout',
						'Website configuration error. No tax destinations have been defined by the Store Administrator. Cannot continue.'
					)
				);
			}
		}

		Yii::app()->shoppingcart->tax_code_id = $objDestination->taxcode;
		Yii::app()->shoppingcart->UpdateCart();
	}
}
