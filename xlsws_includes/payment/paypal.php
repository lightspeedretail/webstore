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
/**
 * Paypal Simple payment module
 *
 *
 *
 */

class paypal extends xlsws_class_payment {
	const x_delim_char = "|";

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues(get_class($this));

		if(isset($config['label']))
			return $config['label'];

		return "PayPal";
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 *
	 *
	 */
	public function admin_name() {
		$config = $this->getConfigValues(get_class($this));
		$strName = "PayPal";
		if (!$this->uses_jumper())$strName .= "&nbsp;&nbsp;&nbsp;<font size=2>Advanced Integration</font>";
		if ($config['live']=="test") $strName .= " **IN TEST (SANDBOX) MODE**";
		return $strName;
	}
	/**
	 * The Web Admin panel for configuring this payment option
	 *
	 * @param $parentObj (payment panel object)
	 * @return array
	 *
	 */
	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new QTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = 'PayPal Credit card (Visa, Mastercard, Amex)';

		$ret['login'] = new QTextBox($objParent);
		$ret['login']->Required = true;
		$ret['login']->Name = _sp('Business Email');

		$ret['address'] = new QListBox($objParent);
		$ret['address']->Name = _sp('Prompt for shipping address again on PayPal');
		$ret['address']->AddItem('off' , 1);
		$ret['address']->AddItem('on' , 0);
		$ret['address']->ToolTip = "Turns on shipping address on PayPal checkout. Turn on if you wish to receive PayPal's Confirmed Address from the user account. May be confusing to user to be prompted for shipping info twice.";


		$ret['live'] = new QListBox($objParent);
		$ret['live']->Name = _sp('Live/Sandbox');
		$ret['live']->AddItem('live' , 'live');
		$ret['live']->AddItem('sandbox' , 'test');

		$ret['ls_payment_method'] = new XLSTextBox($objParent);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Web Credit Card';
		$ret['ls_payment_method']->ToolTip = "Please enter the payment method (from LightSpeed) you would like the payment amount to import into";

		return $ret;
	}

	/**
	 * Check config fields
	 *
	 * The fields generated and returned in config_fields will be passed here for validity.
	 * Return true or false
	 *
	 * Admin panel will ONLY save field configs if all the fields are valid.
	 *
	 * @param $fields[]
	 * @return boolean
	 */
	public function check_config_fields($fields ) {
		return true;
	}

	/**
	 * Customer fields
	 *
	 * Returns customer fields
	 *
	 * @param $parentObj (payment panel object)
	 * @return array
	 */
	public function customer_fields($parentObj) {
		$ret= array();

		$ret['msg'] = new QLabel($parentObj);

		return $ret;
	}

	/**
	 * process
	 *
	 * Process function to build parameters to pass for CC authorization
	 * For more information on these options,
	 *
	 * @param $cart[], $fields[], ref $errortext
	 * @return string|boolean
	 */
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues(get_class($this));

		$paypal_email	= $config['login'];
		$paypal_url = "";

		if($config['live'] == 'live')
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		else
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

		$str = "";

		$str .= "<FORM name=\"_xclick\" action=\"$paypal_url\" method=\"POST\">";
		$str .= _xls_make_hidden('cmd',  '_xclick');
		$str .= _xls_make_hidden('business',   $paypal_email);
		$str .= _xls_make_hidden('currency_code',   _xls_get_conf('CURRENCY_DEFAULT' , 'USD'));
		$str .= _xls_make_hidden('item_name',   $cart->IdStr);
		$str .= _xls_make_hidden('first_name',   $customer->Firstname);
		$str .= _xls_make_hidden('last_name',   $customer->Lastname);
		$str .= _xls_make_hidden('address1',   $customer->Address11);
		$str .= _xls_make_hidden('address2',   $customer->Address12);

		$str .= _xls_make_hidden('city',   $customer->City1);
		$str .= _xls_make_hidden('state',   $customer->State1);
		$str .= _xls_make_hidden('zip',   $customer->Zip1);
		$str .= _xls_make_hidden('lc',   $customer->Country1);
		$str .= _xls_make_hidden('email',   $customer->Email);
		$str .= _xls_make_hidden('cartId',  $cart->IdStr);
		$str .= _xls_make_hidden('phone1',   $customer->Mainphone);
		$str .= _xls_make_hidden('rm',   '2');
		$str .= _xls_make_hidden('no_shipping',   $config['address']);
		$str .= _xls_make_hidden('no_note',   '1');

		$str .= _xls_make_hidden('notify_url',   _xls_site_dir() . "/" . "xls_payment_capture.php");
		$str .= _xls_make_hidden('return',   $cart->Link);
		$str .= _xls_make_hidden('cancel_return',   _xls_site_url('checkout/pg'));
		$str .= _xls_make_hidden('amount',  round($cart->Total , 2));

		$str .=  ('</FORM>');


		if(_xls_get_conf('DEBUG_PAYMENTS' , false))
			_xls_log(get_class($this) . " sending ".$cart->IdStr." in ".$config['live']." mode ".$str,true);

			return $str;
	}

	/**
	 * gateway_response_process
	 *
	 * Processes processor gateway response
	 * Processes returned $_GET or $_POST variables from the third party website
	 */
	public function gateway_response_process() {
		global $XLSWS_VARS;

		$paypal_url = "";
		$order_id = "";


		if(_xls_get_conf('DEBUG_PAYMENTS' , false))
			_xls_log(get_class($this) . " IPN Transaction ".print_r($XLSWS_VARS,true),true);

		$config = $this->getConfigValues(get_class($this));
		if($config['live'] == 'live')
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		else
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

		$paypal_fields = 'cmd=_notify-validate';

		foreach ($XLSWS_VARS as $key => $value) {
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

		if(_xls_get_conf('DEBUG_PAYMENTS' , false))
			_xls_log(get_class($this) . " IPN Verify Response ".$resp,true);

		if (strpos($resp,"VERIFIED") !== FALSE) {
		
			if ($XLSWS_VARS['payment_status']=="Completed")
			{
				$retarr =  array(
					'order_id' => $XLSWS_VARS['item_name'],
					'amount' => $XLSWS_VARS['mc_gross'],
					'success' => true,
					'data' => $XLSWS_VARS['txn_id'],
				);
				return $retarr;
			}
			else
			{
				QApplication::Log(E_ERROR, 'Paypal', "Paypal reported ".
					$XLSWS_VARS['payment_status']." payment on " . $XLSWS_VARS['item_name']);
				return false;
			}
						
			
			
		} else {

			QApplication::Log(E_ERROR, 'Paypal', "Paypal IPN verification failed " . print_r($XLSWS_VARS , true));
			return false;
		}
	}

	/**
	 * Whether this payment method uses a jumper page or not
	 * If it uses a jumper page then process() function must return a HTML FORM string.
	 * @return bool
	 */
	public function uses_jumper() {
		return true;
	}
}
