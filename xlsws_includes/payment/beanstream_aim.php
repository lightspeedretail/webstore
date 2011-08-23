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
 * Beanstream Advanced Integration payment module
 *
 *
 *
 */

include_once(XLSWS_INCLUDES . 'payment/credit_card.php');

class beanstream_aim extends credit_card {
	private $paid_amt;

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('beanstream_aim');

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
		return "Beanstream (Canada/USA)";
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
		$ret['label']->Text = 'Credit card';
		$ret['label']->ToolTip - "The name for the Beanstream payment method as displayed to customer during checkout";

		$ret['login'] = new XLSTextBox($objParent);
		$ret['login']->Required = true;
		$ret['login']->Name = _sp('Merchant ID');

		/*$ret['live'] = new XLSListBox($objParent);
		$ret['live']->Name = _sp('Deployment Mode');
		$ret['live']->AddItem('live' , 'live');
		$ret['live']->AddItem('test' , 'test'); */

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
	 *
	 * @param $cart[], $fields[], ref $errortext
	 * @return string|boolean
	 */
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues('beanstream_aim');

		$merchantId = $config['login'];
		$amount = $cart->Total;
		$beanstream_url = "https://www.beanstream.com/scripts/process_transaction.asp";
		$yearArr = toCharArray($fields['ccexpyr']->SelectedValue);
		$exp_year = $yearArr[2] . $yearArr[3];

		$beanstream_values = array (
			"requestType"		=> "BACKEND",
			"merchant_id"		=> $merchantId,
			"trnCardNumber"		=> $fields['ccnum']->Text,
			"trnCardOwner"		=> $fields['ccname']->Text,
			"trnExpMonth"		=> $fields['ccexpmon']->SelectedValue,
			"trnExpYear"		=> $exp_year,
			"trnCardCvd"		=> $fields['ccsec']->Text,
			"trnOrderNumber"	=> $cart->IdStr,
			"trnAmount"			=> $amount,
			"ordName"			=> $customer->Firstname . " " . $customer->Lastname,
			"ordAddress1"		=> $customer->Address11,
			"ordAddress2"		=> $customer->Address12,
			"ordPostalCode"		=> $customer->Zip1,
			"ordEmailAddress"	=> $customer->Email,
			"ordPhoneNumber"	=> $cart->Phone,
			"ordCity"			=> $customer->City1,
			"ordProvince"		=> $customer->State1,
			"ordCountry"		=> $customer->Country1
		);

		$beanstream_fields = "";

		foreach( $beanstream_values as $key => $value )
			$beanstream_fields .= "$key=" . urlencode( $value ) . "&";

		$ch = curl_init($beanstream_url);
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $beanstream_fields, "& " )); // use HTTP POST to send form data
		### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		$resp_vals = array();

		parse_str($resp, $resp_vals);

		if ($resp_vals['trnApproved'] != "1") {
			$errortext = _sp(urldecode($resp_vals['messageText']));
			return FALSE;
		}

		($resp_vals['authCode'] == "TEST") ? $this->paid_amt = 0.00 : $this->paid_amt = $amount;

		return $resp_vals['authCode'];
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
		return $this->paid_amt;
	}

	public function check() {
		return true;
	}
}
