<?php

/**
 * UPS Module
 * created by LightSpeed
 */
class fedex extends WsShipping
{
	protected $defaultName = "FedEx";

	public static $service_types = array(
		'FEDEX_EXPRESS_SAVER'=>'FedEx Express Saver',
		'FEDEX_2_DAY'=>'FedEx Two-Day Delivery',
		'FIRST_OVERNIGHT'=>'FedEx First Overnight',
		'STANDARD_OVERNIGHT'=>'FedEx Standard Overnight',
		'PRIORITY_OVERNIGHT'=>'FedEx Priority Overnight',
		'INTERNATIONAL_PRIORITY'=>'International Priority',
		'INTERNATIONAL_ECONOMY'=>'International Economy',
		'FEDEX_GROUND'=>'FedEx Ground',
		'GROUND_HOME_DELIVERY'=>'FedEx Ground Home Delivery'
	);


	/**
	 * The run() function is called from Web Store to actually do the calculation. It returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return array
	 */
	public function run()
	{

		if (!is_null($this->CheckoutForm)) {
			$arrReturn = $this->total(null,
				$this->objCart,
				$this->CheckoutForm['shippingCountry'],
				$this->CheckoutForm['shippingPostal'],
				$this->CheckoutForm['shippingState'],
				$this->CheckoutForm['shippingCity'],
				$this->CheckoutForm['shippingAddress2'],
				$this->CheckoutForm['shippingAddress1'],
				'',
				$this->CheckoutForm['shippingLastName'],
				$this->CheckoutForm['shippingFirstName']
			);

			if ($arrReturn===false) return array();
			return $arrReturn;
		} else  return array();
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

		$config = $this->getConfigValues(get_class($this));

		$weight = $cart->Weight;

		if(_xls_get_conf('WEIGHT_UNIT' , 'lb') != 'lb')
			$weight = $weight * 2.2;   // one KG is 2.2 pounds

		$length = $cart->Length;
		$width = $cart->Width;
		$height = $cart->Height;

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


		$ret = array();

		$newline = "<br />";

		//The WSDL is not included with the sample code.
		//Please include and reference in $path_to_wsdl variable.

		$path_to_wsdl = dirname(__FILE__)."/RateService_v7.wsdl";
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

		$config['origincountry'] = Country::CodeById($config['origincountry']);
		$config['originstate'] =  State::CodeById($config['originstate']);

		if ($config['origincountry'] != "CA" && $config['origincountry'] != "US") $config['originstate']=""; //Only required for these countries

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

		if ($country != "CA" && $country != "US") $state=""; //Only required for these countries

		$request['RequestedShipment']['Recipient'] = array(
			'Address' => array(
				'StreetLines' => array($address1 , $address2), // Destination details
				'City' => $city,
				'StateOrProvinceCode' => $state,
				'PostalCode' => $zipcode,
				'CountryCode' => $country,
				'Residential' => ($this->CheckoutForm->shippingResidential==1 ? 1 : 0)
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
					'Amount' => $cart->subtotal,
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
						'Amount' => $cart->subtotal,
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
						'Amount' => $cart->subtotal,
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

			if(_xls_get_conf('DEBUG_SHIPPING' , false)) {
				Yii::log(get_class($this) . " sending ".print_r($request,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::log(get_class($this) . " receiving ".print_r($response,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

			if ($response->HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR') {
				if (isset($response->RateReplyDetails)) {
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
			$response = $exception;
		}



		if(count($ret) <= 0) {
			_xls_log("FedEx could not get rate for  $country, $state , $zipcode .  " );
			_xls_log("FedEx request: " . print_r($request, true));
			_xls_log("FedEx Response: " . print_r($response, true));

			return false;
		}


		asort($ret);
		if(_xls_get_conf('DEBUG_SHIPPING' , false)) {
			Yii::log(get_class($this) . " received shipping array ".print_r($ret,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		return $this->convertRetToDisplay($ret);



	}

}
