<?php

class paypalpro extends WsPayment
{

	protected $defaultName = "PayPal Pro";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode=true;


	/**
	 * The run() function is called from Web Store to run the process.
	 * The return array should have two elements: the first is true/false if the transaction was successful. The second
	 * string is either the successful Transaction ID, or the failure Error String to display to the user.
	 * @return array
	 */
	public function run()
	{

		$strCardType = $this->CheckoutForm->cardType;

		if ($strCardType=="American Express") $strCardType="Amex";

//ITEMAMT=2&SHIPPINGAMT=15.62&AMT=2&TAXAMT=0

		$str  = "&PAYMENTACTION="	.'Sale';
		$str .= "&ITEMAMT="			.$this->objCart->subtotal;
		$str .= "&SHIPPINGAMT="		.$this->objCart->shipping->shipping_sell;
		$str .= "&AMT="				.round($this->objCart->total,2);
		$str .= "&TAXAMT="			.$this->objCart->TaxTotal;
		$str .= "&INVNUM="			.$this->objCart->id_str;
		$str .= "&CREDITCARDTYPE="	.$strCardType;
		$str .= "&ACCT="			._xls_number_only($this->CheckoutForm->cardNumber); //AAAABBBBCCCCDDDD
		$str .= "&EXPDATE="			.$this->CheckoutForm->cardExpiryMonth.$this->CheckoutForm->cardExpiryYear; //MMYYYY
		$str .= "&CVV2="			.$this->CheckoutForm->cardCVV;
		$str .= "&FIRSTNAME="		.urlencode($this->CheckoutForm->contactFirstName);
		$str .= "&LASTNAME="		.urlencode($this->CheckoutForm->contactLastName);
		$str .= "&STREET="			.urlencode(($this->CheckoutForm->billingAddress2 != '' ?
			$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1));
		$str .= "&CITY="				.urlencode($this->CheckoutForm->billingCity);
		$str .= "&STATE="			.strtoupper($this->CheckoutForm->billingState);
		$str .= "&ZIP="				.str_replace(" ","",$this->CheckoutForm->billingPostal);
		$str .= "&COUNTRYCODE="		.strtoupper($this->CheckoutForm->billingCountry);
		$str .= "&EMAIL="			.$this->CheckoutForm->contactEmail;
		$str .= "&CURRENCYCODE="		.strtoupper(_xls_get_conf('CURRENCY_DEFAULT' , 'USD')); //CAD or USD
		$str .= "&IPADDRESS="		.$_SERVER['REMOTE_ADDR'];

		if($this->config['live'] == 'test')
		{
			$API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$API_UserName = $this->config['api_username_sb'];
			$API_Password = $this->config['api_password_sb'];
			$API_Signature = $this->config['api_signature_sb'];
		}
		else
		{
			$API_Endpoint = 'https://api-3t.paypal.com/nvp';
			$API_UserName = $this->config['api_username'];
			$API_Password = $this->config['api_password'];
			$API_Signature = $this->config['api_signature'];

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

		if (isset($this->CheckoutForm->debug) && $this->CheckoutForm->debug)
			return $strPaypalPost;

		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $strPaypalPost);
		$resp = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($resp);

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {

			Yii::log(" sending ".$this->objCart->id_str." for amt ".$this->objCart->total, 'error', get_class($this));
			Yii::log(" string ".$strPaypalPost, 'error', get_class($this));
			Yii::log(" receiving ".print_r($nvpResArray,true), 'error', get_class($this));
		}

		if (curl_errno($ch)) {
			// moving to display page to display curl errors
			Yii::log("curl_error ".curl_errno($ch), 'error', get_class($this));
			Yii::log("curl_error_msg ".curl_error($ch), 'error', get_class($this));
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



		$arrReturn['jump_url']=false;
		$arrReturn['api'] = $this->apiVersion;
		$arrReturn['jump_form']=null;

		if($nvpResArray['ACK'] != 'SUCCESS' ) {

			$arrReturn['success']=false;
			$arrReturn['amount_paid']=0;
			$errortext = _sp($nvpResArray['L_SHORTMESSAGE0'].": ".$nvpResArray['L_LONGMESSAGE0']);
			$arrReturn['result']=$errortext;
			Yii::log($errortext, 'error', get_class($this));

		} else {

			//We have success
			$arrReturn['success']=true;
			$arrReturn['amount_paid']=$nvpResArray['AMT'];
			$arrReturn['result']=$nvpResArray['TRANSACTIONID'];
			$arrReturn['payment_date']=$nvpResArray['TIMESTAMP'];
		}


		return $arrReturn;



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
