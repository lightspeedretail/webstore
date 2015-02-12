<?php

class ewayaim extends WsPayment
{
	protected $defaultName = "eWAY CVN Australia";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode = true;


	/**
	 * The run() function is called from Web Store to run the process.
	 * @return array
	 */
	public function run() {

		$ewayCvnAusValues = array(
			"ewayCardNumber"                    => _xls_number_only($this->CheckoutForm->cardNumber),
			"ewayCardHoldersName"               => $this->CheckoutForm->cardNameOnCard,
			"ewayCardExpiryMonth"               => $this->CheckoutForm->cardExpiryMonth,
			"ewayCardExpiryYear"                => $this->CheckoutForm->cardExpiryYear,
			"ewayCVN"                           => $this->CheckoutForm->cardCVV,
			"ewayCustomerInvoiceRef"            => $this->objCart->id_str,
			"ewayTotalAmount"                   => round($this->objCart->total * 100), //eWay wants in cents, i.e. $15.35 = 1535;
			"ewayCustomerFirstName"             => $this->CheckoutForm->contactFirstName,
			"ewayCustomerLastName"              => $this->CheckoutForm->contactLastName,
			"ewayCustomerAddress"               => ($this->CheckoutForm->billingAddress2 != '' ?
					$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1) .
				", " . $this->CheckoutForm->billingCity . " " . $this->CheckoutForm->billingStateCode,
			"ewayCustomerPostcode"              => $this->CheckoutForm->billingPostal,
			"ewayCustomerEmail"                 => $this->CheckoutForm->contactEmail,
			"ewayCustomerInvoiceDescription"    => _xls_get_conf('STORE_NAME', "Online") . " Order",
			"ewayTrxnNumber"                    => '',
			"ewayOption1"                       => '',
			"ewayOption2"                       => '',
			"ewayOption3"                       => '',
		);

		$xmlRequest = "<ewaygateway><ewayCustomerID>" . $this->config['login'] . "</ewayCustomerID>";
		foreach($ewayCvnAusValues as $key => $value)
		{
			$xmlRequest .= "<$key>$value</$key>";
		}

		$xmlRequest .= "</ewaygateway>";

		Yii::log(
			sprintf(
				"%s sending %s for amt %s\nRequest: %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->objCart->total,
				print_r($this->obfuscateRequestArray($ewayCvnAusValues), true)
			),
			$this->logLevel,
			'application.'.__CLASS__.'.'.__FUNCTION__
		);

		$xmlResponse = $this->sendTransactionToEway($xmlRequest);
		if ($xmlResponse !== '')
		{
			$oXML = new SimpleXMLElement($xmlResponse);
			if ((string)$oXML->ewayTrxnStatus != "True")
			{
				// unsuccessful
				$arrReturn['success'] = false;
				$arrReturn['amount_paid'] = 0;
				$arrReturn['result'] = Yii::t('global', (string)$oXML->ewayTrxnError);
				Yii::log("Declined: ".(string)$oXML->ewayTrxnError, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			} else {
				//We have success
				$arrReturn['success'] = true;
				$arrReturn['amount_paid'] = (stripos((string)$oXML->ewayTrxnError, "Tests CVN Gateway") > 0 ? 0.00 : ((string)$oXML->ewayReturnAmount) / 100);
				$arrReturn['result'] = (string)$oXML->ewayAuthCode;
			}
		} else {
			// Curl call failed.
			$arrReturn['success'] = false;
			$arrReturn['amount_paid'] = 0;
			$arrReturn['result'] = Yii::t('global', "There was an error processing your payment, please try again later.");
			Yii::log("Curl Error: curl call failed.", 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);
		}

		return $arrReturn;

	}


	/**
	 * cURL communcation with Eway to submit parameters for authorization response
	 *
	 * @param $xmlRequest
	 * @return string $xmlResponse
	 */
	function sendTransactionToEway($xmlRequest) {

		if($this->config['live'] == 'live')
		{
			$eway_cvn_aus_url = "https://www.eway.com.au/gateway_cvn/xmlpayment.asp";
		}
		else
		{
			$eway_cvn_aus_url = "https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp";
		}

		$ch = curl_init($eway_cvn_aus_url);
		// Do a regular HTTP POST
		curl_setopt($ch, CURLOPT_POST, 1);
		// Use HTTP POST to send form data.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		// Return response data instead of true(1).
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// TODO - Verify if this is still the recommended way to connect to eway (WS-3516)
		// Don't verify peer.
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Force the use of TLS instead of SSLv3.
		// No official docs on this on eways site, but all the other payment
		//  providers we use have disabled SSL v3 due to POODLE, see:
		//  http://community.developer.authorize.net/t5/The-Authorize-Net-Developer-Blog/Important-POODLE-Information-Updated/ba-p/48163
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);

		$xmlResponse = $this->obfuscateResponse(curl_exec($ch));

		Yii::log(__CLASS__." receiving ".$xmlResponse, $this->logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

		if (curl_errno($ch) != CURLE_OK)
		{
			// Curl call failed.
			$xmlResponse = '';
		}

		return $xmlResponse;
	}


	/**
	 * Find credit card number and replace all digits except
	 * the last four with asterisks.
	 *
	 * @param $str
	 * @return mixed
	 */
	function obfuscateResponse($str)
	{
		// We only need to perform obfuscation if the number is in the response
		if (strpos($str,'Card Data Sent: ') != false)
		{
			$start = strpos($str,'Card Data Sent: ') + strlen('Card Data Sent: ');
			$intCardNumLength = strpos($str,'</ewayTrxnError>') - $start;

			$count = $intCardNumLength - 4; // keep last 4 digits

			for ($i = 0; $i < $count; $i++)
				$str = substr_replace($str, '*', $start + $i, 1);
		}

		return $str;
	}


	/**
	 * Obfuscate sensitive information for logging purposes
	 *
	 * @param array $arr
	 * @return array
	 */
	private function obfuscateRequestArray($arr)
	{
		if (array_key_exists('ewayCardNumber', $arr) === true)
		{
			// cc number
			$arr['ewayCardNumber'] =
				substr_replace(
					$arr['ewayCardNumber'],
					str_repeat('*', strlen($arr['ewayCardNumber']) - 4),
					0,
					strlen($arr['ewayCardNumber']) - 4
				);
		}

		if (array_key_exists('ewayCVN', $arr) === true)
		{
			// cc cvv
			$arr['ewayCVN'] =
				substr_replace(
					$arr['ewayCVN'],
					str_repeat('*', strlen($arr['ewayCVN'])),
					0,
					strlen($arr['ewayCVN'])
				);
		}

		return $arr;

	}
}
