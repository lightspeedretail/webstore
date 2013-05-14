<?php

class merchantware extends WsPayment
{
	protected $defaultName = "MerchantWARE Online";
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

		// URL Configuration
		$merchantware_url = "https://ps1.merchantware.net/MerchantWARE/ws/RetailTransaction/TXRetail31.asmx";


		// MerchantWARE specific values
		$trans_info_transactionid = '';     // Transaction id
		$trans_info_allow_duplicate = '';   // Turn duplicate checking on or off
		$trans_info_register_num = '';      // Register number


		//MerchantWARE expects expiry in 4 digit format
		$card_info_expiry = $this->CheckoutForm->cardExpiryMonth.substr($this->CheckoutForm->cardExpiryYear,2,2);

		//MerchantWARE expects no dashes in WO number
		$wo = str_replace("-","",$this->objCart->id_str);

		// Construct SOAP packet for delivery
		$xml_data =
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
                        <strExpDate>'.$card_info_expiry.'</strExpDate>
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
		curl_setopt($ch, CURLOPT_HEADER, 0);				// set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// Returns response data instead of TRUE(1)

		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);	// use HTTP POST to send SOAP XML Data

		$resp = curl_exec($ch);								//execute post and get results
		curl_close ($ch);

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {
			Yii::log(get_class($this) . " sending ".$this->objCart->id_str." for amt ".$this->objCart->total, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log(get_class($this) . " receiving ".$resp, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}
		$resp = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
		// Parse xml for response values
		$oXML = new SimpleXMLElement($resp);

		if (isset($oXML->soapBody->soapFault))
		{
			$response_status = "DECLINED;00;".$oXML->soapBody->soapFault->faultstring;
			$response_authorization_code="";
		}
		else
		{
			$response_status = $oXML->soapBody->IssueKeyedSaleResponse->IssueKeyedSaleResult->ApprovalStatus;
			$response_authorization_code = (string)$oXML->soapBody->IssueKeyedSaleResponse->IssueKeyedSaleResult->AuthCode;

			if ($response_status=="DECLINED,DUPLICATE;1110;duplicate transaction")
			{
				$response_status = "APPROVED";
				$code = (string)$oXML->soapBody->IssueKeyedSaleResponse->IssueKeyedSaleResult->ExtData;
				$arrResponse = explode(";",$code);
				$response_authorization_code = str_replace("Original AuthCode=","",$arrResponse[1]);
			}
		}


		if($response_status != 'APPROVED' ) {
			//unsuccessful
			$arrReturn['success']=false;
			$arrReturn['amount_paid']=0;
			$arrResponse = explode(";",$response_status);
			$arrReturn['result'] = Yii::t('global',$arrResponse[0].': '.$arrResponse[2]);
			Yii::log("Declined: ".$arrResponse[0].': '.$arrResponse[2], 'error', 'application.'.__CLASS__.".".__FUNCTION__);


		} else {

			//We have success
			$arrReturn['success']=true;
			$arrReturn['amount_paid']=  $this->objCart->total;
			$arrReturn['result']=$response_authorization_code;

		}

		return $arrReturn;



	}



}
