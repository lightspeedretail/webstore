<?php
/**
 * Used as a base for Payment and Shipping modules (we colloquially call them
 * modules but they're not "Yii modules", they're extensions)
 */
class WsExtension extends CComponent
{
	const SHIPPING = 'shipping';
	const PAYMENT = 'payment';
	const SIDEBAR = 'sidebar';
	const THEME = 'theme';

	/**
	 * Name that appears to the shopper
	 * @var string
	 */
	protected $_strModuleName = "Web Store Module";

	/**
	 * Extension version number (note whole numbers only)
	 * @var int
	 */
	protected $_version = 1;

	/**
	 * For billing extensions, does it redirect offsite (SIM ex. Paypal)
	 * @var bool
	 */
	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $uses_jumper = false;
	// @codingStandardsIgnoreEnd

	/**
	 * For billing extensions, where credit card details are involved (SIM or AIM)
	 * @var bool
	 */
	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $uses_credit_card = false;
	// @codingStandardsIgnoreEnd

	/**
	 * Internal Web Store API version number, to determine compatibility
	 * @var int
	 */
	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $apiVersion = 1;
	// @codingStandardsIgnoreEnd

	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $active;
	// @codingStandardsIgnoreEnd

	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $config;
	// @codingStandardsIgnoreEnd

	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $objCart;
	// @codingStandardsIgnoreEnd

	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	protected $CheckoutForm;
	// @codingStandardsIgnoreEnd

	/**
	 * If we have a subform (model file)
	 * @var null
	 */
	public $subform = null;

	public function init()
	{
		$this->objCart = Yii::app()->shoppingcart;
		$this->config = $this->getConfigValues(get_class($this));

		if (!isset($this->config['markup']))
		{
			$this->config['markup'] = 0;
		}

		$objModule = Modules::LoadByName(get_class($this));

		if ($objModule instanceof Modules)
		{
			$this->active = $objModule->active;
		}
		else
		{
			$this->active = false;
		}
	}

	/**
	 * The run() function is called from Web Store to actually do the calculation. It either returns a single
	 * price, indicating that there are no further service options, or it returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return float
	 * @return array
	 */
	public function run()
	{
		/*
		 * This is what we're going to do to calculate the cost
		 * if we're using this as a shipping module
		 *
		 * $arrReturn['price']  = 0;
		 * $arrReturn['level']  = 'Service Level Appears Here';
		 * $arrReturn['label']  = $arrReturn['level'] . " (" ._xls_currency($arrReturn['price']). ")";
		 *
		 * $arrReturn2['price'] = 2;
		 * $arrReturn2['level'] = 'Higher Service Level you pay more for';
		 * $arrReturn2['label'] = $arrReturn2['level'] . " (" ._xls_currency($arrReturn2['price']). ")";\
		 *
		 * Return an array of arrays, where each subarray is the service level
		 * with the price, the level and a display label which is a combination the
		 * of the level and the currency formatted price
		 *
		 */
	}

	public function setCheckoutForm($mixForm = null)
	{
		// Pass the checkout form and put it in our object
		if ($mixForm instanceof CheckoutForm)
		{
			$this->CheckoutForm = $mixForm;
			return $this;
		}
		else
		{
			throw new CException('CheckoutForm not passed to module');
		}
	}


	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name()
	{
		$config = $this->getConfigValues(get_class($this));

		if (isset($config['label']))
		{
			$strName = $config['label'];
		}
		else
		{
			$strName = $this->_strModuleName;
		}

		if (isset($config['live']))
		{
			if ($config['live'] == "test")
			{
				$strName .= " (TEST MODE)";
			}
		}

		return $strName;
	}

	public function customer() {
		return Customer::GetCurrent();
	}

	/**
	 * Return the administrative name of the module for WS Admin Panel.
	 * It is different than the module name returned in front of the
	 * customer.
	 *
	 * @return string
	 */
	public function adminName() {
		return $this->defaultName;
	}

	/**
	 * The description of this module
	 * @return string
	 */
	public function info() {
		return _sp("This module provides a simple cash on delivery payment method.");
	}


