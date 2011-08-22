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
 * Authorize.net Advanced Integration payment module
 *
 *
 *
 */

include_once(XLSWS_INCLUDES . 'payment/credit_card.php');

class authorize_dot_net_aim extends credit_card {
	const x_delim_char = "|";
	private $paid_amount;

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('authorize_dot_net_aim');

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
		return "Authorize.Net Advanced Integration";
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

		$ret['live'] = new XLSListBox($objParent);
		$ret['live']->Name = _sp('Deployment Mode');
		$ret['live']->AddItem('live' , 'live');
		$ret['live']->AddItem('test' , 'test');
		//$ret['live']->AddItem('dev' , 'dev'); //See note in process() statement about this option

		$ret['specialcode'] = new XLSTextBox($objParent);
		$ret['specialcode']->Name = _sp('Special Transaction Code (if any)');

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
	 * @return string|boolean
	 */
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues('authorize_dot_net_aim');

		$auth_net_login_id = $config['login'];
		$auth_net_tran_key = $config['trans_key'];

		/**
		 * This option, and the commented $ret['live']->AddItem('dev' , 'dev') above, are only for API development work.
		 * Regular Authorize.net customers will only use "live" and "test" modes through their account, which can be
		 * chosen through the Web Admin panel.
		 *
		 */
		if($config['live'] == 'dev')
			$auth_net_url = "https://test.authorize.net/gateway/transact.dll";
		else
			$auth_net_url = "https://secure.authorize.net/gateway/transact.dll";

		$authnet_values = array (
			"x_login"				=> $auth_net_login_id,
			"x_delim_char"			=> self::x_delim_char,
			"x_delim_data"			=> "TRUE",
			"x_type"				=> "AUTH_CAPTURE",
			"x_method"				=> "CC",
			"x_tran_key"			=> $auth_net_tran_key,
			"x_relay_response"		=> "FALSE",
			"x_card_num"			=> $fields['ccnum']->Text,
			"x_exp_date"			=> $fields['ccexpmon']->SelectedValue . "-" .  $fields['ccexpyr']->SelectedValue,
			"x_description"			=> $cart->IdStr,
			"x_amount"				=> $cart->Total,
			"x_first_name"			=> $customer->Firstname ,
			"x_last_name"			=> $customer->Lastname,
			"x_address"				=> $customer->Address11 . " " . $customer->Address12,
			"x_city"				=> $customer->City1,
			"x_state"				=> $customer->State1,
			"x_zip"					=> $customer->Zip1,
			"x_country"				=> $customer->Country1,
			"x_customer_ip"			=> $_SERVER['REMOTE_ADDR'],
			"x_email"				=> $customer->Email,
			"SpecialCode"			=> $config['specialcode'],

			"x_ship_to_first_name"	=> $cart->ShipFirstname ,
			"x_ship_to_last_name"	=> $cart->ShipLastname,
			"x_ship_to_company"		=> $cart->ShipCompany,
			"x_ship_to_address"		=> $cart->ShipAddress1 . " " . $cart->ShipAddress2,
			"x_ship_to_city"		=> $cart->ShipCity,
			"x_ship_to_state"		=> $cart->ShipState,
			"x_ship_to_zip"			=> $cart->ShipZip,
			"x_ship_to_country"		=> $cart->ShipCountry,

			"x_invoice_num"			=> $cart->IdStr,
			"x_cust_id"				=> $cart->CustomerId,
			"x_freight"				=> $cart->ShippingSell,
		);

		if($config['live'] == 'test')
			$authnet_values['x_test_request'] = 'TRUE';

		$auth_net_fields = "";
		foreach( $authnet_values as $key => $value )
			$auth_net_fields .= "$key=" . urlencode( $value ) . "&";

		$ch = curl_init($auth_net_url);
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $auth_net_fields, "& " )); // use HTTP POST to send form data
		### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);

		$resp_vals = _xls_delim_to_array($resp , self::x_delim_char);
		$resp_vals = array_values($resp_vals);

		if($resp_vals[0] != '1' ) {
			$this->paid_amount = 0;
			$errortext = _sp("Your credit card has been declined");
			return FALSE;
		}

		$this->paid_amount = $cart->Total;
		// on success, return the transaction ID
		return $resp_vals[4];
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
		return $this->paid_amount;
	}

	/**
	 * check
	 *
	 */
	public function check() {
		return true;
	}
}
