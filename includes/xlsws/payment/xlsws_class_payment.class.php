<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Generic Payment class that all payment classes must extend from
 *
 *
 *
 */

class xlsws_class_payment extends XLSModule {
	protected $strModuleType = "payment";

	public function customer() {
		return Customer::GetCurrent(true);
	}

	/**
	 * The name of the module that will be displayed in the checkout page
	 * @return string
	 */
	public function name() {
		$config = $this->Config;

		if(isset($config['label']))
			return $config['label'];

		return $this->admin_name();
	}

	/**
	 * Return the administrative name of the module for WS Admin Panel.
	 * It is different than the module name returned in front of the
	 * customer.
	 * @return string
	 */
	public function admin_name() {
		return _sp('Cash On Delivery');
	}

	/**
	 * The description of this module
	 * @return string
	 */
	public function info() {
		return _sp("This module provides a simple cash on delivery" .
			" payment method.");
	}

	/**
	 * Returns the Payment Method used within LightSpeed. This must match
	 * the value within LightSpeed exactly.
	 * @return string
	 */
	public function payment_method(Cart $cart) {
		$config = $this->Config;

		if(isset($config['ls_payment_method']))
			return $config['ls_payment_method'];

		return "Cash";
	}

	/**
	 * Return config fields (as array) for user configuration.
	 * The array key is the variable value holder
	 * For example if you wanted to have a admin-editable field called
	 * Message which is a textbox
	 *  $message = new XLSTextBox($parentObj);
	 *  // this will be over-written by the user
	 *  $message->Text = "Default text";
	 *  // You do not have to add action to a field.
	 *  // But if you wanted to this is how you would do it.
	 *  $message->AddAction(new QFocusEvent(),
	 *      new QAjaxControlAction('moduleActionProxy'));
	 * 	// Optionally make a field compulsory.
	 * 	$message->Required = true;
	 * 	return array('message' => $message);
	 *
	 * @param QPanel $parentObj
	 * @return array
	 */
	public function config_fields($parentObj) {
		$ret= array();

		$ret['label'] = new XLSTextBox($parentObj);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = _sp('Cash On Delivery');

		$ret['ls_payment_method'] = new XLSTextBox($parentObj);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Cash';
		$ret['ls_payment_method']->ToolTip = _sp("Please enter the" .
			" payment method (from LightSpeed) you would like the payment" .
			" amount to import into");

		return $ret;
	}

	/**
	 * Check config fields
	 *
	 * The fields generated and returned in config_fields will be passed here for validity.
	 * Return true or false
	 *
	 * Admin panel will ONLY save field configs if all the fields are valid.
	 *
	 * @param $fields[]
	 * @return boolean
	 */
	public function check_config_fields($fields) {
		return true;
	}

	/**
	 * adminLoadFix
	 *
	 * Change display options in Web Admin before panel actually displays
	 *
	 * @param $obj (shipping panel object)
	 * @return none, updates passed object by reference
	 */
	public function adminLoadFix($obj) {
		return;
	}

	/**
	 * Return customer fields (as array) that will be shown in the
	 * checkout page. Fields can be any qcontrol elements.
	 *
	 * The array key is the variable value holder
	 * For example if you wanted to have a service list box where
	 * customer will be choosing a type of service
	 *     $service = new XLSListBox($parentObj);
	 * 	   $service->Name = _sp('Choose your service type');
	 *     $service->AddItem(_sp('Service 1') , 'service1');
	 *     $service->AddItem(_sp('Service 2') , 'service2');
	 *	   $service->SelectedValue = $config['defaultproduct'];
	 * 	   return array('service' => $service);
	 *
	 * @param QPanel $parentObj
	 * @return array
	 */
	public function customer_fields($parentObj) {
		return array();
	}

	/**
	 * Check customer fields
	 *
	 * The fields generated and returned in customer_fields will be
	 * passed here for validity.
	 * Return true or false
	 *
	 * Checkout panel will ONLY continue to checkout if all the fields
	 * are valid.
	 *
	 * @param $fields[]
	 * @return boolean
	 */
	public function check_customer_fields($fields) {
		return true;
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
	public function getConfigValues($classname) {
		return $this->GetConfigurationValues();
	}

	public function install() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return;
	}

	public function remove() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return;
	}

	public function check() {
		return true;
	}

	public function surcharge() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return 0;
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
	public function message($cart) {
		if (($cart->PaymentData == $this->name()) || (!$cart->PaymentData))
			return $this->name();
		else
			return $this->name() . " - " . $cart->PaymentData;
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
	* @param $cart
	* @param $fields
	* @param $errortext
	* @return string|boolean
	*/
	public function process($cart , $fields , $errortext) {
		return $this->name();
	}

	/**
	 * Return the paid amount that is actually going to come to store.
	 * Returned value here will go into paid amount/deposit of LightSpeed.
	 *
	 * @param Cart $cart
	 * @return unknown_type
	 */
	public function paid_amount(Cart $cart) {
		if ($this->admin_name() == "Cash On Delivery")
			return 0.00;
		else
			return $cart->Total;
	}

	/**
	 * Whether this payment method uses a jumper page or not
	 * If it uses a jumper page then process() function must
	 * return a HTML FORM string.
	 *
	 * @return bool
	 */
	public function uses_jumper() {
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
	public function gateway_response_process() {
		global $XLSWS_VARS;
		return false;
	}
}
