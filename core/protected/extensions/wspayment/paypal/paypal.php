<?php

class paypal extends WsPayment
{

	protected $defaultName = "PayPal";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $apiVersion = 1;

	/**
	 * The run() function is called from Web Store to run the process.
	 * The return array should have two elements: the first is true/false if the transaction was successful. The second
	 * string is either the successful Transaction ID, or the failure Error String to display to the user.
	 * @param ContactForm $CheckoutForm
	 * @param Cart $objCart
	 * @return array
	 */
	public function run()
	{

		$paypal_email	= $this->config['login'];

		if($this->config['live'] == 'live')
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		else
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

		$str = "";

		$str .= "<FORM name=\"_xclick\" action=\"$paypal_url\" method=\"POST\">";
		$str .= _xls_make_hidden('cmd',  '_xclick');
		$str .= _xls_make_hidden('business',   $paypal_email);
		$str .= _xls_make_hidden('currency_code',   _xls_get_conf('CURRENCY_DEFAULT' , 'USD'));
		$str .= _xls_make_hidden('item_name',   $this->objCart->id_str);
		$str .= _xls_make_hidden('first_name',   $this->objCart->customer->first_name);
		$str .= _xls_make_hidden('last_name',   $this->objCart->customer->last_name);
		$str .= _xls_make_hidden('address1',   $this->objCart->billaddress->address1);
		$str .= _xls_make_hidden('address2',   $this->objCart->billaddress->address2);

		$str .= _xls_make_hidden('city',   $this->objCart->billaddress->city);
		$str .= _xls_make_hidden('state',   $this->objCart->billaddress->state);
		$str .= _xls_make_hidden('zip',   $this->objCart->billaddress->postal);
		$str .= _xls_make_hidden('lc',   $this->objCart->billaddress->country);
		$str .= _xls_make_hidden('email',   $this->objCart->customer->email);
		$str .= _xls_make_hidden('cartId',  $this->objCart->id_str);
		$str .= _xls_make_hidden('phone1',   $this->objCart->customer->mainphone);
		$str .= _xls_make_hidden('rm',   '2');
		$str .= _xls_make_hidden('no_shipping',   (isset($this->config['address']) ?  $this->config['address'] : 1));
		$str .= _xls_make_hidden('no_note',   '1');

		$str .= _xls_make_hidden('notify_url', Yii::app()->controller->createAbsoluteUrl('/cart/payment/'.$this->modulename));
		$str .= _xls_make_hidden('return',   Yii::app()->controller->createAbsoluteUrl('/cart/receipt', array('getuid'=>$this->objCart->linkid)));
		$str .= _xls_make_hidden('cancel_return',   Yii::app()->controller->createAbsoluteUrl('cart/restore', array('getuid'=>$this->objCart->linkid)));
		$str .= _xls_make_hidden('amount',  round($this->objCart->total , 2));

		$str .=  ('</FORM>');


		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1")
			_xls_log(get_class($this) . " sending ".$this->objCart->id_str." in ".$this->config['live']." mode ".$str,true);

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
	public function gateway_response_process() {

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1")
			Yii::log(get_class($this) . " IPN Transaction ".print_r($_POST,true), CLogger::LEVEL_ERROR, get_class($this));

		$config = $this->getConfigValues(get_class($this));
		if($config['live'] == 'live')
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		else
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

		$paypal_fields = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$paypal_fields .= "&$key=$value";
		}

		$ch = curl_init($paypal_url);
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $paypal_fields, "& " )); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1")
			Yii::log(get_class($this) . " IPN Verify Response ".$resp, CLogger::LEVEL_ERROR, get_class($this));


		if (strpos($resp,"VERIFIED") !== FALSE) {

			if (Yii::app()->getRequest()->getPost('payment_status')=="Completed")
			{
				$retarr =  array(
					'order_id' => Yii::app()->getRequest()->getPost('item_name'),
					'amount' => Yii::app()->getRequest()->getPost('mc_gross'),
					'success' => true,
					'data' => Yii::app()->getRequest()->getPost('txn_id'),
					'payment_date' => Yii::app()->getRequest()->getPost('payment_date')
				);
				return $retarr;
			}
			else
			{
				Yii::log("Paypal reported ".
					Yii::app()->getRequest()->getPost('payment_status')." payment on " . Yii::app()->getRequest()->getPost('item_name'), CLogger::LEVEL_INFO, get_class($this));
				return false;
			}



		} else {
			Yii::log("Paypal IPN verification failed " . print_r($_POST , true), 'error', get_class($this));
			return false;
		}
	}

	

}
