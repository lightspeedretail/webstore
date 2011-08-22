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
 * Worldpay payment module
 *
 *
 *
 */

class worldpay extends xlsws_class_payment {
	const x_delim_char = "|";

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('worldpay');

		if(isset($config['label']))
			return $config['label'];

		return "WorldPay";
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 *
	 *
	 */
	public function admin_name() {
		return "WorldPay";
	}

	/**
	 * The Web Admin panel for configuring this payment option
	 *
	 * @param $parentObj (payment panel object)
	 * @return array
	 *
	 */
	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new QTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = 'WorldPay';


		$ret['login'] = new QTextBox($objParent);
		$ret['login']->Required = true;
		$ret['login']->Name = _sp('Installation ID');

		$ret['live'] = new QListBox($objParent);
		$ret['live']->Name = _sp('Live/Test');
		$ret['live']->AddItem('test' , 'test');
		$ret['live']->AddItem('live' , 'live');

		$ret['ls_payment_method'] = new XLSTextBox($objParent);
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
	public function check_config_fields($fields ) {
		return true;
	}

	/**
	 * process
	 *
	 * Process function to build parameters to pass for CC authorization
	 * For more information on these options,
	 *
	 * @param $cart[], $fields[], ref $errortext
	 * @return string|boolean
	 */
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues('worldpay');

		$installation_id = $config['login'];
		$worldpay_url = "";
		$str = "";

		if($config['live'] == 'live')
			$worldpay_url = "https://secure.wp3.rbsworldpay.com/wcc/purchase";
		else
			$worldpay_url = "https://select-test.wp3.rbsworldpay.com/wcc/purchase";

		$str .= "<FORM name=\"worldpayform\" action=\"$worldpay_url\" method=\"POST\">";
		if($config['live'] == 'test')
				$str .= _xls_make_hidden('testMode',  '100');
		$str .= _xls_make_hidden('address',   $customer->Address11 . " " .$customer->Address12);
		$str .= _xls_make_hidden('postcode',   $customer->Zip1);
		$str .= _xls_make_hidden('country',   $customer->Country1);
		$str .= _xls_make_hidden('email',   $customer->Email);
		$str .= _xls_make_hidden('name',   $customer->Firstname . " " .$customer->Lastname);
		$str .= _xls_make_hidden('tel',   $customer->Mainphone);
		$str .= _xls_make_hidden('instId',   $installation_id);
		$str .= _xls_make_hidden('currency',   _xls_get_conf('CURRENCY_DEFAULT' , 'USD'));
		$str .= _xls_make_hidden('cartId',  $cart->IdStr);
		$str .= _xls_make_hidden('M_cartlink',   $cart->Linkid);
		$str .= _xls_make_hidden('MC_callback',   _xls_site_dir() . "/" . "xls_payment_capture.php");

		$str .= _xls_make_hidden('amount',  round($cart->Total , 2));

		$str .= ('</FORM>');

		return $str;
	}

	/**
	 * Whether this payment method uses a jumper page or not
	 * If it uses a jumper page then process() function must return a HTML FORM string.
	 * @return bool
	 */
	public function uses_jumper() {
		return true;
	}

	/**
	 * gateway_response_process
	 *
	 * Processes processor gateway response
	 * Processes returned $_GET or $_POST variables from the third party website
	 */
	public function gateway_response_process() {
		global $XLSWS_VARS;

		if(!isset($XLSWS_VARS['instId']))
			return false;

		$config = $this->getConfigValues('worldpay');

		if($XLSWS_VARS['instId'] != $config['login']) // it's not the same!
			return false;

		// Did transaction fail?
		if(!isset($XLSWS_VARS['transId']))
			return false;

		if(!isset($XLSWS_VARS['cartId']))
			return false;
		else {
			$cart = Cart::LoadByIdStr($XLSWS_VARS['cartId']);

			$order_id = $cart->IdStr;
		}

		if(empty($XLSWS_VARS['transId'])) {
			// failed order
			_xls_log("WorldPay failed order payment recieved " . print_r($XLSWS_VARS , true)) ;

			return false;
		}

		return array(
			'order_id' => $order_id,
			'amount' => isset($XLSWS_VARS['authAmount']) ? $XLSWS_VARS['authAmount'] : 0,
			'success' => isset($XLSWS_VARS['transId']) ? true : false,
			'data' => isset($XLSWS_VARS['transId']) ? $XLSWS_VARS['transId'] : ''
		);
	}
}
