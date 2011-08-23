<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

include_once(XLSWS_INCLUDES . 'payment/credit_card.php');
include_once('usaepay.php');

class axia extends credit_card {
	private $paid_amount;

	public function admin_name() {
		return "Axia via USAEpay";
	}

	public function name() {

		$config = $this->GetConfigurationValues('axia');

		if(isset($config['label'])) {
			return $config['label'];
		}

		return "Credit Card";
	}

	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = 'Credit card (Visa, Mastercard, Amex)';


		$ret['source_key'] = new XLSTextBox($objParent);
		$ret['source_key']->Required = true;
		$ret['source_key']->Name = _sp('Source Key');
		$ret['source_key']->Width = 320;
		$ret['source_key']->ToolTip = "Axia account key";

		$ret['source_key_pin'] = new XLSTextBox($objParent);
		$ret['source_key_pin']->Required = false;
		$ret['source_key_pin']->Name = _sp('PIN for Source Key (if set)');
		$ret['source_key_pin']->ToolTip = "PIN number if you have set one for your account";

		$ret['live'] = new XLSListBox($objParent);
		$ret['live']->Name = _sp('Deployment Mode');
		$ret['live']->AddItem('live' , 'live');
		$ret['live']->AddItem('test' , 'test');

		$ret['ls_payment_method'] = new XLSTextBox($objParent);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Credit Card';
		$ret['ls_payment_method']->ToolTip = "Please enter the payment method (from LightSpeed) you would like the payment amount to import into";

		return $ret;
	}

	public function check_config_fields($fields) {
		return true;
	}

	// See http://wiki.usaepay.com/developer/phplibrary?DokuWiki=c39cbfafeb3eb97dbc9173da475ed407
	public function process($cart, $fields, $errortext) {

		$customer = $this->customer();

		$config = $this->GetConfigurationValues('axia');

		$DEBUGGING					= 1;				# Display additional information to track down problems
		$TESTING					= 1;				# Set the testing flag so that transactions are not live
		$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur

		$source_key			= $config['source_key'];
		$source_key_pin		= isset($config['source_key_pin']) ? $config['source_key_pin'] : false;

		$tran = new umTransaction;

		$tran->key = $source_key;
		if ($source_key_pin) {
			$tran->pin = $source_key_pin;
		}
		$tran->ip = $_SERVER['REMOTE_ADDR'];   // This allows fraud blocking on the customers ip address

		if ($config['live'] == 'test') {
			$tran->testmode = 1;
		} else {
			$tran->testmode = 0;
		}

		$tran->card = $fields['ccnum']->Text;		// card number, no dashes, no spaces
		$tran->exp = $fields['ccexpmon']->SelectedValue.substr($fields['ccexpyr']->SelectedValue,2,2);// expiration date 4 digits no /
		$tran->amount = $cart->Total;			// charge amount in dollars
		$tran->invoice = $cart->IdStr;   		// invoice number.  must be unique.
		$tran->cardholder = $customer->Firstname . $customer->Lastname; 	// name of card holder
		$tran->street = $customer->Address11 . " " . $customer->Address12;	// street address
		$tran->zip = $customer->Zip1;			// zip code
		$tran->description = $cart->IdStr;	// description of charge
		$tran->cvv2 = $fields['ccsec']->Text;			// cvv2 code

		$tran->billfname = $customer->Firstname;
		$tran->billlname = $customer->Lastname;
		$tran->billstreet = $customer->Address11;
		$tran->billstreet2 = $customer->Address12;
		$tran->billcity = $customer->City1;
		$tran->billstate = $customer->State1;
		$tran->billzip = $customer->Zip1;
		$tran->billcountry = $customer->Country1;
		$tran->email = $customer->Email;

		$tran->shipfname = $cart->ShipFirstname;
		$tran->shiplname = $cart->ShipLastname;
		$tran->shipstreet = $cart->ShipAddress1;
		$tran->shipstreet2 = $cart->ShipAddress2;
		$tran->shipcity = $cart->ShipCity;
		$tran->shipstate = $cart->ShipState;
		$tran->shipzip = $cart->ShipZip;
		$tran->shipcountry = $cart->ShipCountry;

		$tran->custid = $cart->CustomerId;

		if ($tran->Process()) {
			$this->paid_amount = $cart->Total;
			// get the transaction ID
			return $tran->refnum;
		} else {
			$this->paid_amount = 0;
			$errortext = _sp("Your credit card has been declined");
			return FALSE;
		}

		$this->paid_amount = $cart->Total;
		return $resp_vals[4];
	}

	public function paid_amount(Cart $cart){
		return $this->paid_amount;
	}

	public function check(){
		return true;
	}
}
