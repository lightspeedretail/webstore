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
	protected static function addRatesToShippingProviders ($arrShippingProvider)
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
	 * Returns an indexed array of cart scenarios ordered by the shipping price
	 * of the scenario from lowest to highest.
	 *
	 * @return array Indexed array of cart scenarios where each cart scenario
	 * is an associative array with the following keys:
	 *    providerId - The xlsws_module.id of the shipping provider,
	 *    priorityIndex - An index for the shipping priority (unique per provider),
	 *    providerLabel - A label for the shipping provider,
	 *    priorityLabel - A label for the shipping priority,
	 *    shippingPrice - The shipping price for this priortity,
	 *    shippingLabel - A label describing the provider and priority,
	 *    formattedCartSubtotal - The formatted subtotal of the cart for this
	 *        scenario,
	 *    formattedShippingPrice - The formatted shipping price,
	 *    formattedCartTax - The formatted amount of tax on the cart,
	 *    formattedCartTotal - The formatted total price of the cart,
	 *    shoppingCart - An instance of ShoppingCart with attributes set for
	 *        this scenario,
	 *    module - The internal module string identifier (xlsws_module.module).
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
		if (empty($checkoutForm->shippingCountry) === true)
		{
			throw new Exception(
				Yii::t(
					'checkout',
					'Not enough details in checkoutForm to get shipping rates'
				)
			);
		}

		Yii::log('Getting shipping rates ' . print_r($checkoutForm, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		Yii::app()->shoppingcart->setTaxCodeFromAddress(
			$checkoutForm->shippingCountry,
			$checkoutForm->shippingState,
			$checkoutForm->shippingPostal
		);

		$arrShippingProvider = self::getAvailableShippingProviders($checkoutForm);

		if (CPropertyValue::ensureBoolean(_xls_get_conf('DEBUG_SHIPPING', false)) === true)
		{
			Yii::log('Got shipping modules ' . print_r($arrShippingProvider, true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
		} else {
			Yii::log('Got shipping modules ' . print_r($arrShippingProvider, true), 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);
		}

		// Run each shipping module to get the rates.
		$arrShippingProvider = self::addRatesToShippingProviders($arrShippingProvider);

		// Compile each shipping providers rates into an array of "cart scenario"
		// associative-arrays. Each cart scenario contains details
		// about the cart as if the related shipping option had been chosen.
		$arrCartScenario = array();

		// Store the cart tax code ID, to be restored later.
		$savedTaxId = Yii::app()->shoppingcart->tax_code_id;

		foreach ($arrShippingProvider as $shippingModuleId => $shippingProvider)
		{
			// Since Store Pickup means paying local taxes, set the cart so our
			// scenarios work out.
			if ($shippingProvider['component']->IsStorePickup)
			{
				Yii::app()->shoppingcart->tax_code_id = TaxCode::getDefaultCode();
			} else {
				Yii::app()->shoppingcart->tax_code_id = $savedTaxId;
			}

			Yii::app()->shoppingcart->UpdateCart();

			// Get the "shipping" product, which may vary from module to module.
			$strShippingProduct = $shippingProvider['component']->LsProduct;

			if (Yii::app()->params['SHIPPING_TAXABLE'] == '1')
			{
				$objShipProduct = Product::LoadByCode($strShippingProduct);

				Yii::log(
					'Shipping Product for ' . $shippingProvider['module']->module . ' is ' . $strShippingProduct,
					'info',
					'application.'.__CLASS__.".".__FUNCTION__
				);

				// We may not find a shipping product in cloud mode, so
				// just use -1 which skips statuses.
				if ($objShipProduct instanceof Product === true)
				{
					$intLsId = $objShipProduct->taxStatus->lsid;
				} else {
					$intLsId = -1;
				}
			}

			foreach ($shippingProvider['rates'] as $priorityIndex => $priority)
			{
				$priorityPrice = $priority['price'];
				$includeTaxInShippingPrice = false;

				if (Yii::app()->params['SHIPPING_TAXABLE'] == '1')
				{
					$taxes = Tax::CalculatePricesWithTax(
						$priority['price'],
						Yii::app()->shoppingcart->tax_code_id,
						$intLsId
					);

					Yii::log("Taxes retrieved " . print_r($taxes,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

					// TODO Document why [1] ?
					$taxOnShipping = $taxes[1];
					if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '1')
					{
						$includeTaxInShippingPrice = true;
					}

					if ($includeTaxInShippingPrice === true)
					{
						$priorityPrice += $taxOnShipping;
					} else {
						Yii::app()->shoppingcart->AddTaxes($taxOnShipping);
					}
				}

				$shoppingCart = clone Yii::app()->shoppingcart->getModel();

				$arrCartScenario[] = array(
					'providerId' => $shippingModuleId,
					'priorityIndex' => $priorityIndex,
					'providerLabel' => $shippingProvider['component']->Name,
					'priorityLabel' => $priority['label'],
					'shippingPrice' => $priorityPrice,
					'shippingProduct' => $strShippingProduct,
					'shippingLabel' => $shippingProvider['component']->Name . ' ' . $priority['label'],
					'formattedCartSubtotal' => _xls_currency(Yii::app()->shoppingcart->subtotal),
					'formattedShippingPrice' => _xls_currency($priority['price']),
					'formattedCartTax' => _xls_currency(Yii::app()->shoppingcart->TaxTotal),
					'formattedCartTotal' => _xls_currency(Yii::app()->shoppingcart->precalculateTotal($priority['price'])),
					'shoppingCart' => $shoppingCart->attributes,
					'module' => $shippingProvider['module']->module
				);

				// Remove shipping taxes to accommodate the next loop.
				if (Yii::app()->params['SHIPPING_TAXABLE'] == '1' && $includeTaxInShippingPrice === false)
				{
					Yii::app()->shoppingcart->SubtractTaxes($taxes);
				}
			}
		}

		Yii::app()->shoppingcart->tax_code_id = $savedTaxId;
		Yii::app()->shoppingcart->UpdateCart();

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
	 * @param Shipping $shippingOptions A Shipping object.
	 * @return void
	 */
	public static function saveCartScenariosToSession($arrCartScenario)
	{
		Yii::log(
			'Saving ' . sizeof($arrCartScenario) . ' cart scenarios to session ' . print_r($arrCartScenario, true),
			'info',
			'application.'.__CLASS__.".".__FUNCTION__
		);

		Yii::app()->session[self::$cartScenariosSessionKey] = $arrCartScenario;
	}

	/**
	 * Load a Shipping object from the user's session. Used for retrieving
	 * previously calculated cart scenarios.
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
			return null;
		}

		// Save shipping options and rates to session.
		try {
			$arrCartScenario = Shipping::getCartScenarios($checkoutForm);
		} catch (Exception $e) {
			Yii::log('Unable to get cart scenarios: ' . $e->getMessage(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return null;
		}

		// Save the updated rates back to the session.
		Shipping::saveCartScenariosToSession($arrCartScenario);
	}
}
