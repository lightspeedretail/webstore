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


		$eway_cvn_aus_values = array (
			"ewayCardNumber"			=> _xls_number_only($this->CheckoutForm->cardNumber),
			"ewayCardHoldersName"		=> $this->CheckoutForm->cardNameOnCard,
			"ewayCardExpiryMonth"		=> $this->CheckoutForm->cardExpiryMonth,
			"ewayCardExpiryYear" 		=> $this->CheckoutForm->cardExpiryYear,
			"ewayCVN"					=> $this->CheckoutForm->cardCVV,
			"ewayCustomerInvoiceRef"	=> $this->objCart->id_str,
			"ewayTotalAmount"			=> round($this->objCart->total*100), //eWay wants in cents, i.e. $15.35 = 1535;
			"ewayCustomerFirstName"		=> $this->CheckoutForm->contactFirstName,
			"ewayCustomerLastName"		=> $this->CheckoutForm->contactLastName,
			"ewayCustomerAddress"		=> ($this->CheckoutForm->billingAddress2 != '' ?
				$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1) .
				", " . $this->CheckoutForm->billingCity . " " . $this->CheckoutForm->billingState,
			"ewayCustomerPostcode"		=> $this->CheckoutForm->billingPostal,
			"ewayCustomerEmail"			=> $this->CheckoutForm->contactEmail,
			"ewayCustomerInvoiceDescription"	=> _xls_get_conf( 'STORE_NAME'  , "Online") . " Order",
			"ewayTrxnNumber"			=> '',
			"ewayOption1"				=> '',
			"ewayOption2"				=> '',
			"ewayOption3"				=> '',
		);

		$xmlRequest = "<ewaygateway><ewayCustomerID>" . $this->config['login'] . "</ewayCustomerID>";
		foreach($eway_cvn_aus_values as $key=>$value)
			$xmlRequest .= "<$key>$value</$key>";
		$xmlRequest .= "</ewaygateway>";

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {
			_xls_log(get_class($this) . " sending ".$this->objCart->id_str." for amt ".$this->objCart->total,true);
		}

		$xmlResponse = $this->sendTransactionToEway($xmlRequest);
		$oXML = new SimpleXMLElement($xmlResponse);

		if((string)$oXML->ewayTrxnStatus != "True" ) {
			//unsuccessful
			$arrReturn['success']=false;
			$arrReturn['amount_paid']=0;
			$arrReturn['result'] = Yii::t('global',(string)$oXML->ewayTrxnError);
			Yii::log("Declined: ".(string)$oXML->ewayTrxnError, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		} else {

			//We have success
			$arrReturn['success']=true;
			$arrReturn['amount_paid'] =  (stripos((string)$oXML->ewayTrxnError,"Tests CVN Gateway")>0 ? 0.00 : ((string)$oXML->ewayReturnAmount)/100);
			$arrReturn['result']=(string)$oXML->ewayAuthCode;

		}

		return $arrReturn;

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

		if($this->config['live'] == 'live')
			$eway_cvn_aus_url = "https://www.eway.com.au/gateway_cvn/xmlpayment.asp";
		else
			$eway_cvn_aus_url = "https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp";

		$ch = curl_init($eway_cvn_aus_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$xmlResponse = curl_exec($ch);

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {
			_xls_log(get_class($this) . " receiving ".$xmlResponse,true);
		}

		if(curl_errno( $ch ) == CURLE_OK)
			return $xmlResponse;
	}


}
