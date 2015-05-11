<?php

/**
 * CheckoutForm class.
 * CheckoutForm is the data structure for keeping
 * checkout form data. It is used by the 'checkout' action of 'CartController'.
 */
class BaseCheckoutForm extends CFormModel
{
	// @var string The key for when an instance of CheckoutForm is stored in
	// the user's session.
	public static $sessionKey = 'checkoutform.cache';

	public $contactFirstName;
	public $contactLastName;
	public $contactCompany;
	public $contactPhone;
	public $contactEmail;
	public $contactEmail_repeat;
	public $createPassword;
	public $createPassword_repeat;
	public $receiveNewsletter;

	public $billingLabel;
	public $billingAddress1;
	public $billingAddress2;
	public $billingCity;
	public $billingStateCode;
	public $billingPostal;

	/**
	 * @var The billing country.
	 * When submitted from the view layer this is a country ID.
	 * When an instance of BaseCheckoutForm is passed to the shipping and
	 * payment modules this is a country code.
	 */
	public $billingCountry;

	public $billingSameAsShipping;
	public $billingResidential;

	public $shippingLabel;
	public $shippingFirstName;
	public $shippingLastName;
	public $shippingCompany;
	public $shippingAddress1;
	public $shippingAddress2;
	public $shippingCity;
	public $shippingStateCode;
	public $shippingPostal;

	/**
	 * @var The shipping country ID.
	 * When submitted from the view layer this is a country ID.
	 * When an instance of BaseCheckoutForm is passed to the shipping and
	 * payment modules this is a country code.
	 */
	public $shippingCountry;
	public $shippingResidential;
	public $shippingProvider;
	public $shippingPriority;

	public $promoCode;
	public $paymentProvider;

	public $pickupPerson;
	public $pickupFirstName;
	public $pickupLastName;
	public $pickupPersonEmail;
	public $pickupPersonPhone;
	public $recipientName;

	public $company;
	public $shippingPhone;

	public $orderNotes;
	public $acceptTerms;

	public $intShippingAddress;
	public $intBillingAddress;

	//If we are using a payment module with a credit card, we need these fields
	public $cardNumber;
	public $cardNumberLast4;
	public $cardExpiry;         // string following this pattern "MM/YY"
	public $cardExpiryMonth;
	public $cardExpiryYear;
	public $cardType;
	public $cardCVV;
	public $cardNameOnCard;

	//Address book for logged-in users to choose from
	public $objAddresses;

	//For PHPunit testing only, causes payment modules to return their submission string instead of success/decline
	//Note security risk since this exposes credit card and CVV fields
	public $debug = false;

	/**
	 * @return string
	 */
	public function __toString()
	{
		return "fn: ".$this->contactFirstName." ln: ".$this->contactLastName." b1: ".$this->billingAddress1." s1: ".$this->shippingAddress1;
	}

