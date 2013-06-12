<?php

class beanstreamsim extends WsPayment
{

	protected $defaultName = "Beanstream (US/CAN)";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $apiVersion = 1;


	/**
	 * Run the payment process
	 * @return mixed
	 */
	public function run()
	{

		$beanstream_url	= "https://www.beanstream.com/scripts/payment/payment.asp";

		$beanstream_values = array (
			"merchant_id"		=> $this->config['login'],
			"trnOrderNumber"	=> $this->objCart->id_str,
			"trnAmount"			=> $this->objCart->total,
			"ordName"			=> $this->CheckoutForm->contactFirstName . " " . $this->CheckoutForm->contactLastName,
			"ordEmailAddress"	=> $this->CheckoutForm->contactEmail,
			"ordPhoneNumber"	=> $this->CheckoutForm->contactPhone,
			"ordAddress1"		=> $this->CheckoutForm->billingAddress1,
			"ordAddress2"		=> $this->CheckoutForm->billingAddress2,
			"ordCity"			=> $this->CheckoutForm->billingCity,
			"ordProvince"		=> $this->CheckoutForm->billingState,
			"ordCountry"		=> $this->CheckoutForm->billingCountry,
			"ordPostalCode"		=> $this->CheckoutForm->billingPostal,
			"hashValue"			=> $this->config['md5hash'],
			"approvedPage"		=> Yii::app()->controller->createAbsoluteUrl('/cart/payment/'.$this->modulename),
			"declinedPage"		=> Yii::app()->controller->createAbsoluteUrl('/cart/payment/'.$this->modulename)
		);

		$str = "";

		$str .= "<FORM name=\"beanstream_form\" action=\"$beanstream_url\" method=\"POST\">";
		foreach( $beanstream_values as $key => $value )
			$str .= _xls_make_hidden($key, $value);

		$str .=  ('</FORM>');


		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1")
			_xls_log(get_class($this) . " sending ".$this->objCart->id_str." ".$str,true);

		$arrReturn['api'] = $this->apiVersion;
		$arrReturn['jump_form']=$str;
		return $arrReturn;
	}

	/**
	 * gateway_response_process
	 *
	 * Processes processor gateway response
	 * Processes returned $_GET or $_POST variables from the third party website
	 */
	public function gateway_response_process()
	{

		$trnApproved = Yii::app()->getRequest()->getQuery('trnApproved');
		$trnOrderNumber = Yii::app()->getRequest()->getQuery('trnOrderNumber');
		$trnAmount = Yii::app()->getRequest()->getQuery('trnAmount');
		$authCode = Yii::app()->getRequest()->getQuery('authCode');
		$messageText = Yii::app()->getRequest()->getQuery('messageText');


		if ($trnApproved == '1')
		{
			$retArray =  array(
				'order_id' => $trnOrderNumber,
				'amount' => $trnAmount,
				'success' => true,
				'data' => $authCode,
			);

		}
		else
		{
			$objCart = Cart::LoadByIdStr($trnOrderNumber);

			$url = Yii::app()->controller->createAbsoluteUrl('cart/restoredeclined', array('getuid'=>$objCart->linkid,'reason'=>$messageText));

			$retArray =  array(
				'order_id' => $trnOrderNumber,
				'output' => "<html><head><meta http-equiv=\"refresh\" content=\"1;url=$url\"></head><body><a href=\"$url\">Verifying order, please wait...</a></body></html>",
				'success' => false,
			);

			Yii::log("Declined: ".$messageText, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		return $retArray;
	}




}
