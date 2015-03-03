<?php

/**
 * For display of the shipping estimator in the add to cart and edit cart modal
 * windows.
 */
class WsShippingEstimator extends CWidget
{
	/**
	 * @var integer The maximum number of shipping options to return when
	 * formatting for the shipping estimator.
	 * @see Shipping::formatCartScenariosAsShippingOptions.
	 */
	const MAX_SHIPPING_OPTIONS = 8;

	/**
	* @var string CSS class for the shipping estimator on the page.
	*/
	const CSS_CLASS = 'wsshippingestimator';

	/**
	* @var boolean Whether to update the shipping options (cart scenarios)
	* before binding them for the view layer.
	*/
	public $updateShippingOptions = false;

	/**
	 * Run the widget. Renders the shipping estimator lines on the page.
	 */
	public function run()
	{
		// Required assets.
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', false, -1, true);
		Yii::app()->clientScript->registerScriptFile($assets . '/js/WsShippingEstimator.js');

		$checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		// We may wish to update the shipping options right away if we know the
		// cart has changed.
		$updateOnLoad = false;
		if ($this->updateShippingOptions)
		{
			// This check for shippingCountry being null is a workaround to fix
			// WS-3180. When shippingCountry is null, we need to update the
			// shipping estimates *now* because they will not be updated by the
			// JavaScript (since the JavaScript in WsShippingEstimator requires
			// country to be set). The reason we need to do this is because
			// shippingCountry may be null when in-store pickup has been
			// chosen.
			// TODO: Fix this in WsShippingEstimator and remove this workaround.
			if (empty($checkoutForm->shippingCountry) || empty($checkoutForm->shippingPostal))
			{
				Shipping::updateCartScenariosInSession();
			} else {
				$updateOnLoad = true;
			}
		}

		// Use the shipping scenarios and shipping address in the session.
		$arrCartScenario = Shipping::loadCartScenariosFromSession();

		$wsShippingEstimatorOptions = self::getShippingEstimatorOptions(
			$arrCartScenario,
			$checkoutForm->shippingProvider,
			$checkoutForm->shippingPriority,
			$checkoutForm->shippingCity,
			$checkoutForm->shippingStateCode,
			$checkoutForm->shippingCountryCode,
			$updateOnLoad
		);

		$selectedCartScenario = Shipping::getSelectedCartScenarioFromSession();
		if ($selectedCartScenario !== null)
		{
			$formattedShippingPrice = $selectedCartScenario['formattedShippingPrice'];
			$formattedCartTax = $selectedCartScenario['formattedCartTax'];
		} else {
			$formattedShippingPrice = null;
			$formattedCartTax = null;
		}

		$this->render(
			'_shippingestimator',
			array(
				'countries' => CHtml::listData(Country::getShippingCountries(), 'code', 'country'),
				'formattedShippingPrice' => $formattedShippingPrice,
				'formattedCartTax' => $formattedCartTax,
				'shippingCountryCode' => $wsShippingEstimatorOptions['shippingCountryCode'],
				'shippingCountryName' => $wsShippingEstimatorOptions['shippingCountryName'],
				'shippingPostal' => $checkoutForm->shippingPostal,
				'wsShippingEstimatorOptions' => CJSON::encode($wsShippingEstimatorOptions),
				'cssClass' => self::CSS_CLASS
			)
		);
	}

	/**
	 * Formats an array of cart scenarios as required by the front-end shipping
	 * estimator code.
	 *
	 * @param array $arrCartScenario An array of cart scenarios.
	 * @see Shipping::getCartScenarios.
	 * @return Array An indexed array of shipping options. Each shipping option is an
	 * associative array with the following keys:
	 *     providerId - The shipping module id (xlsws_module.id),
	 *     priorityLabel - A label for the shipping priority (e.g. Next Day Delivery),
	 *     shippingLabel - A label for the shipping option encompassing the
	 *         provider and priority (e.g. Fedex Next Day Delivery),
	 *     formattedShippingPrice - The formatted price of the shipping option,
	 *     formattedCartTax - The formatted price of the tax on the cart when
	 *         the shipping option is applied,
	 *     formattedCartTotal - The formatted total price of the cart when the
	 *         shipping option is applied,
	 */
	protected static function formatCartScenariosAsShippingOptions($arrCartScenario)
	{
		// Convert each element in $arrCartScenario to an associative array
		// that can be used by the front end.
		$arrShippingOption = array();
		foreach ($arrCartScenario as $cartScenario)
		{
			$arrShippingOption[] = static::formartCartScenarioAsShippingOption($cartScenario);
		}

		return $arrShippingOption;
	}

	/**
	 * Formats one array of cart scenario as required by the front-end shipping
	 * estimator code.
	 *
	 * @param array $cartScenario A cart scenario
	 * @see Shipping::getCartScenarios.
	 * @return Array An indexed array of shipping options. Each shipping option is an
	 * associative array with the following keys:
	 *     providerId - The shipping module id (xlsws_module.id),
	 *     priorityLabel - A label for the shipping priority (e.g. Next Day Delivery),
	 *     shippingLabel - A label for the shipping option encompassing the
	 *         provider and priority (e.g. Fedex Next Day Delivery),
	 *     formattedShippingPrice - The formatted price of the shipping option,
	 *     formattedCartTax - The formatted price of the tax on the cart when
	 *         the shipping option is applied,
	 *     formattedCartTotal - The formatted total price of the cart when the
	 *         shipping option is applied,
	 */
	protected static function formartCartScenarioAsShippingOption($cartScenario)
	{
		return array_intersect_key(
			$cartScenario,
			array_flip(
				array(
					'providerId',
					'priorityLabel',
					'shippingLabel',
					'formattedShippingPrice',
					'formattedCartTax',
					'formattedCartTax1',
					'formattedCartTax2',
					'formattedCartTax3',
					'formattedCartTax4',
					'formattedCartTax5',
					'formattedCartTotal',
				)
			)
		);
	}

