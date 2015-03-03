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
		{
			$auth_net_url = "https://test.authorize.net/gateway/transact.dll";
		}
		else
		{
			$auth_net_url = "https://secure.authorize.net/gateway/transact.dll";
		}

		$authnet_values = array (
			"x_login"               => $this->config['login'],
			"x_delim_char"          => self::x_delim_char,
			"x_delim_data"          => "TRUE",
			"x_type"                => "AUTH_CAPTURE",
			"x_method"              => "CC",
			"x_tran_key"            => $this->config['trans_key'],
			"x_relay_response"      => "FALSE",
			"x_card_num"            => _xls_number_only($this->CheckoutForm->cardNumber),
			"x_exp_date"            => $this->CheckoutForm->cardExpiryMonth . "-" . $this->CheckoutForm->cardExpiryYear, //MM-YYYY
			"x_description"         => $this->objCart->id_str,
			"x_amount"              => round($this->objCart->total,2),
			"x_first_name"          => $this->CheckoutForm->contactFirstName,
			"x_last_name"           => $this->CheckoutForm->contactLastName,
			"x_phone"               => _xls_number_only($this->CheckoutForm->contactPhone),
			"x_address"             => ($this->CheckoutForm->billingAddress2 != '' ?
					$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1),
			"x_city"                => $this->CheckoutForm->billingCity,
			"x_state"               => $this->CheckoutForm->billingStateCode,
			"x_zip"                 => str_replace(" ","",$this->CheckoutForm->billingPostal),
			"x_country"             => $this->CheckoutForm->billingCountryCode,
			"x_customer_ip"         => $_SERVER['REMOTE_ADDR'],
			"x_email"               => $this->CheckoutForm->contactEmail,
			"SpecialCode"           => $this->config['specialcode'],

			"x_ship_to_first_name"  => $this->CheckoutForm->shippingFirstName,
			"x_ship_to_last_name"   => $this->CheckoutForm->shippingLastName,
			"x_ship_to_company"     => $this->CheckoutForm->shippingCompany,
			"x_ship_to_address"     => ($this->CheckoutForm->shippingAddress2 != '' ?
					$this->CheckoutForm->shippingAddress1 . " " . $this->CheckoutForm->shippingAddress2 : $this->CheckoutForm->shippingAddress1),
			"x_ship_to_city"        => $this->CheckoutForm->shippingCity,
			"x_ship_to_state"       => $this->CheckoutForm->shippingStateCode,
			"x_ship_to_zip"         => $this->CheckoutForm->shippingPostal,
			"x_ship_to_country"     => $this->CheckoutForm->shippingCountryCode,

			"x_invoice_num"         => $this->objCart->id_str,
			"x_solution_id"         => 'A1000010',
			"x_cust_id"             => $this->objCart->customer_id,
			"x_freight"             => $this->objCart->shippingCharge,
		);

		if($this->config['ccv'] == '1')
		{
			$authnet_values['x_card_code'] = $this->CheckoutForm->cardCVV;
		}


		Yii::log(
			sprintf(
				"%s sending %s for amt %s\nRequest %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->objCart->total,
				print_r($this->obfuscateRequestArray($authnet_values), true)
			),
			$this->logLevel,
			'application.'.__CLASS__.'.'.__FUNCTION__
		);


		$authNetFields = "";
		foreach( $authnet_values as $key => $value )
		{
			$authNetFields .= "$key=" . urlencode($value) . "&";
		}

		$ch = curl_init($auth_net_url);
		// Eliminate header info from response.
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// Return response data instead of true(1).
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// Use HTTP POST to send form data.
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($authNetFields, "& "));
		// Force the use of TLS instead of SSLv3.
		//  http://community.developer.authorize.net/t5/The-Authorize-Net-Developer-Blog/Important-POODLE-Information-Updated/ba-p/48163
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		// Uncomment the following line if you get 'no gateway response' errors.
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// Execute post and get results
		$resp = curl_exec($ch);
		curl_close($ch);

		Yii::log(
			sprintf(
				"%s receiving %s",
				__CLASS__,
				$resp
			),
			$this->logLevel,
			'application.'.__CLASS__.'.'.__FUNCTION__
		);

		$respVals = explode(self::x_delim_char, $resp);
		$respVals = array_values($respVals);

		if ($resp === false)
		{
			// Curl call failed.
			$arrReturn['success'] = false;
			$arrReturn['amount_paid'] = 0;
			$arrReturn['result'] = Yii::t('global', "There was an error processing your payment, please try again later.");
			Yii::log("Curl Error: curl call failed.", 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);
		} elseif ($respVals[0] != '1') {
			// Curl call succeeded but transaction was unsuccessful.
			$arrReturn['success'] = false;
			$arrReturn['amount_paid'] = 0;
			$arrReturn['result'] = Yii::t('global', $respVals[3]);
			Yii::log("Declined: ".$respVals[3], 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		} else {
			// Curl call succeeded and the transaction was successful.
			$arrReturn['success'] = true;
			$arrReturn['amount_paid'] = $this->objCart->total;
			$arrReturn['result'] = $respVals[4];
			if($this->config['live'] == 'test')
			{
				$arrReturn['amount_paid'] = 0;
				$arrReturn['result'] = "TEST " . $respVals[4];
			}
		}

		return $arrReturn;
	}


	/**
	 * Obfuscate sensitive information for logging purposes
	 *
	 * @param array $arr
	 * @return array
	 */
	private static function obfuscateRequestArray($arr)
	{
		if (array_key_exists('x_card_num', $arr) === true)
		{
			// cc number
			$arr['x_card_num'] =
				substr_replace(
					$arr['x_card_num'],
					str_repeat('*', strlen($arr['x_card_num']) - 4),
					0,
					strlen($arr['x_card_num']) - 4
				);
		}

		if (array_key_exists('x_card_code', $arr) === true)
		{
			// cc cvv
			$arr['x_card_code'] =
				substr_replace(
					$arr['x_card_code'],
					str_repeat('*', strlen($arr['x_card_code'])),
					0,
					strlen($arr['x_card_code'])
				);
		}

		return $arr;
	}
}
