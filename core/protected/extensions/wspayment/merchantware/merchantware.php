<?php

class merchantware extends WsPayment
{
	protected $defaultName = "MerchantWARE Online";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode = true;
	public $cloudCompatible = true;

	const x_delim_char = "|";
	private $paid_amount;

	/**
	 * The run() function is called from Web Store to run the process.
	 * @return array
	 */
	public function run() {

		// URL Configuration
		$merchantware_url = "https://ps1.merchantware.net/MerchantWARE/ws/RetailTransaction/TXRetail31.asmx";

		// MerchantWARE specific values
		$trans_info_transactionid = '';     // Transaction id
		$trans_info_allow_duplicate = '';   // Turn duplicate checking on or off
		$trans_info_register_num = '';      // Register number

		//MerchantWARE expects expiry in 4 digit format
		$cardInfoExpiry = _xls_number_only($this->CheckoutForm->cardExpiryMonth.substr($this->CheckoutForm->cardExpiryYear, 2, 2));

		//MerchantWARE expects no dashes in WO number
		$wo = str_replace("-", "", $this->objCart->id_str);

		// MerchantWARE does not fully support Canadian postal codes.
		// See: http://confluence.atlightspeed.net/display/webstore/MerchantWare+Online
		if ($this->CheckoutForm->billingCountry == 39)
		{
			$this->CheckoutForm->billingPostal = 0;
		}

		// Construct SOAP packet for delivery
		$xmlData =
			'<soap:Envelope
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
				<soap:Body>
					<IssueKeyedSale
					xmlns="http://merchantwarehouse.com/MerchantWARE/Client3_1/TransactionRetail">
						<strName>'.$this->config['name'].'</strName>
                        <strSiteId>'.$this->config['site_id'].'</strSiteId>
                        <strKey>'.$this->config['trans_key'].'</strKey>
                        <strOrderNumber>'.$wo.'</strOrderNumber>
                        <strAmount>'.$this->objCart->total.'</strAmount>
                        <strPAN>'._xls_number_only($this->CheckoutForm->cardNumber).'</strPAN>
                        <strExpDate>'.$cardInfoExpiry.'</strExpDate>
                        <strCardHolder>'.$this->CheckoutForm->contactFirstName . " " . $this->CheckoutForm->contactLastName.'</strCardHolder>
                        <strAVSStreetAddress>'.$this->CheckoutForm->billingAddress1.'</strAVSStreetAddress>
                        <strAVSZipCode>'.str_pad(str_replace(" ","",$this->CheckoutForm->billingPostal), 5, '0', STR_PAD_RIGHT).'</strAVSZipCode>
                        <strCVCode>'.$this->CheckoutForm->cardCVV.'</strCVCode>
                        <strAllowDuplicates>'.$trans_info_allow_duplicate.'</strAllowDuplicates>
                        <strRegisterNum>'.$trans_info_register_num .'</strRegisterNum>
                        <strTransactionId>'.$trans_info_transactionid .'</strTransactionId>
                    </IssueKeyedSale>
                </soap:Body>
            </soap:Envelope>';

		$ch = curl_init($merchantware_url);

		// Set header with SOAP Action
		$soapaction = "http://merchantwarehouse.com/MerchantWARE/Client3_1/TransactionRetail/IssueKeyedSale";
		$headers = array("Content-Type: text/xml; charset=utf-8", "SOAPAction: ".$soapaction);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// Eliminate header info from response.
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// Do a regular HTTP POST
		curl_setopt($ch, CURLOPT_POST, 1);
		// Do not follow 'Location:' headers
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		// Return response data instead of true(1).
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// Force the use of TLS instead of SSLv3.
		//  http://merchantwarehouse.com/what-you-need-to-know-about-the-poodle-security-vulnerability
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		// Use HTTP POST to send form data.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		// Execute post and get results
		$resp = curl_exec($ch);
		curl_close($ch);

		Yii::log(
			sprintf(
				"%s sending %s for amt %s\nSoap: %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->objCart->total,
				$this->obfuscate($xmlData)
			),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		Yii::log(__CLASS__ . " receiving " . $resp, $this->logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

		if ($resp !== false)
		{
			$resp = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
			// Parse xml for response values
			$oXML = new SimpleXMLElement($resp);

			if (isset($oXML->soapBody->soapFault))
			{
				$responseStatus = "DECLINED;00;" . $oXML->soapBody->soapFault->faultstring;
				$responseAuthorizationCode = "";
			} else {
				$responseStatus = $oXML->soapBody->IssueKeyedSaleResponse->IssueKeyedSaleResult->ApprovalStatus;
				$responseAuthorizationCode = (string)$oXML->soapBody->IssueKeyedSaleResponse->IssueKeyedSaleResult->AuthCode;

				if ($responseStatus == "DECLINED,DUPLICATE;1110;duplicate transaction")
				{
					$code = (string)$oXML->soapBody->IssueKeyedSaleResponse->IssueKeyedSaleResult->ExtData;
					$arrResponse = explode(";", $code);
					$responseAuthorizationCode = str_replace("Original AuthCode=", "", $arrResponse[1]);
					Yii::log(
						"MerchantWare flagging this as duplicate: " .
						$arrResponse[0] . ': ' . $arrResponse[1],
						'error',
						'application.' . __CLASS__ . "." . __FUNCTION__
					);
				}
			}

			if($responseStatus != 'APPROVED')
			{
				//unsuccessful
				$arrReturn['success'] = false;
				$arrReturn['amount_paid'] = 0;
				$arrResponse = explode(";", $responseStatus);
				$arrReturn['result'] = Yii::t('global', $arrResponse[0] . ': ' . $arrResponse[2]);
				$arrReturn['code'] = $arrResponse[1];
				Yii::log("Declined: ".$arrResponse[0].', '.$arrResponse[1].': '.$arrResponse[2], 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			} else {
				//We have success
				$arrReturn['success'] = true;
				$arrReturn['amount_paid'] = $this->objCart->total;
				$arrReturn['result'] = $responseAuthorizationCode;
				Yii::log("Approved: " . $responseStatus, 'info', 'application.' . __CLASS__ . "." . __FUNCTION__);
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
	 * Obfuscate sensitive data from log
	 *
	 * @param $str
	 * @return mixed
	 */

	private function obfuscate($str)
	{
		// cc number
		$needle1 = '<strPAN>';
		$needle2 = '</strPAN>';
		$pos1 = strpos($str, $needle1) + strlen($needle1);
		$pos2 = strpos($str, $needle2);

		$str = substr_replace($str, str_repeat('*', $pos2 - $pos1 - 4), $pos1, $pos2 - $pos1 - 4);

		// cc cvv
		$needle3 = '<strCVCode>';
		$needle4 = '</strCVCode>';
		$pos3 = strpos($str, $needle3) + strlen($needle3);
		$pos4 = strpos($str, $needle4);

		$str = substr_replace($str, str_repeat('*', $pos4 - $pos3), $pos3, $pos4 - $pos3);

		return $str;
	}



}
