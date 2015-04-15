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
	 * Global errors array to facilitate usage in any function
	 *
	 * @var array
	 */
	public $errors = array();


	/**
	 * Meant to track the result of UpdateCartItems()
	 * @var bool
	 */
	public $wasCartModified = false;

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
	 * Get the Cart model underlying this shoppingcart.
	 *
	 * @return CActiveRecord|Cart|null
	 */
	public function getModel()
	{
		// The first time this method is called, we initialise the cart model.
		if ($this->_model === null)
		{
			$intCustomerId = null;

			$objCustomer = Customer::GetCurrent();
			if ($objCustomer instanceof Customer)
			{
				$intCustomerId = $objCustomer->id;
			}

			// Attempt to load the cart from the session.
			$intSessionCartId = Yii::app()->user->getState('cartid', null);

			if ($intSessionCartId === null)
			{
				// The user doesn't have a cart in their session.
				$objCart = null;

				// Attempt to load the user's last cart.
				if ($intCustomerId !== null)
				{
					$objCart = Cart::LoadLastCartInProgress($intCustomerId);
				}

				if (is_null($objCart))
				{
					// TODO: We should probably fix this behaviour:
					// If we don't use initialize() here, then we end up with a tax_code_id=NULL,
					// which is fine, except that in models/Cart.php beforeValidate() we explicitly set
					// the tax_code_id to use the NOTAX tax code if tax_code_id is null.  In the case
					// of TAX_INCLUSIVE stores, this is definitely not what we want.
					$objCart = Cart::initialize($objCustomer);
				}
			} else {
				$objCart = Cart::model()->findByPk($intSessionCartId);

				//We don't want to create a new cart if the cart is currently active in session
				//or waiting for payment transaction to be finished.
				$requiresNewCart = (
					$objCart === null ||
					($objCart->cart_type != CartType::cart && $objCart->cart_type != CartType::awaitpayment)
				);

				if ($requiresNewCart === true)
				{
					$objCart = Cart::initialize($objCustomer);
					Yii::log(
						sprintf(
							'User had cart %s in their session, but creating a new one (cart type is %s).',
							$intSessionCartId,
							$objCart->cart_type
						),
						'error',
						'application.'.__CLASS__.".".__FUNCTION__
					);
				}
			}

			if ($intCustomerId !== null)
			{
				$objCart->customer_id = $intCustomerId;
			}

			if ($objCart->item_count > 0)
			{
				$objCart->recalculateAndSave();
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
		Yii::log("Creating cart, existing id is " . $intCartId, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		if (empty($intCartId) && !Yii::app()->isCommonSSL)
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
				$objCart = Cart::initializeAndSave();
			}

			$this->_model = $objCart;
			Yii::app()->user->setState('cartid', $objCart->id);
		}
	}

	public function getIsActive()
	{
		if (!$this->_model)
		{
			return false;
		}

		return true;
	}

	/**
	 * If the user had a cart in progress, merge the items into the current cart
	 * @param null $objCartToMerge
	 * @return bool
	 */
	public function loginMerge($objCartToMerge = null)
	{
		if (is_null($objCartToMerge) === false)
		{
			$objCartInProgress = $objCartToMerge;
		} else {
			$objCartInProgress = Cart::LoadLastCartInProgress(Yii::app()->user->id, $this->id);
		}

		if ($objCartInProgress instanceof Cart)
		{
			Yii::log("Found prior cart " . $objCartInProgress->id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			if (count($this->cartItems) == 0)
			{
				$this->_model = $objCartInProgress;
				$arrPastItems = array();
			} else {
				$arrPastItems = $objCartInProgress->cartItems;
			}

			// Merge in any new items we had in our cart from this session.
			if (count($arrPastItems) > 0)
			{
				foreach ($arrPastItems as $objItem)
				{
					$objProduct = Product::model()->findbyPk($objItem->product_id);

					// We strip any discount from another cart usu. promo code.
					$retVal = $this->model->AddProduct(
						$objProduct,
						$objItem->qty,
						$objItem->cart_type,
						$objItem->wishlist_item,
						$objItem->description,
						$objItem->sell,
						0
					);

					if ($objItem->wishlist_item > 0)
					{
						WishlistItem::model()->updateByPk($objItem->wishlist_item, array('cart_item_id' => $retVal));
					}

					if (is_null($objCartToMerge))
					{
						$objItem->delete();
					}
				}

				// If we aren't being passed a cart from a share (that we don't
				// want to delete), then remove the old cart in progress.
				if (is_null($objCartToMerge))
				{
					Yii::app()->user->setFlash('success', Yii::t('cart', 'Your prior cart has been restored.'));
					$objCartInProgress->delete();
				}

				$this->model->updateMissingProducts();
			}

			$this->model->customer_id = Yii::app()->user->id;
			$this->model->datetime_cre = new CDbExpression('NOW()'); //Reset time to current time
			if (!$this->model->save())
			{
				Yii::log("Error saving cart " . print_r($this->model->getErrors(), true), 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);
			}

			$this->recalculateAndSave();
			$this->_model = Cart::model()->findByPk($this->id);
			Yii::app()->user->setState('cartid', $this->id);
			return true;
		}

		return false;
	}

	/**
	 * Merge a document (i.e. quote) into the current cart.
	 * @param null $objDocument
	 * @return bool
	 */
	public function loginQuote($objDocument = null)
	{
		if (is_null($objDocument))
		{
			return;
		}

		$cartid = Yii::app()->user->getState('cartid');
		if (empty($cartid))
		{
			$this->createCart();
		}

		$arrPastItems = $objDocument->documentItems;

		// Merge in items from our quote to the cart.
		if (count($arrPastItems) > 0)
		{
			foreach($arrPastItems as $objItem)
			{
				$objProduct = Product::model()->findbyPk($objItem->product_id);
				$retVal = $this->model->AddProduct(
					$objProduct,
					$objItem->qty,
					CartType::quote,
					$objItem->gift_registry_item,
					$objItem->description,
					$objItem->sell,
					$objItem->discount
				);

				if (strlen($retVal) > 5)
				{
					return $retVal;
				}
			}
		}

		$this->model->document_id = $objDocument->id;

		$this->model->datetime_cre = new CDbExpression('NOW()'); //Reset time to current time
		if (!$this->model->save())
		{
			Yii::log(
				"Error saving cart ".print_r($this->model->getErrors(), true),
				'error',
				'application.'.__CLASS__.".".__FUNCTION__
			);
		}

		$this->recalculateAndSave();
		$this->_model = Cart::model()->findByPk($this->id);

		$this->assignCustomer(Yii::app()->user->id);
		return true;
	}

	/**
	 * Compare cart prices with the original product prices and update if necessary
	 */
	public function verifyPrices()
	{

		if ($this->model->cart_type != CartType::cart)
		{
			return;
		}

		$strFlash = '';
		$arrItems = array();

		foreach ($this->cartItems as $item)
		{
			// Make sure our object is current and not cached through our relations.
			$objProduct = Product::model()->findByPk($item->product_id);

			if ($item->sell_base != $objProduct->PriceValue)
			{
				$strFlash .= Yii::t(
					'cart',
					'The item {item} in your cart has {updown} in price to {price}.',
					array(
						'{item}' => $item->description,
						'{updown}' => ($item->sell_base > $objProduct->PriceValue ? Yii::t('cart', 'decreased') : Yii::t('cart', 'increased')),
						'{price}' => $objProduct->getPrice(1, $this->IsTaxIn)
					)
				) . '<br>';

				$arrItem['product'] = $objProduct;
				$arrItem['qty'] = $item->qty;
				$arrItem['wishlist_item'] = $item->wishlist_item;
				$arrItems[] = $arrItem;
				$item->delete();
			}
		}

		if (!empty($strFlash))
		{
			Yii::app()->user->setFlash('info', $strFlash);
		}

		$this->recalculateAndSave();
		foreach ($arrItems as $arrItem)
		{
			$this->addProduct($arrItem['product'], $arrItem['qty'], $arrItem['wishlist_item']);
		}

		$this->recalculateAndSave();
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

		$count = 0;
		foreach ($this->cartItems as $item)
		{
			$count += $item->qty;
		}

		return $count;
	}

	public function setTaxCodeId($id)
	{
		$this->model->tax_code_id = $id;
		// TODO: Investigate whether save and refresh is required here.
		$this->model->save();
		$this->model->refresh();
		$this->recalculateAndSave();
	}

	/**
	 * Forcibly assign a shopping cart to a specific cartid
	 * @param $id
	 */
	public function assign($id)
	{
		Yii::app()->user->setState('cartid', $id);
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
	public function addProduct($mixProduct, $intQuantity = 1, $intGiftItemId = null, $auto = null)
	{
		$cartid = Yii::app()->user->getState('cartid');

		if (empty($cartid) && is_null($this->model))
		{
			$this->createCart();
		}

		if ($mixProduct instanceof Product)
		{
			$objProduct = $mixProduct;
		} else {
			$objProduct = Product::model()->findByPk($mixProduct);
		}

		if (!(_xls_get_conf('QTY_FRACTION_PURCHASE')))
		{
			$intQuantity = intval($intQuantity);
		}

		if ($objProduct instanceof Product)
		{
			// We have to clear the cached shipping options here otherwise the
			// incorrect prices will be shown when returning to legacy checkout
			// after adding a product.
			$this->clearCachedShipping();

			//Actually add product to cart
			$retVal = $this->model->AddProduct($objProduct, $intQuantity, CartType::cart, $intGiftItemId);

			if (is_numeric($retVal) && is_null($auto)) //prevent circular logic adding
			{
				foreach($objProduct->productRelateds as $objProductAdditional)
				{
					if (isset($objProductAdditional->related) &&
						$objProductAdditional->related->IsAddable &&
						$objProductAdditional->autoadd == 1 &&
						$objProductAdditional->related->master_model == 0)
					{
						$this->addProduct($objProductAdditional->related, $objProductAdditional->qty, null, true);
					}
				}
			}

			return $retVal;
		}

		return false;
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
		{
			Yii::log("Error saving cart " . print_r($this->model->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

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
	// @codingStandardsIgnoreStart
	public function GenerateLink()
	// @codingStandardsIgnoreEnd
	{
		return $this->model->GenerateLink();
	}

	/**
	 * Remove all items from the cart
	 * @return bool
	 */
	public function clearCart()
	{

		if ($this->model->cart_type != CartType::cart)
		{
			return false;
		}

		unset(Yii::app()->session['checkout.cache']);
		unset(Yii::app()->session[MultiCheckoutForm::$sessionKey]);
		$this->clearCachedShipping();

		foreach ($this->cartItems as $item)
		{
			if (!is_null($item->wishlist_item))
			{
				WishlistItem::model()->updateByPk($item->wishlist_item, array('cart_item_id' => null));
			}

			$item->delete();
		}

		$this->model->fk_promo_id = null;
		$this->model->save();
		$this->model->refresh();
		$this->recalculateAndSave();
		$this->model->refresh();

		return true;
	}

	public function updateMissingProducts()
	{
		if (Yii::app()->user->getState('cartid') > 0 && isset($this->model))
		{
			$this->model->updateMissingProducts();
		}
	}

	/**
	 * Removes the promo code and discounts that are applied to a shopping cart
	 */
	public function removePromoCode()
	{
		$this->model->fk_promo_id = NULL;
		$this->model->removeDiscounts();
		$this->model->save();
		$this->model->refresh();
		$this->recalculateAndSave();
		$this->model->refresh();
	}

	/**
	 * Check that the promo code applied to the cart is still valid.
	 * If the promo code is no longer valid, remove it and return an error
	 * message.
	 */
	public function revalidatePromoCode()
	{
		if (is_null($this->id))
		{
			// If id is null then the cart has yet to be saved, which means it contains
			// no products and therefore there is no promocode to be revalidated.
			return;
		}

		if (isset($this->model) === false)
		{
			// If model isn't set then there is no Cart object, cannot revalidate.
			return;
		}

		$arrPromoCodeValid = $this->model->revalidatePromoCode();
		if ($arrPromoCodeValid === null)
		{
			// Not invalid, nothing to do.
			return;
		}

		Yii::app()->user->setFlash(
			'error',
			Yii::t(
				'cart',
				'Promo Code {promocode} removed. {reason}',
				array(
					'{promocode}' => sprintf('<strong>%s</strong>', $arrPromoCodeValid['code']),
					'{reason}' => $arrPromoCodeValid['reason']
				)
			)
		);

		$this->removePromoCode();
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

	// @codingStandardsIgnoreStart
	public function UpdateItemQuantity($objItem, $qty)
	// @codingStandardsIgnoreEnd
	{
		$this->clearCachedShipping();
		return $this->model->UpdateItemQuantity($objItem, _xls_number_only($qty));

	}

	public function applyPromoCode($mixCode)
	{
		if ($mixCode instanceof PromoCode)
		{
			$objPromoCode = $mixCode;
		} else {
			$objPromoCode = PromoCode::LoadByCode($mixCode);
		}

		if ($objPromoCode instanceof PromoCode)
		{
			$this->model->fk_promo_id = $objPromoCode->id;
			$this->recalculateAndSave();
		}
	}

	// These are pass-through functions which go currently to our cart object.

	/**
	 * True - cart should display tax inclusive prices
	 * False - cart should not display tax inclusive prices
	 *
	 * @return boolean whether the shopping cart uses tax inclusive pricing.
	 */
	public function getIsTaxIn()
	{
		$objCart = $this->getModel();

		if ($objCart instanceof Cart === true && $objCart->hasTaxCode() === true)
		{
			return $objCart->getIsTaxIn();
		}

		$objCustomer = Customer::GetCurrent();
		if ($objCustomer instanceof Customer === true)
		{
			return $objCustomer->defaultShippingIsTaxIn();
		}

		return CPropertyValue::ensureBoolean(Yii::app()->params['TAX_INCLUSIVE_PRICING']);
	}

	public function getPromoCode()
	{
		return $this->model->PromoCode;
	}

	public function completeUpdatePromoCode()
	{
		$this->model->completeUpdatePromoCode();
	}

	// @codingStandardsIgnoreStart
	public function SetIdStr()
	// @codingStandardsIgnoreEnd
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
		if (is_numeric($mixCustomer))
		{
			$objCustomer = Customer::model()->findByPk($mixCustomer);
		} else {
			$objCustomer = $mixCustomer;
		}

		if ($objCustomer instanceof Customer)
		{
			Yii::log("Assigning customer id #" . $objCustomer->id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$this->model->customer_id = $objCustomer->id;
			return true;
		}

		return false;
	}

	public function setTaxCodeByDefaultShippingAddress()
	{
		$this->model->setTaxCodeByDefaultShippingAddress();
	}

	public function recalculateAndSave()
	{
		$this->model->recalculateAndSave();
	}

	// @codingStandardsIgnoreStart
	public function RecalculateInventoryOnCartItems()
	// @codingStandardsIgnoreEnd
	{
		$this->model->RecalculateInventoryOnCartItems();
	}

	// @codingStandardsIgnoreStart
	public function UpdateWishList()
	// @codingStandardsIgnoreEnd
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
	 * Display a message if a customer's shipping address is in no-tax destination in tax-inclusive mode.
	 *
	 * @return bool true if no-tax message will be displayed.
	 */
	public static function displayNoTaxMessage()
	{
		// If the store is tax exclusive, just return, because price modes don't switch with
		// tax exlusive stores.
		if (CPropertyValue::ensureBoolean(Yii::app()->params['TAX_INCLUSIVE_PRICING']) === false)
		{
			return false;
		}

		$objCustomer = Customer::GetCurrent();
		if ($objCustomer instanceof Customer === false)
		{
			return false;
		}

		if ($objCustomer->defaultShippingIsNoTax() === true)
		{
			Yii::app()->user->setFlash(
				'warning',
				Yii::t('global', 'Note: Because of your default shipping address, prices will be displayed without tax.')
			);
			return true;
		}

		return false;
	}

	/**
	 * Erase expired carts and their associated items.
	 * A cart is considered expired if
	 *	1. it was last modified over CART_LIFE days ago AND
	 *	2. it has no id_str associated with it AND
	 *	3. it is of type cart, giftregistry or awaitpayment AND
	 *	4. it has no items in it OR
	 *	5. it has no customer_id associated with it
	 *
	 *	1. AND 2. AND 3. AND (4. OR 5.)
	 *
	 * @return int The number of carts + items that were erased.
	 */
	public static function eraseExpired() {
		// Erase carts older than CART_LIFE days.
		$cartLife = CPropertyValue::ensureInteger(_xls_get_conf('CART_LIFE', 30));

		// We can't use the class constants directly in the call to
		// bindParam(), put them in variables first.
		$cartType = CPropertyValue::ensureInteger(CartType::cart);
		$giftRegistryType = CPropertyValue::ensureInteger(CartType::giftregistry);
		$awaitPaymentType = CPropertyValue::ensureInteger(CartType::awaitpayment);

		$deleteSql = "
			DELETE c, ci
			FROM xlsws_cart c
			LEFT JOIN xlsws_cart_item ci
			ON c.id = ci.cart_id
			WHERE
				(c.customer_id IS NULL OR ci.id IS NULL) AND
				(c.cart_type IN (:cart, :giftregistry, :awaitpayment) AND
				c.modified < CURDATE() - INTERVAL :cartlife DAY AND
				c.id_str IS NULL)";
		$deleteCommand = Yii::app()->db->createcommand($deleteSql);
		$deleteCommand->bindparam(":cart", $cartType, PDO::PARAM_INT);
		$deleteCommand->bindparam(":giftregistry", $giftRegistryType, PDO::PARAM_INT);
		$deleteCommand->bindparam(":awaitpayment", $awaitPaymentType, PDO::PARAM_INT);
		$deleteCommand->bindparam(":cartlife", $cartLife, PDO::PARAM_INT);

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			Yii::app()->db->createCommand("set foreign_key_checks = 0")->execute();
			$numErased = $deleteCommand->execute();
			Yii::app()->db->createCommand("set foreign_key_checks = 1")->execute();
			$transaction->commit();
		} catch(Exception $e) {
			$transaction->rollback();
		}

		return $numErased;
	}

	/**
	 * Optimize the tables related to the shopping cart.
	 * This is an administrative function and should be called with care.
	 *
	 * @return void
	 */
	public static function optimizeTables() {
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_cart")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_cart_item")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_customer")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_wish_list")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_wish_list_items")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_product")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_product_related")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_category")->execute();
		Yii::app()->db->createCommand("OPTIMIZE table xlsws_product_category_assn")->execute();
	}

	/**
	 * Check to see if the cart contains items that have quantity discounts
	 * applied to them. If there is one element that those this method will
	 * return true.
	 *
	 * @return bool|mixed If the cart has a discount it returns true
	 * false otherwise.
	 */
	public function hasQtyDiscount()
	{
		return $this->model->hasQtyDiscount();
	}

	/**
	 * There's 2 types of discounts that can show up on webstore:
	 * promo codes or quantity discounts. If one or the other is currently
	 * active on the cart the line for promotion should be displayed.
	 *
	 * @return bool True if the promo code line should be displayed. False
	 * otherwise.
	 *
	 */
	public function displayPromoLine()
	{
		return ($this->hasQtyDiscount() ||
			$this->promoCode);
	}

	/**
	 * @param string $strName
	 * @return int|mixed
	 */
	public function __get($strName)
	{
		switch ($strName)
		{
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
				if (empty($this->model->subtotal))
				{
					return 0;
				}
				return $this->model->subtotal;

			case 'subtotalFormatted':
				if (empty($this->model->subtotal))
				{
					return '';
				}
				return _xls_currency($this->model->subtotal);

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
				return $this->model->totalDiscountFormatted;

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
				{
					return $this->model->shipping->shipping_sell;
				}
				return 0;

			case 'formattedShippingCharge':
				if ($this->model && $this->model->shipping)
				{
					return _xls_currency($this->model->shippingCharge);
				}
				return _xls_currency(0);

			case 'shippingCharge':
				return $this->model->shippingCharge;

			case 'customer':
				if ($this->model->customer)
				{
					return $this->model->customer;
				}
				return null;

			case 'payment':
				if ($this->model->payment)
				{
					return $this->model->payment;
				}
				return null;

			case 'shipping':
				if ($this->model->shipping)
				{
					return $this->model->shipping;
				}
				return null;

			case 'billaddress':
				if ($this->model->billaddress)
				{
					return $this->model->billaddress;
				}
				return null;

			case 'shipaddress':
				if ($this->model->shipaddress)
				{
					return $this->model->shipaddress;
				}
				return null;

			case 'originalSubTotal':
				return self::calculateOriginalSubtotal();

			default:
				// As a clever trick to get to our model through the component.
				if (isset($this->model) && $strName != "model" && $this->model->hasAttribute($strName))
				{
					return $this->model->$strName;
				}
				return parent::__get($strName);
		}

	}

	// @codingStandardsIgnoreStart
	private function calculateOriginalSubtotal()
	// @codingStandardsIgnoreEnd
	{
		$originalSubTotal = 0;
		if ($this->model->cartItems)
		{
			foreach($this->cartItems as $objItem)
			{
				$originalSubTotal += $objItem->sell_base * $objItem->qty;
			}
		}

		return $originalSubTotal;
	}

	/**
	 * @param string $strName
	 * @param mixed $mixValue
	 * @return mixed
	 */
	public function __set($strName, $mixValue)
	{
		switch ($strName)
		{
			case 'cartItems':
				return $this->model->cartItems;

			default:
				// As a clever trick to get to our model through the component,
				if ($strName != "model" && $this->model->hasAttribute($strName))
				{
					$this->model->__set($strName, $mixValue);
				} else {
					return parent::__set($strName, $mixValue);
				}
		}
	}

	/**
	 * Find the tax code associated with the provided address and update the
	 * shopping cart to use it.
	 *
	 * When the shipping country is empty, the Store Default tax code is used.
	 * This is generally used before an address is entered and for store
	 * pickup.
	 *
	 * If the provided address is not matched to any destination, the tax code
	 * for ANY/ANY is used.
	 *
	 * @param mixed $shippingCountry The 2-letter country code for the country or the country ID.
	 * @param mixed $shippingState The 2-letter code for the state or the state ID.
	 * @param string $shippingPostal The postal code with all spaces removed.
	 * @return void
	 * @throws CException If tax destinations are not configured.
	 */
	public function setTaxCodeByAddress(
		$shippingCountry,
		$shippingState,
		$shippingPostal)
	{

		$previousTaxCodeId = $this->getModel()->tax_code_id;

		$taxCode = TaxCode::getTaxCodeByAddress(
			$shippingCountry,
			$shippingState,
			$shippingPostal
		);

		$newTaxCodeId = $taxCode->lsid;
		$this->setTaxCodeId($newTaxCodeId);

		// Validate the promo code after saving, since recalculating updates
		// the cart item prices and may invalidate a promo code based on its
		// threshold.
		$this->recalculateAndSave();
		$this->revalidatePromoCode();

		// In a tax inclusive environment there can only be 2 tax codes.
		// Changing tax code means we've gone from tax-inclusive to
		// tax-exclusive or vice versa. This implies that the price display has
		// changed.
		// TODO: Is this always true? What if tax_code_id was null?
		if (CPropertyValue::ensureBoolean(_xls_get_conf('TAX_INCLUSIVE_PRICING')) == 1 &&
			$previousTaxCodeId !== $newTaxCodeId)
		{
			Yii::app()->user->setFlash(
				'taxModeChange',
				Yii::t('checkout', 'Prices have changed based on your tax locale.')
			);
		}
	}

	public function setTaxCodeByCheckoutForm($checkoutForm)
	{
		$this->setTaxCodeByAddress(
			$checkoutForm->shippingCountryCode,
			$checkoutForm->shippingStateCode,
			$checkoutForm->shippingPostal
		);
	}

	public function getHasPromoCode()
	{
		return $this->getModel()->getHasPromoCode();
	}
}
