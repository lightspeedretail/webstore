<?php

class authorizedotnetaim extends WsPayment
{
	protected $defaultName = "Authorize.Net";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode = true;


	const x_delim_char = "|";
	private $paid_amount;

	/**
	 * The run() function is called from Web Store to run the process.
	 * @return array
	 */
	public function run() {

		if($this->config['live'] == 'test')
			$auth_net_url = "https://test.authorize.net/gateway/transact.dll";
		else
			$auth_net_url = "https://secure.authorize.net/gateway/transact.dll";

		$authnet_values = array (
			"x_login"				=> $this->config['login'],
			"x_delim_char"			=> self::x_delim_char,
			"x_delim_data"			=> "TRUE",
			"x_type"				=> "AUTH_CAPTURE",
			"x_method"				=> "CC",
			"x_tran_key"			=> $this->config['trans_key'],
			"x_relay_response"		=> "FALSE",
			"x_card_num"			=> _xls_number_only($this->CheckoutForm->cardNumber),
			"x_exp_date"			=> $this->CheckoutForm->cardExpiryMonth . "-" . $this->CheckoutForm->cardExpiryYear, //MM-YYYY
			"x_description"			=> $this->objCart->id_str,
			"x_amount"				=> round($this->objCart->total,2),
			"x_first_name"			=> $this->CheckoutForm->contactFirstName,
			"x_last_name"			=> $this->CheckoutForm->contactLastName,
			"x_phone"			    => _xls_number_only($this->CheckoutForm->contactPhone),
			"x_address"				=> ($this->CheckoutForm->billingAddress2 != '' ?
				$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1),
			"x_city"				=> $this->CheckoutForm->billingCity,
			"x_state"				=> $this->CheckoutForm->billingState,
			"x_zip"					=> str_replace(" ","",$this->CheckoutForm->billingPostal),
			"x_country"				=> $this->CheckoutForm->billingCountry,
			"x_customer_ip"			=> $_SERVER['REMOTE_ADDR'],
			"x_email"				=> $this->CheckoutForm->contactEmail,
			"SpecialCode"			=> $this->config['specialcode'],

			"x_ship_to_first_name"	=> $this->CheckoutForm->shippingFirstName,
			"x_ship_to_last_name"	=> $this->CheckoutForm->shippingLastName,
			"x_ship_to_company"		=> $this->CheckoutForm->shippingCompany,
			"x_ship_to_address"		=> ($this->CheckoutForm->shippingAddress2 != '' ?
				$this->CheckoutForm->shippingAddress1 . " " . $this->CheckoutForm->shippingAddress2 : $this->CheckoutForm->shippingAddress1),
			"x_ship_to_city"		=> $this->CheckoutForm->shippingCity,
			"x_ship_to_state"		=> $this->CheckoutForm->shippingState,
			"x_ship_to_zip"			=> $this->CheckoutForm->shippingPostal,
			"x_ship_to_country"		=> $this->CheckoutForm->shippingCountry,

			"x_invoice_num"			=> $this->objCart->id_str,
			"x_cust_id"				=> $this->objCart->customer_id,
			"x_freight"				=> $this->objCart->shipping_sell,
		);

		if($this->config['ccv'] == '1')
			$authnet_values['x_card_code'] = $this->CheckoutForm->cardCVV;


		//if($this->config['live'] == 'test')
		//	$authnet_values['x_test_request'] = 'TRUE';

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

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {
			Yii::log(get_class($this) . " sending ".$this->objCart->id_str." for amt ".$this->objCart->total, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log(get_class($this) . " receiving ".$resp, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		$resp_vals = _xls_delim_to_array($resp , self::x_delim_char);
		$resp_vals = array_values($resp_vals);

		if($resp_vals[0] != '1' ) {
			//unsuccessful
			$arrReturn['success']=false;
			$arrReturn['amount_paid']=0;
			$arrReturn['result'] = Yii::t('global',$resp_vals[3]);
			Yii::log("Declined: ".$resp_vals[3], 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		} else {

			//We have success
			$arrReturn['success']=true;
			$arrReturn['amount_paid']=$this->objCart->total;
			$arrReturn['result']=$resp_vals[4];
			if($this->config['live'] == 'test')
			{
				$arrReturn['amount_paid']=0;
				$arrReturn['result']="TEST ".$resp_vals[4];
			}

		}

		return $arrReturn;
	}






}
