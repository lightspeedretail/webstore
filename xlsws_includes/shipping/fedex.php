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
 * FexEx shipping module
 * When it absolutely, positively has to be there overnight.
 *
 *
 *
*/

class fedex extends xlsws_class_shipping {
	/**
	 * The name of the shipping module that will be displayed in the checkout page
	 * @return string
	 *
	 *
	 */
	public function name() {
		$config = $this->getConfigValues(get_class($this));

		if(isset($config['label']))
			return $config['label'];

		return $this->admin_name();
	}

	/**
	 * The name of the shipping module that will be displayed in Web Admin payments
	 * @return string
	 *
	 *
	 */
	public function admin_name() {
		return _sp("FedEx");
	}

	/**
	 * check() verifies nothing has changed in the configuration since initial load
	 * @return boolean
	 *
	 *
	 */
	public function check() {
		if(defined('XLSWS_ADMIN_MODULE'))
			return true;

		$vals = $this->getConfigValues(get_class($this));

		// if nothing has been configed return null
		if(!$vals || count($vals) == 0)
			return false;
		return true;
	}

	/**
	 * make_Fedex_services populates with shipping options available through shipper
	 * @param &field (by reference)
	 * no return value since we're updating the reference
	 *
	 *
	 */
	protected function make_Fedex_services($field) {
		// valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...

		$field->AddItem('FedEx Standard Overnight' , 'STANDARD_OVERNIGHT');
		$field->AddItem('FedEx Priority Overnight' , 'PRIORITY_OVERNIGHT');
		$field->AddItem('FedEx Ground'  , 'FEDEX_GROUND');
	}

	/**
	 * The Web Admin panel for configuring this shipping option
	 *
	 * @param $parentObj (shipping panel object)
	 * @return array
	 *
	 */
	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = $this->admin_name();

		$ret['accnumber'] = new XLSTextBox($objParent);
		$ret['accnumber']->Name = _sp('Account Number');
		$ret['accnumber']->Required = true;

		$ret['meternumber'] = new XLSTextBox($objParent);
		$ret['meternumber']->Name = _sp('Meter Number');
		$ret['meternumber']->Required = true;

		$ret['securitycode'] = new XLSTextBox($objParent);
		$ret['securitycode']->Name = _sp('Security Code (Production Password)');
		$ret['securitycode']->Required = true;

		$ret['authkey'] = new XLSTextBox($objParent);
		$ret['authkey']->Name = _sp('Authentication Key');

		$ret['originadde'] = new XLSTextBox($objParent);
		$ret['originadde']->Name = _sp('Origin Address');
		$ret['originadde']->Required = true;

		$ret['origincity'] = new XLSTextBox($objParent);
		$ret['origincity']->Name = _sp('Origin City');
		$ret['origincity']->Required = true;

		$ret['originpostcode'] = new XLSTextBox($objParent);
		$ret['originpostcode']->Name = _sp('Origin Zip/Postal Code');
		$ret['originpostcode']->Required = true;

		$ret['origincountry'] = new XLSListBox($objParent);
		$ret['origincountry']->Name = _sp('Origin Country');

