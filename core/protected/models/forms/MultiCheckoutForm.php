<?php

// Extends CheckoutForm to handle different validation scenarios

class MultiCheckoutForm extends CheckoutForm
{
	const USA = 224;
	const CANADA = 39;
	const GREATBRITAIN = 223;
	const AUSTRALIA = 13;

	/**
	 * @var The string to translate when a credit card type is not enabled.
	 */
	const DISABLED_CARD_TYPE = '{card type} card type is not supported.';

	/**
	 * @var the last successfully validated scenario
	 */
	public $passedScenario;


	/**
	 * @var array attributes of the selected payment method's subform, if applicable
	 */
	public $subFormModel;


	/**
	 * @var boolean - determines whether or not to display a helpful message to the end
	 * user in the event the tax mode changes (inclusive to exclusive or vice versa)
	 * based on the given shipping destination
	 */
	public $hasTaxModeChanged;

	/**
	 * Validation rules
	 */
	public function rules()
	{
		return array(

			array(
				'contactEmail, contactEmailConfirm,
				recipientName, contactFirstName, contactLastName, contactCompany, contactPhone, company,
				pickupPerson, pickupFirstName, pickupLastName, pickupPersonEmail, pickupPersonPhone,
				intShippingAddress, intBillingAddress, billingSameAsShipping,
				shippingFirstName, shippingLastName,
				shippingAddress1, shippingAddress2, shippingCity, shippingState, shippingStateCode, shippingPostal, shippingCountry, shippingCountryCode, shippingPhone, shippingCompany
				billingAddress1, billingAddress2, billingCity, billingState, billingStateCode, billingPostal, billingCountry, billingCountryCode,
				shippingProvider, shippingPriority,
				paymentProvider,
				cardNumber, cardNumberLast4, cardExpiry, cardExpiryMonth, cardExpiryYear, cardType, cardCVV, cardNameOnCard,
				acceptTerms,
				objAddresses,
				shippingLabel, billingLabel, shippingResidential, billingResidential,
				promoCode, orderNotes, receiveNewsletter,
				createPassword, createPassword_repeat,
				passedScenario, subFormModel, hasTaxModeChanged, debug', 'safe'),

			array('contactEmail', 'required', 'on' => 'Login, StorePickup, Shipping, ShippingOptions, Payment, PaymentSim, PaymentStorePickup, PaymentStorePickupCC, Confirmation, ConfirmationSim, ConfirmationStorePickup, ConfirmationStorePickupCC'),

			array('shippingFirstName, shippingLastName, contactPhone',
				'required', 'on' => 'StorePickup, Shipping, ShippingOptions, Payment, PaymentSim, PaymentStorePickup, PaymentStorePickupCC, Confirmation, ConfirmationSim, ConfirmationStorePickup, ConfirmationStorePickupCC'),

			array('shippingAddress1, shippingCity, shippingCountry',
				'validateShippingBlock', 'on' => 'CalculateShipping, Shipping, ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('billingAddress1, billingCity, billingCountry',
				'validateBillingBlock','on' => 'Payment, PaymentSim, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim'),

			array('shippingState', 'StateValidator', 'on' => 'CalculateShipping, Shipping, ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('shippingPostal',
				'validatePostal','on' => 'CalculateShipping, Shipping, ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('shippingProvider, shippingPriority', 'required', 'on' => 'ShippingOptions, Payment, PaymentSim, Confirmation, ConfirmationSim'),

			array('billingState', 'StateValidator', 'on' => 'Payment, PaymentSim, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim'),

			array('billingPostal', 'validatePostal', 'on' => 'Payment, PaymentSim, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim'),

			array('paymentProvider','required', 'on' => 'Payment, PaymentSim, PaymentStorePickup, PaymentStorePickupCC, PaymentStorePickupSimCC, Confirmation, ConfirmationSim, ConfirmationStorePickup, ConfirmationStorePickupCC'),

			// Credit card validation.
			array(
				'cardNumber, cardCVV, cardExpiryMonth, cardExpiryYear, cardNameOnCard',
				'required',
				'on' => 'Payment, PaymentStorePickupCC, Confirmation'
			),
			array(
				'cardType',
				'validateCardType',
				'on' => 'Payment, PaymentStorePickupCC, Confirmation'
			),
			array(
				'cardNumber',
				'validateCardNumber',
				'on' => 'Payment, PaymentStorePickupCC, Confirmation'
			),
			array(
				'cardCVV',
				'validateCardCVV',
				'on' => 'Payment, PaymentStorePickupCC, Confirmation'
			),
			array(
				'cardExpiryDate',
				'validateCardExpiryDate',
				'on' => 'Payment, PaymentStorePickupCC, Confirmation'
			),

			array('contactPhone', 'length', 'min' => 7, 'max' => 32),

			// email has to be a valid email address
			array('contactEmail', 'email'),
			array('contactEmail_repeat', 'safe'),
			array('contactEmail_repeat', 'validateEmailRepeat', 'on' => 'Login'),

			array('acceptTerms','required', 'requiredValue' => 1,
				'message' => Yii::t('global', 'You must accept Terms and Conditions'),
				'on' => 'Confirmation, ConfirmationSim, ConfirmationStorePickup'),

			array('createPassword', 'length', 'max' => 255),
			array('createPassword', 'compare', 'on' => 'formSubmitCreatingAccount'),
			array('createPassword_repeat', 'safe'),

		);

	}

	/**
	 * Only validate the zip / postal code for countries
	 * that have a defined postcode pattern matching rule
	 * in the db
	 * @param $attribute The attribute name.
	 * @param $params Additional parameters defined in the rules.
	 * @return void
	 */
	public function validatePostal($attribute, $params)
	{
		$validate = false;
		$objCountries = Country::model()->withpostal()->findAll();

		if ($attribute == 'shippingPostal')
		{
			$country = $this->shippingCountry;
		}

		if ($attribute == 'billingPostal')
		{
			$country = $this->billingCountry;
		}

		if (empty($country))
		{
			return;
		}

		foreach ($objCountries as $objCountry)
		{
			if ($country === $objCountry->id)
			{
				$validate = true;
				break;
			}
		}

		if ($validate === true)
		{
			parent::validatePostal($attribute, $params);
		}

		return;
	}

	/**
	 * Returns the ECC validator based on the attributes in this form.
	 *
	 * @return null|ECCValidator An instance of ECCValidator.
	 */
	protected function getCardValidator()
	{
		if (empty($this->paymentProvider))
		{
			Yii::log(
				'Unable to get card validator: paymentProvider is empty',
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return null;
		}

		$objPaymentModule = Modules::model()->findByPk($this->paymentProvider);

		if (Yii::app()->getComponent($objPaymentModule->module)->uses_credit_card === false)
		{
			Yii::log(
				'Unable to get card validator: paypment provider does not use credit card',
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return null;
		}

		if (empty($this->cardType))
		{
			Yii::log(
				'Unable to get card validator: cardType is empty.',
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return null;
		}

		$objCreditCard = CreditCard::model()->findByAttributes(
			array('label' => $this->cardType)
		);

		if ($objCreditCard === null)
		{
			Yii::log(
				sprintf('Unable to get card validator: no such card type found: %s', $this->cardType),
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return null;
		}

		Yii::import('ext.validators.ECCValidator');
		$validatorFormat = 'ECCValidator::' . $objCreditCard->validfunc;
		if (defined($validatorFormat) === false)
		{
			Yii::log(
				sprintf('Unable to get card validator: no validator such validator: ', $validatorFormat),
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return null;
		}

		$cc = new ECCValidator();
		$cc->format = array(constant($validatorFormat));
		return $cc;
	}

	/**
	 * Check the credit card type.
	 * @param $attribute The attribute name.
	 * @param $params Additional parameters defined in the rules.
	 * @return void
	 */
	public function validateCardType($attribute, $params)
	{
		if (empty($this->cardType) === true)
		{
			// If the card type isn't sent, we allow it and rely on the payment
			// processor to decline if invalid.
			Yii::log(
				'Unable to validate card type - card type is empty.',
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return;
		}

		$arrEnabledCreditCardLabel = array_map(
			function ($creditCard) {
				return $creditCard->label;
			},
			CreditCard::model()->enabled()->findAll()
		);

		if (in_array($this->cardType, $arrEnabledCreditCardLabel) === false)
		{
			$this->addError(
				$attribute,
				Yii::t(
					'checkout',
					static::DISABLED_CARD_TYPE,
					array('{card type}' => $this->cardType)
				)
			);
		}
	}

	/**
	 * Check the credit card number.
	 * @param $attribute The attribute name.
	 * @param $params Additional parameters defined in the rules.
	 * @return void
	 */
	public function validateCardNumber($attribute, $params)
	{
		$validator = static::getCardValidator();
		if ($validator === null)
		{
			$this->addError(
				$attribute,
				Yii::t(
					'checkout',
					'Unsupported card type.'
				)
			);
			return;
		}

		if($validator->validateNumber($this->cardNumber) === false)
		{
			$this->addError(
				$attribute,
				Yii::t(
					'checkout',
					'Invalid Card Number or Type mismatch.'
				)
			);
		} else {
			Yii::log(
				sprintf('Validated cardNumber as %s', $this->cardType),
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
		}
	}

	/**
	 * Check the credit card CVV.
	 * @param $attribute The attribute name.
	 * @param $params Additional parameters defined in the rules.
	 * @return void
	 */
	public function validateCardCVV($attribute, $params)
	{
		$validator = static::getCardValidator();
		if ($validator === null)
		{
			Yii::log(
				'Unable to validate card CVV.',
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return;
		}

		if ($validator->validateCVV($this->cardCVV) === false)
		{
			$this->addError(
				$attribute,
				Yii::t(
					'checkout',
					'Invalid CVV or type mismatch.'
				)
			);
		} else {
			Yii::log(
				sprintf('Validated cardCVV as %s', $this->cardType),
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
		}
	}

	/**
	 * Check the credit card expiry date.
	 * @param $attribute The attribute name.
	 * @param $params Additional parameters defined in the rules.
	 * @return void
	 */
	public function validateCardExpiryDate($attribute, $params)
	{
		$validator = static::getCardValidator();
		if ($validator === null)
		{
			Yii::log(
				'Unable to validate card expiry date.',
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			return;
		}

		if ($validator->validateDate($this->cardExpiryMonth, $this->cardExpiryYear) === false)
		{
			$this->addError(
				$attribute,
				Yii::t(
					'checkout',
					'Invalid expiry date.'
				)
			);
		} else {
			Yii::log(
				sprintf('Validated cardExpiryDate as %s', $this->cardType),
				'info',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
		}
	}

	/**
	 * Return shipping address as one line string
	 *
	 * @return string
	 */
	public function getStrShippingAddress()
	{
		// ensure address is up to date
		if (empty($this->intShippingAddress) === false)
		{
			$this->fillAddressFields($this->intShippingAddress, 'shipping');
		}

		$str = '';

		$str .= $this->shippingAddress1 . ', ';
		$str .= $this->shippingAddress2 ? $this->shippingAddress2.' ': '';
		$str .= $this->shippingCity . ', ';
		$str .= $this->shippingStateCode ? $this->shippingStateCode . ', ' : '';
		$str .= $this->shippingCountryCode . ' ';
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
		if ($this->billingSameAsShipping == 1)
		{
			return $this->getStrShippingAddress();
		}

		$str = '';

		$str .= $this->billingAddress1 . ', ';
		$str .= $this->billingAddress2 ? $this->billingAddress2.' ': '';
		$str .= $this->billingCity . ', ';
		$str .= $this->billingStateCode ? $this->billingStateCode . ', ' : '';
		$str .= $this->billingCountryCode . ' ';
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
		$str .= $this->shippingAddress2 ? $this->shippingAddress2 . '<br>' : ' ';
		$str .= $this->shippingCity. ', ';
		$str .= $this->shippingStateCode ? $this->shippingStateCode . ', ' : '';
		$str .= $this->shippingPostal ? $this->shippingPostal . '<br>' : '';

		// Only show the country if different from the store default.
		if ($this->shippingCountry != Yii::app()->params['DEFAULT_COUNTRY'])
		{
			$str .= Country::CountryById($this->shippingCountry);
		}

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
		{
			return $this->getHtmlShippingAddress();
		}

		$str = '';
		$str .= $this->billingAddress1 . '<br>';
		$str .= $this->billingAddress2 ? $this->billingAddress2 . '<br>' : '';
		$str .= $this->billingCity. ', ';
		$str .= $this->billingStateCode ? $this->billingStateCode . ', ' : '';
		$str .= $this->billingPostal ? $this->billingPostal . '<br>' : '';

		// Only show the country if different from the store default.
		if ($this->billingCountry != Yii::app()->params['DEFAULT_COUNTRY'])
		{
			$str .= Country::CountryById($this->billingCountry);
		}

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
				$this->billingState = $objAddress->state_id;
				$this->billingPostal = $objAddress->postal;
				$this->billingCountry = $objAddress->country_id;
				break;

			case 'shipping':
				$this->shippingFirstName = $objAddress->first_name;
				$this->shippingLastName = $objAddress->last_name;
				$this->shippingCompany = $objAddress->company;
				$this->shippingAddress1 = $objAddress->address1;
				$this->shippingAddress2 = $objAddress->address2;
				$this->shippingCity = $objAddress->city;
				$this->shippingState = $objAddress->state_id;
				$this->shippingPostal = $objAddress->postal;
				$this->shippingCountry = $objAddress->country_id;
				$this->shippingResidential = $objAddress->residential;
				if (isset($objAddress->phone) && $objAddress->phone !== '')
				{
					// the intended behaviour is to always set the shipping address
					// phone number (if it exists) as the contact number...
					$this->contactPhone = $objAddress->phone;
				}
				else
				{
					// ...otherwise we set it to the customer's main phone number
					$this->contactPhone = $objAddress->customer->mainphone;
				}
				break;
		}
	}


	/**
	 * Clear specified address fields from form
	 *
	 * @param string $str
	 * @return void
	 */
	public function clearAddressFields($str = 'shipping')
	{
		switch ($str)
		{
			case 'shipping':
				$this->intShippingAddress = null;
				$this->billingSameAsShipping = null;
				$this->shippingFirstName = null;
				$this->shippingLastName = null;
				$this->shippingAddress1 = null;
				$this->shippingAddress2 = null;
				$this->shippingCity = null;
				$this->shippingStateCode = null;
				$this->shippingCountry = null;
				$this->shippingPostal = null;
				$this->shippingResidential = null;
				$this->contactPhone = null;
				break;

			case 'billing':
				$this->intBillingAddress = null;
				$this->billingAddress1 = null;
				$this->billingAddress2 = null;
				$this->billingCity = null;
				$this->billingStateCode = null;
				$this->billingCountry = null;
				$this->billingPostal = null;
				break;
		}
	}

	/**
	 * Load an instance of MultiCheckoutForm from the user's session. If
	 * there's not one in the session, create a new one.
	 * TODO: Should probably be renamed to getFromSessionOrNew().
	 * @return MultiCheckoutForm A CheckoutForm object.
	 */
	public static function loadFromSessionOrNew()
	{
		$checkoutForm = self::loadFromSession();
		if ($checkoutForm === null)
		{
			return new MultiCheckoutForm();
		}

		return $checkoutForm;
	}

	/**
	 * Load an instance of MultiCheckoutForm from the user's session.
	 * TODO: Should probably be renamed to getFromSession().
	 * @return CheckoutForm A CheckoutForm object.
	 */
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
	 * @return string|null The shipping provider ID that the cart will ship using.
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
	 * See if the passed id is a match for
	 * an address in objAddresses
	 *
	 * @param $intAddressId
	 * @return bool
	 */
	public function addressBelongsToUser($intAddressId)
	{
		if ($intAddressId === null)
		{
			return false;
		}

		foreach ($this->objAddresses as $address)
		{
			if ($intAddressId === $address->id)
			{
				return true;
			}
		}

		return false;
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

	/**
	* This method checks to see if the customer's addresses should be displayed in the shipping option.
	*
	* @return bool true if the store pickup is not the only option available | false if store pickup should only
	* be displayed and not the addresses because it's the only option available in the admin by the store owner
	*/
	public function shouldDisplayShippingAddresses()
	{
		if (count(Modules::model()->shipping()->notStorePickup()->findAll()) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * This method checks if the customer has the In Store pickup option selected
	 *
	 * @return bool true if store pickup is selected | false otherwise
	 */
	public function isStorePickupSelected()
	{
		$shipping = Modules::model()->findByPk($this->shippingProvider);

		if (is_null($shipping))
		{
			return false;
		}

		if ($shipping->module === 'storepickup')
		{
			return true;
		}

		return false;
	}

	/**
	 * If a customer selects in-store pickup we clear
	 * all the fields in the checkoutForm related to another form
	 * of shipping and put in values that respects the in-store pickup
	 * shipping option.
	 *
	 * @return void
	 */
	public function fillFieldsForStorePickup()
	{
		// clear any existing shipping address info from the form
		$this->clearAddressFields();

		// No validations around the these values
		$this->shippingFirstName = $this->pickupFirstName;
		$this->shippingLastName = $this->pickupLastName;
		$this->shippingPhone = $this->pickupPersonPhone;
		$this->contactFirstName = $this->pickupFirstName;
		$this->contactLastName = $this->pickupLastName;
		$this->contactPhone = $this->pickupPersonPhone;

		$this->orderNotes = $this->_storePickupNotes();

		$this->cardNameOnCard = $this->shippingFirstName . ' ' . $this->shippingLastName;

		$obj = Modules::LoadByName('storepickup');
		$data = unserialize($obj->configuration);
		$this->shippingProvider = $obj->id;
		$this->shippingPriority = $data['offerservices'];

		$this->saveFormToSession();
	}

	/**
	 * Creates the string for the in-store pickup note
	 *
	 * @return string The in-store pickup note
	 */
	private function _storePickupNotes()
	{
		$str = 'Contact Phone number for In-Store Pickup: ' . $this->pickupPersonPhone;
		$str .= "\nContact Email for In-Store Pickup: ";
		$str .= $this->pickupPersonEmail ? $this->pickupPersonEmail : $this->contactEmail;
		$str .= "\n";

		return $str;
	}

	/**
	 * Cache needed information: checkoutform, shipping rates
	 *
	 * @return void
	 */
	public function saveFormToSession()
	{
		MultiCheckoutForm::saveToSession($this);
	}

	/**
	 * Updates the cart shipping address or billing address.
	 * Create address if required.
	 * TODO: This function could probably have a better name and might be
	 * better off on CheckoutForm.
	 *
	 * @param string $str
	 * @return bool
	 */
	public function updateAddressId($str = 'shipping')
	{
		$objAddress = null;
		$intAddressId = null;

		$objCart = Yii::app()->shoppingcart;

		// We need to validate the form. To make sure that we're not passing in an empty
		// checkoutForm or a form missing some values for the cart.
		if ($this->billingSameAsShipping == 0 && $this->validate() === false)
		{
			return false;
		}

		switch ($str)
		{
			case 'shipping':
				if (!is_null($this->intShippingAddress) && $this->intShippingAddress != 0)
				{
					$intAddressId = $this->intShippingAddress;
					$this->fillAddressFields($intAddressId, 'shipping');
				}

				else
				{
					$attributes = array(
						'customer_id' => $objCart->customer_id,
						'first_name' => $this->shippingFirstName,
						'last_name' => $this->shippingLastName,
						'address1' => $this->shippingAddress1 ? $this->shippingAddress1 : null,
						'address2' => $this->shippingAddress2 ? $this->shippingAddress2 : null,
						'city' => $this->shippingCity ? $this->shippingCity : null,
						'postal' => $this->shippingPostal ? $this->shippingPostal : null,
						'country_id' => isset($this->shippingCountry) ? $this->shippingCountry : null,
						'state_id' => isset($this->shippingState) ? $this->shippingState : null,
					);

					if (isset($this->shippingAddress1) == false)
					{
						// if address1 is blank, shopper has chosen store pickup
						$attributes['store_pickup_email'] = $this->pickupPersonEmail ? $this->pickupPersonEmail : $this->contactEmail;
					}
					else
					{
						$attributes['store_pickup_email'] = null;
					}

					Yii::log(
						"Find or create new Shipping address\n" . print_r($attributes, true),
						'info',
						'application.'.__CLASS__.".".__FUNCTION__
					);

					$objAddress = CustomerAddress::findOrCreate($attributes);

					$objAddress->address_label = $this->shippingLabel ? $this->shippingLabel : Yii::t('global', 'Unlabeled Address');
					$objAddress->phone = is_null($this->shippingPhone) === true ? $this->contactPhone : $this->shippingPhone;
					$objAddress->residential = $this->shippingResidential;
				}
				break;

			case 'billing':
				if ($this->billingSameAsShipping == 1)
				{
					// we should always have a shipping id before we have a billing id
					$intAddressId = $objCart->shipaddress_id;
				}

				elseif (is_null($this->intBillingAddress) === false && $this->intBillingAddress != 0)
				{
					$intAddressId = $this->intBillingAddress;
				}

				// billing address must have at least address1, city and country filled in
				elseif ($this->billingAddress1 && $this->billingCity && $this->billingCountry)
				{
					$continue = true;

					if (empty($this->billingAddress1) === true)
					{
						$this->addErrors(array('billing_address' => Yii::t('checkout', 'Billing Address cannot be blank')));
						$continue = false;
					}

					if (empty($this->billingCity) === true)
					{
						$this->addErrors(array('billing_city' => Yii::t('checkout', 'Billing City cannot be blank')));
						$continue = false;
					}

					if ($continue === false)
					{
						Yii::log('Billing address cannot be created or updated, information is missing', 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
						return false;
					}

					$attributes = array(
						'customer_id' => $objCart->customer_id,
						'address1' => $this->billingAddress1,
						'address2' => $this->billingAddress2,
						'city' => $this->billingCity,
						'postal' => $this->billingPostal,
						'country_id' => $this->billingCountry,
						'state_id' => $this->billingState,
					);

					Yii::log(
						"Find or create new Billing address\n" . print_r($attributes, true),
						'info',
						'application.'.__CLASS__.".".__FUNCTION__
					);

					$objAddress = CustomerAddress::findOrCreate($attributes);

					if (isset($objAddress->address_label) === false)
					{
						$objAddress->address_label = $this->billingLabel ? $this->billingLabel : Yii::t('global', 'Unlabeled Address');
					}

					if (isset($objAddress->first_name) === false)
					{
						$objAddress->first_name = $this->contactFirstName ? $this->contactFirstName : $this->shippingFirstName;
					}

					if (isset($objAddress->last_name) === false)
					{
						$objAddress->last_name = $this->contactLastName ? $this->contactLastName : $this->shippingLastName;
					}

					$objAddress->residential = $this->billingResidential ? $this->billingResidential : $objAddress->residential;
				}
				else
				{
					$objAddress = null;
					$intAddressId = null;
				}
				break;
		}

		if ($objAddress instanceof CustomerAddress)
		{
			if ($this->scenario === 'StorePickup')
			{
				$objAddress->setScenario('StorePickup');
			}

			if ($objAddress->save() === false)
			{
				$this->addErrors($objAddress->getErrors());
				Yii::log("Error creating $str address\n" . print_r($objAddress->getErrors(), true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}

			$intAddressId = $objAddress->id;
		}

		switch ($str)
		{
			case 'shipping':
				$objCart->shipaddress_id = $intAddressId;
				break;

			case 'billing':
				$objCart->billaddress_id = $intAddressId;
				$this->fillAddressFields($intAddressId);
				break;
		}

		if ($objCart->save() === false)
		{
			// TODO: We might want to add an error here.
			Yii::log("Error saving Cart:\n".print_r($objCart->getErrors()), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
		}
		else
		{
			$objCart->recalculateAndSave();
		}

		return true;
	}

	/**
	 * Creates a customer with the information from the checkoutForm
	 * It will then update the shopping cart by adding that customer
	 * id to it
	 *
	 * @return bool
	 */
	public function updateCartCustomerId()
	{
		$objCart = Yii::app()->shoppingcart;
		$intCustomerId = $objCart->customer_id;

		if (Yii::app()->user->isGuest && is_null($intCustomerId))
		{
			// Guest - not logged in.
			Yii::log(
				"Creating Guest account to complete checkout",
				'info',
				'application.'.__CLASS__.".".__FUNCTION__
			);

			// Create a new guest ID.
			$identity = new GuestIdentity();
			Yii::app()->user->login($identity, 300);
			$intCustomerId = $identity->getId();
			$objCustomer = Customer::model()->findByPk($intCustomerId);
			$objCustomer->first_name = $this->contactFirstName;
			$objCustomer->last_name = $this->contactLastName;
			$objCustomer->mainphone = $this->contactPhone;
			$objCustomer->email = $this->contactEmail;

			if ($objCart->shipaddress)
			{
				if ($objCart->shipping->isStorePickup)
				{
					$objCustomer->default_shipping_id = null;
				} else {
					$objCustomer->default_shipping_id = $objCart->shipaddress->id;
				}
			}

			if ($objCart->billaddress)
			{
				$objCustomer->default_billing_id = $objCart->billaddress->id;
			}

			if ($objCustomer->save() === false)
			{
				Yii::log("Error saving Guest:\n" . print_r($objCustomer->getErrors(), true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
				$this->addErrors($objCustomer->getErrors());

				return false;
			}
		} elseif (!is_null($intCustomerId)) {
			$objCustomer = Customer::model()->findByPk($intCustomerId);

			// Is this a registered customer or a guest?
			if ($objCustomer->record_type == Customer::GUEST)
			{
				// A 'logged in' guest.
				// Update information in case it was changed.
				$objCustomer->first_name = $this->contactFirstName;
				$objCustomer->last_name = $this->contactLastName;
				$objCustomer->mainphone = $this->contactPhone;
				$objCustomer->email = $this->contactEmail;
				if ($objCart->shipaddress)
				{
					if ($objCart->shipping->isStorePickup)
					{
						$objCustomer->default_shipping_id = null;
					} else {
						$objCustomer->default_shipping_id = $objCart->shipaddress->id;
					}
				}

				if ($objCart->billaddress)
				{
					$objCustomer->default_billing_id = $objCart->billaddress->id;
				}

				if (!$objCustomer->save())
				{
					Yii::log("Error updating Guest:\n" . print_r($objCustomer->getErrors(), true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
					$this->addErrors($objCustomer->getErrors());
					return false;
				}
			}
		}

		if ($objCart->shipaddress && is_null($objCart->shipaddress->customer_id))
		{
			$objCart->shipaddress->customer_id = $intCustomerId;
			$objCart->shipaddress->save();
		}

		if ($objCart->billaddress && is_null($objCart->billaddress->customer_id))
		{
			$objCart->billaddress->customer_id = $intCustomerId;
			$objCart->billaddress->save();
		}

		$objCart->assignCustomer($intCustomerId);
		return true;
	}

	/**
	 * When a user selects an address on the shipping address page the information gets
	 * updated to the checkout form object.
	 * @param $addressId
	 * @return bool false if it was not possible to change shipping and customer information
	 * true if it was possible.
	 */
	public function fetchCustomerShippingAddress($addressId)
	{
		// Should be moved to CustomerAddress.php
		$selectedAddress = CustomerAddress::findCustomerAddress(Yii::app()->user->id, $addressId);

		if ($selectedAddress instanceof CustomerAddress === false)
		{
			return false;
		}

		$this->intShippingAddress = $selectedAddress->id;
		$this->intBillingAddress = $selectedAddress->id;
		$this->fillAddressFields($selectedAddress->id, 'shipping');
		$this->shippingResidential = $selectedAddress->residential;

		// TODO: Verify that none the shippingFirstName can't never be null based
		// on the validations on CustomerAddress
		if (isset($this->shippingFirstName))
		{
			$this->contactFirstName = $this->shippingFirstName;
		}
		else
		{
			$this->contactFirstName = $this->shippingFirstName = $selectedAddress->first_name;
		}

		if (isset($this->shippingLastName))
		{
			$this->contactLastName = $this->shippingLastName;
		}
		else
		{
			$this->contactLastName = $this->shippingLastName = $selectedAddress->last_name;
		}

		$this->recipientName = $this->shippingFirstName . ' ' . $this->shippingLastName;
		$this->pickupPerson = $this->recipientName;
		$this->saveFormToSession();
		return true;
	}

	/**
	 * Get the correct route for end user returning to checkout within same session. We also re-populate
	 * the cart depending on the scenario in the rare event that Web Store initializes
	 * a new cart.
	 * @return null|string
	 */
	public function getCheckoutPoint()
	{
		$route = null;
		$this->setScenario($this->passedScenario);

		switch ($this->passedScenario)
		{
			// we passed Login -> shipping
			case 'Guest':
			case 'StorePickup':
			case 'PaymentStorePickupSimCC':
			case 'PaymentStorePickup':
				$route = '/checkout/shipping';
				break;

			case 'Existing':
				$route = '/checkout/shippingaddress';
				break;

			// any other valid scenarios -> shipping options
			case 'PaymentSim':
			case 'PaymentSimCC':
			case 'ShippingOptions':
			case 'Shipping':
				// If in store pickup is selected after we went through using a different
				// shipping option, redirect the user to the shipping page.
				if ($this->isStorePickupSelected())
				{
					$route = '/checkout/shipping';
				}
				elseif ($this->validate() &&
					$this->isStorePickupSelected() === false)
				{
					$this->updateAddressId('shipping');
					$route = '/checkout/shippingoptions';
				}
				else
				{
					$route = null;
				}
				break;

			default:
				// send to index/login
				$route = null;
		}

		if (Yii::app()->shoppingcart->customer_id === null && Yii::app()->user->id > 0)
		{
			// something weird happened and the logged in user is not
			// attached to the cart, so re-attach them
			Yii::app()->shoppingcart->customer_id = Yii::app()->user->id;
		}

		return $route;
	}


	/**
	 * Clear form of sensitive credit card data
	 *
	 * @return void
	 */
	public function clearCCdata()
	{
		$this->cardNumber = null;
		$this->cardNumberLast4 = null;
		$this->cardCVV = null;
		$this->cardExpiryMonth = null;
		$this->cardExpiryYear = null;
		$this->cardType = null;
		$this->saveFormToSession();
	}

	/**
	 * Return all payment modules that have a sub form
	 * such that the end user is prompted for input
	 *
	 * @return array $arrModuleForms
	 * Array of CForms for each payment method that has one
	 */
	public function getAlternativePaymentMethodsThatUseSubForms()
	{
		$arrModuleForms = parent::getAlternativePaymentMethodsThatUseSubForms();
		$arrRemoveIds = array();

		foreach ($arrModuleForms as $id => $form)
		{
			// get the input elements of this form
			$objElements = $form->getElements();

			// isolate the list into an array we can check
			$arrKeys = $objElements->getKeys();

			if (count($arrKeys) == 0)
			{
				// no input fields
				$arrRemoveIds[] = $id;
			}
		}

		foreach($arrRemoveIds as $id)
		{
			unset($arrModuleForms[$id]);
		}

		return $arrModuleForms;
	}

	/**
	 * Return the validation outcome of a payment method's subform
	 *
	 * @return bool
	 */
	public function handleSubForm()
	{
		$paymentSubFormModel = $this->getPaymentSubFormModel();

		if (is_null($paymentSubFormModel))
		{
			// method doesn't use a subform, no validation required

			// erase any old data
			$this->subFormModel = null;
			return true;
		}

		$this->subFormModel = $paymentSubFormModel->attributes = isset($_POST[get_class($paymentSubFormModel)]) ? $_POST[get_class($paymentSubFormModel)] : array();

		if ($paymentSubFormModel->validate())
		{
			Yii::log(sprintf('%s validation passed', get_class($paymentSubFormModel)), 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return true;
		}

		Yii::log(
			sprintf(
				"%s validation error\n %s",
				get_class($paymentSubFormModel),
				print_r($paymentSubFormModel->getErrors(), true)
			),
			'info',
			'application.'.__CLASS__.'.'.__FUNCTION__
		);

		$this->addErrors($paymentSubFormModel->getErrors());
		return false;
	}

	/**
	 * Return a new instance of the payment method's subform model object
	 * or null if the method does not have a subform
	 *
	 * @return null
	 */
	public function getPaymentSubFormModel()
	{
		$arrModuleForms = $this->getAlternativePaymentMethodsThatUseSubForms();
		if (isset($this->paymentProvider) && array_key_exists($this->paymentProvider, $arrModuleForms))
		{
			$objPaymentModule = Modules::model()->findByPk($this->paymentProvider);
			$objComponent = Yii::app()->getComponent($objPaymentModule->module);
			if (isset($objComponent->subform))
			{
				$paymentSubform = $objComponent->subform;
				$paymentSubFormModel = new $paymentSubform();
			}
		}
		else
		{
			$paymentSubFormModel = null;
		}

		return $paymentSubFormModel;
	}
}
