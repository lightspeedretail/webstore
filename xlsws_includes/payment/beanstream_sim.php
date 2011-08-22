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

class beanstream_sim extends xlsws_class_payment {
	private $paid_amt;

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('beanstream_sim');

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
		return "Beanstream Simple Integration (Canada/USA)";
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
		$ret['login']->ToolTip = "Your Beanstream Merchant ID for processing payments online";

		$ret['md5hash'] = new XLSTextBox($objParent);
		$ret['md5hash']->Required = false;
		$ret['md5hash']->Name = _sp('MD5 Hash Value (Optional)');
		$ret['md5hash']->ToolTip = "If your account requires that an MD5 hash is passed for added security, enter it here.";

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

		$config = $this->getConfigValues('beanstream_sim');

		$merchantId		= $config['login'];
		$hashval		= $config['md5hash'];

		$amount			= $cart->Total;
		$beanstream_url	= "https://www.beanstream.com/scripts/payment/payment.asp";

		$beanstream_values = array (
			"merchant_id"		=> $merchantId,
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
			"hashValue"			=> $hashval,
			"approvedPage"		=> _xls_site_dir() . "/" . "xls_payment_capture.php",
			"declinedPage"		=> _xls_site_dir() . "/" . "xls_payment_capture.php",
			"ordCountry"		=> $customer->Country1
		);

		$str = "";

		$str .= "<FORM name=\"beanstream_form\" action=\"$beanstream_url\" method=\"POST\">";
		foreach( $beanstream_values as $key => $value )
			$str .= _xls_make_hidden($key, $value);

		$str .=  ('</FORM>');
		//_xls_log($str);
		return $str;
	}

	/**
	 * gateway_response_process
	 *
	 * Processes processor gateway response
	 * Processes returned $_GET or $_POST variables from the third party website
	 */
	public function gateway_response_process() {
		global $XLSWS_VARS;
		$retarr = array();

		if ($XLSWS_VARS['trnApproved'] == '1') {
			$retarr =  array(
				'order_id' => $XLSWS_VARS['trnOrderNumber'],
				'amount' => $XLSWS_VARS['trnAmount'],
				'success' => true,
				'data' => $XLSWS_VARS['authCode'],
			);
			_xls_log("Beanstream success " . print_r($retarr , true)) ;
		} else {
			$url = _xls_site_dir() . "/" . "index.php?xlspg=msg";
			_xls_stack_add('msg', "Your payment could not be processed due to the following error: " . $XLSWS_VARS['messageText'] . ". Please try again");
			$retarr =  array(
				'order_id' => $XLSWS_VARS['trnOrderNumber'],
				'output' => "<html><head><meta http-equiv=\"refresh\" content=\"1;url=$url\"></head><body><a href=\"$url\">Verifying order, please wait...</a></body></html>",
				'success' => false,
			);
			_xls_log("Beanstream fail " . print_r($XLSWS_VARS,true));
		}

		return $retarr;
	}

	public function check() {
		return true;
	}
}
