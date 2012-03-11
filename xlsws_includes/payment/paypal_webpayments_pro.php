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
 * Paypal Web Pro Advanced Integration payment module
 *
 *
 *
 */

include_once(XLSWS_INCLUDES . 'payment/credit_card.php');

class paypal_webpayments_pro extends credit_card {

	private $paid_amount;

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

		return "Credit Card";
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 *
	 *
	 */
	public function admin_name() {
		return "PayPal Payments Pro (Advanced Integration)";
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

		$ret['api_username'] = new XLSTextBox($objParent);
		$ret['api_username']->Required = true;
		$ret['api_username']->Name = _sp('API Username');
		$ret['api_username']->Width = 300;

		$ret['api_password'] = new XLSTextBox($objParent);
		$ret['api_password']->Required = true;
		$ret['api_password']->Name = _sp('API Password');
		$ret['api_password']->Width = 300;

		$ret['api_signature'] = new XLSTextBox($objParent);
		$ret['api_signature']->Required = true;
		$ret['api_signature']->Name = _sp('API Signature');
		$ret['api_signature']->Width = 300;

		$ret['live'] = new XLSListBox($objParent);
		$ret['live']->Name = _sp('Deployment Mode');
		$ret['live']->AddItem('Live','live');
		$ret['live']->AddItem('Sandbox (test)','test');
		//$ret['live']->AddItem('dev' , 'dev'); //See note in process() statement about this option


		$ret['api_username_sb'] = new XLSTextBox($objParent);
		$ret['api_username_sb']->Required = false;
		$ret['api_username_sb']->Name = _sp('API Username (Sandbox)');
		$ret['api_username_sb']->Width = 300;

		$ret['api_password_sb'] = new XLSTextBox($objParent);
		$ret['api_password_sb']->Required = false;
		$ret['api_password_sb']->Name = _sp('API Password (Sandbox)');
		$ret['api_password_sb']->Width = 300;

		$ret['api_signature_sb'] = new XLSTextBox($objParent);
		$ret['api_signature_sb']->Required = false;
		$ret['api_signature_sb']->Name = _sp('API Signature (Sandbox)');
		$ret['api_signature_sb']->Width = 300;


		$ret['ls_payment_method'] = new XLSTextBox($objParent);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Web Credit Card';
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
	 *
	 * @param $cart[], $fields[], ref $errortext
	 * @return string|boolean
	 */
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues(get_class($this));

		$auth_net_login_id = $config['login'];
		$auth_net_tran_key = $config['trans_key'];

		$strCardType = $fields['cctype']->SelectedValue;
		if ($strCardType=="American Express") $strCardType="Amex";
		
			
		$str  = "&PAYMENTACTION="	.'Sale';
		$str .= "&ITEMAMT="			.$cart->Subtotal;
		$str .= "&SHIPPINGAMT="		.$cart->ShippingSell;
		$str .= "&AMT="				.round($cart->Total,2);
		$str .= "&TAXAMT="			.round(round($cart->Tax1,2)+round($cart->Tax2,2)+
									round($cart->Tax3,2)+round($cart->Tax4,2)+round($cart->Tax5,2),2);
		$str .= "&INVNUM="			.$cart->IdStr;
		$str .= "&CREDITCARDTYPE="	.$strCardType;
		$str .= "&ACCT="			._xls_number_only($fields['ccnum']->Text); //AAAABBBBCCCCDDDD
		$str .= "&EXPDATE="			.$fields['ccexpmon']->SelectedValue.$fields['ccexpyr']->SelectedValue; //MMYYYY
		$str .= "&CVV2="			.$fields['ccsec']->Text;
		$str .= "&FIRSTNAME="		.urlencode($customer->Firstname);
		$str .= "&LASTNAME="			.urlencode($customer->Lastname);
		$str .= "&STREET="			.urlencode(($customer->Address12 != '' ? 
			$customer->Address11 . " " . $customer->Address12 : $customer->Address11));
		$str .= "&CITY="				.urlencode($customer->City1);
		$str .= "&STATE="			.strtoupper($customer->State1);
		$str .= "&ZIP="				.str_replace(" ","",$customer->Zip1);
		$str .= "&COUNTRYCODE="		.strtoupper($customer->Country1);
		$str .= "&EMAIL="			.$customer->Email;
		$str .= "&CURRENCYCODE="		.strtoupper(_xls_get_conf('CURRENCY_DEFAULT' , 'USD')); //CAD or USD
		$str .= "&IPADDRESS="		.$_SERVER['REMOTE_ADDR'];
			
		if($config['live'] == 'test')
        {
        	$API_Endpoint = 'https://api.sandbox.paypal.com/nvp';
        	$API_UserName = $config['api_username_sb'];
			$API_Password = $config['api_password_sb'];
			$API_Signature = $config['api_signature_sb'];
    	}
   		else
        {
        	$API_Endpoint = 'https://api-3t.paypal.com/nvp';
       		$API_UserName = $config['api_username'];
			$API_Password = $config['api_password'];
			$API_Signature = $config['api_signature'];
 
		}

    
    	$version = '57.0';
    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	    curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
	    // Turn off the server and peer verification (TrustManager Concept).
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);

