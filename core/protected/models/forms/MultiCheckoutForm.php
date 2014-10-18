<?php

// Extends CheckoutForm to handle different validation scenarios

class MultiCheckoutForm extends CheckoutForm
{
	const USA = 224;
	const CANADA = 39;
	const GREATBRITAIN = 223;
	const AUSTRALIA = 13;

	/**
	 * @var the last successfully validated scenario
	 */
	public $passedScenario;

	/*
	 * Validation rules
	 */
	public function rules()
	{
		return array(

			array(
				'contactEmail, contactEmailConfirm,
				recipientName, contactFirstName, contactLastName, contactCompany, contactPhone,
				pickupPerson, pickupFirstName, pickupLastName, pickupPersonEmail, pickupPersonPhone,
				intShippingAddress, intBillingAddress, billingSameAsShipping,
				shippingFirstName, shippingLastName,
				shippingAddress1, shippingAddress2, shippingCity, shippingState, shippingPostal, shippingCountry, shippingCountryCode,
				billingAddress1, billingAddress2, billingCity, billingState, billingPostal, billingCountry,
				shippingProvider, shippingPriority,
				paymentProvider,
				cardNumber, cardNumberLast4, cardExpiry, cardExpiryMonth, cardExpiryYear, cardType, cardCVV, cardNameOnCard,
				acceptTerms,
				objAddresses,
				shippingLabel, billingLabel, shippingResidential, billingResidential,
				promoCode, orderNotes, receiveNewsletter,
				createPassword, createPassword_repeat,
				passedScenario', 'safe'),

			array('contactEmail', 'required', 'on' => 'Login, StorePickup, Shipping, ShippingOptions, Payment, PaymentSim, PaymentStorePickup, PaymentStorePickupCC, Confirmation, ConfirmationSim, ConfirmationStorePickup, ConfirmationStorePickupCC'),

			array('shippingFirstName, shippingLastName, contactPhone',
				'required', 'on' => 'StorePickup, Shipping, ShippingOptions, Payment, PaymentSim, PaymentStorePickup, PaymentStorePickupCC, Confirmation, ConfirmationSim, ConfirmationStorePickup, ConfirmationStorePickupCC'),

			array('shippingAddress1, shippingCity, shippingCountry',
				'validateShippingBlock', 'on' => 'CalculateShipping, Shipping, ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('billingAddress1, billingCity, billingCountry',
				'validateBillingBlock','on' => 'Payment, PaymentSim, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim'),

			array('shippingState', 'validateState', 'on' => 'CalculateShipping, Shipping, ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('shippingPostal',
				'validatePostal','on' => 'CalculateShipping, Shipping, ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('shippingProvider, shippingPriority', 'required', 'on' => 'ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('billingState', 'validateState', 'on' => 'Payment, PaymentSim, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim'),

			array('billingPostal', 'validatePostal', 'on' => 'Payment, PaymentSim, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim'),

			array('paymentProvider','required', 'on' => 'Payment, PaymentSim, PaymentStorePickup, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim, ConfirmationStorePickup, ConfirmationStorePickupCC'),

			array('cardNumber, cardExpiryMonth, cardExpiryYear, cardType, cardCVV, cardNameOnCard',
				'validateCard', 'on' => 'Payment, PaymentStorePickupCC, Confirmation'),

			array('cardCVV', 'length', 'max' => 4, 'on' => 'Payment, PaymentStorePickupCC, Confirmation'),

			array('contactPhone', 'length', 'min' => 7, 'max' => 32),

			// email has to be a valid email address
			array('contactEmail', 'email'),
			array('contactEmail_repeat', 'safe'),
			array('contactEmail_repeat', 'validateEmailRepeat', 'on' => 'Login'),

			array('acceptTerms','required', 'requiredValue' => 1,
				'message' => Yii::t('global','You must accept Terms and Conditions'),
				'on' => 'Confirmation, ConfirmationSim, ConfirmationStorePickup'),

			array('createPassword', 'length', 'max' => 255),
			array('createPassword', 'compare', 'on' => 'formSubmitCreatingAccount'),
			array('createPassword_repeat', 'safe'),

		);

	}


	/**
	 * Until we figure out a better way to determine what country
	 * addresses require the state field, we will force validation
	 * for the ones we know should have it only
	 *
	 * @param $attributeName
	 * @param $params
	 * @return void
	 */
	public function validateState($attributeName, $params)
	{
		switch ($attributeName)
		{
			case 'shippingState':
				$objCountry = Country::LoadByCode($this->shippingCountry);
				break;
			case 'billingState':
				$objCountry = Country::LoadByCode($this->billingCountry);
				break;
			default:
				// Cannot validate any other attributes.
				return;
		}

		if ($objCountry === null)
		{
			// Country isn't valid, can't validate the state!
			return;
		}

		$countriesToValidateState = array(
			self::USA,
			self::CANADA,
			self::AUSTRALIA,
		);

		if (in_array($objCountry->id, $countriesToValidateState) === false)
		{
			// Do not attempt to validate the state.
			return;
		}

		if (empty($this->$attributeName) === true)
		{
			$this->addError(
				$attributeName,
				Yii::t(
					'yii',
					'{attributeName} cannot be blank.',
					array('{attributeName}' => $this->getattributeLabel($attributeName))
				)
			);
		} else {
			$objState = State::LoadByCode($this->$attributeName, $objCountry->id);

			if ($objState === null)
			{
				$this->addError(
					$attributeName,
					Yii::t(
						'yii',
						'{attributeName} is invalid.',
						array('{attributeName}' => $this->getattributeLabel($attributeName))
					)
				);
			}
		}
	}

	/**
	 * Return shipping address as one line string
	 *
	 * @return string
	 */
	public function getStrShippingAddress()
	{
		$str = '';

		$str .= $this->shippingAddress1 . ', ';
		$str .= $this->shippingAddress2 ? $this->shippingAddress2 : '';
		$str .= $this->shippingCity . ', ';
		$str .= $this->shippingState ? $this->shippingState . ', ' : '';
		if (is_numeric($this->shippingCountry) === true)
		{
			$str .= Country::CodeById($this->shippingCountry) . ' ';
		}
		else
		{
			$str .= $this->shippingCountry ? $this->shippingCountry . ' ' : '';
		}

		$str .= $this->shippingPostal;

		return $str;
	}

	/**
	 * Return billing address as one line string
	 *
	 * @return string
	 */
	public function getStrBillingAddress()
	{
		$str = '';

		$str .= $this->billingAddress1 . ', ';
		$str .= $this->billingAddress2 ? $this->billingAddress2 : '';
		$str .= $this->billingCity . ', ';
		$str .= $this->billingState ? $this->billingState . ', ' : '';
		if (is_numeric($this->billingCountry) === true)
		{
			$str .= Country::CodeById($this->billingCountry) . ' ';
		}
		else
		{
			$str .= $this->billingCountry ? $this->billingCountry . ' ' : '';
		}

		$str .= $this->billingPostal;

		return $str;
	}

	/**
	 * Return shipping address as formatted html
	 *
	 * @return string
	 */

	public function getHtmlShippingAddress()
	{
		$str = '';
		$str .= $this->shippingAddress1 . '<br>';
		$str .= $this->shippingAddress2 ? $this->shippingAddress2 . '<br>' : '';
		$str .= $this->shippingCity. ', ';
		$str .= $this->shippingState ? $this->shippingState . ', ' : '';
		$str .= $this->shippingPostal ? $this->shippingPostal . '<br>' : '';
		$str .= _xls_country() === $this->shippingCountry ? '' : Country::CountryByCode($this->shippingCountry);

		return $str;
	}

	/**
	 * Return shipping address as formatted html
	 *
	 * @return string
	 */

	public function getHtmlBillingAddress()
	{
		if ($this->billingSameAsShipping == 1)
			return $this->getHtmlShippingAddress();

		$str = '';
		$str .= $this->billingAddress1 . '<br>';
		$str .= $this->billingAddress2 ? $this->billingAddress2 . '<br>' : '';
		$str .= $this->billingCity. ', ';
		$str .= $this->billingState ? $this->billingState . ', ' : '';
		$str .= $this->billingPostal ? $this->billingPostal . '<br>' : '';
		$str .= _xls_country() === $this->billingCountry ? '' : Country::CountryByCode($this->billingCountry). '<br>';

		return $str;
	}


	/**
	 * Populate the address fields as required to pass validation
	 *
	 * @param $intAddressId
	 * @param string $str
	 * @return void
	 */

	public function fillAddressFields($intAddressId, $str = 'billing')
	{
		$objAddress = CustomerAddress::model()->findByPk($intAddressId);

		if (!$objAddress instanceof CustomerAddress)
		{
			Yii::log('No address found with id: '.$intAddressId, 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return;
		}


		switch ($str)
		{
			case 'billing':
				$this->billingAddress1 = $objAddress->address1;
				$this->billingAddress2 = $objAddress->address2;
				$this->billingCity = $objAddress->city;
				$this->billingState = State::CodeById($objAddress->state_id);
				$this->billingPostal = $objAddress->postal;
				$this->billingCountry = Country::CodeById($objAddress->country_id);
				break;

			case 'shipping':
				$this->shippingFirstName = $objAddress->first_name;
				$this->shippingLastName = $objAddress->last_name;
				$this->shippingAddress1 = $objAddress->address1;
				$this->shippingAddress2 = $objAddress->address2;
				$this->shippingCity = $objAddress->city;
				$this->shippingState = State::CodeById($objAddress->state_id);
				$this->shippingPostal = $objAddress->postal;
				$this->shippingCountry = Country::CodeById($objAddress->country_id);
				break;
		}
	}

	public static function loadFromSessionOrNew()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return new MultiCheckoutForm();
		}

		return $checkoutForm;
	}

	public static function loadFromSession()
	{
		$checkoutFormAttributes = Yii::app()->session->get(self::$sessionKey);
		if ($checkoutFormAttributes === null)
		{
			return null;
		}

		$checkoutForm = new MultiCheckoutForm();
		$checkoutForm->attributes = $checkoutFormAttributes;
		return $checkoutForm;
	}

	/**
	 * Save the checkout form to the user's session.
	 * @param CheckoutForm $checkoutForm A partially completed checkoutForm.
	 * @return void
	 */
	public static function saveToSession($checkoutForm)
	{
		Yii::app()->session[self::$sessionKey] = $checkoutForm->attributes;
	}

	/**
	 * Returns the shipping country code from the session.
	 * @return string|null The shipping provider ID thast the cart will ship using.
	 */
	public static function getShippingProviderFromSession()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return $checkoutForm->shippingProvider;
	}