		$objCountries= Country::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Country()->Country)));
		if ($objCountries) foreach ($objCountries as $objCountry) {
			$ret['origincountry']->AddItem($objCountry->Country, $objCountry->Code);
		}

		// save the random control id
		$countryID = "iscfedex" . time();
		_xls_stack_add("iscfedex" , $countryID);

		$ret['origincountry'] = new XLSListBox($objParent , $countryID);
		$ret['origincountry']->Name = _sp('Origin Country');

		$objCountries= Country::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Country()->Country)));
		if ($objCountries) foreach ($objCountries as $objCountry) {
			$ret['origincountry']->AddItem($objCountry->Country, $objCountry->Code);
		}

		$ret['origincountry']->SelectedValue = "US";
		$ret['origincountry']->ActionParameter = "loadStates";
		$ret['origincountry']->AddAction(new QChangeEvent() , new QAjaxAction('moduleActionProxy'));

		// save the random control id
		$stateid = "issfedex" . time();
		_xls_stack_add("issfedex" , $stateid);
		$ret['originstate'] = new XLSListBox($objParent , $stateid);
		$ret['originstate']->Name = _sp('State');

		$this->loadStates($objParent);

		// valid values FEDEX_BOK, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		$ret['packaging'] = new XLSListBox($objParent);
		$ret['packaging']->Name = _sp('Packaging');
		$ret['packaging']->AddItem('Your packaging', 'YOUR_PACKAGING');
		$ret['packaging']->AddItem('FedEx Box', 'FEDEX_BOK');
		$ret['packaging']->AddItem('FedEx Pack', 'FEDEX_PAK');
		$ret['packaging']->AddItem('FedEx Tube', 'FEDEX_TUBE');

		$ret['ratetype'] = new XLSListBox($objParent);
		$ret['ratetype']->Name = _sp('Rate Type');
		$ret['ratetype']->AddItem('List Rates', 'RATED_LIST');
		$ret['ratetype']->AddItem('Negotiated rates', 'RATED_ACCOUNT');

		$ret['customs'] = new XLSListBox($objParent);
		$ret['customs']->Name = _sp('Customs (International)');
		$ret['customs']->AddItem('FedEx Handles Customs Clearance', 'CLEARANCEFEE');
		$ret['customs']->AddItem('We handle Customs Clearance', 'NOCHARGE');

		$ret['product'] = new XLSTextBox($objParent);
		$ret['product']->Name = _sp('LightSpeed Product Code');
		$ret['product']->Required = true;
		$ret['product']->Text = 'SHIPPING';

		$ret['markup'] = new XLSTextBox($objParent);
		$ret['markup']->Name = _sp('Mark up ($)');
		$ret['markup']->Required = true;
		$ret['markup']->Text = 3.00;

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
	public function check_config_fields($fields) {
		//check that postcode exists
		$val = $fields['originpostcode']->Text;
		if(trim($val) == ''){
			QApplication::ExecuteJavaScript("alert('Please provide postcode')");
			return false;
		}

		return true;
	}

	/**
	 * Customer fields
	 *
	 * Returns customer fields
	 *
	 * @param $parentObj (shipping panel object)
	 * @return array
	 */
	public function customer_fields($objParent) {
		$ret = array();
		$config = $this->getConfigValues('fedex');

		$ret['service'] = new XLSListBox($objParent);
		$this->make_Fedex_services($ret['service']);
		$ret['service']->Name = _sp('Preference:');
		return $ret;
	}

	/**
	 * adminLoadFix
	 *
	 * Change display options in Web Admin before panel actually displays
	 *
	 *
	 * @param $obj (shipping panel object)
	 * @return none, updates passed object by reference
	 */
	public function adminLoadFix($obj) {
		$this->loadStates($obj);
		$saved_vars = $this->getConfigValues(get_class($this));

		if(!(isset($saved_vars['originstate'])))
			return;

		$stateid = _xls_stack_get("issfedex");
		$lstState = $obj->GetChildControl($stateid);

		$lstState->SelectedValue = $saved_vars['originstate'];

		return;
	}

	/**
	 * loadStates
	 *
	 * Loads US states for FedEx shipping calculation
	 * Called from adminLoadFix()
	 *
	 * @param $obj (shipping panel object)
	 * @return array
	 */
	public function loadStates($obj) {
		$countryID = _xls_stack_get("iscfedex");

		$stateid = _xls_stack_get("issfedex");

		if($obj instanceof QPanel)
			$lstCountry = $obj->GetChildControl($countryID);
		else
			$lstCountry = $obj->GetControl($countryID);

		if(!$lstCountry)
			return;

		if($obj instanceof QPanel)
			$lstState = $obj->GetChildControl($stateid);
		else
			$lstState = $obj->GetControl($stateid);

		$states = State::LoadArrayByCountryCode($lstCountry->SelectedValue, QQ::Clause(QQ::OrderBy(QQN::State()->State)));

		if(!$lstState)
			return;

		$lstState->RemoveAllItems();
		foreach($states as $state) {
			$lstState->AddItem($state->State, $state->Code);
		}
	}

	/**
	 * total
	 *
	 * Based on passed address information, calculates the total shipping cost
	 *
	 * @param $fields &array
	 * @param Cart $cart
	 * @param $country optional
	 * @param $zipcode optional
	 * @param $state optional
	 * @param $city optional
	 * @param $address2 optional
	 * @param $address1 optional
	 * @param $company optional
	 * @param $lname optional
	 * @param $fname optional
	 *
	 * @return array
	 */
	public function total($fields, $cart, $country = '', $zipcode = '', $state = '',
			$city = '', $address2 = '', $address1= '', $company = '', $lname = '', $fname = '') {

		$config = $this->getConfigValues('fedex');

		$weight = $cart->total_weight();

		if(_xls_get_conf('WEIGHT_UNIT' , 'lb') != 'lb')
			$weight = $weight * 2.2;   // one KG is 2.2 pounds

		$length = $cart->total_length();
		$width = $cart->total_width();
		$height = $cart->total_height();

		if(_xls_get_conf('DIMENSION_UNIT' , 'in') != 'in') {
			$length = round($length /2.54);
			$width = round($width /2.54);
			$height = round($height /2.54);

		}

		if ($length < 1 && $length > 0) $length = 1;
		if ($width < 1 && $width > 0) $width = 1;
		if ($height < 1 && $height > 0) $height = 1;

		if(empty($config['securitycode'])  ||  empty($config['accnumber'])  ||  empty($config['meternumber'])  )
			return FALSE;

		$selected = $fields['service']->SelectedValue;

		$fields['service']->RemoveAllItems();

		$ret = array();

		$newline = "<br />";

		//The WSDL is not included with the sample code.
		//Please include and reference in $path_to_wsdl variable.

		$path_to_wsdl = XLSWS_INCLUDES . "shipping" . "/" . "RateService_v7.wsdl";

		ini_set("soap.wsdl_cache_enabled", "0");

		$client = new SoapClient($path_to_wsdl, array('trace' => 1));

		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
				'Key' => $config['authkey'],
				'Password' => $config['securitycode']
			)
		);
		$request['ClientDetail'] = array('AccountNumber' => $config['accnumber'], 'MeterNumber' => $config['meternumber']);
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v7 using PHP ***');
		$request['Version'] = array('ServiceId' => 'crs', 'Major' => '7', 'Intermediate' => '0', 'Minor' => '0');
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date('c');

		//Uncomment these additional options below if they are needed for your shipments

		//$request['RequestedShipment']['SpecialServicesRequested'] = array( 'SpecialServiceTypes' => array('SIGNATURE_OPTION'), 'SignatureOptionDetail' => array('OptionType' => 'ADULT'));
		//$request['RequestedShipment']['SignatureOptionDetail']['OptionType'] = 'ADULT';
		//$request['RequestedShipment']['ServiceType'] = 'PRIORITY_OVERNIGHT'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...

		$request['RequestedShipment']['PackagingType'] = $config['packaging'];
		$request['RequestedShipment']['Shipper'] = array(
			'Address' => array(
				'StreetLines' => array($config['originadde']), // Origin details
				'City' => $config['origincity'],
				'StateOrProvinceCode' => $config['originstate'],
				'PostalCode' => $config['originpostcode'],
				'CountryCode' => $config['origincountry']
			)
		);

		$request['RequestedShipment']['Recipient'] = array(
			'Address' => array(
				'StreetLines' => array($address1 , $address2), // Destination details
				'City' => $city,
				'StateOrProvinceCode' => $state,
				'PostalCode' => $zipcode,
				'CountryCode' => $country
			)
		);

		$request['RequestedShipment']['ShippingChargesPayment'] = array(
			'PaymentType' => 'SENDER',
			'Payor' => array(
				'AccountNumber' => $config['accnumber'],
				'CountryCode' => $config['origincountry']
			)
		);

		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST';

		if ($config['origincountry'] != $country && $config['customs'] == "CLEARANCEFEE") {
			$request['RequestedShipment']['InternationalDetail'] = array(
				'CustomsValue' => array (
					'Amount' => $cart->Subtotal,
					'Currency' => _xls_get_conf('CURRENCY_DEFAULT' , 'USD')
				)
			);
		}

		$request['RequestedShipment']['PackageCount'] = '1';
		$request['RateRequest']['CurrencyType'] = _xls_get_conf('CURRENCY_DEFAULT' , 'USD');
		$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';

		if (($length + $width + $height) == 0){
			$request['RequestedShipment']['RequestedPackageLineItems'] = array(
				'0' => array(
					'SequenceNumber' => '1',
					'InsuredValue' => array(
						'Amount' => $cart->Subtotal,
						'Currency' => _xls_get_conf('CURRENCY_DEFAULT' , 'USD')
					),
					'ItemDescription' => 'Ordered items',
					'Weight' => array(
						'Value' => $weight,
						'Units' => 'LB'
					),
					'CustomerReferences' => array(
						'CustomerReferenceType' => 'CUSTOMER_REFERENCE',
						'Value' => _xls_get_conf('STORE_NAME' , 'Web Order')
					)
				)
			);
		} else {
			$request['RequestedShipment']['RequestedPackageLineItems'] = array(
				'0' => array(
					'SequenceNumber' => '1',
					'InsuredValue' => array(
						'Amount' => $cart->Subtotal,
						'Currency' => _xls_get_conf('CURRENCY_DEFAULT' , 'USD')
					),
					'ItemDescription' => 'Ordered items',
					'Weight' => array(
						'Value' => $weight,
						'Units' => 'LB'
					),
					'Dimensions' => array(
						'Length' => $length,
						'Width' => $width,
						'Height' => $height,
						'Units' => 'IN'
					),
					'CustomerReferences' => array(
						'CustomerReferenceType' => 'CUSTOMER_REFERENCE',
						'Value' => _xls_get_conf('STORE_NAME' , 'Web Order')
					)
				)
			);
		}
		try {
			$response = $client->getRates($request);

			if ($response->HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR') {
				if ($response->RateReplyDetails) {
					foreach ($response -> RateReplyDetails as $rateReply) {
						foreach ($rateReply->RatedShipmentDetails as $choice) {
							if ($choice->ShipmentRateDetail->RateType == $config['ratetype'])
								$ret[ $rateReply->ServiceType] = $choice->ShipmentRateDetail->TotalNetCharge->Amount;
							else if ($rateReply->ServiceType == "FEDEX_GROUND")
								$ret[ $rateReply->ServiceType] = $rateReply->RatedShipmentDetails[1]->ShipmentRateDetail->TotalNetCharge->Amount;
						}
					}
				}
			} else {
				foreach ($response->Notifications as $notification) {
					if(is_array($response->Notifications)) {

					}
					else {

					}
				}
			}
		} catch (SoapFault $exception) {
			_xls_log("FedEx Soap Fault : " . $exception . " " );
		}

		$fields['service']->Visible = false;

		if(count($ret) <= 0) {
			_xls_log("FedEx could not get rate for  $country, $state , $zipcode .  " );
			_xls_log("FedEx request: " . print_r($request, true));
			_xls_log("FedEx Response: " . print_r($response, true));

			return false;
		}

		$fields['service']->Visible = true;

		foreach($ret as $service => $rate) {
			$desc = strtolower(str_replace("_" , " " , $service ));
			$desc = ucfirst($desc);

			$fields['service']->AddItem("$desc (" . _xls_currency(floatval($rate)+ floatval($config['markup'])) . ")" , $service);
		}

		$arr = array(
			'price' => false,
			'markup' => floatval($config['markup']),
			'product' => $config['product']
		);

		if(isset($ret[$selected])) {
			$fields['service']->SelectedValue = $selected;
			$arr['price'] = floatval($ret[$selected])+ floatval($config['markup']);
		} else {
			reset($ret);
			$selected = key($ret);
			$fields['service']->SelectedValue = $selected;
			$arr['price'] = floatval($ret[$selected])+ floatval($config['markup']);
		}

		return $arr;
	}
}
