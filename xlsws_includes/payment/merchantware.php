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
 * Merchantware payment module
 *
 *
 *
 */

include_once(XLSWS_INCLUDES . 'payment/credit_card.php');

class merchantware extends credit_card {
	private $paid_amount;

	public function admin_name() {
		return "MerchantWARE Online";
	}

	public function name() {
		$config = $this->getConfigValues('merchantware');

		if(isset($config['label']))
			return $config['label'];

		return "Credit Card";
	}

	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = 'Credit card (Visa, Mastercard, Amex)';

		$ret['name'] = new XLSTextBox($objParent);
		$ret['name']->Name = _sp('Account Name');
		$ret['name']->ToolTip = _sp('Your assigned account name');

		$ret['site_id'] = new XLSTextBox($objParent);
		$ret['site_id']->Required = true;
		$ret['site_id']->Name = _sp('Account Site ID');
		$ret['site_id']->ToolTip = _sp('Format: XXXXXXXX');

		$ret['trans_key'] = new XLSTextBox($objParent);
		$ret['trans_key']->Required = true;
		$ret['trans_key']->Name = _sp('Account Key');
		$ret['trans_key']->ToolTip = _sp('Format: XXXXX-XXXXX-XXXXX-XXXXX-XXXXX');
		$ret['trans_key']->Width = 300;

		$ret['ls_payment_method'] = new XLSTextBox($objParent);
		$ret['ls_payment_method']->Name = _sp('LightSpeed Payment Method');
		$ret['ls_payment_method']->Required = true;
		$ret['ls_payment_method']->Text = 'Credit Card';
		$ret['ls_payment_method']->ToolTip = "Please enter the payment method (from LightSpeed) you would like the payment amount to import into";

		return $ret;
	}

	public function check_config_fields($fields ) {
		return true;
	}

	// See https://ps1.merchantware.net/merchantware/documentation31/
	public function process($cart , $fields, $errortext) {
		$customer = $this->customer();

		$config = $this->getConfigValues('merchantware');

		// Credential configuration
		$cred_site_id = $config['site_id'];
		$cred_key = $config['trans_key'];
		$cred_name = $config['name'];

		// URL Configuration
		$merchantware_url = "https://ps1.merchantware.net/MerchantWARE/ws/RetailTransaction/TXRetail31.asmx";


        // MerchantWARE specific values
        $trans_info_transactionid = '';     // Transaction id
        $trans_info_allow_duplicate = '';   // Turn duplicate checking on or off
        $trans_info_register_num = '';      // Register number
        
        //MerchantWARE expects expiry in 4 digit format
        $card_info_expiry = $fields['ccexpmon']->SelectedValue.substr($fields['ccexpyr']->SelectedValue,2,2);
        
        //MerchantWARE expects no dashes in WO number
        $wo = str_replace("-","",$cart->IdStr);
        
        // Construct SOAP packet for delivery
        $xml_data =
            '<soap:Envelope
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema"
            xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Body>
                    <IssueKeyedSale
                    xmlns="http://merchantwarehouse.com/MerchantWARE/Client3_1/TransactionRetail">
                        <strName>'.$config['name'].'</strName>
                        <strSiteId>'.$config['site_id'].'</strSiteId>
                        <strKey>'.$config['trans_key'].'</strKey>
                        <strOrderNumber>'.$wo.'</strOrderNumber>
                        <strAmount>'.$cart->Total.'</strAmount>
                        <strPAN>'.$fields['ccnum']->Text.'</strPAN>
                        <strExpDate>'.$card_info_expiry.'</strExpDate>
                        <strCardHolder>'.$customer->Firstname.' '.$customer->Lastname.'</strCardHolder>
                        <strAVSStreetAddress>'.$customer->Address11.'</strAVSStreetAddress>
                        <strAVSZipCode>'.$customer->Zip1.'</strAVSZipCode>
                        <strCVCode>'.$fields['ccsec']->Text.'</strCVCode>
                        <strAllowDuplicates>'.$trans_info_allow_duplicate.'</strAllowDuplicates>
                        <strRegisterNum>'.$trans_info_register_num .'</strRegisterNum>
                        <strTransactionId>'.$trans_info_transactionid .'</strTransactionId>
                    </IssueKeyedSale>
                </soap:Body>
            </soap:Envelope>';        


		$ch = curl_init($merchantware_url);

		// Set header with SOAP Action
		$soapaction = "http://merchantwarehouse.com/MerchantWARE/Client3_1/TransactionRetail/IssueKeyedSale";
		$headers = array("Content-Type: text/xml; charset=utf-8", "SOAPAction: ".$soapaction);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 0);				// set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// Returns response data instead of TRUE(1)

		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);	// use HTTP POST to send SOAP XML Data

		$resp = curl_exec($ch);								//execute post and get results
		curl_close ($ch);

		// Parse xml for response values
		$oXML = new SimpleXMLElement($resp);

		// Setup xpath namespace alias for response fields
		$oXML->registerXPathNamespace("mw", "http://merchantwarehouse.com/MerchantWARE/Client3_1/TransactionRetail");

		//These items return arrays, so we read value 0 for the information
		$response_status = $oXML->xpath('//mw:ApprovalStatus');
		$response_authorization_code = $oXML->xpath('//mw:AuthCode');

		// Handle transaction response data
		if($response_status[0] != 'APPROVED' ) {
			$this->paid_amount = 0;
			$errortext = _sp("Your credit card has been declined");
			return FALSE;
		}

		$this->paid_amount = $cart->Total;
		return $response_authorization_code[0];
	}

	public function paid_amount(Cart $cart) {
		return $this->paid_amount;
	}

	public function check() {
		return true;
	}
}
