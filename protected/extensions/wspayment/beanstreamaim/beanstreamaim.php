<?php

class beanstreamaim extends WsPayment
{
	protected $defaultName = "Beanstream (US/CAN)";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode=true;


	const x_delim_char = "|";
	private $paid_amount;

	/**
	 * The run() function is called from Web Store to run the process.
	 * @return array
	 */
	public function run() {


		$beanstream_url = "https://www.beanstream.com/scripts/process_transaction.asp";

		$strState = $this->CheckoutForm->billingState;
		if ($this->CheckoutForm->billingCountry != "US" && $this->CheckoutForm->billingCountry != "CA") $strState="--";

		$strShipState = $this->CheckoutForm->shippingState;
		if ($this->CheckoutForm->shippingCountry != "US" && $this->CheckoutForm->shippingCountry != "CA") $strShipState="--";

		$beanstream_values = array (
			"requestType"		=> "BACKEND",
			"merchant_id"		=> $this->config['login'],
			"trnCardNumber"		=> _xls_number_only($this->CheckoutForm->cardNumber),
			"trnCardOwner"		=> $this->CheckoutForm->cardNameOnCard,
			"trnExpMonth"		=> $this->CheckoutForm->cardExpiryMonth,
			"trnExpYear"		=> substr($this->CheckoutForm->cardExpiryYear,2,2),
			"trnCardCvd"		=> $this->CheckoutForm->cardCVV,
			"trnOrderNumber"	=> $this->objCart->id_str,
			"trnAmount"			=> $this->objCart->total,
			"ordName"			=> $this->CheckoutForm->contactFirstName . " " . $this->CheckoutForm->contactLastName,
			"ordAddress1"		=> $this->CheckoutForm->billingAddress1,
			"ordAddress2"		=> $this->CheckoutForm->billingAddress2,
			"ordPostalCode"		=> str_replace(" ","",$this->CheckoutForm->billingPostal),
			"ordEmailAddress"	=> $this->CheckoutForm->contactEmail,
			"ordPhoneNumber"	=> _xls_number_only($this->CheckoutForm->contactPhone),
			"ordCity"			=> $this->CheckoutForm->billingCity,
			"ordProvince"		=> $strState,
			"ordCountry"		=> $this->CheckoutForm->billingCountry,


			"shipName"			=> $this->CheckoutForm->shippingFirstName." ".$this->CheckoutForm->shippingLastName,
			"shipAddress1"		=> $this->CheckoutForm->shippingAddress1,
			"shipAddress2"		=> $this->CheckoutForm->shippingAddress2,
			"shipCity"			=> $this->CheckoutForm->shippingCity,
			"shipProvince"		=> $strShipState,
			"shipPostalCode"	=> $this->CheckoutForm->shippingPostal,
			"shipCountry"		=> $this->CheckoutForm->shippingCountry,
			"shippingMethod"	=> $this->objCart->shipping->shipping_data

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

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {
			Yii::log(get_class($this) . " sending ".$this->objCart->id_str." for amt ".$this->objCart->total, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log(get_class($this) . " receiving ".$resp, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		parse_str($resp, $resp_vals);


		if($resp_vals['trnApproved'] != '1' ) {
			//unsuccessful
			$arrReturn['success']=false;
			$arrReturn['amount_paid']=0;
			$arrReturn['result'] = Yii::t('global',urldecode($resp_vals['messageText']));
			Yii::log("Declined: ".urldecode($resp_vals['messageText']), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			if(stripos($resp_vals['messageText'],"Enter your phone number")>0)
				$arrReturn['result'] = Yii::t('global',"Declined: Your phone number is missing in your profile, which is required by the credit card processor. Click {link} to update your account with your phone number. Then return to checkout.",array("{link}"=>CHtml::link(Yii::t('global','Edit Account'),Yii::app()->createUrl("myaccount/edit"))));

		} else {

			//We have success
			$arrReturn['success']=true;
			$arrReturn['amount_paid']=  ($resp_vals['authCode'] == "TEST" ? 0.00 : $resp_vals['trnAmount']);
			$arrReturn['result']=$resp_vals['authCode'];
			$arrReturn['payment_date']=$resp_vals['trnDate'];

		}

		return $arrReturn;
		

	}





}