	/**
	 * Get the options required by WsShippingEstimator.js.
	 *
	 * @param array $arrCartScenario An array of cart scenarios @see
	 * Shipping::getCartScenarios.
	 * @param integer $selectedShippingProviderId The ID (xlsws_modules.id) of
	 * the selected shipping provider.
	 * @param string $selectedShippingPriorityLabel The label for the shipping
	 * priority. This value in combination with $selectedShippingProviderId
	 * describes which shipping option is selected.
	 * @param string $shippingCity The city that the cart is shipping to.
	 * @param string $shippingStateCode The code for the state that the cart is shipping to.
	 * @param string $shippingCountryCode The code the country that the cart is shipping to.
	 * @param bool $updateOnLoad Whether the shipping estimator should get updated estimates right away.
	 *
	 * @return array $shippingEstimatorOptions
	 */
	public static function getShippingEstimatorOptions(
		$arrCartScenario,
		$selectedShippingProviderId,
		$selectedShippingPriorityLabel,
		$shippingCity,
		$shippingStateCode,
		$shippingCountryCode,
		$updateOnLoad = false
	) {
		// Build up an associative array of the options required for the shipping estimator.
		$shippingEstimatorOptions = array(
			'class' => self::CSS_CLASS,
			'getShippingRatesEndpoint' => Yii::app()->createUrl('cart/getshippingrates'),
			'setShippingOptionEndpoint' => Yii::app()->createUrl('cart/chooseshippingoption'),
			'updateOnLoad' => CPropertyValue::ensureBoolean($updateOnLoad),
			'shippingCity' => $shippingCity,
			'shippingState' => $shippingStateCode,
			'messages' => array()
		);

		// If a shipping country code is provided, then use it to get the
		// shipping country name.
		if ($shippingCountryCode !== null)
		{
			$shippingCountryName = Country::CountryByCode($shippingCountryCode);
		} else {
			// Otherwise, just use the first option from the list of countries.
			$countries = CHtml::listData(Country::getShippingCountries(), 'code', 'country');
			$shippingCountryName = reset($countries);
			$shippingCountryCode = key($countries);
		}

		$shippingEstimatorOptions['shippingCountryName'] = $shippingCountryName;
		$shippingEstimatorOptions['shippingCountryCode'] = $shippingCountryCode;
		$shippingEstimatorOptions['selectedShippingOption'] = null;
		// With a set of shipping scenarios available and a previously selected
		// option, we can try to find a match.
		if ($arrCartScenario !== null)
		{
			if ($selectedShippingProviderId !== null && $selectedShippingPriorityLabel !== null)
			{
				// Try to find the previously selected option in the cart scenario array.
				$selectedCartScenario = findWhere(
					$arrCartScenario,
					array(
						'providerId' => $selectedShippingProviderId,
						'priorityLabel' => $selectedShippingPriorityLabel
					)
				);

				if ($selectedCartScenario !== null)
				{
					// The selected scenario is available.
					$shippingEstimatorOptions['selectedProviderId'] = $selectedShippingProviderId;
					$shippingEstimatorOptions['selectedPriorityLabel'] = $selectedShippingPriorityLabel;
					// Currently if we get back too many shipping options, the selected one might
					// not be part of the returned options on the view since we limit our results to 8
					// For now, a selected shipping option will be added as a key to the $shippingEstimatorOptions
					$shippingEstimatorOptions['selectedShippingOption'] = static::formartCartScenarioAsShippingOption($selectedCartScenario);
				} else {
					// The selected shipping option is not available.
					array_push(
						$shippingEstimatorOptions['messages'],
						array(
							'code' => 'WARN',
							'message' => Yii::t(
								'cart',
								'Shipping option unavailable. Please choose another shipping option.'
							)
						)
					);
				}
			}

			// Find store pickup and remove from array if found.
			// We must do this after we check to see if store
			// pickup was previously selected
			foreach ($arrCartScenario as $key => $cartScenario)
			{
				if ($cartScenario['module'] === 'storepickup')
				{
					// Setup message to show end user
					$strMessage = sprintf('We also offer %s. Proceed to checkout for complete details', $cartScenario['providerLabel']);
					array_push(
						$shippingEstimatorOptions['messages'],
						array(
							'code' => 'INFOTOP',
							'message' => Yii::t('cart', $strMessage)
						)
					);

					// remove store pickup from options
					unset($arrCartScenario[$key]);

					// there can be only one storepickup scenario
					// so no need to continue the loop
					break;
				}
			}

			// Apply the maximum shipping options limit to the cart scenarios.
			if (sizeof($arrCartScenario) > self::MAX_SHIPPING_OPTIONS)
			{
				$totalNumberOfOptions = sizeof($arrCartScenario);

				// In order to prevent writing "1 more shipping options"
				// (because it's not grammatically correct) we actually show 1
				// less than the limit.
				$arrCartScenario = array_slice($arrCartScenario, 0, self::MAX_SHIPPING_OPTIONS - 1);

				array_push(
					$shippingEstimatorOptions['messages'],
					array(
						'code' => 'INFO',
						'message' => Yii::t(
							'cart',
							'{number} more shipping options. Proceed to checkout for complete options.',
							array(
								'{number}' => $totalNumberOfOptions - sizeof($arrCartScenario)
							)
						)
					)
				);
			}

			// Format the cart scenarios into an array of shipping options.
			$shippingEstimatorOptions['shippingOptions'] =
				self::formatCartScenariosAsShippingOptions($arrCartScenario);
		}

		return $shippingEstimatorOptions;
	}
}
