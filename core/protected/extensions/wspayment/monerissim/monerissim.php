<?php

class monerissim extends WsPayment
{
	protected $defaultName = "Moneris";
	protected $version = 1.0;
	protected $uses_jumper = true;
	protected $apiVersion = 1;
	public $cloudCompatible = true;



	public function run()
	{

		$ps_store_id = $this->config['ps_store_id'];
		$hpp_key = $this->config['hpp_key'];

		if($this->config['live'] == 'live')
			$moneris_url = "https://www3.moneris.com/HPPDP/index.php";
		else
			$moneris_url = "https://esqa.moneris.com/HPPDP/index.php";

		$str = "";

		$str .= "<FORM method=\"POST\" action=\"$moneris_url\">";
		$str .= _xls_make_hidden('ps_store_id',  $ps_store_id);
		$str .= _xls_make_hidden('hpp_key',  $hpp_key);
		$str .= _xls_make_hidden('order_id', $this->objCart->id_str);

		foreach ($this->objCart->cartItems as $id=>$item)
		{
			$str .= _xls_make_hidden('description'.$id, $item->description);
			$str .= _xls_make_hidden('id'.$id, $item->code);
			$str .= _xls_make_hidden('quantity'.$id, $item->qty);
//			$str .= _xls_make_hidden('price'.$id, $item->discount ? number_format(round($item->sell_discount,2),2) : number_format(round($item->sell,2),2));
//			$str .= _xls_make_hidden('subtotal'.$id, number_format(round($item->sell_total,2),2));
			$str .= _xls_make_hidden('price'.$id, number_format(round($item->sell_total,2),2));
		}

		foreach ($this->objCart->Taxes as $tax=>$taxvalue)
			switch (strtolower($tax)) {
				case 'gst':
					if ($taxvalue>0)
						$str .= _xls_make_hidden('gst', number_format(round($taxvalue,2),2));
					break;

				case 'pst':
				case 'qst':
					if ($taxvalue>0)
						$str .= _xls_make_hidden('pst', number_format(round($taxvalue,2),2));
					break;

				case 'hst':
					if ($taxvalue>0)
						$str .= _xls_make_hidden('hst', number_format(round($taxvalue,2),2));
					break;

				// todo - account for electronics tax
			}




		$str .= _xls_make_hidden('shipping_cost', number_format(round($this->objCart->shipping_sell,2),2));
		$str .= _xls_make_hidden('note', $this->CheckoutForm->orderNotes);

		$str .= _xls_make_hidden('bill_first_name',   $this->CheckoutForm->contactFirstName);
		$str .= _xls_make_hidden('bill_last_name',   $this->CheckoutForm->contactLastName);
		$str .= _xls_make_hidden('bill_company_name', $this->CheckoutForm->contactCompany);
		$str .= _xls_make_hidden('bill_address_one', ($this->CheckoutForm->billingAddress2 != '' ?
			$this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2 : $this->CheckoutForm->billingAddress1));
		$str .= _xls_make_hidden('bill_city', $this->CheckoutForm->billingCity);
		$str .= _xls_make_hidden('bill_state_or_province', $this->CheckoutForm->billingState);
		$str .= _xls_make_hidden('bill_postal_code', $this->CheckoutForm->billingPostal);
		$str .= _xls_make_hidden('bill_country', $this->CheckoutForm->billingCountry);
		$str .= _xls_make_hidden('bill_phone', _xls_number_only($this->CheckoutForm->contactPhone));

		$str .= _xls_make_hidden('email', $this->CheckoutForm->contactEmail);
		$str .= _xls_make_hidden('cust_id', "WC-" . $this->objCart->customer_id);

		$str .= _xls_make_hidden('ship_first_name',   $this->CheckoutForm->shippingFirstName);
		$str .= _xls_make_hidden('ship_last_name',   $this->CheckoutForm->shippingLastName);
		$str .= _xls_make_hidden('ship_company_name',   $this->CheckoutForm->shippingCompany);
		$str .= _xls_make_hidden('ship_address_one',   $this->CheckoutForm->shippingAddress1 . " " . $this->CheckoutForm->shippingAddress2);
		$str .= _xls_make_hidden('ship_city',   $this->CheckoutForm->shippingCity);
		$str .= _xls_make_hidden('ship_state_or_province',   $this->CheckoutForm->shippingState);
		$str .= _xls_make_hidden('ship_postal_code',   $this->CheckoutForm->shippingPostal);
		$str .= _xls_make_hidden('ship_country',   $this->CheckoutForm->shippingCountry);

		$str .= _xls_make_hidden('charge_total',  number_format(round($this->objCart->total , 2),2));

		$str .=  ('</FORM>');

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1")
			_xls_log(get_class($this) . " sending ".$this->objCart->id_str." in ".$this->config['live']." mode ".$str,true);

		$arrReturn['api'] = $this->apiVersion;
		$arrReturn['jump_form']=$str;
		return $arrReturn;

	}


	public function gateway_response_process() {

		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1")
			Yii::log(get_class($this) . "  Transaction ".print_r($_POST,true), CLogger::LEVEL_ERROR, get_class($this));

		$response_code = Yii::app()->getRequest()->getPost('response_code');
		$response_order_id = Yii::app()->getRequest()->getPost('response_order_id');
		$charge_total = Yii::app()->getRequest()->getPost('charge_total');
		$bank_transaction_id = Yii::app()->getRequest()->getPost('bank_transaction_id');
		$result = Yii::app()->getRequest()->getPost('result');


		if(empty($result) || empty($response_order_id) || is_null($response_code))
			return false;

		if($response_code >= 50){
			// failed order
			Yii::log(get_class($this) . " failed order payment received ".print_r($_POST,true), CLogger::LEVEL_ERROR, get_class($this));
			return false;
		}

		$objCart = Cart::LoadByIdStr($response_order_id);
		$url = Yii::app()->createAbsoluteUrl('cart/receipt',array('getuid'=>$objCart->linkid),'http');

		return array(
			'order_id' => $response_order_id,
			'amount' => !empty($charge_total) ? $charge_total : 0,
			'success' => true,
			'data' => !empty($bank_transaction_id) ? $bank_transaction_id : '',
			'output' => "<html><head><meta http-equiv=\"refresh\" content=\"0;url=$url\"></head><body><a href=\"$url\">" .
			Yii::t('global','Redirecting to your receipt')."...</a></body></html>"
		);

	}


	}
