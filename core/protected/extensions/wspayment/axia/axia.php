<?php


class axia extends WsPayment
{
	protected $defaultName = "Axia";
	protected $version = 1.0;
	protected $uses_credit_card = true;
	protected $apiVersion = 1;
	public $advancedMode = true;


	/**
	 * The run() function is called from Web Store to run the process.
	 * @return array
	 */
	public function run() {

		require_once "usaepay.php";

		$DEBUGGING					= 1;				# Display additional information to track down problems
		$TESTING					= 1;				# Set the testing flag so that transactions are not live
		$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur

		$source_key			= $this->config['source_key'];
		$source_key_pin		= isset($this->config['source_key_pin']) ? $this->config['source_key_pin'] : false;

		$tran = new umTransaction;

		$tran->key = $source_key;
		if ($source_key_pin) {
			$tran->pin = $source_key_pin;
		}
		$tran->ip = $_SERVER['REMOTE_ADDR'];   // This allows fraud blocking on the customers ip address

		if ($this->config['live'] == 'test') {
			$tran->testmode = 1;
		} else {
			$tran->testmode = 0;
		}

		$tran->card = _xls_number_only($this->CheckoutForm->cardNumber);		// card number, no dashes, no spaces
		$tran->exp = $this->CheckoutForm->cardExpiryMonth.substr($this->CheckoutForm->cardExpiryYear,2,2);// expiration date 4 digits no /
		$tran->amount = $this->objCart->total;			// charge amount in dollars
		$tran->invoice = $this->objCart->id_str;   		// invoice number.  must be unique.
		$tran->cardholder = $this->CheckoutForm->cardNameOnCard; 	// name of card holder
		$tran->street = $this->CheckoutForm->billingAddress1 . " " . $this->CheckoutForm->billingAddress2;	// street address
		$tran->zip = str_replace(" ","",$this->CheckoutForm->billingPostal);			// zip code
		$tran->description = _xls_get_conf('STORE_NAME')." ".$this->objCart->id_str;	// description of charge
		$tran->cvv2 = $this->CheckoutForm->cardCVV;			// cvv2 code

		$tran->billfname = $this->CheckoutForm->contactFirstName;
		$tran->billlname = $this->CheckoutForm->contactLastName;
		$tran->billstreet = $this->CheckoutForm->billingAddress1;
		$tran->billstreet2 = $this->CheckoutForm->billingAddress2;
		$tran->billcity = $this->CheckoutForm->billingCity;
		$tran->billstate = $this->CheckoutForm->billingStateCode;
		$tran->billzip = $this->CheckoutForm->billingPostal;
		$tran->billcountry = $this->CheckoutForm->billingCountryCode;
		$tran->billphone = $this->CheckoutForm->contactPhone;
		$tran->email = $this->CheckoutForm->contactEmail;

		$tran->shipfname = $this->CheckoutForm->shippingFirstName;
		$tran->shiplname = $this->CheckoutForm->shippingLastName;
		$tran->shipstreet = $this->CheckoutForm->shippingAddress1;
		$tran->shipstreet2 = $this->CheckoutForm->shippingAddress2;
		$tran->shipcity = $this->CheckoutForm->shippingCity;
		$tran->shipstate = $this->CheckoutForm->shippingStateCode;
		$tran->shipzip = $this->CheckoutForm->shippingPostal;
		$tran->shipcountry = $this->CheckoutForm->shippingCountryCode;

		$tran->custid = Yii::app()->user->id;

		$tranTemp = clone $tran;

		Yii::log(
			sprintf(
				"%s sending %s for amt %s\nRequest %s",
				__CLASS__,
				$this->objCart->id_str,
				$this->objCart->total,
				print_r($this->obfuscateRequestArray($tranTemp), true)
			),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		if ($tran->Process()) {
			//We have success
			$arrReturn['success'] = true;
			$arrReturn['amount_paid'] =  $this->objCart->total;
			$arrReturn['result'] = $tran->refnum;
		} else {

			$arrReturn['success'] = false;
			$arrReturn['amount_paid'] = 0;
			$errortext = Yii::t('global',$tran->error);
			$arrReturn['result'] = Yii::t('global',$errortext);
			Yii::log("Declined: ".$errortext, 'error', 'application.'.__CLASS__.".".__FUNCTION__);


		}

		unset($tran->card);
		unset($tran->exp);
		unset($tran->key);
		unset($tran->pin);

		Yii::log(
			sprintf(
				"%s receiving %s",
				__CLASS__,
				print_r($tran, true)
			),
			$this->logLevel,
			'application.'.__CLASS__.".".__FUNCTION__
		);

		return $arrReturn;
	}


	/**
	 * Obfuscate sensitive information for logging purposes
	 *
	 * @param umTransaction $tran
	 * @return umTransaction
	 */
	private static function obfuscateRequestArray($tran)
	{
		if (empty($tran->card) === false)
		{
			// cc number
			$tran->card =
				substr_replace(
					$tran->card,
					str_repeat('*', strlen($tran->card) - 4),
					0,
					strlen($tran->card)-4
				);
		}

		if (empty($tran->cvv2) === false)
		{
			// cc cvv
			$tran->cvv2 =
				substr_replace(
					$tran->cvv2,
					str_repeat('*', strlen($tran->cvv2)),
					0,
					strlen($tran->cvv2)
				);
		}

		return $tran;
	}



}
