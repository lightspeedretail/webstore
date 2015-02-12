<?php

class beanstreamsim extends WsPayment
{
	const SHA1_HASH_STRING_LENGTH = 40;
	const HASH_VALUE_SEARCH_STRING = "&hashValue=";

	protected $defaultName = "Beanstream (US/CAN)";
	protected $version = 1.0;
	protected $apiVersion = 1;
	public $cloudCompatible = true;
	public $performInternalFinalizeSteps = false;
	protected $uses_credit_card = true;

	/**
	 * Run the payment process
	 * @return mixed
	 */
	public function run()
	{

		$strBeanstreamUrl	= "https://www.beanstream.com/scripts/payment/payment.asp";

		$arrBeanStreamValues = array (
			"merchant_id"       => $this->config['login'],
			"trnOrderNumber"    => $this->objCart->id_str,
			"trnAmount"         => $this->objCart->total,
			"ordName"           => $this->CheckoutForm->contactFirstName . " " . $this->CheckoutForm->contactLastName,
			"ordEmailAddress"   => $this->CheckoutForm->contactEmail,
			"ordPhoneNumber"    => $this->CheckoutForm->contactPhone,
			"ordAddress1"       => $this->CheckoutForm->billingAddress1,
			"ordAddress2"       => $this->CheckoutForm->billingAddress2,
			"ordCity"           => $this->CheckoutForm->billingCity,
			"ordProvince"       => $this->CheckoutForm->billingStateCode,
			"ordCountry"        => $this->CheckoutForm->billingCountryCode,
			"ordPostalCode"     => $this->CheckoutForm->billingPostal,
			"approvedPage"      => Yii::app()->controller->createAbsoluteUrl('/cart/payment/'.$this->modulename),
			"declinedPage"      => Yii::app()->controller->createAbsoluteUrl('/cart/payment/'.$this->modulename),
		);

		$strQueryParams = http_build_query($arrBeanStreamValues);

		if ($this->config['sha1hash'])
		{
			$strHashValue = sha1($strQueryParams . $this->config['sha1hash']);
			$strQueryParams .= '&' . 'hashValue=' . $strHashValue;
		}

		Yii::log(
			sprintf(
				"%s attempting payment on cart %s\nRequest %s",
				__CLASS__,
				$this->objCart->id_str,
				print_r($arrBeanStreamValues, true)
			),
			$this->logLevel,
			'application.' . __CLASS__ . "." . __FUNCTION__
		);

		$strJumpUrl =  $strBeanstreamUrl . '?' . $strQueryParams;

		$arrReturn['api'] = $this->apiVersion;
		$arrReturn['jump_url'] = $strJumpUrl;

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
		$trnOrderNumber = Yii::app()->getRequest()->getQuery('trnOrderNumber');
		$trnApproved = Yii::app()->getRequest()->getQuery('trnApproved');
		$trnAmount = Yii::app()->getRequest()->getQuery('trnAmount');
		$authCode = Yii::app()->getRequest()->getQuery('authCode');
		$messageText = Yii::app()->getRequest()->getQuery('messageText');

		if ($this->config['sha1hash'])
		{
			if (!Yii::app()->getRequest()->getQuery('hashValue'))
				return self::generateErrorResponse($trnOrderNumber, "Not able to validate the transaction");

			$strRequestUri = Yii::app()->getRequest()->getRequestUri();
			$strQueryString = Yii::app()->getRequest()->queryString;
			$strHashValueParam = substr($strQueryString, strpos($strQueryString, self::HASH_VALUE_SEARCH_STRING) + strlen(self::HASH_VALUE_SEARCH_STRING));

			if (strlen($strHashValueParam) > self::SHA1_HASH_STRING_LENGTH)
			{
				//SHA-1 hash value should be always 40 characters long.
				//If there are anything else after the hashValue parameter,
				//assume that the query string has been tempered with.
				Yii::log("Invalid Beanstream response: " . $strRequestUri);
				return self::generateErrorResponse($trnOrderNumber, "Not able to validate the transaction");
			}

			$strToHash = substr($strQueryString, 0, strpos($strQueryString, '&hashValue')) . $this->config['sha1hash'];
			$strHashedRequestParams = sha1($strToHash);

			if ($strHashedRequestParams !== Yii::app()->getRequest()->getQuery('hashValue'))
			{
				$arrReturn = self::generateErrorResponse($trnOrderNumber, "Payment transaction validation failed");
				Yii::log("Declined: " . "Beanstream response hashValue validation failed.", 'error', 'application.' . __CLASS__ . "." . __FUNCTION__);
			}
			else
			{
				if ($trnApproved == '1')
					$arrReturn =  self::generateSuccessResponse($trnOrderNumber, $trnAmount, $authCode);
				else
				{
					$arrReturn = self::generateErrorResponse($trnOrderNumber, $messageText);
					Yii::log("Declined: ".$messageText, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}
			}
		}
		else
		{
			if ($trnApproved == '1')
				$arrReturn =  self::generateSuccessResponse($trnOrderNumber, $trnAmount, $authCode);
			else
			{
				$arrReturn = self::generateErrorResponse($trnOrderNumber, $messageText);
				Yii::log("Declined: ".$messageText, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

		}

		return $arrReturn;
	}

	private static function generateSuccessResponse($trnOrderNumber, $trnAmount, $authCode)
	{
		return array(
			'order_id' => $trnOrderNumber,
			'amount' => $trnAmount,
			'success' => true,
			'data' => $authCode,
		);
	}

	private static function generateErrorResponse($trnOrderNumber, $messageText)
	{
		$objCart = Cart::LoadByIdStr($trnOrderNumber);

		$url = Yii::app()->controller->createAbsoluteUrl('cart/restoredeclined', array('getuid'=>$objCart->linkid,'reason'=>$messageText));

		$arrReturn =  array(
			'order_id' => $trnOrderNumber,
			'output' => "<html><head><meta http-equiv=\"refresh\" content=\"1;url=$url\"></head><body><a href=\"$url\">Verifying order, please wait...</a></body></html>",
			'success' => false,
		);

		return $arrReturn;
	}
}
