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
		Yii::app()->clientScript->registerCssFile($assets . '/css/wsshippingestimator.css');
		Yii::app()->clientScript->registerScriptFile($assets . '/js/WsShippingEstimator.js');

		// Use the shipping scenarios and shipping address in the session.
		$arrCartScenario = Shipping::loadCartScenariosFromSession();
		$checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		// We may wish to update the shipping options right away if we know the
		// cart has changed.
		if ($this->updateShippingOptions)
		{
			$updateOnLoad = true;
		} else {
			$updateOnLoad = false;
		}

		$wsShippingEstimatorOptions = self::getShippingEstimatorOptions(
			$arrCartScenario,
			$checkoutForm->shippingProvider,
			$checkoutForm->shippingPriority,
			$checkoutForm->shippingCity,
			$checkoutForm->shippingState,
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
			$arrShippingOption[] = array_intersect_key(
				$cartScenario,
				array_flip(
					// Keep these keys.
					array(
						'providerId',
						'priorityLabel',
						'shippingLabel',
						'formattedShippingPrice',
						'formattedCartTax',
						'formattedCartTotal',
					)
				)
			);
		}

		return $arrShippingOption;
	}

	/**
	 * Get the options required by WsShippingEstimator.js.
	 *
	 * @param array $arrCartScenario An array of cart scenarios @see
	 * Shipping::getCartScenarios.
	 * @param integer $selectedShippingProviderId The ID (xlsws_modules.id) of
	 * the selected shipping provider.
	 * @param string $selectedShippingPriotyLabel The label for the shipping
	 * priority. This value in combination with $selectedShippingProviderId
	 * describes which shipping option is selected.
	 * @param string $shippingCity The city that the cart is shipping to.
	 * @param string $shippingState The code for the state that the cart is shipping to.
	 * @param string $shippingCountryCode The code the country that the cart is shipping to.
	 * @param boolean $updateOnLoad Whether the shipping estimator should get updated estimates right away.
	 */
	public static function getShippingEstimatorOptions(
		$arrCartScenario,
		$selectedShippingProviderId,
		$selectedShippingPriorityLabel,
		$shippingCity,
		$shippingState,
		$shippingCountryCode,
		$updateOnLoad = false
	) {
		// Build up an associative array of the options required for the shipping estimator.
		$shippingEstimatorOptions = array(
			'class' => self::CSS_CLASS,
			'updateOnLoad' => CPropertyValue::ensureBoolean($updateOnLoad),
			'shippingCity' => $shippingCity,
			'shippingState' => $shippingState,
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