	/**
	 * Returns the Payment Method used within Lightspeed. This must match
	 * the value within Lightspeed exactly.
	 *
	 * @param Cart $cart
	 * @return string
	 */
	// @codingStandardsIgnoreStart
	// Renaming this could break custom payment methods.
	// TODO: This method does not appear to be called anywhere, remove it.
	public function payment_method(Cart $cart)
	{
		// @codingStandardsIgnoreEnd
		$config = $this->Config;

		if (isset($config['ls_payment_method']))
		{
			return $config['ls_payment_method'];
		}

		return "Cash";
	}



	public function getAdminModel()
	{
		$className = $this->getAdminModelName();
		$reflector = new ReflectionClass(get_class($this));
		$classPath = $reflector->getFileName();
		$adminFile = str_replace(get_class($this).".php", "models/".$className, $classPath.".php");

		if (file_exists($adminFile))
		{
			return new $className;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Build the specific filename based on the classname. The classname should be our payment/shipping class (in lower case) with AdminForm appended.
	 * The filename is the same with the addition of the .php extension in a subfolder called models
	 * @return string
	 */
	public function getAdminModelName()
	{
		$className = get_class($this)."AdminForm";

		return $className;
	}


	/**
	 * getConfigValues
	 *
	 * Returns initial configuration for selected payment type (class)
	 *
	 * @param $classname
	 * @return $values[]
	 *
	 */
	public function getConfigValues($strClass = null) {
		if (is_null($strClass))
		{
			$strClass = get_class($this);
		}

		$arr = array();

		$objModule = Modules::model()->findByAttributes(array('module' => $strClass));
		if ($objModule instanceof Modules)
		{
			try {
				$arr = unserialize($objModule->configuration);
			} catch(Exception $e) {
				Yii::log("Could not unserialize " . $strClass. " . Error : " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return array();
			}
		}

		return $arr;
	}


	/**
	 * Returns initial configuration for selected payment type (class)
	 *
	 * @param $arr
	 * @return array
	 */
	public function setConfigValues($arr) {

		$strClass = get_class($this);

		$objModule = Modules::model()->findByAttributes(array('module' => $strClass));
		if ($objModule instanceof Modules)
		{
			Yii::log("Writing config " . print_r($arr, true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			try {
				$objModule->configuration = serialize($arr);
				$objModule->save();
			} catch(Exception $e) {
				Yii::log("Could not save " . $strClass. " . Error : " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return array();
			}
		}

	}

	public function getDefaultConfiguration()
	{
		return false;
	}

	public function getVersion()
	{
		return $this->_version;
	}

	public function getAdminNameNormal()
	{
		return str_replace("&nbsp;", "", strip_tags($this->AdminName));
	}

	public function install() {
		return;
	}

	public function remove() {
		return;
	}

	/**
	 * Check if the module is valid or not.
	 * Returning false here will exclude the module from checkout page
	 * Can be used for tests against cart conditions
	 *
	 * @return boolean
	 */
	public function check()
	{
		// if nothing has been configured, return null
		if (!$this->config || count($this->config) == 0)
		{
			return false;
		}

		// Remove possible "null" string which should be same as not set
		if (isset($this->config['restrictcountry']) && $this->config['restrictcountry'] == "null")
		{
			unset($this->config['restrictcountry']);
		}

		// Check possible scenarios why we would not offer this type of shipping
		if (isset($this->config['restrictcountry'])) //we have a country restriction
		{
			switch($this->config['restrictcountry']) {
				case 'CUS':
					if ($this->CheckoutForm->shippingCountryCode == "US")
					{
						if ($this->CheckoutForm->shippingStateCode == "AK" || $this->CheckoutForm->shippingStateCode == "HI")
						{
							return false;
						} else {
							return true;
						}
					}
					return false;
					break;

				case 'NORAM':
					if ($this->CheckoutForm->shippingCountryCode != "US" && $this->CheckoutForm->shippingCountryCode != "CA")
					{
						return false;
					}
					break;

				case 'AUNZ':
					if ($this->CheckoutForm->shippingCountryCode != "AU" && $this->CheckoutForm->shippingCountryCode != "NZ")
					{
						return false;
					}
					break;

				case 'OUTSIDE':
					if (_xls_get_conf('DEFAULT_COUNTRY') == $this->CheckoutForm->shippingCountry)
					{
						return false;
					}
					break;

				default:
					if ($this->config['restrictcountry'] != $this->CheckoutForm->shippingCountryCode)
					{
						return false;
					}
			}
		}

		return true;
	}

	public function getShow()
	{
		return $this->check();
	}

	public function getModuleName($strClass = null)
	{

		if (is_null($strClass))
		{
			$strClass = get_class($this);
		}

		if (substr($strClass, -6) == "Module")
		{
			$strClass = substr($strClass, 0, -6);
		}

		return strtolower($strClass);
	}
	/**
	 * message
	 *
	 * Generic message function to return result string
	 *
	 * @param $cart[]
	 * @return string
	 *
	 */
	public function message($cart)
	{
		if (($cart->PaymentData == $this->name()) || (!$cart->PaymentData))
		{
			return $this->name();
		}
		else
		{
			return $this->name() . " - " . $cart->PaymentData;
		}
	}

	/**
	 *
	 * Process payment
	 *
	 * Return string to be stored as part of the payment to WS if
	 * successful (e.g. Reference number)
	 *
	 * If you are going to do a jumper page, you should return a full
	 * HTML FORM that will be executed in users' browser.
	 *
	 * Please provide a gateway_response_process() function which should
	 * take care of the returned $_GET or $_POST variables from the third
	 * party website
	 *
	 * Return false if processing has failed. Error can be returned as part
	 * of the $errortext variable (ByRef)
	 *
	 * @return string|boolean
	 */
	public function process() {
		return $this->name();
	}

	/**
	 * Return the paid amount that is actually going to come to store.
	 * Returned value here will go into paid amount/deposit of Lightspeed.
	 *
	 * @param Cart $cart
	 * @return unknown_type
	 */
	// @codingStandardsIgnoreStart
	// Renaming this could break custom payment methods.
	// TODO: This does not appear to be used anywhere, remove it.
	public function paid_amount(Cart $cart)
	{
		// @codingStandardsIgnoreEnd
		if ($this->adminName() == "Cash On Delivery")
		{
			return 0.00;
		}
		else
		{
			return $cart->Total;
		}
	}

	/**
	 * Whether this payment method uses a jumper page or not
	 * If it uses a jumper page then process() function must
	 * return a HTML FORM string.
	 *
	 * @return bool
	 */
	// @codingStandardsIgnoreStart
	// Renaming this could break custom payment methods.
	// TODO: This does not appear to be used anywhere, remove it.
	public function uses_jumper() {
		// @codingStandardsIgnoreEnd
		return false;
	}

	/**
	 *
	 * this function processes silent or hosted payment responses
	 *
	 * Payment methods such as Authorize.net AIM or SIM uses this function to process payment status in WS.
	 *
	 * return false if not appplicable to you
	 * Other wise return an array containing
	 * 		- order_id => Order Id
	 * 		- amount => paid amount
	 * 		- data  => payment data to store
	 * 		- success => true| false
	 * 		- output =>
	 */
	// @codingStandardsIgnoreStart
	// Renaming this to respect coding standards could break custom payment integrations.
	public function gateway_response_process() {
		// @codingStandardsIgnoreEnd
		return false;
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Name':
				return $this->name();

			case 'AdminName':
				return $this->adminName();

			case 'DefaultName':
				return $this->defaultName;

			case 'advancedMode':
				return $this->advancedMode;

			case 'modulename':
				return $this->getModuleName();

			case 'uses_credit_card':
				return $this->uses_credit_card;

			case 'uses_jumper':
				return $this->uses_jumper;

			default:
				return parent::__get($strName);
		}
	}

	public function __set($strName, $mixValue) {
		switch ($strName) {
			case 'objCart':
				$this->objCart = $mixValue;
				return;

			case 'CheckoutForm':
				$this->CheckoutForm = $mixValue;
				return;

			default:
				return parent::__set($strName, $mixValue);
		}

	}
}
