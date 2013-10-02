<?php

class moneris extends WsPayment
{
	protected $defaultName = "Moneris";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode = true;


	const x_delim_char = "|";
	private $paid_amount;


	public function run()
	{

		require_once "mpgClasses.php";


		/**************************** Request Variables *******************************/

		$store_id=$this->config['store_id'];
		$api_token=$this->config['api_token'];


		/************************* Transactional Variables ****************************/

		$type='purchase';
		$cust_id='';
		$order_id=$this->objCart->id_str.date("YmdHis");
		$amount=number_format(round($this->objCart->total,2),2, '.', '');
		$pan=_xls_number_only($this->CheckoutForm->cardNumber);
		$expiry_date=$this->CheckoutForm->cardExpiryMonth.substr($this->CheckoutForm->cardExpiryYear,2,2);
		$crypt='7';
		$dynamic_descriptor=_xls_get_conf('STORE_NAME');


		/************************** AVS Variables *****************************/
		$avs_street_number = '';
		$avs_street_name = $this->CheckoutForm->billingAddress1;
		$avs_zipcode = $this->CheckoutForm->billingPostal;
		$avs_email = $this->CheckoutForm->contactEmail;
		$avs_hostname = '';
		$avs_browser = '';
		$avs_shiptocountry = $this->CheckoutForm->shippingCountry;
		$avs_merchprodsku = '';
		$avs_shipmethod = $this->CheckoutForm->shippingProvider;
		$avs_custip = _xls_get_ip();
		$avs_custphone = _xls_number_only($this->CheckoutForm->contactPhone);
		/************************** CVD Variables *****************************/
		$cvd_indicator = '1';
		$cvd_value = $this->CheckoutForm->cardCVV;

		/********************** AVS Associative Array *************************/
		$avsTemplate = array('avs_street_number'=>$avs_street_number,
			'avs_street_name' =>$avs_street_name,
			'avs_zipcode' => $avs_zipcode,
            'avs_hostname'=>$avs_hostname,
            'avs_email' =>$avs_email,
            'avs_browser' =>$avs_browser,
			'avs_shiptocountry' => $avs_shiptocountry,
			'avs_shipmethod' => $avs_shipmethod,
			'avs_merchprodsku' => $avs_merchprodsku,
			'avs_custip'=>$avs_custip,
			'avs_custphone' => $avs_custphone
		);
		/********************** CVD Associative Array *************************/
		$cvdTemplate = array('cvd_indicator' => $cvd_indicator, 'cvd_value' => $cvd_value);
		/************************** AVS Object ********************************/
		$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);
		/************************** CVD Object ********************************/
		$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

		/*********************** Transactional Associative Array **********************/

		$txnArray=array('type'=>$type,
			'order_id'=>$order_id,
			'cust_id'=>$cust_id,
			'amount'=>$amount,
			'pan'=>$pan,
			'expdate'=>$expiry_date,
			'crypt_type'=>$crypt,
			'dynamic_descriptor'=>$dynamic_descriptor
		);

		$txnArrayx=array_merge(array('type'=>$type,
			'order_id'=>$order_id,
			'cust_id'=>$cust_id,
			'amount'=>$amount,
			'pan'=>'REDACTED',
			'expdate'=>'REDACTED',
			'crypt_type'=>$crypt,
			'dynamic_descriptor'=>$dynamic_descriptor
		),$avsTemplate);


		if(_xls_get_conf('DEBUG_PAYMENTS' , false)=="1") {
			Yii::log(get_class($this) . " sending ".print_r($txnArrayx,true)." for amt ".$this->objCart->total, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		/**************************** Transaction Object *****************************/

		$mpgTxn = new mpgTransaction($txnArray);

		/************************ Set AVS and CVD *****************************/
		//if ($this->config['avs']==1) $mpgTxn->setAvsInfo($mpgAvsInfo);
		if ($this->config['ccv']==1) $mpgTxn->setCvdInfo($mpgCvdInfo);

		/****************************** Request Object *******************************/

		$mpgRequest = new mpgRequest($mpgTxn);

		/***************************** HTTPS Post Object *****************************/

		$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest,$this->config);

		/******************************* Response ************************************/

		$mpgResponse=$mpgHttpPost->getMpgResponse();
		if (isset($mpgResponse->responseData['title']) && stripos($mpgResponse->responseData['title'],'Error')>0)
		{
			$code=500;
			$response =  Yii::t('global','Error: The credit card processor is currently unreachable.');
			Yii::log("Moneris system error: ".print_r($mpgResponse,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}
		else
		{
			$response = $mpgResponse->getMessage();
			$code = $mpgResponse->getResponseCode();
		}



		if($code>=1 && $code<=50) {
			//We have success
			$arrReturn['success']=true;
			$arrReturn['amount_paid']=$mpgResponse->getTransAmount();
			$arrReturn['result']=$mpgResponse->getAuthCode();
			$arrReturn['payment_date']=$mpgResponse->getTransDate()." ".$mpgResponse->getTransTime();
			if($this->config['live'] == 'test')
				$arrReturn['amount_paid']=0;

		} else {
			//unsuccessful
			$arrReturn['success']=false;
			$arrReturn['amount_paid']=0;
			$arrReturn['result'] = Yii::t('global',$response);
			Yii::log("Declined: ".$response, 'error', 'application.'.__CLASS__.".".__FUNCTION__);


		}

		return $arrReturn;

	}




}
