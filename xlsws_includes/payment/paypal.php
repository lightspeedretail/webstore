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

class Paypal extends xlsws_class_payment {
	const x_delim_char = "|";

	/**
	 * The name of the payment module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues('paypal');

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
		return _sp('PayPal');
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

		$ret['live'] = new QListBox($objParent);
		$ret['live']->Name = _sp('Live/Test');
		$ret['live']->AddItem('test' , 'test'); // TODO before distribute make live
		$ret['live']->AddItem('live' , 'live');

		$ret['ls_payment_method'] = new XLSTextBox($objParent);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Credit Card';
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

		$config = $this->getConfigValues('paypal');

		$paypal_email	= $config['login'];
		$paypal_url = "";

		if($config['live'] == 'live')
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		else
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscrl";

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
		$str .= _xls_make_hidden('no_shipping',   '2');

		$str .= _xls_make_hidden('notify_url',   _xls_site_dir() . "/" . "xls_payment_capture.php");

		$str .= _xls_make_hidden('amount',  round($cart->Total , 2));

		$str .=  ('</FORM>');

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

		$config = $this->getConfigValues('paypal');
		if($config['live'] == 'live')
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		else
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscrl";

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
		
		if (strpos($resp,"VERIFIED") !== FALSE) {
		
			if ($XLSWS_VARS['payment_status']=="Completed")
			{
				$retarr =  array(
					'order_id' => $XLSWS_VARS['item_name'],
					'amount' => $XLSWS_VARS['mc_gross'],
					'success' => true,
					'data' => $XLSWS_VARS['txn_id'],
				);
				QApplication::Log(E_ERROR, 'Paypal', "Paypal ".$XLSWS_VARS['payment_status']." " . print_r($retarr , true));
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
