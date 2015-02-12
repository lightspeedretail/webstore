<?php

class worldpaysim extends WsPayment
{

	protected $defaultName = "Worldpay";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $apiVersion = 1;
	public $cloudCompatible = true;
	protected $uses_credit_card = true;
	public $performInternalFinalizeSteps = false;

	/**
	 * Run the payment process
	 * @return mixed
	 */
	public function run()
	{
		if($this->config['live'] == 'live')
		{
			$worldpay_url = "https://secure.wp3.rbsworldpay.com/wcc/purchase";
		}
		else
		{
			$worldpay_url = "https://select-test.wp3.rbsworldpay.com/wcc/purchase";
		}

		$str = sprintf('<FORM name="worldpayform" action="%s" method="POST">', $worldpay_url);
		
		if ($this->config['live'] == 'test')
		{
			$str .= _xls_make_hidden('testMode',  '100');
		}

		$str .= _xls_make_hidden('address1',    $this->CheckoutForm->billingAddress1);
		$str .= _xls_make_hidden('address2',    $this->CheckoutForm->billingAddress2);
		$str .= _xls_make_hidden('town',        $this->CheckoutForm->billingCity);
		$str .= _xls_make_hidden('region',      $this->CheckoutForm->billingStateCode);
		$str .= _xls_make_hidden('postcode',    $this->CheckoutForm->billingPostal);
		$str .= _xls_make_hidden('country',     $this->CheckoutForm->billingCountryCode);
		$str .= _xls_make_hidden('email',       $this->CheckoutForm->contactEmail);
		$str .= _xls_make_hidden('name',        $this->CheckoutForm->contactFirstName . " " . $this->CheckoutForm->contactLastName);
		$str .= _xls_make_hidden('tel',         $this->CheckoutForm->contactPhone);
		$str .= _xls_make_hidden('instId',      $this->config['login']);
		$str .= _xls_make_hidden('currency',    _xls_get_conf('CURRENCY_DEFAULT' , 'USD'));
		$str .= _xls_make_hidden('cartId',      $this->objCart->id_str);
		$str .= _xls_make_hidden('desc',        _xls_get_conf( 'STORE_NAME'  , "Online") . " Order");
		$str .= _xls_make_hidden('M_cartlink',  Yii::app()->controller->createAbsoluteUrl('cart/restore', array('getuid'=>$this->objCart->linkid)));
		$str .= _xls_make_hidden('MC_callback', Yii::app()->controller->createAbsoluteUrl('cart/payment', array('id'=>$this->modulename)));
		$str .= _xls_make_hidden('amount',      round($this->objCart->total , 2));

		$str .= '</FORM>';

		Yii::log(
			sprintf(
				"%s sending %s\nRequest %s",
				__CLASS__,
				$this->objCart->id_str,
				$str
			),
			$this->logLevel,
			'application.'.__CLASS__.'.'.__FUNCTION__
		);

		$arrReturn['api'] = $this->apiVersion;
		$arrReturn['jump_form']=$str;
		return $arrReturn;
	}

	/**
	 * Processes returned $_GET or $_POST variables from the third party website
	 *
	 * @return array|bool
	 */
	public function gateway_response_process()
	{
		Yii::log(
			sprintf("%s Transaction %s", __CLASS__, print_r($_GET, true)),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		$instId = Yii::app()->getRequest()->getQuery('instId');
		$transId = Yii::app()->getRequest()->getQuery('transId');
		$cartId = Yii::app()->getRequest()->getQuery('cartId');
		$authAmount = Yii::app()->getRequest()->getQuery('authAmount');
		$messageText = Yii::app()->getRequest()->getQuery('rawAuthMessage');
		$transTime = Yii::app()->getRequest()->getQuery('transTime'); //Unix epoch time

		if (empty($transId)) {
			// failed order
			Yii::log("Failed: ".print_r($_GET,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		if (empty($instId))
		{
			return false;
		}

		if ($instId != $this->config['login'])
		{
			// it's not the same!
			return false;
		}

		if (empty($cartId))
		{
			return false;
		}

		if ($transId>0) {
			$retArray =  array(
				'order_id' => $cartId,
				'amount' => $authAmount,
				'success' => true,
				'data' => $transId,
				'payment_date' => date("Y-m-d H:i:s", strtotime($transTime))
			);

		}
		else
		{
			Yii::log("Declined Reason: ".strtoupper($messageText), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			$retArray = array(
				'order_id' => $cartId,
				'amount' => 0,
				'success' => false,
				'data' => ''
			);

		}

		return $retArray;
	}

}