	/**
	 * Declares the validation rules.
	 * Note our following scenarios:
	 * formSubmitGuest - Not logged in, not filling out password fields
	 * formSubmitCreatingAccount - Not logged in, filled out password fields to create account
	 * formSubmitExistingAccount - User is already logged in before reaching checkout
	 * CalculateShipping - Used for AJAX call for Calculate shipping, since only certain fields are important then
	 *
	 */
	public function rules()
	{
		//Do we require a person to create an account
		if (_xls_get_conf('REQUIRE_ACCOUNT', 0))
		{
			$retArray = array('createPassword, createPassword_repeat', 'required', 'on' => 'formSubmitCreatingAccount, formSubmitGuest');
		}
		else
		{
			$retArray = array('createPassword, createPassword_repeat', 'safe');
		}

		return array(
			$retArray,
			array('shippingCountryCode, shippingLabel, billingLabel, shippingResidential, billingResidential, recipientName,
			contactFirstName , contactLastName, contactCompany, contactPhone, contactEmail, contactEmailConfirm, createPassword, createPassword_repeat ,
			receiveNewsletter, billingAddress1, billingAddress2, billingCity, billingState, billingPostal, billingCountry, billingCountryCode, billingSameAsShipping ,
			shippingFirstName, shippingLastName, shippingAddress1, shippingAddress2, shippingCity, shippingState, shippingPostal,
			shippingCountry, promoCode, shippingProvider, shippingPriority, paymentProvider, orderNotes, cardNumber, cardNumberLast4, cardExpiry,
			cardExpiryMonth, cardExpiryYear, cardType, cardCVV, cardNameOnCard, intShippingAddress, intBillingAddress, acceptTerms', 'safe'),

			array('contactFirstName, contactLastName, contactEmail, contactPhone',
				'required', 'on' => 'formSubmitGuest, formSubmitCreatingAccount'),

			array('billingAddress1, billingCity, billingPostal, billingCountry',
				'validateBillingBlock', 'on' => 'formSubmitGuest, formSubmitCreatingAccount'),

			array('shippingFirstName, shippingLastName, shippingAddress1, shippingCity, shippingCountry',
				'validateShippingBlock', 'on' => 'CalculateShipping, formSubmitGuest, formSubmitCreatingAccount, formSubmitExistingAccount, ShipToMe'),

			array('shippingCountry, shippingPostal',
				'validateShippingBlock', 'on' => 'MinimalShipping'),

			array('shippingPostal, billingPostal',
				'validatePostal', 'on' => 'CalculateShipping, formSubmitGuest, formSubmitCreatingAccount, formSubmitExistingAccount'),

			array('acceptTerms, shippingProvider, shippingPriority, paymentProvider','required',
				'on' => 'formSubmit, formSubmitGuest, formSubmitCreatingAccount, formSubmitExistingAccount'),

			array('cardNumber, cardExpiryMonth, cardExpiryYear, cardType, cardCVV, cardNameOnCard', 'validateCard',
				'on' => 'formSubmitGuest, formSubmitCreatingAccount, formSubmitExistingAccount'),

			array('cardCVV', 'length', 'max' => 4, 'on' => ' '),

			array('contactPhone', 'length', 'min' => 7, 'max' => 32),

			// email has to be a valid email address
			array('contactEmail', 'email'),
			array('contactEmail_repeat', 'safe'),
			array('contactEmail_repeat', 'validateEmailRepeat', 'on' => 'formSubmitGuest, formSubmitCreatingAccount'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),

			array('acceptTerms', 'required', 'requiredValue' => 1,
				'message' => Yii::t('global', 'You must accept Terms and Conditions'),
				'on' => 'formSubmit, formSubmitGuest, formSubmitCreatingAccount, formSubmitExistingAccount'),

			array('createPassword', 'length', 'max' => 255),
			array('createPassword', 'compare', 'on' => 'formSubmitCreatingAccount'),
			array('createPassword_repeat', 'safe'),
			array('createPassword, createPassword_repeat', 'PasswordLengthValidator', 'on' => 'formSubmitCreatingAccount'),

			array('shippingResidential', 'numerical', 'integerOnly' => true),
		);
	}


	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateBillingBlock($attribute, $params)
	{
		if ($this->billingSameAsShipping == 0)
		{
			if ($this->$attribute == '')
			{
				$this->addError(
					$attribute,
					Yii::t(
						'yii',
						'{attribute} cannot be blank.',
						array('{attribute}' => $this->getAttributeLabel($attribute)
						)
					)
				);
			}
		}

	}


	/**
	 * Determine whether to validate a shipping
	 * or billing address zip / postal code
	 *
	 * @param $attribute
	 * @param $params
	 * @return void
	 */
	public function validatePostal($attribute, $params)
	{
		if ($attribute == 'shippingPostal')
		{
			$this->validateShippingPostal($attribute);
		}

		if ($attribute == 'billingPostal')
		{
			$this->validateBillingPostal($attribute);
		}
	}


	/**
	 * Check for an entered shipping country and then execute validation function
	 *
	 * @param $attribute
	 * @return void
	 */
	protected function validateShippingPostal($attribute)
	{
		if (is_null($this->shippingCountry) === true)
		{
			return;
		}

		$this->postValidatePostal($attribute, $this->shippingCountry);
	}


	/**
	 * Check for an entered or existing billing country, or that
	 * the end user has declared their shipping and billing addresses
	 * are the same
	 *
	 * @param $attribute
	 * @return void
	 */
	protected function validateBillingPostal($attribute)
	{
		if (is_null($this->billingCountry) === true || $this->intBillingAddress > 0 || $this->billingSameAsShipping == 1)
		{
			return;
		}

		$this->postValidatePostal($attribute, $this->billingCountry);
	}


	/**
	 * Perform validation on the zip/postal code and populate model
	 * with error if necessary
	 *
	 * @param $attribute
	 * @return void
	 */
	protected function postValidatePostal($attribute, $country)
	{
		if (is_numeric($country) === true)
		{
			$objCountry = Country::Load($country);
		}
		else
		{
			$objCountry = Country::LoadByCode($country);
		}

		if ($objCountry instanceof Country === false)
		{
			Yii::log(
				sprintf('Shipping Country: %s is not a valid country', $this->shippingCountry),
				'error',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);

			$this->addError(
				$attribute,
				Yii::t(
					'yii',
					'{attribute} is invalid.',
					array('{attribute}' => $this->getAttributeLabel('shippingCountry'))
				)
			);
		}
		else
		{
			if ($this->$attribute == '')
			{
				$this->addError(
					$attribute,
					Yii::t(
						'yii',
						'{attribute} cannot be blank.',
						array('{attribute}' => $this->getAttributeLabel($attribute))
					)
				);
			}

			elseif (is_null($objCountry->zip_validate_preg) === false && _xls_validate_zip($this->$attribute, $objCountry->zip_validate_preg) === false)
			{
				$this->addError(
					$attribute,
					Yii::t(
						'yii',
						'{attribute} format is incorrect for this country.',
						array('{attribute}' => $this->getAttributeLabel($attribute))
					)
				);
			}
		}
	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateShippingBlock($attribute, $params)
	{
		if ($this->intShippingAddress == 0) //We haven't chosen from our address book
		{
			if ($this->$attribute == '')
			{
				$this->addError(
					$attribute,
					Yii::t(
						'yii',
						'{attribute} cannot be blank.',
						array('{attribute}' => $this->getAttributeLabel($attribute))
					)
				);
			}
		}

	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateEmailRepeat($attribute, $params)
	{
		if (Yii::app()->user->isGuest && $this->contactEmail != $this->contactEmail_repeat)
		{
			$this->addError('contactEmail_repeat', Yii::t('checkout', 'Email address does not match'));
		}
	}

	/**
	 * Check the credit card fields if required, also do LUHN 10 check on CC number
	 * @param $attribute
	 * @param $params
	 */
	public function validateCard($attribute, $params)
	{
		if (empty($this->paymentProvider))
		{
			return;
		}

		$objPaymentModule = Modules::model()->findByPk($this->paymentProvider);

		if (Yii::app()->getComponent($objPaymentModule->module)->advancedMode)
		{
			switch ($attribute)
			{
				case 'cardCVV':
					if ($this->cardCVV != '' && !is_null($this->cardType))
					{
						Yii::import('ext.validators.ECCValidator');
						$cc = new ECCValidator();

						$cc->format = array(constant('ECCValidator::'.$this->cardType));

						if (!$cc->validateCVV($this->cardCVV))
						{
							$this->addError(
								$attribute,
								Yii::t(
									'yii',
									'Invalid CVV or type mismatch',
									array('{attribute}' => $this->getAttributeLabel($attribute))
								)
							);
						}
					}

					//we purposely don't have a break here so it drops to the next check when blank

				case 'cardNumber':
					if ($this->cardNumber != '' && !is_null($this->cardType))
					{
						Yii::import('ext.validators.ECCValidator');
						$cc = new ECCValidator();

						$cc->format = array(constant('ECCValidator::'.$this->cardType));

						if(!$cc->validateNumber($this->cardNumber))
						{
							$this->addError(
								$attribute,
								Yii::t(
									'yii',
									'Invalid Card Number or Type mismatch',
									array('{attribute}' => $this->getAttributeLabel($attribute))
								)
							);
						}
					}

					//we purposely don't have a break here so it drops to the next check when blank

				default:
					if ($this->$attribute == '')
					{
						$this->addError(
							$attribute,
							Yii::t(
								'yii',
								'{attribute} cannot be blank.',
								array('{attribute}' => $this->getAttributeLabel($attribute))
							)
						);
					}
			}
		}
	}


	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'recipientName' => Yii::t('forms', 'Recipient Name'),
			'contactFirstName' => Yii::t('forms', 'First Name'),
			'contactLastName' => Yii::t('forms', 'Last Name'),
			'contactCompany' => Yii::t('forms', 'Company'),
			'contactPhone' => Yii::t('forms', 'Phone'),
			'contactEmail' => Yii::t('forms', 'Email Address'),
			'contactEmail_repeat' => Yii::t('forms', 'Email Address (confirm)'),
			'createPassword' => Yii::t('forms', 'Password'),
			'createPassword_repeat' => Yii::t('forms', 'Password (confirm)'),
			'receiveNewsletter' => Yii::t('forms', 'Allow us to send you emails about our products'),
			'billingLabel' => Yii::t('forms', 'Label for this address (i.e. Home, Work)'),
			'billingAddress1' => Yii::t('forms', 'Address'),
			'billingAddress2' => Yii::t('forms', 'Address 2 (optional)'),
			'billingCity' => Yii::t('forms', 'City'),
			'billingState' => Yii::t('forms', 'State/Province'),
			'billingPostal' => Yii::t('forms', 'Zip/Postal'),
			'billingCountry' => Yii::t('forms', 'Country'),
			'billingSameAsShipping' => Yii::app()->params['SHIP_SAME_BILLSHIP']
				? Yii::t('forms', 'We require your billing and shipping addresses to match')
				: Yii::t('forms', 'My shipping address is also my billing address'),
			'billingResidential' => Yii::t('forms', 'This is a residential address'),
			'shippingLabel' => Yii::t('forms', 'Label for this address (i.e. Home, Work)'),
			'shippingFirstName' => Yii::t('forms', 'First Name'),
			'shippingLastName' => Yii::t('forms', 'Last Name'),
			'shippingAddress1' => Yii::t('forms', 'Address'),
			'shippingAddress2' => Yii::t('forms', 'Address 2 (optional)'),
			'shippingCity' => Yii::t('forms', 'City'),
			'shippingState' => Yii::t('forms', 'State/Province'),
			'shippingPostal' => Yii::t('forms', 'Zip/Postal'),
			'shippingCountry' => Yii::t('forms', 'Country'),
			'shippingResidential' => Yii::t('forms', 'This is a residential address'),
			'promoCode' => Yii::t('forms', 'Promo Code'),
			'shippingProvider' => Yii::t('forms', 'Shipping Method'),
			'shippingPriority' => Yii::t('forms', 'Delivery Speed'),
			'orderNotes' => Yii::t('forms', 'Comments'),
			'acceptTerms' => Yii::t('forms', 'Accept Terms'),
			'verifyCode' => Yii::t('forms', 'Verification Code'),
			'cardNumber' => Yii::t('forms', 'Card Number'),
			'cardNumberLast4' => Yii::t('forms', 'Card Number Last 4 Digits'),
			'cardExpiry' => Yii::t('forms', 'Expiry Date'),
			'cardExpiryMonth' => Yii::t('forms', 'Expiry Month'),
			'cardExpiryYear' => Yii::t('forms', 'Expiry Year'),
			'cardType' => Yii::t('forms', 'Card Type'),
			'cardCVV' => Yii::t('forms', 'CVV'),
			'cardNameOnCard' => Yii::t('forms', 'Cardholder Name'),
		);
	}


	/**
	 * If the shopper has chosen an address from the address book, copy the values to the
	 * relevant address fields since they're needed for shipping and payment calculations
	 *
	 * @return void
	 */
	public function fillFieldsFromPreselect()
	{
		$arrObjAddresses = CustomerAddress::getActiveAddresses();
		$objCustomer = Customer::GetCurrent();

		if ($this->intShippingAddress > 0)
		{
			// We've picked a preset to ship to, so grab that info from the db.
			if (Yii::app()->shoppingcart->HasShippableGift)
			{
				$objAddresses = array_merge($arrObjAddresses, Yii::app()->shoppingcart->GiftAddress);
			}

			foreach ($arrObjAddresses as $objAddress)
			{
				if ($objAddress->id != $this->intShippingAddress)
				{
					continue;
				}

				$this->shippingFirstName = $objAddress->first_name;
				$this->shippingLastName = $objAddress->last_name;
				$this->shippingAddress1 = $objAddress->address1;
				$this->shippingAddress2 = $objAddress->address2;
				$this->shippingCity = $objAddress->city;
				$this->shippingState = $objAddress->state_id;
				$this->shippingPostal = $objAddress->postal;
				$this->shippingCountry = $objAddress->country_id;
				$this->shippingResidential = $objAddress->residential;
				if (empty($objAddress->phone) === false)
				{
					$this->shippingPhone = $objAddress->phone;
				}

				break;
			}
		}

		if ($this->billingSameAsShipping == 1)
		{
			$this->billingAddress1 = $this->shippingAddress1;
			$this->billingAddress2 = $this->shippingAddress2;
			$this->billingCity = $this->shippingCity;
			$this->billingCountry = $this->shippingCountry;
			$this->billingCountryCode = $this->shippingCountryCode;
			$this->billingState = $this->shippingState;
			$this->billingStateCode = $this->shippingStateCode;
			$this->billingPostal = $this->shippingPostal;
			$this->billingResidential = $this->shippingResidential;
		}
		elseif ($this->intBillingAddress > 0)
		{
			// end-user has selected an existing address
			$objAddress = CustomerAddress::findCustomerAddress($objCustomer->id, $this->intBillingAddress);

			$this->billingAddress1 = $objAddress->address1;
			$this->billingAddress2 = $objAddress->address2;
			$this->billingCity = $objAddress->city;
			$this->billingState = $objAddress->state_id;
			$this->billingPostal = $objAddress->postal;
			$this->billingCountry = $objAddress->country_id;
			$this->billingResidential = $objAddress->residential;
		}

		if ($objCustomer instanceof Customer)
		{
			$this->contactFirstName = $objCustomer->first_name;
			$this->contactLastName = $objCustomer->last_name;
			$this->contactPhone = $objCustomer->mainphone;
			$this->contactEmail = $objCustomer->email;
		}
	}

	/**
	 * @param string $attribute
	 * @return string
	 */
	public function getAttributeLabel($attribute)
	{
		$baseLabel = parent::getAttributeLabel($attribute);
		return Yii::t('CheckoutForm', $baseLabel);
	}

	/**
	 * Returns a CHtml list mapping from the country code to the country name.
	 * @return array
	 */
	public function getCountries() {
		$model = Country::getShippingCountries();
		return CHtml::listData($model, 'id', 'country');
	}

	/**
	 * This function returns the valid shipping states for a given country.
	 *
	 * Call this function with EITHER $countryType set to 'billing' or
	 * 'shipping' - OR - $countryType set to something else entirely and
	 * $intCountryId set to a country ID.
	 *
	 * If $countryType is 'billing' or 'shipping' then $countryType is used in
	 * preference to $intCountryId. Otherwise $intCountryId is used. If
	 * $countryType is not one of: billing, shipping; and $intCountryId is null
	 * or not provided then the default country is used.
	 *
	 * This is not a good interface for a function but legacy view code relies on it.
	 *
	 * @param string $countryType Either 'billing' or 'shipping'; or some other value
	 * in which case $intCountryId is used.
	 * @param int|null $intCountryId An ID of a country.
	 * @return CActiveRecord[] An array of States.
	 */
	public function getStates($countryType = 'billing', $intCountryId = null) {
		if ($intCountryId === null)
		{
			switch ($countryType)
			{
				case 'billing':
					if ($this->billingCountry !== null)
					{
						$intCountryId = $this->billingCountry;
					}
					break;
				case 'shipping':
					if ($this->shippingCountry !== null)
					{
						$intCountryId = $this->shippingCountry;
					}
					break;
					// For backwards compatibility we can't throw an error if another
					// value is passed. Some older code (e.g.
					// views-cities/myaccount.address.php) sends meaningless values
					// values for $countryType to use the second parameter.
			}
		}

		if ($intCountryId === null)
		{
			$intCountryId = _xls_get_conf('DEFAULT_COUNTRY', 224);
		}

		return Country::getCountryShippingStates($intCountryId);
	}

	/**
	 * @return array
	 */
	public function getShippingModules()
	{
		$arr = CHtml::listData(
			Modules::model()->findAllByAttributes(
				array('active' => 1, 'category' => 'shipping'),
				array('order' => 'sort_order, module')
			),
			'id',
			'configuration'
		);

		foreach ($arr as $key => $value)
		{
			$config = unserialize($value);
			$arr[$key] = $config['label'];
		}

		return $arr;
	}


	/**
	 * @param $str - payment module name
	 * @return bool
	 */
	public function isPaymentMethodValid($str)
	{
		$paymentLabel = Modules::getModuleConfig($str, 'label');
		if (empty($paymentLabel))
		{
			return false;
		}

		$validPaymentMethods = $this->getPaymentMethods();
		return in_array($paymentLabel, $validPaymentMethods);
	}

	/**
	 * Returns an array of payment module objects that are valid
	 * according to the defined shipping address and the restriction
	 * rules in the Admin Panel
	 *
	 * @return array
	 */
	public function getValidPaymentModules()
	{
		$objModules = Modules::model()->payment()->findAll();
		$arrValidModules = array();

		foreach ($objModules as $obj)
		{
			$objModule = Yii::app()->getComponent($obj->module);
			if (is_null($objModule)) {
				Yii::log("Could not find component for module $obj->module", 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);
				continue;
			}

			if ($objModule->setCheckoutForm($this)->Show === false) {
				continue;
			}

			array_push($arrValidModules, $obj);
		}

		return $arrValidModules;
	}

	/**
	 * Returns a formatted array of all the active and valid payment modules
	 * such that the array item index is the Module id and the array item is
	 * the store owner defined label for the method.
	 *
	 * @return array
	 */
	public function getPaymentMethods()
	{
		$objModules = $this->getValidPaymentModules();
		$arr = CHtml::listData($objModules, 'id', 'configuration');
		foreach ($arr as $key => $value)
		{
			$config = unserialize($value);
			$arr[$key] = $config['label'];
		}

		return $arr;
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 * @see BaseCheckoutForm::getPaymentMethods()
	 */
	public function getPaymentModules()
	{
		return $this->getPaymentMethods();
	}

	/**
	 * In legacy checkout, this gets called both initially in form creation and also alongside
	 * the Calculate Shipping command, since a shipping address can be used to filter available
	 * payment methods. We return HTML formatted strings instead of just an array.
	 *
	 * @return string
	 */
	public function getPaymentMethodsAjax()
	{
		$objModules = $this->getValidPaymentModules();

		$retHtml = "";
		foreach($objModules as $objModule)
		{
			$objComponent = Yii::app()->getComponent($objModule->module);

			$retHtml .= CHtml::tag(
				'option',
				array('value' => $objModule->id),
				CHtml::encode($objComponent->Name),
				true
			);
		}

		return $retHtml;
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 * @return string A string that _cartjs.php can use like: var a = new Array($str);
	 * That is, a string that looks like: '12', '14', '30'.
	 */
	public function getPaymentModulesThatUseCard()
	{
		return sprintf(
			"'%s'",
			implode(
				"','",
				array_keys(
					$this->getAimPaymentMethods()
				)
			)
		);
	}

	/**
	 * Return the active advanced payment methods.
	 * These are the methods that prompt for the end user's credit
	 * card information within Web Store's checkout process
	 *
	 * @return array
	 */
	public function getAimPaymentMethods()
	{
		return $this->getPaymentModulesGroup('advanced');
	}

	/**
	 * Return the active simple payment methods.
	 * With the exception of PayPal, these are the methods that redirect
	 * the end user to a third party site which will prompt them for
	 * their credit card details and send them back to Web Store
	 *
	 * @return array
	 */
	public function getSimPaymentMethods()
	{
		return $this->getPaymentModulesGroup('simple');
	}

	/**
	 * Return the active alternative payment methods.
	 * These are the offline methods and (currently) include
	 * Cash on Delivery, Check, Phone Order and Purchase Order.
	 *
	 * @return array
	 */
	public function getAlternativePaymentMethods()
	{
		return $this->getPaymentModulesGroup('alternative');
	}

	/**
	 * Return the active alternative payment methods that have subForms.
	 * ex. Purchase Order, which prompts the end user for additional input when selected
	 *
	 * @return array
	 */
	public function getAlternativePaymentMethodsThatUseSubForms()
	{
		return $this->getPaymentModulesGroup('subform');
	}

	/**
	 * Return an array of active payment methods based on the input string.
	 * In all but one case, the array is formatted such that each array item is the store
	 * owner defined label for that method, and its index is the corresponding Module id.
	 *
	 * @param $strGroup
	 * @return array
	 */
	protected function getPaymentModulesGroup($strGroup)
	{
		$arrPaymentModules = $this->getValidPaymentModules();
		$arrReturn = array();

		foreach ($arrPaymentModules as $objModule)
		{
			$label = $objModule->getConfig('label');
			$id = $objModule->id;

			$objComponent = Yii::app()->getComponent($objModule->module);

			// ToDo: Find a way to put this logic into WsPayment and handle getting the subForm in the view's calling code
			switch ($strGroup)
			{
				case 'advanced':
					$inGroup = ($objComponent->advancedMode === true && $objComponent->uses_credit_card === true);
					break;

				case 'simple':
					$inGroup = ($objComponent->advancedMode === false && $objComponent->uses_credit_card === true && $objModule->module !== 'paypal');
					break;

				case 'alternative':
					$inGroup = ($objComponent->advancedMode === false && $objComponent->uses_credit_card === false);
					break;

				case 'subform':
					$inGroup = (isset($objComponent->subform));
					if ($inGroup === true)
					{
						$subForm = $objComponent->subform;
						$model = new $subForm;
						$label = new CForm($model->Subform, $model);
					}
					break;

				default:
					Yii::log("Invalid group type: $strGroup", 'error', 'application.' . __CLASS__ . '.' . __FUNCTION__);
					return array();
			}

			if ($inGroup === true) {
				$arrReturn[$id] = $label;
			}
		}

		return $arrReturn;
	}

	/**
	 * @return array
	 */
	public function getCardTypes() {

		return CHtml::listData(
			CreditCard::model()->findAllByAttributes(
				array('enabled' => 1),
				array('order' => 'sort_order,label')
			),
			'validfunc',
			'label'
		);

	}

	/**
	 * @return array
	 */
	public function getCardMonths()
	{
		foreach (Yii::app()->locale->getMonthNames('abbreviated') as $key => $value)
		{
			$arr[sprintf("%02d", $key)] = $value;
		}

		return $arr;

	}

	/**
	 * @return mixed
	 */
	public function getCardYears() {

		for ($x = date("Y"); $x <= date("Y") + 10; $x++)
		{
			$arrYear[$x] = $x;
		}

		return $arrYear;

	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 * @param $blnReturnJavascript Not used. For backwards-compatibility only.
	 * @return string A string that _cartjs.php can use like: var a = new Array($str);
	 * That is, a string that looks like: '12', '14', '30'.
	 */
	public function getPaymentModulesThatUseForms($blnReturnJavascript = false)
	{
		return sprintf(
			"'%s'",
			implode(
				"','",
				array_keys(
					$this->getAlternativePaymentMethodsThatUseSubForms()
				)
			)
		);
	}

	/**
	 * Array of shipping providers, mapping from the module ID to the name of
	 * the provider. Called when Checkout form is displayed. We only send
	 * information from the cache since that would mean we're refreshing the
	 * form after an error
	 * @return array
	 */
	public function getProviders()
	{
		return Yii::app()->session->get('ship.providerLabels.cache', array());
	}

	/**
	 * Array of shipping priorities, called when Checkout form is displayed. We
	 * only send information from the cache since that would mean we're
	 * refreshing the form after an error.
	 * @return array
	 */
	public function getPriorities($shippingProvider = null)
	{
		if ($shippingProvider === null)
		{
			return array();
		}

		$cachedProviderRadio = Yii::app()->session->get('ship.providerRadio.cache', null);
		$cachedShippingProvider = Yii::app()->session->get('ship' . $shippingProvider, null);

		if ($cachedProviderRadio === null || $cachedShippingProvider === null)
		{
			return array();
		}

		foreach ($cachedShippingProvider as $key => $val)
		{
			$arr[$key] = $val['label'];
		}

		return $arr;
	}

	/**
	 * Return the cached shipping providers.
	 *
	 * @return string|null An HTML snippet containing radio buttons, one for each
	 * shipping provider.
	 */
	public function getSavedProvidersRadioArr()
	{
		return Yii::app()->session->get('ship.providerRadio.cache', null);
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 */
	public function getSavedProvidersRadio()
	{
		return CJSON::encode($this->getSavedProvidersRadioArr());
	}

	/**
	 * Return cached shipping prices.
	 *
	 * @return array|null Mapping from the shipping provider ID to an array
	 * that maps the shipping priority index to the shipping price for that
	 * option.
	 */
	public function getSavedFormattedPricesArr()
	{
		return Yii::app()->session->get('ship.formattedPrices.cache', null);
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 */
	public function getSavedPrices()
	{
		return CJSON::encode($this->getSavedFormattedPricesArr());
	}

	/**
	 * Return cached taxes.
	 *
	 * @return array|null Mapping from the shipping provider ID to an array
	 * that maps the shipping priority index to the tax price.
	 */
	public function getSavedTaxArr()
	{
		return Yii::app()->session->get('ship.taxes.cache', null);
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 */
	public function getSavedTax()
	{
		return CJSON::encode($this->getSavedTaxArr());
	}

	/**
	 * Return cached shipping priorities.
	 *
	 * @return array|null Mapping from the shipping provider ID to a string
	 * HTML snippet containing radio buttons for each priority option.
	 */
	public function getSavedPrioritiesRadioArr()
	{
		return Yii::app()->session->get('ship.priorityRadio.cache', null);
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 */
	public function getSavedPrioritiesRadio()
	{
		return CJSON::encode($this->getSavedPrioritiesRadioArr());
	}

	/**
	 * Return the cached cart totals.
	 *
	 * @return array|null Mapping from the shipping provider ID to a string
	 * HTML snippet showing the cart.
	 */
	public function getSavedScenariosArr()
	{
		return Yii::app()->session->get('ship.formattedCartTotals.cache', null);
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 */
	public function getSavedScenarios()
	{
		return CJSON::encode($this->getSavedScenariosArr());
	}

	/**
	 * Return cached cart scenarios.
	 *
	 * @return array|null Mapping from the shipping provider ID to the a string
	 * HTML snippet showing the cart.
	 */
	public function getSavedCartScenariosArr()
	{
		return Yii::app()->session->get('ship.htmlCartItems.cache', null);
	}

	/**
	 * @deprecated since 3.2.2. This is called by _cartjs.php (removed in 3.2.2).
	 */
	public function getSavedCartScenarios()
	{
		return CJSON::encode($this->getSavedCartScenariosArr());
	}

	/**
	 * Virtual method for getting the shipping state id.
	 *
	 * @return integer The state country id.
	 */
	public function getShippingState()
	{
		$objShippingState = State::LoadByCode($this->shippingStateCode, $this->shippingCountry);
		if ($objShippingState === null)
		{
			return null;
		}

		return $objShippingState->id;
	}

	/*
	 * Virtual method for setting the shipping state.
	 *
	 * @return void
	 */
	public function setShippingState($intState)
	{
		$objShippingState = State::Load($intState);
		if ($objShippingState !== null)
		{
			$this->shippingStateCode = $objShippingState->code;
			$this->shippingCountry = $objShippingState->country_id;
		}
	}

	/**
	 * Virtual method for getting the billing state.
	 *
	 * @return integer The billing state id
	 */
	public function getBillingState()
	{
		$objBillingState = State::LoadBycode($this->billingStateCode, $this->billingCountry);
		if ($objBillingState === null)
		{
			return null;
		}

		return $objBillingState->id;
	}

	/*
	 * Virtual method for setting the billing state code.
	 *
	 * @return void
	 */
	public function setBillingState($intState)
	{
		$objBillingState = State::Load($intState);
		if ($objBillingState !== null)
		{
			$this->billingStateCode = $objBillingState->code;
			$this->billingCountry = $objBillingState->country_id;
		}
	}

	/**
	 * Virtual method for getting the shipping country code.
	 *
	 * @return string The shipping country code, e.g. "US", "GB".
	 */
	public function getShippingCountryCode()
	{
		$objShippingCountry = Country::model()->findByPk($this->shippingCountry);
		if ($objShippingCountry === null)
		{
			return null;
		}

		return $objShippingCountry->code;
	}

	/*
	 * Virtual method for setting the shipping country code.
	 *
	 * @return void
	 */
	public function setShippingCountryCode($strCountryCode)
	{
		$objShippingCountry = Country::LoadByCode($strCountryCode);
		if ($objShippingCountry === null)
		{
			$this->shippingCountry = null;
		} else {
			$this->shippingCountry = $objShippingCountry->id;
		}
	}

	/**
	 * Virtual method for getting the billing country code.
	 *
	 * @return string The shipping country code, e.g. "US", "GB".
	 */
	public function getBillingCountryCode()
	{
		$objBillingCountry = Country::model()->findByPk($this->billingCountry);
		if ($objBillingCountry === null)
		{
			return null;
		}

		return $objBillingCountry->code;
	}

	/*
	 * Virtual method for setting the billing country code.
	 *
	 * @return void
	 */
	public function setBillingCountryCode($strCountryCode)
	{
		$objBillingCountry = Country::LoadByCode($strCountryCode);
		if ($objBillingCountry === null)
		{
			$this->billingCountry = null;
		} else {
			$this->billingCountry = $objBillingCountry->id;
		}
	}
}
