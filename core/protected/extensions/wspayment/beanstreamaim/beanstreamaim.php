<?php

class beanstreamaim extends WsPayment
{
	protected $defaultName = "Beanstream (US/CAN)";
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


		$beanstream_url = "https://www.beanstream.com/scripts/process_transaction.asp";

		$strState = $this->CheckoutForm->billingStateCode;
		$strBillCountry = $this->CheckoutForm->billingCountryCode;
		if($strBillCountry != "US" && $strBillCountry != "CA")
		{
			$strState = "--";
		}

		$strShipState = $this->CheckoutForm->shippingStateCode;
		$strShipCountry = $this->CheckoutForm->shippingCountryCode;
		if ($strShipCountry != "US" && $strShipCountry != "CA" && is_null($strShipCountry) === false)
		{
			$strShipState = "--";
		}

		$beanstream_values = array (
			"requestType"		=> "BACKEND",
			"merchant_id"		=> $this->config['login'],
			"trnCardNumber"		=> _xls_number_only($this->CheckoutForm->cardNumber),
			"trnCardOwner"		=> $this->CheckoutForm->cardNameOnCard,
			"trnExpMonth"		=> trim($this->CheckoutForm->cardExpiryMonth),
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
			"ordCountry"		=> $strBillCountry,

			"shipName"			=> $this->CheckoutForm->shippingFirstName." ".$this->CheckoutForm->shippingLastName,
			"shipAddress1"		=> $this->CheckoutForm->shippingAddress1,
			"shipAddress2"		=> $this->CheckoutForm->shippingAddress2,
			"shipCity"			=> $this->CheckoutForm->shippingCity,
			"shipProvince"		=> $strShipState,
			"shipPostalCode"	=> $this->CheckoutForm->shippingPostal,
			"shipCountry"		=> $strShipCountry,
			"shippingMethod"	=> substr($this->objCart->shipping->shipping_data, 0, 63) // beanstream doesn't allow this field to be more than 64 characters
		);

		$beanstream_values = array_filter($beanstream_values);

		Yii::log(
			sprintf(
				"%s sending %s for amt %s\nResponse %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->objCart->total,
				print_r($this->obfuscateRequestArray($beanstream_values), true)
			),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		$beanstremFields = "";

		foreach($beanstream_values as $key => $value )
		{
			$beanstremFields .= "$key=" . urlencode($value) . "&";
		}

		$ch = curl_init($beanstream_url);
		// Eliminate header info from response.
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// Return response data instead of true(1).
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// Use HTTP POST to send form data.
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($beanstremFields, "& "));
		// Force the use of TLS instead of SSLv3
		//  http://community.developer.authorize.net/t5/The-Authorize-Net-Developer-Blog/Important-POODLE-Information-Updated/ba-p/48163
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		// Uncomment the following line if you get 'no gateway response' errors.
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// Execute post and get results
		$resp = curl_exec($ch);
		curl_close($ch);
		$respVals = array();

		Yii::log(
			sprintf(
				"%s receiving %s",
				__CLASS__,
				$resp
			),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		parse_str($resp, $respVals);

		// Handle the results of the curl call
		if ($resp === false)
		{
			// Curl call failed
			$arrReturn['success'] = false;
			$arrReturn['amount_paid'] = 0;
			$arrReturn['result'] = Yii::t('global', "There was an error processing your payment, please try again later.");
			Yii::log("Curl Error: curl call failed.", 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);
		} elseif ($respVals['trnApproved'] != '1') {
			// Curl call succeeded but transaction was unsuccessful
			$arrReturn['success'] = false;
			$arrReturn['amount_paid'] = 0;

			// beanstream sometimes returns messages prefixed with <li> and suffixed with <br>
			// we handle these bonkers messages here
			$htmlMessage = urldecode($respVals['messageText']);
			$message = strip_tags($htmlMessage, '<br>');
			// remove the last <br> tag
			$intPos = strrpos($message, '<br>');
			if (empty($intPos) === false)
			{
				$message = substr($message, 0, $intPos);
			}

			$arrReturn['result'] = $message;
			Yii::log("Declined: " . urldecode($respVals['messageText']), 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);

			if(stripos($respVals['messageText'], "Enter your phone number") > 0)
			{
				$arrReturn['result'] = Yii::t('global', "Declined: Your phone number is missing in your profile, which is required by the credit card processor. Click {link} to update your account with your phone number. Then return to checkout.", array("{link}" => CHtml::link(Yii::t('global', 'Edit Account'), Yii::app()->createUrl("myaccount/edit"))));
			}
		} else {
			// Curl call succeeded and the transaction was successful
			$arrReturn['success'] = true;
			$arrReturn['amount_paid'] = ($respVals['authCode'] == "TEST" ? 0.00 : $respVals['trnAmount']);
			$arrReturn['result'] = $respVals['authCode'];
			$arrReturn['payment_date'] = $respVals['trnDate'];
		}

		return $arrReturn;
	}

	/**
	 * Obfuscate sensitive information for logging purposes
	 *
	 * @param $arr array
	 * @return array
	 */
	private static function obfuscateRequestArray($arr)
	{
		if (array_key_exists('trnCardNumber', $arr) === true)
		{
			// cc number
			$arr['trnCardNumber'] =
				substr_replace(
					$arr['trnCardNumber'],
					str_repeat('*', strlen($arr['trnCardNumber']) - 4),
					0,
					strlen($arr['trnCardNumber'])-4
				);
		}

		if (array_key_exists('trnCardCvd', $arr) === true)
		{
			// cc cvv
			$arr['trnCardCvd'] =
				substr_replace(
					$arr['trnCardCvd'],
					str_repeat('*', strlen($arr['trnCardCvd'])),
					0,
					strlen($arr['trnCardCvd'])
				);
		}

		return $arr;
	}
}
