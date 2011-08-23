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
 * Authorize.net Simple Integration payment module
 *
 *
 *
 */

class authorize_dot_net_sim extends xlsws_class_payment {
	const x_delim_char = "|";

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('authorize_dot_net_sim');

		if(isset($config['label']))
			return $config['label'];

		return "Credit Card";
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 *
	 *
	 */
	public function admin_name() {
		return _sp('Authorize.net Simple Integration');
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

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = 'Credit card (Visa, Mastercard, Amex)';

		$ret['login'] = new XLSTextBox($objParent);
		$ret['login']->Required = true;
		$ret['login']->Name = _sp('Login ID');

		$ret['trans_key'] = new XLSTextBox($objParent);
		$ret['trans_key']->Required = true;
		$ret['trans_key']->Name = _sp('Transaction Key');

		$ret['md5hash'] = new XLSTextBox($objParent);
		$ret['md5hash']->Required = false;
		$ret['md5hash']->Name = _sp('MD5 Hash Value');

		$ret['live'] = new XLSListBox($objParent);
		$ret['live']->Name = _sp('Deployment Mode');
		$ret['live']->AddItem('live' , 'live');
		$ret['live']->AddItem('test' , 'test');
		//$ret['live']->AddItem('dev' , 'dev'); //See note in process() statement about this option

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
	public function check_config_fields($fields) {
		return true;
	}

	/**
	 * process
	 *
	 * Process function to build parameters to pass for CC authorization
	 * For more information on these options,
	 * See http://developer.authorize.net/guides/AIM/Transaction_Response/Fields_in_the_Payment_Gateway_Response.htm
	 *
	 * @param $cart[], $fields[], ref $errortext
	 * @return $str[]
	 */
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues('authorize_dot_net_sim');

		$auth_net_login_id	= $config['login'];
		$auth_net_tran_key	= $config['trans_key'];

		/**
		 * This option, and the commented $ret['live']->AddItem('dev' , 'dev') above, are only for API development work.
		 * Regular Authorize.net customers will only use "live" and "test" modes through their account, which can be
		 * chosen through the Web Admin panel.
		 *
		 */
		if($config['live'] == 'dev')
			$auth_net_url	= "https://test.authorize.net/gateway/transact.dll";
		else
			$auth_net_url	= "https://secure.authorize.net/gateway/transact.dll";

		$str = "";

		$str .= "<FORM action=\"$auth_net_url\" method=\"POST\">";
		$str .= $this->InsertFP($auth_net_login_id, $auth_net_tran_key, round($cart->Total,2), $cart->Currency);

		$str .= _xls_make_hidden('x_invoice_num', $cart->IdStr);
		$str .= _xls_make_hidden('x_first_name', $customer->Firstname);
		$str .= _xls_make_hidden('x_last_name', $customer->Lastname);
		$str .= _xls_make_hidden('x_company', $customer->Company);
		$str .= _xls_make_hidden('x_address', $customer->Address11 . " " . $customer->Address12);
		$str .= _xls_make_hidden('x_city', $customer->City1);
		$str .= _xls_make_hidden('x_state', $customer->State1);
		$str .= _xls_make_hidden('x_zip', $customer->Zip1);
		$str .= _xls_make_hidden('x_country', $customer->Country1);
		$str .= _xls_make_hidden('x_phone', $customer->Mainphone);

		$str .= _xls_make_hidden('x_email', $customer->Email);
		$str .= _xls_make_hidden('x_cust_id', "WC-" . $cart->CustomerId);

		$str .= _xls_make_hidden('x_ship_to_first_name',   $cart->ShipFirstname);
		$str .= _xls_make_hidden('x_ship_to_last_name',   $cart->ShipLastname);
		$str .= _xls_make_hidden('x_ship_to_company',   $cart->ShipCompany);
		$str .= _xls_make_hidden('x_ship_to_address',   $cart->ShipAddress1 . " " . $cart->ShipAddress2);
		$str .= _xls_make_hidden('x_ship_to_city',   $cart->ShipCity);
		$str .= _xls_make_hidden('x_ship_to_state',   $cart->ShipState);
		$str .= _xls_make_hidden('x_ship_to_zip',   $cart->ShipZip);
		$str .= _xls_make_hidden('x_ship_to_country',   $customer->Country2);

		$str .= _xls_make_hidden('x_description',  _xls_get_conf( 'STORE_NAME'  , "Online") . " Order");

		$str .= _xls_make_hidden('x_login',   $auth_net_login_id);
		$str .= _xls_make_hidden('x_type',   'AUTH_CAPTURE');
		$str .= _xls_make_hidden('x_currency_code',   $cart->Currency);  //trying to get currency code to submit
		$str .= _xls_make_hidden('x_amount',  round($cart->Total,2));
		$str .= _xls_make_hidden('x_show_form',   'PAYMENT_FORM');

		$str .= _xls_make_hidden('x_relay_url',   _xls_site_dir() . "/" . "xls_payment_capture.php");
		$str .= _xls_make_hidden('x_relay_response',   'TRUE');

		if($config['live'] == 'test')
			$str .= _xls_make_hidden('x_test_request',   'TRUE');

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
	 * hmac
	 * Computes hash, then converts to hex format, used as part of "fingerprint" for Auth.net simple
	 * @param $key (transaction key), $data[]
	 * @return string
	 */
	public function hmac ($key, $data) {
		return (bin2hex (mhash(MHASH_MD5, $data, $key)));
	}

	/**
	 * CalculateFP
	 * Calculate and return Fingerprint for Auth.net simple access
	 * Use when you need control on the HTML output
	 * @param $loginid string
	 * @param $x_tran_key string
	 * @param $amount decimal
	 * @param $sequence int
	 * @param $tstamp int (time)
	 * @param $currency string (optional)
	 * @return string
	 */
	public function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp, $currency = "") {
		return ($this->hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
	}

	// Inserts the hidden variables in the HTML FORM required for SIM
	// Invokes hmac function to calculate fingerprint.

	// public function InsertFP ($loginid, $x_tran_key, $amount, $sequence, $currency = "")
	/**
	 * InsertFP
	 * Creates hidden fields for Auth.net simple access
	 * inclued as part of FORM submitted
	 * @param $loginid string
	 * @param $x_tran_key string
	 * @param $amount decimal
	 * @param $currency string
	 * @return string
	 */
	public function InsertFP ($loginid, $x_tran_key, $amount, $currency) {
		srand(time());

		$sequence = rand(1, 1000);

		$tstamp = time ();

		$fingerprint = $this->hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

		$str = ('<input type="hidden" name="x_fp_sequence" value="' . $sequence . '"/>' );
		$str .= ('<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '"/>' );
		$str .= ('<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '"/>' );

		return $str;
	}

	/**
	 * check
	 *
	 */
	public function check() {
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

		if(!isset($XLSWS_VARS['x_response_code']))
			return false;

		if(!isset($XLSWS_VARS['x_invoice_num']))
			return false;
		else
			$order_id = $XLSWS_VARS['x_invoice_num'];

		if($XLSWS_VARS['x_response_code'] != 1){
			// failed order
			_xls_log("authorize.net.sim failed order payment recieved " . print_r($XLSWS_VARS , true));

			return false;
		}

		//confirm md5 hash
		$config = $this->getConfigValues('authorize_dot_net_sim');

		if(isset($config['md5hash'])  && ($config['md5hash']) && isset($XLSWS_VARS['x_MD5_Hash'])) {
			$md5 = strtolower(md5($config['md5hash'] . $config['login'] . $XLSWS_VARS['x_trans_id'] . $XLSWS_VARS['x_amount']));
			if(strtolower($XLSWS_VARS['x_MD5_Hash']) != $md5) {
				_xls_log("authorize.net.sim failed md5 hash. Found $XLSWS_VARS[x_MD5_Hash] expecting $md5");
				return false;
			}
		}

		return array(
			'order_id' => $order_id,
			'amount' => isset($XLSWS_VARS['x_amount']) ? $XLSWS_VARS['x_amount'] : 0,
			'success' => true,
			'data' => isset($XLSWS_VARS['x_trans_id']) ? $XLSWS_VARS['x_trans_id'] : ''
		);
	}
}