    	// Set the API operation, version, and API signature in the request.	
    	$strPaypalPost="METHOD=doDirectPayment&VERSION=".urlencode($version)."&PWD=".urlencode($API_Password).
    			"&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature).$str;

	    // Set the request as a POST FIELD for curl.
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $strPaypalPost);
	    $resp = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($resp);
	    	    
		if(_xls_get_conf('DEBUG_PAYMENTS' , false)) {
			QApplication::Log(E_ERROR, get_class($this), "sending ".$cart->IdStr." for amt ".$cart->Total);
			QApplication::Log(E_ERROR, get_class($this), "receiving ".print_r($nvpResArray,true));
		}
		
		if (curl_errno($ch)) {
			// moving to display page to display curl errors
			QApplication::Log(E_ERROR, get_class($this), "curl_error ".curl_errno($ch));
			QApplication::Log(E_ERROR, get_class($this), "curl_error_msg ".curl_error($ch));
			$errortext = _sp("There was a PayPal system error. Check error logs.");
			curl_close($ch);
			  return FALSE;
		 } else {
			 //closing the curl
				curl_close($ch);
				$nvpResArray['ACK'] = strtoupper($nvpResArray['ACK']);
		  }


		/*
			Sample returned 
			[TIMESTAMP] => 2009-06-09T22:23:58Z
			[CORRELATIONID] => aa77bb77aa77
			[ACK] => SUCCESS
			[VERSION] => 57.0
			[BUILD] => 921486
			[AMT] => 52.49
			[CURRENCYCODE] => USD
			[AVSCODE] => X
			[CVV2MATCH] => M
			[TRANSACTIONID] => 2SK51234GE217235G
		
			[TIMESTAMP] => 2009-06-09T22:24:03Z
			[CORRELATIONID] => aa77bb77aa77
			[ACK] => Failure
			[VERSION] => 57.0
			[BUILD] => 921486
			[L_ERRORCODE0] => 10508
			[L_SHORTMESSAGE0] => Invalid Data
			[L_LONGMESSAGE0] => This transaction cannot be processed. Please enter a valid credit card expiration date.
			[L_SEVERITYCODE0] => Error
			[AMT] => 52.49
			[CURRENCYCODE] => USD	
			*/


		if($nvpResArray['ACK'] != 'SUCCESS' ) {
			$this->paid_amount = 0;
			$errortext = _sp($nvpResArray['L_SHORTMESSAGE0'].": ".$nvpResArray['L_LONGMESSAGE0']);
			QApplication::Log(E_ERROR, get_class($this), "Transaction Error: ".$errortext);
			return array(false,$errortext);
		}

		$this->paid_amount = $cart->Total;
		// on success, return the transaction ID
		return array(true,$nvpResArray['TRANSACTIONID']);
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
	
	/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	  */

	function deformatNVP($nvpstr)
	{
	
		$intial=0;
	 	$nvpArray = array();
	
	
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
	
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}


}
