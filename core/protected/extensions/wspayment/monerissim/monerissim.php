<?php

class monerissim extends WsPayment
{
	const ERROR_RESPONSE_CODE = 50;

	protected $defaultName = "Moneris";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $cloudCompatible = true;
	public $performInternalFinalizeSteps = false;

	protected function formatCurrencyValue($amount)
	{
		// Moneris requires that currencies are formatted with exactly 2 decimals and without any thousand separators.
		// See WS-3058.
		// Moneris documentation: https://s3.amazonaws.com/uploads.hipchat.com/21792/814855/QVqvZKmaU8ZWTVY/moneris-eSELECTplus_HPP_IG.pdf
		return number_format(
			round($amount, 2),
			2, // 2 decimal places.
			'.', // Decimal separator.
			'' // Thousands separator.
		);
	}

	public function run()
	{

		$ps_store_id = $this->config['ps_store_id'];
		$hpp_key = $this->config['hpp_key'];

		if ($this->config['live'] == 'live')
		{
			$moneris_url = "https://www3.moneris.com/HPPDP/index.php";
		}
		else
		{
			$moneris_url = "https://esqa.moneris.com/HPPDP/index.php";
		}

		$str = "";

		$str .= sprintf('<FORM method="POST" action="%s">', $moneris_url);
		$str .= _xls_make_hidden('ps_store_id',  $ps_store_id);
		$str .= _xls_make_hidden('hpp_key',  $hpp_key);
		$str .= _xls_make_hidden('order_id', $this->objCart->id_str . '-' . date("YmdHis"));

		foreach ($this->objCart->cartItems as $id=>$item)
		{
			$str .= _xls_make_hidden('description'.$id, $item->description);
			$str .= _xls_make_hidden('id'.$id, $item->code);
			$str .= _xls_make_hidden('quantity'.$id, $item->qty);
			$str .= _xls_make_hidden('price'.$id, self::formatCurrencyValue($item->sell_total));
		}

		foreach ($this->objCart->Taxes as $tax=>$taxvalue)
			switch (strtolower($tax)) {
				case 'gst':
					if ($taxvalue>0)
						$str .= _xls_make_hidden('gst', self::formatCurrencyValue($taxvalue));
					break;

				case 'pst':
				case 'qst':
					if ($taxvalue>0)
						$str .= _xls_make_hidden('pst', self::formatCurrencyValue($taxvalue));
					break;

				case 'hst':
					if ($taxvalue>0)
						$str .= _xls_make_hidden('hst', self::formatCurrencyValue($taxvalue));
					break;

				// todo - account for electronics tax
			}

		$str .= _xls_make_hidden('shipping_cost', self::formatCurrencyValue($this->objCart->shippingCharge));
		$str .= _xls_make_hidden('note', $this->CheckoutForm->orderNotes);

		$str .= _xls_make_hidden('bill_first_name',   $this->CheckoutForm->contactFirstName);
		$str .= _xls_make_hidden('bill_last_name',   $this->CheckoutForm->contactLastName);
		$str .= _xls_make_hidden('bill_company_name', $this->CheckoutForm->contactCompany);
		$str .= _xls_make_hidden('bill_address_one', ($this->CheckoutForm->billingAddress2 != '' ?
			$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1));
		$str .= _xls_make_hidden('bill_city', $this->CheckoutForm->billingCity);
		$str .= _xls_make_hidden('bill_state_or_province', $this->CheckoutForm->billingStateCode);
		$str .= _xls_make_hidden('bill_postal_code', $this->CheckoutForm->billingPostal);
		$str .= _xls_make_hidden('bill_country', $this->CheckoutForm->billingCountryCode);
		$str .= _xls_make_hidden('bill_phone', _xls_number_only($this->CheckoutForm->contactPhone));

		$str .= _xls_make_hidden('email', $this->CheckoutForm->contactEmail);
		$str .= _xls_make_hidden('cust_id', "WC-" . $this->objCart->customer_id);

		$str .= _xls_make_hidden('ship_first_name',   $this->CheckoutForm->shippingFirstName);
		$str .= _xls_make_hidden('ship_last_name',   $this->CheckoutForm->shippingLastName);
		$str .= _xls_make_hidden('ship_company_name',   $this->CheckoutForm->shippingCompany);
		$str .= _xls_make_hidden('ship_address_one',   $this->CheckoutForm->shippingAddress1 . " " . $this->CheckoutForm->shippingAddress2);
		$str .= _xls_make_hidden('ship_city',   $this->CheckoutForm->shippingCity);
		$str .= _xls_make_hidden('ship_state_or_province',   $this->CheckoutForm->shippingStateCode);
		$str .= _xls_make_hidden('ship_postal_code',   $this->CheckoutForm->shippingPostal);
		$str .= _xls_make_hidden('ship_country',   $this->CheckoutForm->shippingCountryCode);

		$str .= _xls_make_hidden('charge_total',  self::formatCurrencyValue($this->objCart->total));

		$str .=  ('</FORM>');

		Yii::log(
			sprintf(
				"%s sending %s in %s mode\nRequest %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->config['live'],
				$str
			),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		$arrReturn['api'] = $this->apiVersion;
		$arrReturn['jump_form']=$str;
		return $arrReturn;

	}


	public function gateway_response_process() {

		Yii::log(
			sprintf("%s Transaction %s", __CLASS__, print_r($_POST, true)),
			$this->logLevel,
			'application.'.__CLASS__.'.'.__FUNCTION__
		);

		// The order id will come in as GET variable on a cancelled transaction.
		// Otherwise, it comes in as a POST variable.

		$webstore_order_id = null;
		$response_order_id = Yii::app()->getRequest()->getQuery('order_id');

		if (is_null($response_order_id) === true)
		{
			$response_order_id = Yii::app()->getRequest()->getPost('response_order_id');
		}

		if (is_null($response_order_id) === false)
		{
			// ex. WO-123-20140413124313 becomes WO-123
			$webstore_order_id = substr($response_order_id, 0, strrpos($response_order_id, '-'));
		}

		$response_message = Yii::app()->getRequest()->getQuery('cancelTXN');

		if (is_null($response_message) === false)
		{
			// Transaction was cancelled
			Yii::log(__CLASS__ . ' cancelled order payment received ' . print_r($_GET, true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return self::generateErrorResponse($webstore_order_id, $response_message);
		}

		$response_code = Yii::app()->getRequest()->getPost('response_code');
		$response_message = Yii::app()->getRequest()->getPost('message');
		$charge_total = Yii::app()->getRequest()->getPost('charge_total');
		$bank_transaction_id = Yii::app()->getRequest()->getPost('bank_transaction_id');
		$result = Yii::app()->getRequest()->getPost('result');

		if (empty($result) || empty($webstore_order_id) || is_null($response_code) || $response_code >= self::ERROR_RESPONSE_CODE)
		{
			Yii::log(__CLASS__ . " failed order payment received " . print_r($_POST, true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);

			return self::generateErrorResponse($webstore_order_id, $response_message);
		}

		Yii::log(__CLASS__ . " successfully processed payment " . print_r($_POST, true), $this->logLevel, 'application.'.__CLASS__.'.'.__FUNCTION__);

		return self::generateSuccessResponse($webstore_order_id, $charge_total, $bank_transaction_id);
	}

	private static function generateSuccessResponse($orderId, $chargeTotal, $bankTransactionId)
	{
		$objCart = Cart::LoadByIdStr($orderId);

		$url = Yii::app()->createAbsoluteUrl('cart/receipt',array('getuid'=>$objCart->linkid),'http');

		$arrReturn =  array(
			'order_id' => $orderId,
			'amount' => !empty($chargeTotal) ? $chargeTotal : 0,
			'success' => true,
			'data' => !empty($bankTransactionId) ? $bankTransactionId : '',
			'output' => "<html><head><meta http-equiv=\"refresh\" content=\"0;url=$url\"></head><body><a href=\"$url\">" .
				Yii::t('global','Redirecting to your receipt')."...</a></body></html>"
		);

		return $arrReturn;
	}

	private static function generateErrorResponse($orderId, $messageText)
	{
		$objCart = Cart::LoadByIdStr($orderId);

		$url = Yii::app()->controller->createAbsoluteUrl('cart/restoredeclined', array('getuid'=>$objCart->linkid,'reason'=>$messageText));

		$arrReturn =  array(
			'order_id' => $orderId,
			'output' => "<html><head><meta http-equiv=\"refresh\" content=\"1;url=$url\"></head><body><a href=\"$url\">Verifying order, please wait...</a></body></html>",
			'success' => false,
		);

		return $arrReturn;
	}
}
