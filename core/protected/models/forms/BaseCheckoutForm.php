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
	public $billingState;
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
	public $shippingState;
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
			receiveNewsletter, billingAddress1, billingAddress2, billingCity, billingState, billingPostal, billingCountry, billingSameAsShipping ,
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

		if (Yii::app()->getComponent($objPaymentModule->module)->uses_credit_card)
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
			'recipientName' => 'Recipient Name',
			'contactFirstName' => 'First Name',
			'contactLastName' => 'Last Name',
			'contactCompany' => 'Company',
			'contactPhone' => 'Phone',
			'contactEmail' => 'Email Address',
			'contactEmail_repeat' => 'Email Address (confirm)',
			'createPassword' => 'Password',
			'createPassword_repeat' => 'Password (confirm)',
			'receiveNewsletter' => 'Allow us to send you emails about our products',
			'billingLabel' => 'Label for this address (i.e. Home, Work)',
			'billingAddress1' => 'Address',
			'billingAddress2' => 'Address 2 (optional)',
			'billingCity' => 'City',
			'billingState' => 'State/Province',
			'billingPostal' => 'Zip/Postal',
			'billingCountry' => 'Country',
			'billingSameAsShipping' => Yii::app()->params['SHIP_SAME_BILLSHIP'] ? 'We require your billing and shipping addresses to match' : 'My shipping address is also my billing address',
			'billingResidential' => 'This is a residential address',
			'shippingLabel' => 'Label for this address (i.e. Home, Work)',
			'shippingFirstName' => 'First Name',
			'shippingLastName' => 'Last Name',
			'shippingAddress1' => 'Address',
			'shippingAddress2' => 'Address 2 (optional)',
			'shippingCity' => 'City',
			'shippingState' => 'State/Province',
			'shippingPostal' => 'Zip/Postal',
			'shippingCountry' => 'Country',
			'shippingResidential' => 'This is a residential address',
			'promoCode' => 'Promo Code',
			'shippingProvider' => 'Shipping Method',
			'shippingPriority' => 'Delivery Speed',
			'orderNotes' => 'Comments',
			'acceptTerms' => 'Accept Terms',
			'verifyCode' => 'Verification Code',
			'cardNumber' => 'Card Number',
			'cardNumberLast4' => 'Card Number Last 4 Digits',
			'cardExpiry' => 'Expiry Date',
			'cardExpiryMonth' => 'Expiry Month',
			'cardExpiryYear' => 'Expiry Year',
			'cardType' => 'Card Type',
			'cardCVV' => 'CVV',
			'cardNameOnCard' => 'Cardholder Name',
		);
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
	 * Returns payment modules available on checkout. This gets called both initially in form creation and also
	 * alongside the Calculate Shipping command, since a shipping address can be used to filter available payment
	 * methods. If we're calling this via Ajax, we have to return HTML formatted strings instead of just an array.
	 * @param null $ajax
	 * @return array|string
	 */
	public function getPaymentModules($ajax = null)
	{

		$objModules = Modules::model()->findAllByAttributes(
			array('active' => 1,'category' => 'payment'),
			array('order' => 'sort_order,module')
		);
		$arr = CHtml::listData($objModules, 'id', 'configuration');
		foreach ($arr as $key => $value)
		{
			$config = unserialize($value);
			$arr[$key] = $config['label'];
		}

		if (is_null($ajax))
		{
			return $arr;
		} //we have to tweak the return depending on if this gets called at form creation or ajax

		$retHtml = "";
		foreach($objModules as $obj)
		{
			$CheckoutForm = clone $this;
			$CheckoutForm->billingState = State::CodeById($CheckoutForm->billingState);
			$CheckoutForm->billingCountry = Country::CodeById($CheckoutForm->billingCountry);
			$CheckoutForm->shippingState = State::CodeById($CheckoutForm->shippingState);
			$CheckoutForm->shippingCountry = Country::CodeById($CheckoutForm->shippingCountry);

			$moduleValue = $obj->module;
			$objModule = Yii::app()->getComponent($moduleValue);
			if (!$objModule)
			{
				Yii::log("Error missing module ".$moduleValue, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
			elseif ($objModule->setCheckoutForm($CheckoutForm)->Show)
			{
				$retHtml .= CHtml::tag(
					'option',
					array('value' => $obj->id),
					CHtml::encode($objModule->Name),
					true
				);
			}
		}

		return $retHtml;
	}

	/**
	 * @return string
	 */
	public function getStrPaymentModulesThatUseCard()
	{
		$arrModuleIds = array();
		$arrModules = CHtml::listData(
			Modules::model()->findAllByAttributes(
				array('active' => 1,'category' => 'payment'),
				array('order' => 'sort_order,module')
			),
			'id',
			'module'
		);

		foreach ($arrModules as $key => $value)
		{
			try {
				$objComponent = Yii::app()->getComponent($value);
				if ($objComponent)
				{
					if($objComponent->uses_credit_card && $objComponent->advancedMode)
					{
						$arrModuleIds[] = $key;
					}
				}
			}
			catch (Exception $e) {
				Yii::log("Could not find module $value $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

		return "'".implode("','", $arrModuleIds)."'";

	}


	/**
	 * Return an array of the active advanced payment methods
	 *
	 * @return array
	 */

	public function getPaymentModulesThatUseCard()
	{
		$arr = array();

		$strIds = $this->getStrPaymentModulesThatUseCard();

		// do we have active aim methods?
		if ($strIds !== "''")
		{
			$arrIds = explode(',', $strIds);

			foreach ($arrIds as $id)
			{
				$objModule = Modules::model()->findByPk((int)trim($id, "'"));
				$config = unserialize($objModule->configuration);
				$arr[$objModule->id] = $config['label'];
			}
		}

		return $arr;
	}

	/**
	 * Return an array of the simple payment methods
	 *
	 * @return array
	 */

	public function getSimPaymentModules()
	{
		$arrModules = array();
		$objModules = Modules::model()->findAllByAttributes(array('active' => 1,'category' => 'payment'), array('order' => 'sort_order,module'));
		foreach ($objModules as $obj)
		{
			$config = unserialize($obj->configuration);

			try {
				$objComponent = Yii::app()->getComponent($obj->module);
				if ($objComponent)
				{
					if(!$objComponent->advancedMode)
					{
						$arrModules[$obj->id] = $config['label'];
					}
				}
			}
			catch (Exception $e) {
				Yii::log("Could not find module $obj->module $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

		return $arrModules;
	}

	/**
	 * Return an array of the simple credit card payment methods
	 *
	 * @return array
	 */

	public function getSimPaymentModulesThatUseCard()
	{
		$arrModules = array();
		$objModules = Modules::model()->findAllByAttributes(array('active' => 1,'category' => 'payment'), array('order' => 'sort_order,module'));
		foreach ($objModules as $obj)
		{
			$config = unserialize($obj->configuration);

			try {
				$objComponent = Yii::app()->getComponent($obj->module);
				if ($objComponent && $obj->module !== 'paypal')
				{
					if ($objComponent->uses_credit_card && !$objComponent->advancedMode)
					{
						$arrModules[] = array('id' => $obj->id, 'label' => $config['label']);
					}
				}
			}
			catch (Exception $e) {
				Yii::log("Could not find module $obj->module $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

		return $arrModules;
	}

	/**
	 * Return an array of the simple non credit card payment methods
	 *
	 * @return array
	 */

	public function getSimPaymentModulesNoCard()
	{
		$arr = $this->getSimPaymentModules();
		$arrCC = $this->getSimPaymentModulesThatUseCard();
		$arrKeys = array();

		foreach ($arrCC as $module)
		{
			if (in_array($module['label'], $arr))
			{
				$arrKeys[] = $module['id'];
			}
		}

		// remove paypal
		$paypal = Modules::LoadByName('paypal');
		$arrKeys[] = $paypal->id;

		foreach ($arrKeys as $key)
		{
			unset($arr[$key]);
		}

		return $arr;
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
	 * @return array
	 */
	public function getPaymentModulesThatUseForms($blnReturnJavascript = false)
	{
		$arrModuleIds = array();
		$arrModules = CHtml::listData(
			Modules::model()->findAllByAttributes(
				array('active' => 1,'category' => 'payment'),
				array('order' => 'sort_order,module')
			),
			'id',
			'module'
		);

		foreach ($arrModules as $key => $value)
		{
			try {
				if (Yii::app()->getComponent($value))
				{
					if(isset(Yii::app()->getComponent($value)->subform))
					{
						$modelname = Yii::app()->getComponent($value)->subform;
						$model = new $modelname;
						$form = new CForm($model->Subform, $model);
						$arrModuleIds[$key] = $form;
					}
				}
			}
			catch (Exception $e) {
				Yii::log("Could not find module $value $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

		if ($blnReturnJavascript)
		{
			return "'".implode("','", array_keys($arrModuleIds))."'";
		}
		else
		{
			return $arrModuleIds;
		}

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
		if (isset(Yii::app()->session['ship.providerLabels.cache']))
		{
			return Yii::app()->session['ship.providerLabels.cache'];
		}
		else
		{
			return array();
		}

	}

	/**
	 * List of shipping priorities, called when Checkout form is displayed. We only send information
	 * from the cache since that would mean we're refreshing the form after an error
	 * @return array
	 */
	public function getPriorities($shippingProvider = null)
	{
		if (!is_null($shippingProvider) &&
			isset(Yii::app()->session['ship.providerRadio.cache']) &&
			isset(Yii::app()->session['ship'.$shippingProvider])
		)
		{
			foreach (Yii::app()->session['ship'.$shippingProvider] as $key => $val)
			{
				$arr[$key] = $val['label'];
			}

			return $arr;
		}
		else
		{
			return array();
		}

	}

	/** for cached shipping, providers as radio buttons */
	public function getSavedProvidersRadio()
	{
		if (isset(Yii::app()->session['ship.providerRadio.cache']))
		{
			return Yii::app()->session['ship.providerRadio.cache'];
		}
		else
		{
			return "";
		}
	}

	/** for cached shipping
	 * To send this back to checkout, we build a javascript array out of the previously calculated prices.
	 */
	public function getSavedPrices()
	{

		if (isset(Yii::app()->session['ship.prices.cache']))
		{
			$strReturn = "{";

			$outercount = 0;
			foreach (Yii::app()->session['ship.prices.cache'] as $key => $value)
			{
				if ($outercount++ > 0)
				{
					$strReturn .= ",";
				}

				$strReturn .= $key.":{";
				$innercount = 0;
				foreach ($value as $key2 => $value2)
				{
					if ($innercount++ > 0)
					{
						$strReturn .= ",";
					}

					$strReturn .= $key2.":'"._xls_currency($value2)."'";
				}

				$strReturn .= "}";
			}

			$strReturn .= "}";
			return $strReturn;
		}

		else
		{
			return "''";
		}

	}
	/** for cached taxes
	 * To send this back to checkout, we build a javascript array out of the previously calculated taxes.
	 */
	public function getSavedTax()
	{

		if (isset(Yii::app()->session['ship.taxes.cache']))
		{
			$strReturn = "{";

			$outercount = 0;
			foreach (Yii::app()->session['ship.taxes.cache'] as $key => $value)
			{
				if ($outercount++ > 0)
				{
					$strReturn .= ",";
				}

				$strReturn .= $key.":{";
				$innercount = 0;
				foreach ($value as $key2 => $value2)
				{
					if ($innercount++ > 0)
					{
						$strReturn .= ",";
					}

					$strReturn .= $key2.":'"._xls_ajaxclean($value2)."'";
				}

				$strReturn .= "}";
			}

			$strReturn .= "}";
			return $strReturn;
		}

		else
		{
			return "''";
		}

	}

	/**
	 * @return string
	 */
	public function getSavedPrioritiesRadio()
	{
		if (isset(Yii::app()->session['ship.priorityRadio.cache']))
		{
			$strReturn = "{";
			$outercount = 0;
			foreach (Yii::app()->session['ship.priorityRadio.cache'] as $key => $value)
			{
				if ($outercount++ > 0)
				{
					$strReturn .= ",";
				}

				$strReturn .= $key.":'"._xls_jssafe_name($value)."'";
			}

			$strReturn .= "}";
			return $strReturn;
		}

		else
		{
			return "''";
		}
	}

	/** for cached shipping */
	public function getSavedScenarios()
	{
		if (isset(Yii::app()->session['ship.formattedCartTotals.cache']) &&
			!empty(Yii::app()->session['ship.formattedCartTotals.cache'])
		)
		{
			$strReturn = "{";

			$outercount = 0;
			foreach (Yii::app()->session['ship.formattedCartTotals.cache'] as $key => $value)
			{
				if ($outercount++ > 0)
				{
					$strReturn .= ",";
				}

				$strReturn .= $key.":{";
				$innercount = 0;
				foreach ($value as $key2 => $value2)
				{
					if ($innercount++ > 0)
					{
						$strReturn .= ",";
					}

					$strReturn .= $key2.":'".$value2."'";
				}

				$strReturn .= "}";
			}

			$strReturn .= "}";
			return $strReturn;
		}

		else
		{
			return "''";
		}
	}
	/** for cached shipping */
	public function getSavedCartScenarios()
	{
		if (isset(Yii::app()->session['ship.htmlCartItems.cache']) && is_array(Yii::app()->session['ship.htmlCartItems.cache']))
		{
			$strReturn = "{";
			$outercount = 0;
			foreach (Yii::app()->session['ship.htmlCartItems.cache'] as $key => $value)
			{
				if ($outercount++ > 0)
				{
					$strReturn .= ",";
				}

				$value = str_replace("\t", "", $value);
				$strReturn .= $key.":'".addslashes($value)."'";
			}

			$strReturn .= "}";
			return $strReturn;
		}

		else
		{
			return "''";
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
}
