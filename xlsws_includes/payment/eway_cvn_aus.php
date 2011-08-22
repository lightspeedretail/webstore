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
 * Eway payment module
 *
 *
 *
 */

include_once(XLSWS_INCLUDES . 'payment/credit_card.php');

class eway_cvn_aus extends credit_card {
	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('eway_cvn_aus');

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
		return "eWAY CVN (Australia)";
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
		$ret['label']->ToolTip - "The name for the eWAY payment method as displayed to customer during checkout";

		$ret['login'] = new XLSTextBox($objParent);
		$ret['login']->Required = true;
		$ret['login']->Name = _sp('eWAY Customer ID');

		$ret['live'] = new XLSListBox($objParent);
		$ret['live']->Name = _sp('Deployment Mode');
		$ret['live']->AddItem('live' , 'live');
		$ret['live']->AddItem('test' , 'test');

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

		$config = $this->getConfigValues('eway_cvn_aus');

		$ewayCustomerID	= $config['login'];
		$amount			= $cart->Total;


		$eway_cvn_aus_values = array (
			"ewayCardNumber"			=> $fields['ccnum']->Text,
			"ewayCardHoldersName"		=> $fields['ccname']->Text,
			"ewayCardExpiryMonth"		=> $fields['ccexpmon']->SelectedValue,
			"ewayCardExpiryYear" 		=> $fields['ccexpyr']->SelectedValue,
			"ewayCVN"					=> $fields['ccsec']->Text,
			"ewayCustomerInvoiceRef"	=> $cart->IdStr,
			"ewayTotalAmount"			=> round($amount*100),
			"ewayCustomerFirstName"		=> $customer->Firstname,
			"ewayCustomerLastName"		=> $customer->Lastname,
			"ewayCustomerAddress"		=> $customer->Address11 . ", " . $customer->City1 . " " . $customer->State1,
			"ewayCustomerPostcode"		=> $customer->Zip1,
			"ewayCustomerEmail"			=> $customer->Email,
			"ewayCustomerInvoiceDescription"	=> '',
			"ewayTrxnNumber"			=> '',
			"ewayOption1"				=> '',
			"ewayOption2"				=> '',
			"ewayOption3"				=> '',
		);

		$xmlRequest = "<ewaygateway><ewayCustomerID>" . $ewayCustomerID . "</ewayCustomerID>";
		foreach($eway_cvn_aus_values as $key=>$value)
			$xmlRequest .= "<$key>$value</$key>";
		$xmlRequest .= "</ewaygateway>";

		$xmlResponse = $this->sendTransactionToEway($xmlRequest);

		$ewayResponseFields = $this->parseResponse($xmlResponse);

		if($ewayResponseFields["EWAYTRXNSTATUS"]=="True") {
			return $ewayResponseFields["EWAYAUTHCODE"];
		}

		$errortext = $ewayResponseFields["EWAYTRXNERROR"];
		return false;
	}

	/**
	 * sendTransactionToEway
	 *
	 * cURL communcation with Eway to submit parameters for authorization response
	 *
	 * @param $xmlRequest
	 * @return $xmlResponse
	 */
	function sendTransactionToEway($xmlRequest) {
		$config = $this->getConfigValues('eway_cvn_aus');

		if($config['live'] == 'live')
			$eway_cvn_aus_url = "https://www.eway.com.au/gateway_cvn/xmlpayment.asp";
		else
			$eway_cvn_aus_url = "https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp";

		$ch = curl_init($eway_cvn_aus_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$xmlResponse = curl_exec($ch);

		if(curl_errno( $ch ) == CURLE_OK)
			return $xmlResponse;
	}

	/**
	 * parseResponse
	 *
	 * XML Parser
	 *
	 * @param $xmlResponse
	 * @return $responseFields[]
	 */
	function parseResponse($xmlResponse) {
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser,  $xmlResponse, $xmlData, $index);
		$responseFields = array();
		foreach($xmlData as $data)
			if($data["level"] == 2)
				$responseFields[$data["tag"]] = $data["value"];
		return $responseFields;
	}

	public function check() {
		return true;
	}
}
