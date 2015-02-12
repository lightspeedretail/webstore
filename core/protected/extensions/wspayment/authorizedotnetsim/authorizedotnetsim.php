<?php

class authorizedotnetsim extends WsPayment
{

	protected $defaultName = "Authorize.Net";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $apiVersion = 1;
	public $cloudCompatible = true;
	public $performInternalFinalizeSteps = false;
	protected $uses_credit_card = true;

	const x_delim_char = "|";

	/**
	 * Run the payment process
	 * @return mixed
	 */
	public function run()
	{

		$auth_net_login_id	= $this->config['login'];
		$auth_net_tran_key	= $this->config['trans_key'];

		/**
		 * This option, and the commented $ret['live']->AddItem('dev' , 'dev') above, are only for API development work.
		 * Regular Authorize.net customers will only use "live" and "test" modes through their account, which can be
		 * chosen through the Web Admin panel.
		 *
		 */
		if($this->config['live'] == 'test')
		{
			$auth_net_url	= "https://test.authorize.net/gateway/transact.dll";
		}
		else
		{
			$auth_net_url	= "https://secure.authorize.net/gateway/transact.dll";
		}

		$str = "";

		$str .= sprintf('<FORM action="%s" method="POST">', $auth_net_url);
		$str .= $this->InsertFP($auth_net_login_id, $auth_net_tran_key, round($this->objCart->Total,2), $this->objCart->currency);

		$str .= _xls_make_hidden('x_invoice_num',           $this->objCart->id_str);
		$str .= _xls_make_hidden('x_first_name',            $this->CheckoutForm->contactFirstName);
		$str .= _xls_make_hidden('x_last_name',             $this->CheckoutForm->contactLastName);
		$str .= _xls_make_hidden('x_company',               $this->CheckoutForm->contactCompany);
		$str .= _xls_make_hidden('x_address',               $this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2);
		$str .= _xls_make_hidden('x_city',                  $this->CheckoutForm->billingCity);
		$str .= _xls_make_hidden('x_state',                 $this->CheckoutForm->billingStateCode);
		$str .= _xls_make_hidden('x_zip',                   $this->CheckoutForm->billingPostal);
		$str .= _xls_make_hidden('x_country',               $this->CheckoutForm->billingCountryCode);
		$str .= _xls_make_hidden('x_phone',                 _xls_number_only($this->CheckoutForm->contactPhone));
		$str .= _xls_make_hidden('x_email',                 $this->CheckoutForm->contactEmail);
		$str .= _xls_make_hidden('x_cust_id',               "WC-" . $this->objCart->customer_id);
		$str .= _xls_make_hidden('x_ship_to_first_name',    $this->CheckoutForm->shippingFirstName);
		$str .= _xls_make_hidden('x_ship_to_last_name',     $this->CheckoutForm->shippingLastName);
		$str .= _xls_make_hidden('x_ship_to_company',       $this->CheckoutForm->shippingCompany);
		$str .= _xls_make_hidden('x_ship_to_address',       $this->CheckoutForm->shippingAddress1 . " " . $this->CheckoutForm->shippingAddress2);
		$str .= _xls_make_hidden('x_ship_to_city',          $this->CheckoutForm->shippingCity);
		$str .= _xls_make_hidden('x_ship_to_state',         $this->CheckoutForm->shippingStateCode);
		$str .= _xls_make_hidden('x_ship_to_zip',           $this->CheckoutForm->shippingPostal);
		$str .= _xls_make_hidden('x_ship_to_country',       $this->CheckoutForm->shippingCountryCode);
		$str .= _xls_make_hidden('x_description',           _xls_get_conf('STORE_NAME', "Online") . " Order");
		$str .= _xls_make_hidden('x_login',                 $auth_net_login_id);
		$str .= _xls_make_hidden('x_solution_id',           'A1000010');
		$str .= _xls_make_hidden('x_type',                  'AUTH_CAPTURE');
		$str .= _xls_make_hidden('x_currency_code',         $this->objCart->currency);  //trying to get currency code to submit
		$str .= _xls_make_hidden('x_amount',                round($this->objCart->Total,2));
		$str .= _xls_make_hidden('x_show_form',             'PAYMENT_FORM');
		$str .= _xls_make_hidden('x_relay_response',        'TRUE');

		$str .= _xls_make_hidden(
			'x_relay_url',
			Yii::app()->controller->createAbsoluteUrl('cart/payment', array(), 'http').'/'.$this->modulename
		);

		$str .= _xls_make_hidden(
			'x_cancel_url',
			Yii::app()->controller->createAbsoluteUrl('cart/restore', array('getuid' => $this->objCart->linkid), 'http')
		);

		if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
		{
			$str .= _xls_make_hidden('x_header_html_payment_form', str_replace("\"","'",
				CHtml::image("https:".Yii::app()->params['HEADER_IMAGE'],
					Yii::app()->params['STORE_NAME'],
					array('style'=>'max-width:580px'))
			));
		}
		else
		{
			$str .= _xls_make_hidden(
				'x_header_html_payment_form',
				str_replace(
					"\"",
					"'",
					CHtml::image(
						Yii::app()->controller->createAbsoluteUrl(Yii::app()->params['HEADER_IMAGE'], array(), 'https'),
						Yii::app()->params['STORE_NAME'],
						array('style' => 'max-width:580px')
					)
				)
			);
		}

		$str .= ('</FORM>');

		Yii::log(
			sprintf(
				"%s sending %s in %s mode\nRequest %s",
				__CLASS__,
				$this->objCart->id_str,
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
	 * Computes hash, then converts to hex format, used as part of "fingerprint" for Auth.net simple
	 *
	 * @param $key string - transaction key
	 * @param $data array
	 * @return string
	 */
	public function hmac ($key, $data) {
		return hash_hmac('md5', $data, $key);
	}

	/**
	 * CalculateFP
	 * Calculate and return Fingerprint for Auth.net simple access
	 * Use when you need control on the HTML output
	 * @param $loginid string
	 * @param $x_tran_key string
	 * @param $amount float
	 * @param $sequence int
	 * @param $tstamp int (time)
	 * @param $currency string (optional)
	 * @return string
	 */
	public function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp, $currency = "") {
		return ($this->hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
	}

	/**
	 * Inserts the hidden variables in the HTML FORM required for SIM
	 * and invokes hmac function to calculate fingerprint.
	 * @param $loginid string
	 * @param $x_tran_key string
	 * @param $amount float
	 * @param $currency string
	 * @return string
	 */
	public function InsertFP ($loginid, $x_tran_key, $amount, $currency) {
		srand(time());

		$sequence = rand(1, 1000);

		$tstamp = time ();

		$fingerprint = $this->hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

		$str = ('<input type="hidden" name="x_fp_sequence" value="' . $sequence . '"/>' );
		$str .= ('<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '"/>' );
		$str .= ('<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '"/>' );

		return $str;
	}


	/**
	 * gateway_response_process
	 *
	 * Processes processor gateway response
	 * Processes returned $_GET or $_POST variables from the third party website
	 */
	public function gateway_response_process()
	{
		Yii::log("Response Transaction ".print_r($_POST, true), $this->logLevel, 'application.'.__CLASS__.".".__FUNCTION__);

		$x_response_code = Yii::app()->getRequest()->getPost('x_response_code');
		$x_invoice_num = Yii::app()->getRequest()->getPost('x_invoice_num');
		$x_MD5_Hash = Yii::app()->getRequest()->getPost('x_MD5_Hash');
		$x_amount = Yii::app()->getRequest()->getPost('x_amount');
		$x_trans_id = Yii::app()->getRequest()->getPost('x_trans_id');

		if (empty($x_response_code) || empty($x_invoice_num))
		{
			return false;
		}

		if ($x_response_code != 1)
		{
			// failed order
			Yii::log(__CLASS__ . " failed order payment received ".print_r($_POST,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		if (isset($this->config['md5hash'])  && ($this->config['md5hash']) && !empty($x_MD5_Hash))
		{
			$md5 = strtolower(md5($this->config['md5hash'] . $this->config['login'] . Yii::app()->getRequest()->getPost('x_trans_id') . $x_amount));
			if(strtolower($x_MD5_Hash) != $md5)
			{
				Yii::log("authorize.net.sim failed md5 hash", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		}
		
		$objCart = Cart::LoadByIdStr($x_invoice_num);
		$url = Yii::app()->createAbsoluteUrl('cart/receipt', array('getuid' => $objCart->linkid));

		return array(
			'order_id' => $x_invoice_num,
			'amount' => !empty($x_amount) ? $x_amount : 0,
			'success' => true,
			'data' => !empty($x_trans_id) ? $x_trans_id : '',
			'output' => "<html><head><meta http-equiv=\"refresh\" content=\"0;url=$url\"></head><body><a href=\"$url\">" .
				Yii::t('global','Redirecting to your receipt')."...</a></body></html>"

		);
	}




}
