<?php

/**
 * A model class with mainly static methods for dealing with the shipping modules.
 *
 * Provides support for getting shipping estimates to the old-style single-page
 * checkout and the new-style "advanced" checkout which uses a shipping
 * estimator (extensions/wsshippingestimator).
 *
 */
class Shipping
{
	// @var string The key for when an instance of Shipping is stored in the
	// user's session.
	public static $cartScenariosSessionKey = 'shipping-cart-scenarios';

	/**
	 * Return the enabled shipping modules.
	 *
	 * @return array Returns an associative array indexed on the shipping
	 * module ID (xlsws_module.id) where each value is an array with 2 keys:
	 * module and component. The module property is the CActiveRecord
	 * xlsws_module instance. The component is the corresponding application
	 * component (IApplicationComponent) instance.
	 *
	 * @throws Exception When no shipping providers are available.
	 */
	protected static function getAvailableShippingProviders($checkoutForm)
	{
		Yii::log("Contacting each live shipping module", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		$shippingModules = Modules::model()->shipping()->findAll();
		$arrShippingProvider = array();

		foreach ($shippingModules as $objModule)
		{
			if (empty($checkoutForm->shippingCountryCode) === true &&
				$objModule->module !== 'storepickup')
			{
				continue;
			}

			if (_xls_get_conf('DEBUG_SHIPPING', false))
			{
				Yii::log("Attempting to get the component for module ".$objModule->module, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

			$objComponent = Yii::app()->getComponent($objModule->module);
			if ($objComponent === null)
			{
				Yii::log("Error missing component for module ".$objModule->module, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				continue;
			}

			// The shipping component needs data from the checkout form.
			$objComponent->setCheckoutForm($checkoutForm);

			// Restrictions may apply to some modules.
			if ($objComponent->Show === false)
			{
				Yii::log("Module is not shown ".$objModule->module, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				continue;
			}

			$arrShippingProvider[$objModule->id]['module'] = $objModule;
			$arrShippingProvider[$objModule->id]['component'] = $objComponent;
		}

		// If we have no providers in our list, it means either the
		// restrictions have cancelled them out or they aren't turned on in
		// the first place.
		if (count($arrShippingProvider) === 0)
		{
			Yii::log("No shipping methods apply to this order, cannot continue!", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			throw new Exception(
				Yii::t(
					'checkout',
					'Website configuration error. No shipping methods apply to this order. Cannot continue.'
				)
			);
		}

		return $arrShippingProvider;
	}

	/**
	 * Given an array of shipping modules, runs each of them in turn and
	 * add the rates into the provided array.
	 *
	 * It is assumed that the shipping module's component has already had its
	 * checkout form set (so has the data it requires needed to get the
	 * shipping rates).
	 *
	 * @param array $arrShippingProvider An array of shipping modules indexed on
	 * the module ID. Each element of the array should have a 'module' and
	 * 'component' key. See getAvailableShippingProviders.
	 * @return array A modified version of $arrShippingProvider array with a new
	 * 'rates' key for each module. The 'rates' array has the following keys:
	 *    label - the label for this shipping priority.
	 *    price - the price for this shipping priority.
	 *
	 * Modules for which rates are not available are not included in the
	 * returned array.
	 *
	 * @see getAvailableShippingProviders.
	 * @throws Exception If no shipping providers are able to provide rates.
	 */
	protected static function addRatesToShippingProviders($arrShippingProvider)
	{
		$arrRates = array();
		foreach ($arrShippingProvider as $shippingModuleId => $shippingProvider)
		{
			Yii::log(
				'Attempting to calculate ' . $shippingProvider['module']->module,
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);

			try {
				$arrShippingRates = $shippingProvider['component']->run();
			} catch (Exception $e) {
				Yii::log(
					'Cannot process module ' . $shippingProvider['module']->module . $e,
					'error',
					'application.'.__CLASS__.".".__FUNCTION__
				);
				continue;
			}

			if (count($arrShippingRates) === 0 || is_array($arrShippingRates) === false)
			{
				// If the returned value is not valid, we can't use it.
				Yii::log(
					'Cannot use module ' . $shippingProvider['module']->module,
					'error',
					'application.'.__CLASS__.".".__FUNCTION__
				);
				continue;
			}

			$arrRates[$shippingModuleId] = $shippingProvider;
			$arrRates[$shippingModuleId]['rates'] = $arrShippingRates;
		}

		// If none of the shipping options are valid, we must have received
		// errors from them.
		if (count($arrShippingProvider) === 0)
		{
			throw new Exception(
				Yii::t(
					'checkout',
					'Website configuration error. Shipping modules are not ' .
					'configured properly by the Store Administrator. Cannot continue.'
				)
			);
		}

		return $arrRates;
	}

	/**
	 * Returns an indexed array of hypothetical cart scenarios ordered by the
	 * shipping price of the scenario from lowest to highest.
	 *
	 * TODO: WS-3481 Refactor this to use Cart instead of ShoppingCart.
	 * TODO: WS-3676 Refactor Shipping::getCartScenarios to implicitly modify the cart and the checkoutform
	 *
	 * @param $checkoutForm
	 * @return array Indexed array of cart scenarios where each cart scenario
	 * is an associative array with the following keys:
	 *    formattedCartSubtotal - The formatted subtotal of the cart for this
	 *        scenario,
	 *    formattedCartTax - The formatted amount of tax on the cart,
	 *    formattedCartTotal - The formatted total price of the cart,
	 *    formattedShippingPrice - The formatted shipping price,
	 *    module - The internal module string identifier (xlsws_module.module).
	 *    priorityIndex - An index for the shipping priority (unique per provider),
	 *    priorityLabel - A label for the shipping priority,
	 *    providerId - The xlsws_module.id of the shipping provider,
	 *    providerLabel - A label for the shipping provider,
	 *    shippingLabel - A label describing the provider and priority,
	 *    shippingPrice - The shipping price for this priortity,
	 *    shoppingCart - An instance of ShoppingCart with attributes set for
	 *        this scenario,
	 *    sortOrder - The xlsws_module.sort_order.
	 *    cartItems - The individual cartItem objects for the scenario
	 *
	 * Formatted currencies are formatted according to the user's language.
	 *
	 * @throws Exception If $checkoutForm does not contain enough details to
	 * get shipping rates.
	 * @throws Exception If no shipping providers are enabled (via
	 * Shipping::getAvailableShippingProviders).
	 * @throws Exception If no shipping providers are able to provide rates
	 * (via Shipping::addRatesToShippingProviders).
	 */
	public static function getCartScenarios($checkoutForm)
	{
		$logLevel = 'info';
		if (CPropertyValue::ensureBoolean(_xls_get_conf('DEBUG_SHIPPING', false)) === true)
		{
			$logLevel = 'error';
		}

		// TODO: This, and the setting of hasTaxModeChanged, should be
		// refactored out of this method. It would be better if
		// getCartScenarios did not have side-effects.
		Yii::app()->shoppingcart->setTaxCodeByCheckoutForm($checkoutForm);

		// We are going to modify the shopping cart and save the intermediate
		// values so we need to save the current value.
		$savedTaxId = Yii::app()->shoppingcart->tax_code_id;
		$cart = Yii::app()->shoppingcart->getModel();

		// The call to setTaxCodeByCheckoutForm() on the shopping cart will call
		// recalculateAndSave(). That call is going to add taxes on shipping by
		// calling updateTaxShipping(). The first run will have the correct values.
		// On later runs, we will have taxes set in the shopping cart and add more
		// when we call updateTaxShipping(). Plus, we used to also make a call to
		// recalculateAndSave() while going through the shipping providers. Then we
		// would call AddTaxes() which would add taxes on top of taxes.
		$cart->updateTaxExclusive();

		$savedStorePickup = $cart->blnStorePickup;

		// Get the list of shipping modules.
		$arrShippingProvider = self::getAvailableShippingProviders($checkoutForm);
		Yii::log('Got shipping modules ' . print_r($arrShippingProvider, true), $logLevel, 'application.'.__CLASS__.'.'.__FUNCTION__);

		// Run each shipping module to get the rates.
		$arrShippingProvider = self::addRatesToShippingProviders($arrShippingProvider);

		// Compile each shipping providers rates into an array of "cart scenarios".
		// Each cart scenario is an associative array containing details about
		// the cart as it would be if a particular shipping option were chosen.
		$arrCartScenario = array();

		// The shopping cart variable has to be set in case we encounter
		// a case where the arrShippingProvider is empty.
		$shoppingCart = Yii::app()->shoppingcart->getModel();
		$savedStorePickup = false;

		foreach ($arrShippingProvider as $shippingModuleId => $shippingProvider)
		{
			// Since Store Pickup means paying local taxes, set the cart so our
			// scenarios work out.
			if ($shippingProvider['component']->IsStorePickup === true)
			{
				Yii::app()->shoppingcart->tax_code_id = TaxCode::getDefaultCode();
				$cart->blnStorePickup = true;
			} else {
				Yii::app()->shoppingcart->tax_code_id = $savedTaxId;
				$cart->blnStorePickup = false;
			}

			// Get the "shipping" product, which may vary from module to module.
			$strShippingProduct = $shippingProvider['component']->LsProduct;
			Yii::log(
				'Shipping Product for ' . $shippingProvider['module']->module . ' is ' . $strShippingProduct,
				$logLevel,
				'application.'.__CLASS__.".".__FUNCTION__
			);

			if (Yii::app()->params['SHIPPING_TAXABLE'] == 1)
			{
				// When shipping is taxable we need to find the tax code on the actual shipping product.
				$objShipProduct = Product::LoadByCode($strShippingProduct);

				if ($objShipProduct instanceof Product === true)
				{
					$intShipProductLsid = $objShipProduct->taxStatus->lsid;
				} else {
					// We may not find a shipping product in cloud mode, so
					// just use -1 which skips statuses.
					$intShipProductLsid = -1;
				}
			}

			foreach ($shippingProvider['rates'] as $priorityIndex => $priority)
			{
				$priorityPrice = $priority['price'];
				$includeTaxInShippingPrice = false;
				$shippingTaxValues = array();

				if (Yii::app()->params['SHIPPING_TAXABLE'] == '1')
				{
					$shippingTaxPrices = Tax::calculatePricesWithTax(
						$priority['price'],
						Yii::app()->shoppingcart->tax_code_id,
						$intShipProductLsid
					);

					Yii::log("Shipping Taxes retrieved " . print_r($shippingTaxPrices, true), $logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

					$shippingTaxValues = $shippingTaxPrices['arrTaxValues'];
					if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '1')
					{
						$includeTaxInShippingPrice = true;
					}

					if ($includeTaxInShippingPrice === true)
					{
						$priorityPrice = $shippingTaxPrices['fltSellTotalWithTax'];
					}
					else
					{
						Yii::app()->shoppingcart->AddTaxes($shippingTaxValues);
					}
				}

				$formattedCartTax = _xls_currency(Yii::app()->shoppingcart->TaxTotal);
				if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '1')
				{
					// For tax inclusive stores, we never show cart tax. This is because either:
					// 1. The destination is inside the tax-inclusive region, or
					// 2. The destination is inside a tax-exclusive region, in
					//    which case it must be set up as 0% tax.
					$formattedCartTax = '';
				}

				// TODO: Do the _xls_currency() in the formatter rather than here.
				$arrCartScenario[] = array(
					'formattedCartSubtotal' => _xls_currency(Yii::app()->shoppingcart->subtotal),
					'formattedCartTax' => $formattedCartTax,
					'formattedCartTax1' => _xls_currency(Yii::app()->shoppingcart->tax1),
					'formattedCartTax2' => _xls_currency(Yii::app()->shoppingcart->tax2),
					'formattedCartTax3' => _xls_currency(Yii::app()->shoppingcart->tax3),
					'formattedCartTax4' => _xls_currency(Yii::app()->shoppingcart->tax4),
					'formattedCartTax5' => _xls_currency(Yii::app()->shoppingcart->tax5),
					'formattedCartTotal' => _xls_currency($cart->getTotalWithShipping($priorityPrice)),
					'cartTax1' => Yii::app()->shoppingcart->tax1,
					'cartTax2' => Yii::app()->shoppingcart->tax2,
					'cartTax3' => Yii::app()->shoppingcart->tax3,
					'cartTax4' => Yii::app()->shoppingcart->tax4,
					'cartTax5' => Yii::app()->shoppingcart->tax5,
					'formattedShippingPrice' => _xls_currency($priorityPrice),
					'module' => $shippingProvider['module']->module,
					'priorityIndex' => $priorityIndex,
					'priorityLabel' => $priority['label'],
					'providerId' => $shippingModuleId,
					'providerLabel' => $shippingProvider['component']->Name,
					'shippingLabel' => $shippingProvider['component']->Name . ' ' . $priority['label'],
					'shippingPrice' => $priority['price'],
					'shippingPriceWithTax' => $priorityPrice,
					'shippingProduct' => $strShippingProduct,
					'cartAttributes' => $cart->attributes,
					'cartItems' => $cart->cartItems,
					'sortOrder' => $shippingProvider['module']->sort_order
				);

				// Remove shipping taxes to accommodate the next shipping priority in the loop.
				if (Yii::app()->params['SHIPPING_TAXABLE'] == '1' && $includeTaxInShippingPrice === false)
				{
					Yii::app()->shoppingcart->SubtractTaxes($shippingTaxValues);
				}
			}
		}

		// Restore the original storePickup boolean
		$cart->blnStorePickup = $savedStorePickup;

		// Restore the original tax code on the cart.
		Yii::app()->shoppingcart->setTaxCodeId($savedTaxId);

		// Sort the shipping options based on the price key.
		usort(
			$arrCartScenario,
			function ($item1, $item2) {
				if ($item1['shippingPrice'] == $item2['shippingPrice'])
				{
					return 0;
				}

				return ($item1['shippingPrice'] > $item2['shippingPrice']) ? 1 : -1;
			}
		);

		return $arrCartScenario;
	}

	/**
	 * Save a Shipping object to the user's session. Used for storing
	 * previously calculated cart scenarios.
	 *
	 * @param array $arrCartScenario - a formatted array of cart scenarios
	 * See getCartScenarios()
	 */
	public static function saveCartScenariosToSession($arrCartScenario)
	{
		Yii::app()->session[self::$cartScenariosSessionKey] = $arrCartScenario;
	}

	/**
	 * Load a Shipping object from the user's session. Used for retrieving
	 * previously calculated cart scenarios.
	 * TODO: Should probably be renamed to getCartScenariosFromSession().
	 * TODO: Default to an empty array.
	 * @return Shipping|null The Shipping object stored in the session.
	 */
	public static function loadCartScenariosFromSession()
	{
		return Yii::app()->session->get(self::$cartScenariosSessionKey);
	}

	/**
	 * Returns the cartScenario (element of array returned by
	 * Shipping::getCartScenarios) that has been selected, from the session.
	 * @return array|null A cart scenario associative array.
	 * @see Shipping::getCartScenarios.
	 */
	public static function getSelectedCartScenarioFromSession()
	{
		$arrCartScenario = self::loadCartScenariosFromSession();
		if ($arrCartScenario === null)
		{
			return null;
		}

		$checkoutForm = MultiCheckoutForm::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return findWhere(
			$arrCartScenario,
			array(
				'providerId' => $checkoutForm->shippingProvider,
				'priorityLabel' => $checkoutForm->shippingPriority
			)
		);
	}

	/**
	 * Get the selected cart scenario from the session.
	 * If there's no selected cart scenario, formatted the shopping cart in the same way.
	 * TODO: Create a CartScenario.php component and change this function to
	 * CartScenario::formatFromShoppingCart().
	 * @return CartScenario @see Shipping::getCartScenarios.
	 */
	public static function getSelectedCartScenarioFromSessionOrShoppingCart()
	{
		$selectedCartScenario = static::getSelectedCartScenarioFromSession();

		if ($selectedCartScenario !== null)
		{
			return $selectedCartScenario;
		}

		// Return a version of the shopping cart formatted like a cart scenario.
		$sc = Yii::app()->shoppingcart;
		return array(
			'formattedCartSubtotal' => _xls_currency($sc->subtotal),
			'formattedCartTax' => $sc->taxTotalFormatted,
			'formattedCartTax1' => $sc->formattedCartTax1,
			'formattedCartTax2' => $sc->formattedCartTax2,
			'formattedCartTax3' => $sc->formattedCartTax3,
			'formattedCartTax4' => $sc->formattedCartTax4,
			'formattedCartTax5' => $sc->formattedCartTax5,
			'formattedCartTotal' => $sc->totalFormatted,
			'cartTax1' => $sc->tax1,
			'cartTax2' => $sc->tax2,
			'cartTax3' => $sc->tax3,
			'cartTax4' => $sc->tax4,
			'cartTax5' => $sc->tax5,
			'formattedShippingPrice' => $sc->formattedShippingCharge,
			'module' => null,
			'priorityIndex' => null,
			'priorityLabel' => null,
			'providerId' => null,
			'providerLabel' => null,
			'shippingLabel' => null,
			'shippingPrice' => null,
			'shippingPriceWithTax' => null,
			'shippingProduct' => null,
			'shoppingCart' => null,
			'sortOrder' => null
		);
	}

	/**
	 * Updates the cart scenarios stored in the session.
	 *
	 * @return void
	 * @see Shipping::getCartScenarios.
	 */
	public static function updateCartScenariosInSession()
	{
		// If we already have shipping details in the session we can try to
		// update the cart scenarios.
		$checkoutForm = MultiCheckoutForm::loadFromSession();
		if ($checkoutForm === null)
		{
			$arrCartScenario = null;
		} else {
			// Save shipping options and rates to session.
			try {
				$arrCartScenario = Shipping::getCartScenarios($checkoutForm);
			} catch (Exception $e) {
				// TODO: We should probably execute this block if $arrCartScenario is an empty array as well.
				Yii::log('Unable to get cart scenarios: ' . $e->getMessage(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				// If there are no valid cart scenarios we can deselect whatever
				// was previously selected.
				// TODO: We should possibly do this if the newly update cart
				// scenarios don't include the previously selected one.
				$checkoutForm->shippingProvider = null;
				$checkoutForm->shippingPriority = null;
				MultiCheckoutForm::saveToSession($checkoutForm);

				// Remove any previously stored cart scenarios.
				$arrCartScenario = null;
			}
		}

		// Save the updated rates back to the session.
		Shipping::saveCartScenariosToSession($arrCartScenario);
	}
}
