<?php

/**
 * CheckoutForm class.
 * CheckoutForm is the data structure for keeping
 * checkout form data. It is used by the 'checkout' action of 'CartController'.
 */
class BaseCheckoutForm extends CFormModel
{

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
	public $shippingCountry;
	public $shippingResidential;
	public $promoCode;
	public $shippingProvider;
	public $shippingPriority;
	public $paymentProvider;

	public $orderNotes;
	public $acceptTerms;

	public $intShippingAddress;
	public $intBillingAddress;


	//If we are using a payment module with a credit card, we need these fields
	public $uses_card;
	public $cardNumber;
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
		if (_xls_get_conf('ALLOW_GUEST_CHECKOUT',0))
			$retArray = array('createPassword,createPassword_repeat','required','on'=>'formSubmitCreatingAccount');
		else $retArray = array('createPassword,createPassword_repeat','safe');

		return array(
			$retArray,
			array('shippingLabel,billingLabel,shippingResidential,billingResidential,
			contactFirstName ,contactLastName,contactCompany,contactPhone,contactEmail,contactEmailConfirm,createPassword,createPassword_repeat ,
			receiveNewsletter,billingAddress1,billingAddress2 ,billingCity ,billingState ,billingPostal ,billingCountry ,billingSameAsShipping ,
			shippingFirstName,shippingLastName,shippingAddress1,shippingAddress2,shippingCity,shippingState,shippingPostal,
			shippingCountry,promoCode,shippingProvider,shippingPriority,paymentProvider,orderNotes,cardNumber,
			cardExpiryMonth, cardExpiryYear,cardType,cardCVV,cardNameOnCard,intShippingAddress,intBillingAddress,acceptTerms','safe'),

			array('contactFirstName, contactLastName,contactEmail, contactPhone',
				'required','on'=>'formSubmitGuest,formSubmitCreatingAccount'),

			array('billingAddress1,billingCity, billingPostal, billingCountry',
				'validateBillingBlock','on'=>'formSubmitGuest,formSubmitCreatingAccount'),

			array('shippingFirstName,shippingLastName,shippingAddress1,shippingCity,shippingPostal,shippingCountry',
				'validateShippingBlock','on'=>'CalculateShipping,formSubmitGuest,formSubmitCreatingAccount,formSubmitExistingAccount'),

			array('acceptTerms,shippingProvider,shippingPriority','required',
				'on'=>'formSubmit,formSubmitCreatingAccount,formSubmitExistingAccount'),

			array('cardNumber,cardExpiryMonth, cardExpiryYear,cardType,cardCVV,cardNameOnCard','validateCard',
				'on'=>'formSubmitGuest,formSubmitCreatingAccount,formSubmitExistingAccount'),

			array('cardCVV', 'length', 'max'=>4,'on'=>'formSubmit,formSubmitCreatingAccount,formSubmitExistingAccount'),


			// email has to be a valid email address
			array('contactEmail', 'email'),
			array('contactEmail_repeat', 'safe'),
			array('contactEmail_repeat', 'validateEmailRepeat','on'=>'formSubmitGuest,formSubmitCreatingAccount'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),

			array('acceptTerms','required', 'requiredValue'=>1,
				'message'=>Yii::t('global','You must accept Terms and Conditions'),
				'on'=>'formSubmit,formSubmitCreatingAccount,formSubmitExistingAccount'),

			array('createPassword', 'length', 'max'=>255),
			array('createPassword', 'compare', 'on'=>'formSubmitCreatingAccount'),
			array('createPassword_repeat', 'safe'),
			//array('createPassword1, createPassword2', 'required', 'on'=>'insert'),

		);
	}


	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateBillingBlock($attribute, $params)
	{
		if ( $this->billingSameAsShipping == 0)
			if ( $this->$attribute == '' )
				$this->addError($attribute,
					Yii::t('yii','{attribute} cannot be blank.',
					array('{attribute}'=>$this->getAttributeLabel($attribute)))
				);

	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateShippingBlock($attribute, $params)
	{
		if ($this->intShippingAddress==0) //We haven't chosen from our address book
			if ( $this->$attribute == '' )
				$this->addError($attribute,
					Yii::t('yii','{attribute} cannot be blank.',
					array('{attribute}'=>$this->getAttributeLabel($attribute)))
				);

	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateEmailRepeat($attribute, $params)
	{
		if (Yii::app()->user->isGuest &&
			$this->contactEmail != $this->contactEmail_repeat)
		{
			$this->addError('contactEmail_repeat',
				Yii::t('checkout','Email address does not match')
			);
		}
	}

	/**
	 * Check the credit card fields if required, also do LUHN 10 check on CC number
	 * @param $attribute
	 * @param $params
	 */
	public function validateCard($attribute,$params)
	{
		if (empty($this->paymentProvider)) return;
		$objPaymentModule = Modules::model()->findByPk($this->paymentProvider);

		if(Yii::app()->getComponent($objPaymentModule->module)->uses_credit_card)
		{
			switch ($attribute)
			{
				case 'cardNumber':
					if ($this->cardNumber != '')
					{
						Yii::import('ext.validators.ECCValidator');
						$cc = new ECCValidator();
						$cc->format = array(ECCValidator::MASTERCARD, ECCValidator::VISA);

						if(!$cc->validateNumber($this->cardNumber))
							$this->addError($this->cardNumber,
								Yii::t('yii','Invalid Card Number',
									array('{attribute}'=>$this->getAttributeLabel($attribute)))
							);
					}
					//we purposely don't have a break here so it drops to the next check when blank

				default:
					if ($this->$attribute == '' )
						$this->addError($attribute,
							Yii::t('yii','{attribute} cannot be blank.',
								array('{attribute}'=>$this->getAttributeLabel($attribute)))
						);

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
			'contactFirstName'=>'First Name',
			'contactLastName'=>'Last Name',
			'contactCompany'=>'Company',
			'contactPhone'=>'Phone',
			'contactEmail'=>'Email Address',
			'contactEmail_repeat'=>'Email Address (confirm)',
			'createPassword'=>'Password',
			'createPassword_repeat'=>'Password (confirm)',
			'receiveNewsletter'=> 'Allow us to send you emails about our products',
			'billingLabel'=>'Label for this address (i.e. Home, Work)',
			'billingAddress1'=>'Address',
			'billingAddress2'=>'Address 2 (optional)',
			'billingCity'=>'City',
			'billingState'=>'State/Province',
			'billingPostal'=>'Zip/Postal',
			'billingCountry'=>'Country',
			'billingSameAsShipping'=>'My shipping address is also my billing address',
			'billingResidential'=>'This is a residential address',
			'shippingLabel'=>'Label for this address (i.e. Home, Work)',
			'shippingFirstName'=>'First Name',
			'shippingLastName'=>'Last Name',
			'shippingAddress1'=>'Address',
			'shippingAddress2'=>'Address 2 (optional)',
			'shippingCity'=>'City',
			'shippingState'=>'State/Province',
			'shippingPostal'=>'Zip/Postal',
			'shippingCountry'=>'Country',
			'shippingResidential'=>'This is a residential address',
			'promoCode'=>'Promo Code',
			'shippingProvider'=>'Shipping Method',
			'shippingPriority'=>'Delivery Speed',
			'orderNotes'=> 'Comments',
			'acceptTerms'=> 'Accept Terms',


			'verifyCode'=>'Verification Code',


			'cardNumber'=>'Card Number',
			'cardExpiryMonth'=>'Expiry Month',
			'cardExpiryYear'=>'Expiry Year',
			'cardType'=>'Card Type',
			'cardCVV'=>'CVV',
			'cardNameOnCard'=>'Cardholder Name',

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
	 * @return array
	 */
	public function getCountries() {

		return CHtml::listData(Country::model()->findAllByAttributes(array('active'=>1),array('order'=>'sort_order,country')), 'id', 'country');

	}


	//Called both from original form when displayed, and from AJAX query as Country changes (via Cart Controller)
	/**
	 * @param string $type
	 * @param null $intCountry
	 * @return array
	 */
	public function getStates($type = 'billing',$intCountry = null) {

		if (is_null($intCountry))
			$intCountry = (int)_xls_get_conf('DEFAULT_COUNTRY',224);

		//These are only on first display so state list defaults to chosen country
		if ($type=='billing') $intCountry = $this->billingCountry;
		if ($type=='shipping') $intCountry = $this->shippingCountry;

		$arrStates = CHtml::listData(State::model()->findAllByAttributes(array('country_id'=>$intCountry,'active'=>1),array('order'=>'sort_order,state')), 'id', 'code');
		if (count($arrStates)==0)
			$arrStates[null]="n/a";
		return $arrStates;
	}

	/**
	 * @return array
	 */
	public function getShippingModules()
	{
		$arr = CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'shipping'),array('order'=>'sort_order,module')),'id','configuration');
		foreach ($arr as $key => $value) {
			$config = unserialize($value);
			$arr[$key]=$config['label'];
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

		$objModules = Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'payment'),array('order'=>'sort_order,module'));
		$arr = CHtml::listData($objModules,'id','configuration');
		foreach ($arr as $key => $value) {
			$config = unserialize($value);
			$arr[$key]=$config['label'];
		}
		if (is_null($ajax)) return $arr; //we have to tweak the return depending on if this gets called at form creation or ajax

		$retHtml = "";
		foreach($objModules as $obj)
		{
			$moduleValue = $obj->module;
			$objModule = Yii::app()->getComponent($moduleValue);error_log($moduleValue." ".$objModule->setCheckoutForm($this)->Show);
			if (!$objModule)
				Yii::log("Error missing module ".$moduleValue, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			elseif($objModule->setCheckoutForm($this)->Show)
				$retHtml .= CHtml::tag('option',
					array('value'=>$obj->id),CHtml::encode($objModule->Name),true);



		}

		return $retHtml;

	}

	/**
	 * @return string
	 */
	public function getPaymentModulesThatUseCard()
	{
		$arrModuleIds = array();
		$arrModules = CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'payment'),array('order'=>'sort_order,module')),'id','module');
		foreach ($arrModules as $key => $value) {

			try {
				if (Yii::app()->getComponent($value))
					if(Yii::app()->getComponent($value)->uses_credit_card)
						$arrModuleIds[]=$key;
			}
			catch (Exception $e) {
				Yii::log("Could not find module $value $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}
		return "'".implode("','",$arrModuleIds)."'";

	}

	/**
	 * @return array
	 */
	public function getCardTypes() {

		return CHtml::listData(CreditCard::model()->findAllByAttributes(array('enabled'=>1),array('order'=>'sort_order,label')), 'label', 'label');

	}

	/**
	 * @return array
	 */
	public function getCardMonths()
	{
		foreach (Yii::app()->locale->getMonthNames('abbreviated') as $key=>$value)
			$arr[sprintf("%02d", $key)] = $value;
		return $arr;

	}

	/**
	 * @return mixed
	 */
	public function getCardYears() {

		for ($x = date("Y"); $x<=date("Y")+10; $x++)
			$arrYear[$x]=$x;
		return $arrYear;

	}


	/**
	 * @return array
	 */
	public function getPaymentModulesThatUseForms($blnReturnJavascript = false)
	{
		$arrModuleIds = array();
		$arrModules = CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'payment'),array('order'=>'sort_order,module')),'id','module');
		foreach ($arrModules as $key => $value) {

			try {
				if (Yii::app()->getComponent($value))
					if(isset(Yii::app()->getComponent($value)->subform))
					{
						$modelname = Yii::app()->getComponent($value)->subform;
						$model = new $modelname;
						$form = new CForm($model->Subform, $model);
						$arrModuleIds[$key]=$form;
					}
			}
			catch (Exception $e) {
				Yii::log("Could not find module $value $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

		if ($blnReturnJavascript)
			return "'".implode("','",array_keys($arrModuleIds))."'";
		else
			return $arrModuleIds;

	}



	/**
	 * List of shippers, called when Checkout form is displayed. We only send information
	 * from the cache since that would mean we're refreshing the form after an error
	 * @return array
	 */
	public function getProviders()
	{
		if (isset(Yii::app()->session['ship.provider.cache']))
			return Yii::app()->session['ship.provider.cache'];
		else return array();

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
			isset(Yii::app()->session['ship'.$shippingProvider]))
		{
			foreach (Yii::app()->session['ship'.$shippingProvider] as $key=>$val)
				$arr[$key]=$val['label'];
			return $arr;
		}
		else
			return array();

	}

	/** for cached shipping, providers as radio buttons */
	public function getSavedProvidersRadio()
	{
		if (isset(Yii::app()->session['ship.providerRadio.cache']))
			return Yii::app()->session['ship.providerRadio.cache'];
		else return "";
	}

	/** for cached shipping
	 * To send this back to checkout, we build a javascript array out of the previously calculated prices.
	 */
	public function getSavedPrices()
	{

		if (isset(Yii::app()->session['ship.prices.cache'])) {
			$strReturn = "{";

			$outercount=0;
			foreach (Yii::app()->session['ship.prices.cache'] as $key => $value) {
				if ($outercount++>0) $strReturn .= ",";
				$strReturn .= $key.":{";
				$innercount=0;
				foreach ($value as $key2=>$value2)
				{
					if ($innercount++>0) $strReturn .= ",";
					$strReturn .= $key2.":'"._xls_currency($value2)."'";
				}
				$strReturn .= "}";
			}
			$strReturn .= "}";
			return $strReturn;
			}

		else return "''";

	}
	/** for cached taxes
	 * To send this back to checkout, we build a javascript array out of the previously calculated taxes.
	 */
	public function getSavedTax()
	{

		if (isset(Yii::app()->session['ship.taxes.cache'])) {
			$strReturn = "{";

			$outercount=0;
			foreach (Yii::app()->session['ship.taxes.cache'] as $key => $value) {
				if ($outercount++>0) $strReturn .= ",";
				$strReturn .= $key.":{";
				$innercount=0;
				foreach ($value as $key2=>$value2)
				{
					if ($innercount++>0) $strReturn .= ",";
					$strReturn .= $key2.":'"._xls_ajaxclean($value2)."'";
				}
				$strReturn .= "}";
			}
			$strReturn .= "}";
			return $strReturn;
			}

		else return "''";

	}

	/**
	 * @return string
	 */
	public function getSavedPrioritiesRadio()
	{
		if (isset(Yii::app()->session['ship.priorityRadio.cache'])) {
			$strReturn = "{";
			$outercount=0;
			foreach (Yii::app()->session['ship.priorityRadio.cache'] as $key => $value) {
				if ($outercount++>0) $strReturn .= ",";
				$strReturn .= $key.":'".$value."'";
			}
			$strReturn .= "}";
			return $strReturn;
		}

		else return "''";
	}

	/** for cached shipping */
	public function getSavedScenarios()
	{
		if (isset(Yii::app()->session['ship.scenarios.cache'])) {
			$strReturn = "{";

			$outercount=0;
			foreach (Yii::app()->session['ship.scenarios.cache'] as $key => $value) {
				if ($outercount++>0) $strReturn .= ",";
				$strReturn .= $key.":{";
				$innercount=0;
				foreach ($value as $key2=>$value2)
				{
					if ($innercount++>0) $strReturn .= ",";
					$strReturn .= $key2.":'".$value2."'";
				}
				$strReturn .= "}";
			}
			$strReturn .= "}";
			return $strReturn;
		}

		else return "''";
	}
}