	/**
	 * Returns the shipping country code from the session.
	 * @return string|null The shipping priority label that the cart will ship using.
	 */
	public static function getShippingPriorityFromSession()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return $checkoutForm->shippingPriority;
	}

	/**
	 * Returns whether the user has made a choice about their shipping provider.
	 * @return boolean True if the user has chosen a shipping provider, false
	 * otherwise.
	 */
	public static function hasSelectedShippingProvider()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return false;
		}

		return (
			$checkoutForm->shippingProvider !== null &&
			$checkoutForm->shippingPriority !== null
		);
	}

	/**
	 * Returns the shipping country code from the session.
	 * @return string|null The country code that the cart will ship to.
	 */
	public static function getShippingCountryCodeFromSession()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return $checkoutForm->shippingCountry;
	}

	/**
	 * Returns the shipping postal code from the session.
	 * @return string|null The postal code that the cart will ship to.
	 */
	public static function getShippingPostalFromSession()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return $checkoutForm->shippingPostal;
	}

	/**
	 * Returns the shipping city from the session.
	 * @return string|null The city that the cart will ship to.
	 */
	public static function getShippingCityFromSession()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return $checkoutForm->shippingCity;
	}

	/**
	 * Returns the shipping state from the session.
	 * @return string|null The state code that the cart will ship to.
	 */
	public static function getShippingStateFromSession()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return null;
		}

		return $checkoutForm->shippingState;
	}

	/**
	 * Save the provided cart scenario as the selected one in the session.
	 * @param array $cartScenario A cart scenario.
	 * @see Shipping::getCartScenarios.
	 * @return void
	 */
	public static function saveSelectedCartScenario($cartScenario)
	{
		$checkoutForm = self::loadFromSessionOrNew();
		$checkoutForm->shippingProvider = $cartScenario['providerId'];
		$checkoutForm->shippingPriority = $cartScenario['priorityLabel'];
		self::saveToSession($checkoutForm);
	}

	/**
	 * Returns if the In Store Pickup is active.
	 *
	 * @return bool true if in-store pickup option is active
	 */
	public function isInStorePickupActive()
	{
		return Modules::isActive("storepickup", "shipping");
	}
}
