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
 * iUPS (International UPS) shipping module
 *
 *
 *
 */

class iups extends xlsws_class_shipping {
	public $service_types;

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
		return _sp("IUPS");
	}

	public function check() {
		if(defined('XLSWS_ADMIN_MODULE'))
			return true;

		$vals = $this->getConfigValues(get_class($this));

		// if nothing has been configed return null
		if(!$vals || count($vals) == 0)
			return false;
		return true;
	}

	protected function make_iups_products($field) {
		$config = $this->getConfigValues('iups');
		$region = $config['regionservices'];

		$this->service_types = $values = $this->$region;

		foreach($values as $key=>$service)
			$field->AddItem($service , $key);
	}

	// return the keys for this module
	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = $this->admin_name();

		$ret['username'] = new XLSTextBox($objParent);
		$ret['username']->Name = _sp('Username');
		$ret['username']->Required = true;

		$ret['password'] = new XLSTextBox($objParent);
		$ret['password']->Name = _sp('Password');
		$ret['password']->Required = true;
		$ret['password']->TextMode = QTextMode::Password;

		$ret['accesskey'] = new XLSTextBox($objParent);
		$ret['accesskey']->Name = _sp('Access Key');
		$ret['accesskey']->Required = true;

		$ret['originpostcode'] = new XLSTextBox($objParent);
		$ret['originpostcode']->Name = _sp('Origin Zip/Postal Code');
		$ret['originpostcode']->Required = true;

		// save the random control id
		$countryID = "isciups" . time();
		_xls_stack_add("isciups" , $countryID);

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
		$stateid = "issiups" . time();
		_xls_stack_add("issiups" , $stateid);
		$ret['originstate'] = new XLSListBox($objParent , $stateid);
		$ret['originstate']->Name = _sp('State');

		$this->loadStates($objParent);

		$ret['regionservices'] = new XLSListBox($objParent);
		$ret['regionservices']->Name = _sp('Use services for region');
		$ret['regionservices']->AddItem('US' , 'ups_service_us');
		$ret['regionservices']->AddItem('European Union'  , 'ups_service_eu');
		$ret['regionservices']->AddItem('Canada' , 'ups_service_ca');
		$ret['regionservices']->AddItem('Other' , 'ups_service_other');

		// $ret['defaultproduct'] = new XLSListBox($objParent);
		// $ret['defaultproduct']->Name = _sp('Default shipping product');
		// $this->make_iups_products($ret['defaultproduct']);

		$ret['ratecode'] = new XLSListBox($objParent);
		$ret['ratecode']->Name = _sp('Rate Code');
		$ret['ratecode']->AddItem('Regular Daily Pickup', 'Regular+Daily+Pickup');
		$ret['ratecode']->AddItem('On Call Air', 'On+Call+Air');
		$ret['ratecode']->AddItem('One Time Pickup', 'One+Time+Pickup');
		$ret['ratecode']->AddItem('Letter Center', 'Letter+Center');
		$ret['ratecode']->AddItem('Customer Counter', 'Customer+Counter');

		$ret['package'] = new XLSListBox($objParent);
		$ret['package']->Name = _sp('Packaging');
		$ret['package']->AddItem('Customer Packaging', 'CP');
		$ret['package']->AddItem('UPS Letter Envelope', 'ULE');
		$ret['package']->AddItem('UPS Tube', 'UT');
		$ret['package']->AddItem('UPS Express Box', 'UEB');
		$ret['package']->AddItem('UPS Worldwide 25 kilo', 'UW25');
		$ret['package']->AddItem('UPS Worldwide 10 kilo', 'UW10');

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

	public function check_config_fields($fields) {
		//check that postcode exists
		$val = $fields['originpostcode']->Text;
		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide postcode')");
			return false;
		}

		return true;
	}

	public function customer_fields($objParent) {
		$ret = array();
		$config = $this->getConfigValues('iups');

		$ret['service'] = new XLSListBox($objParent);
		$this->make_iups_products($ret['service']);
		$ret['service']->Name = _sp('Preference:');
		//$ret['product']->SelectedValue = $config['defaultproduct'];
		return $ret;
	}

	public function total($fields, $cart, $country = '', $zipcode = '', $state = '', $city = '', $address2 = '', $address1 = '', $company = '', $lname = '', $fname = '') {
		$config = $this->getConfigValues('iups');

		if(empty($config['originpostcode']) || empty($config['origincountry']) || empty($config['username']) || empty($config['accesskey']))
			return false;

		$this->s_zip = $config['originpostcode'];
		$this->s_state = $config['originstate'];
		$this->s_country = $config['origincountry'];
		$this->userid = $config['username'];
		$this->passwd = $config['password'];
		$this->accesskey = $config['accesskey'];
		$this->currency = $cart->Currency;

		$weight = $cart->total_weight();
		$this->weight_type = strtoupper(_xls_get_conf('WEIGHT_UNIT', 'lb'));
		$this->weight_type .= "S"; // Add KGS or LBS

		$length = $cart->total_length();
		$width = $cart->total_width();
		$height = $cart->total_height();
		$this->measurement_type = strtoupper(_xls_get_conf('DIMENSION_UNIT', 'in'));

		$selected = $fields['service']->SelectedValue;

		$this->make_iups_products($fields['service']);

		$fields['service']->RemoveAllItems();

		$found = 0;
		$ret = array();

			$rates = $this->rate(
				$selected,
				$zipcode,
				$state,
				$country,
				$weight,
				$length,
				$width,
				$height,
				($company!='') ? 0 : 1,
				$cart->Total,
				$this->package_type
			);

			if($rates === false) {
				$fields['service']->Visible = false;
				return false;
			}

		foreach($rates as $type=>$rate) {
			if(isset($this->service_types[$type]))
				$desc = $this->service_types[$type];
			else
				$desc = "UPS $type";

			$fields['service']->AddItem("$desc (" . _xls_currency(floatval($rate) + floatval($config['markup'])) . ")" , $type);

			$ret[$type] = floatval($rate) + floatval($config['markup']);

			$found++;
		}

		if($found <=0) {
			QApplication::Log(E_ERROR, __CLASS__,
				'Could not get shipping information for '.$state." ".$zipcode." ".$country);
			QApplication::Log(E_ERROR, __CLASS__,
				"Shipper Response: " . print_r($rates,TRUE));

			$fields['service']->Visible = false;
			return false;
		}

		$fields['service']->Visible = true;

		$arr = array(
			'price' => false,
			'msg' => '',
			'markup' => floatval($config['markup']),
			'product' => $config['product']
		);

		if(isset($ret[$selected])){
			$fields['service']->SelectedValue = $selected;
			$arr['price'] = $ret[$selected];
			$arr['msg'] = $this->service_types[$selected];
		} else {
			reset($ret);
			$selected = key($ret);
			$fields['service']->SelectedValue = $selected;
			$arr['price'] = $ret[$selected];
			$arr['msg'] = $this->service_types[$selected];
		}

		return $arr;

		if(!($rate === FALSE))
			return $rate;
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

		$stateid = _xls_stack_get("issiups");
		$lstState = $obj->GetChildControl($stateid);

		$lstState->SelectedValue = $saved_vars['originstate'];

		return;
	}

	/**
	 * loadStates
	 *
	 * Loads US states for shipping calculation
	 * Called from adminLoadFix()
	 *
	 * @param $obj (shipping panel object)
	 * @return array
	 */
	public function loadStates($obj) {
		$countryID = _xls_stack_get("isciups");
		$stateid = _xls_stack_get("issiups");

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

		$states = State::LoadArrayByCountryCode($lstCountry->SelectedValue , QQ::Clause(QQ::OrderBy(QQN::State()->State)));

		if(!$lstState)
			return;

		$lstState->RemoveAllItems();

		foreach($states as $state) {
			$lstState->AddItem($state->State, $state->Code);
		}
	}

	//US ORIGIN
	protected $ups_service_us = array (
		'01' => 'UPS Next Day Air',
		'02' => 'UPS 2nd Day Air',
		'03' => 'UPS Ground',
		'07' => 'UPS Worldwide Express',
		'08' => 'UPS Worldwide Expedited',
		'11' => 'UPS Standard',
		'12' => 'UPS 3 Day Select',
		'13' => 'UPS Next Day Air Saver',
		'14' => 'UPS Next Day Air Early A.M.',
		'54' => 'UPS Worldwide Express Plus',
		'59' => 'UPS 2nd Day Air A.M.',
		'65' => 'UPS Express Saver',
		'82' => 'UPS Today Standard',
		'83' => 'UPS Today Dedicated',
		'84' => 'UPS Today Intercity',
		'85' => 'UPS Today Express'
	);


	protected $ups_service_eu = array (
		'01' => 'UPS Next Day Air',
		'02' => 'UPS 2nd Day Air',
		'03' => 'UPS Ground',
		'07' => 'UPS Worldwide Express',
		'08' => 'UPS Worldwide Expedited',
		'11' => 'UPS Standard',
		'12' => 'UPS 3 Day Select',
		'13' => 'UPS Next Day Air Saver',
		'14' => 'UPS Next Day Air Early A.M.',
		'54' => 'UPS Worldwide Express Plus',
		'59' => 'UPS 2nd Day Air A.M.',
		'65' => 'UPS Express Saver',
		'82' => 'UPS Today Standard',
		'83' => 'UPS Today Dedicated',
		'84' => 'UPS Today Intercity',
		'85' => 'UPS Today Express'
	);

	protected $ups_service_ca = array (
		'01' => 'UPS Next Day Air',
		'02' => 'UPS 2nd Day Air',
		'03' => 'UPS Ground',
		'07' => 'UPS Worldwide Express',
		'08' => 'UPS Worldwide Expedited',
		'11' => 'UPS Standard',
		'12' => 'UPS 3 Day Select',
		'13' => 'UPS Next Day Air Saver',
		'14' => 'UPS Next Day Air Early A.M.',
		'54' => 'UPS Worldwide Express Plus',
		'59' => 'UPS 2nd Day Air A.M.',
		'65' => 'UPS Express Saver',
		'82' => 'UPS Today Standard',
		'83' => 'UPS Today Dedicated',
		'84' => 'UPS Today Intercity',
		'85' => 'UPS Today Express'
	);

	//From Other origin
	protected $ups_service_other = array (
		'07' => 'UPS Express',
		'08' => 'UPS Worldwide Expedited',
		'11' => 'UPS Standard',
		'54' => 'UPS Worldwide Express Plus',
		'65' => 'UPS Saver'
	);

	var $userid;
	var $passwd;
	var $accesskey;
	var $currency;
	var $upstool='https://www.ups.com/ups.app/xml/Rate';
	var $request;
	var $service;
	var $pickuptype='01'; // 01 daily pickup
	  /* Pickup Type
		01- Daily Pickup
		03- Customer Counter
		06- One Time Pickup
		07- On Call Air
		11- Suggested Retail Rates
		19- Letter Center
		20- Air Service Center
	  */
	var $residential;
	var $value;

	//ship from location or shipper
	var $s_zip;
	var $s_state;
	var $s_country;

	//ship to location
	var $t_zip;
	var $t_state;
	var $t_country;

	//package info
	var $package_type = '02';  // 02 customer supplied package

	var $weight;
	var $l;
	var $w;
	var $h;

	//measurement inches or cm, lbs or kg
	var $measurement_type = "IN";
	var $weight_type = "LBS";

	var $error=0;
	var $errormsg;

	var $xmlarray = array();

	var $xmlreturndata = "";

	function dimensions($len,$wid,$hgt){
		$this->l =$len;
		$this->w =$wid;
		$this->h = $hgt;
	}

	/**
	 * construct_request_xml
	 *
	 * Build XML request for iUPS submission
	 * Called from adminLoadFix()
	 *
	 * @return string (xml format)
	 */
	function construct_request_xml(){
		$currency_code = $this->currency;

		$customer_classification = '';

		if ($this->s_country == 'US' && $this->pickuptype == '11') {

			$customer_classification=<<<EOT

	<CustomerClassification>

		<Code>04</Code>

	</CustomerClassification>

EOT;

		}


		$xml=<<<EOT

<?xml version="1.0"?>

<AccessRequest xml:lang="en-US">

	<AccessLicenseNumber>$this->accesskey</AccessLicenseNumber>

	<UserId>$this->userid</UserId>

	<Password>$this->passwd</Password>

</AccessRequest>

<?xml version="1.0"?>

<RatingServiceSelectionRequest xml:lang='en-US'>

  <Request>

	<TransactionReference>

	  <CustomerContext>Rating and Service</CustomerContext>

	  <XpciVersion>1.0001</XpciVersion>

	</TransactionReference>

	<RequestAction>Rate</RequestAction>

	<RequestOption>shop</RequestOption>

  </Request>

	<PickupType>

	<Code>$this->pickuptype</Code>

  </PickupType>

  $customer_classification

  <Shipment>

	<Shipper>

		<Address>

			<PostalCode>$this->s_zip</PostalCode>

			<CountryCode>$this->s_country</CountryCode>

		</Address>

	</Shipper>

	<ShipTo>

		<Address>

			<PostalCode>$this->t_zip</PostalCode>

			<CountryCode>$this->t_country</CountryCode>

			<ResidentialAddressIndicator>$this->residential</ResidentialAddressIndicator>

		</Address>

	</ShipTo>

	<ShipFrom>

		<Address>

			<PostalCode>$this->s_zip</PostalCode>

			<CountryCode>$this->s_country</CountryCode>

		</Address>

	</ShipFrom>
	<Service>

			<Code>$this->service</Code>

	</Service>
	<Package>

		<PackagingType>

			<Code>$this->package_type</Code>

		</PackagingType>

			<Dimensions>

				<UnitOfMeasurement>

				  <Code>$this->measurement_type</Code>

				</UnitOfMeasurement>

				<Length>$this->l</Length>

				<Width>$this->w</Width>

				<Height>$this->h</Height>

			</Dimensions>

		<PackageWeight>

			<UnitOfMeasurement>

				 <Code>$this->weight_type</Code>

			</UnitOfMeasurement>

			<Weight>$this->weight</Weight>

		</PackageWeight>

	</Package>
	<PackageServiceOptions>
		<InsuredValue>
			<CurrencyCode>$currency_code</CurrencyCode>
			<MonetaryValue>$this->value</MonetaryValue>
		</InsuredValue>
	</PackageServiceOptions>

  </Shipment>

</RatingServiceSelectionRequest>

EOT;
		return $xml;
	}

	/**
	 * rate
	 *
	 * Based on passed address information, calculates the total shipping cost
	 *
	 * @param $service optional
	 * @param $tzip
	 * @param $tstate
	 * @param $tcountry
	 * @param $weight
	 * @param $length
	 * @param $width
	 * @param $height
	 * @param $residential
	 * @param $val
	 * @param $packagetype optional
	 * @return $return[]
	 */
	function rate($service='',$tzip,$tstate,$tcountry,
		$weight,$length, $width, $height, $residential,$val, $packagetype='02') {

		if($service=='')
			$this->request = 'shop';
		else
			$this->request = 'rate';

		$this->service = $service;
		$this->t_zip = $tzip;
		$this->t_state= $tstate;
		$this->t_country = $tcountry;
		$this->weight = $weight;
		$this->residential=$residential;
		$this->package_type=$packagetype;
		$this->l = $length;
		$this->w = $width;
		$this->h = $height;
		$this->value = $val;

		$this->__runCurl();

		// Parse xml for response values
		$oXML = new SimpleXMLElement($this->xmlreturndata);

		if($oXML->Response->ResponseStatusDescription=="Failure") {
			//What we have is ... failure to communicate
			QApplication::Log(
				E_ERROR,
				__CLASS__,
				'Could not get shipping information for '.$tstate." ".$tzip." ".$tcountry
			);
			return false;
		}

		$retval = array();
		foreach($oXML->RatedShipment as $key=>$val)
			$retval[''.$val->Service->Code] = floatval($val->TotalCharges->MonetaryValue);

		return $retval;
	}

	function __runCurl() {
		$y = $this->construct_request_xml();

		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL,"$this->upstool");
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "$y");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$this->xmlreturndata = curl_exec ($ch);
		curl_close ($ch);
	}
}
