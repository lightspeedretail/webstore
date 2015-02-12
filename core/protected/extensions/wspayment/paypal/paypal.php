<?php

class paypal extends WsPayment
{

	protected $defaultName = "PayPal";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $cloudCompatible = true;


	/**
	 * The run() function is called from Web Store to run the process.
	 * The return array should have two elements: the first is the processor's api version
	 * that we force so that the form is received and processed as expected on the other
	 * end; the second is the form formatted as an html string.
	 *
	 * @return array
	 */
	public function run()
	{
		$paypal_email	= $this->config['login'];

		if ($this->config['live'] == 'live')
		{
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		else
		{
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}

		$str = "";

		$str .= sprintf('<FORM name="_xclick" action="%s" method="POST">', $paypal_url);
		$str .= _xls_make_hidden('cmd',  '_xclick');
		$str .= _xls_make_hidden('business',   $paypal_email);
		$str .= _xls_make_hidden('currency_code',   _xls_get_conf('CURRENCY_DEFAULT' , 'USD'));
		$str .= _xls_make_hidden('item_name',   $this->objCart->id_str);
		$str .= _xls_make_hidden('first_name',   $this->CheckoutForm->contactFirstName);
		$str .= _xls_make_hidden('last_name',   $this->CheckoutForm->contactLastName);

		$str .= _xls_make_hidden('address1',   $this->CheckoutForm->billingAddress1);
		$str .= _xls_make_hidden('address2',   $this->CheckoutForm->billingAddress2);
		$str .= _xls_make_hidden('city',   $this->CheckoutForm->billingCity);
		$str .= _xls_make_hidden('state',   $this->CheckoutForm->billingStateCode);
		$str .= _xls_make_hidden('zip',  $this->CheckoutForm->billingPostal);
		$str .= _xls_make_hidden('country',  $this->CheckoutForm->billingCountryCode);

		$str .= _xls_make_hidden('email',   $this->CheckoutForm->contactEmail);
		$str .= _xls_make_hidden('cartId',  $this->objCart->id_str);
		$str .= _xls_make_hidden('phone1',    $this->CheckoutForm->contactPhone);
		$str .= _xls_make_hidden('rm',   '2');
		$str .= _xls_make_hidden('no_shipping',   (isset($this->config['address']) ?  $this->config['address'] : 1));
		$str .= _xls_make_hidden('no_note',   '1');

		$str .= _xls_make_hidden('notify_url',
			Yii::app()->controller->createAbsoluteUrl('cart/payment/').'/'.$this->modulename);
		$str .= _xls_make_hidden('return',
			Yii::app()->controller->createAbsoluteUrl('cart/receipt', array('getuid'=>$this->objCart->linkid),'http'));
		$str .= _xls_make_hidden('cancel_return',
			Yii::app()->controller->createAbsoluteUrl('cart/restoredeclined', array('getuid'=>$this->objCart->linkid, 'reason' => 'Cancelled')));
		$str .= _xls_make_hidden('amount',  round($this->objCart->total , 2));

		$str .=  '</FORM>';

		Yii::log(
			sprintf(
				"%s sending %s in %s mode\nString: %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->config['live'],
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
	public function gateway_response_process() {

		Yii::log("IPN Transaction ".print_r($_POST,true), $this->logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

		$config = $this->getConfigValues(get_class($this));

		if ($config['live'] == 'live')
		{
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		else
		{
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}

		$paypal_fields = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$paypal_fields .= "&$key=$value";
		}

		$ch = curl_init($paypal_url);
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $paypal_fields, "& " )); // use HTTP POST to send form data
		// Paypal has switched to TLS to mitigate POODLE, see:
		//  https://ppmts.custhelp.com/app/answers/detail/a_id/1182/session/L3RpbWUvMTQxNjg0NzY2Mi9zaWQvb0t6Y3llOG0%3D
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$resp = curl_exec($ch); // execute post and get results
		curl_close ($ch);

		Yii::log("IPN Verify Response ".$resp, $this->logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

		if (strpos($resp,"VERIFIED") !== FALSE)
		{
			if (Yii::app()->getRequest()->getPost('payment_status') == "Completed")
			{
				$retarr =  array(
					'order_id' => Yii::app()->getRequest()->getPost('item_name'),
					'amount' => Yii::app()->getRequest()->getPost('mc_gross'),
					'success' => true,
					'data' => Yii::app()->getRequest()->getPost('txn_id'),
					'payment_date' => Yii::app()->getRequest()->getPost('payment_date'),
					'output'=>' '
				);
				return $retarr;
			}
			else
			{
				Yii::log("Paypal reported ".
					Yii::app()->getRequest()->getPost('payment_status')." payment on " .
					Yii::app()->getRequest()->getPost('item_name'), $this->logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

				return false;
			}
		}
		else
		{
			Yii::log("Paypal IPN verification failed " . print_r($_POST , true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}
	}
}
