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
 * Phone payment module
 *
 *
 *
 */

class phone_order extends xlsws_class_payment {
	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues(get_class($this));

		if(isset($config['label']))
			return $config['label'];

		return $this->admin_name();
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 *
	 *
	 */

	public function admin_name() {
		return "Phone Order";
	}

	/**
	 * The description of this module
	 * @return string
	 *
	 *
	 */
	public function info() {
		return "This module provides phone order option in placing a order. Phone Order can be used if customer did not want to type Credit Card information .";
	}

	/**
	 * The Web Admin panel for configuring this payment option
	 *
	 * @param $parentObj (payment panel object)
	 * @return array
	 *
	 */
	public function config_fields($parentObj) {
		$ret = array();

		$ret['label'] = new XLSTextBox($parentObj);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = 'Phone Order';

		$ret['phone'] = new XLSTextBox($parentObj);
		$ret['phone']->Name = _sp('Phone number for calling with credit card');
		$ret['phone']->Text = sprintf(_sp('Please call us on % with your credit card details.') , _xls_get_conf('STORE_PHONE' , 'XXXXX') );

		$ret['ls_payment_method'] = new XLSTextBox($parentObj);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Credit Card';
		$ret['ls_payment_method']->ToolTip = "Please enter the payment method (from LightSpeed) you would like the payment amount to import into";

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
	 * Customer fields
	 *
	 * Returns customer fields
	 *
	 * @param $parentObj (payment panel object)
	 * @return array
	 */
	public function customer_fields($parentObj) {
		$ret= array();

		$config = $this->getConfigValues('phone_order');
		$ret['phone'] = new QLabel($parentObj);
		$ret['phone']->Text = $config['phone'];
		return $ret;
	}

	/**
	 * Check customer fields
	 *
	 * The fields generated and returned in customer_fields will be passed here for validity.
	 * Return true or false
	 *
	 * @param $fields[]
	 * @return boolean
	 */
	public function check_customer_fields($fields) {
		return true;
	}

	/**
	 * paid_amount
	 *
	 * Returns the amount paid extracted from the $cart array
	 * 	 *
	 * @param $cart[]
	 * @return decimal
	 */
	public function paid_amount(Cart $cart) {
		return 0;
	}
}
