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
 * Intershipper shipping module
 *
 *
 *
 */

class intershipper extends xlsws_class_shipping {
	//Define some global vars for later use
	public $state = array();
	public $quote = array();
	public $quotes = array();
	public $package_id;
	public $boxID;

	protected $strModuleName = "InterShipper";
	

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
		$ret['password']->TextMode = QTextMode::Password;
		$ret['password']->Required = true;

		$ret['originname'] = new XLSTextBox($objParent);
		$ret['originname']->Name = _sp('Your/Sender name');
		$ret['originname']->Required = true;
		$ret['originname']->Text = _xls_get_conf('STORE_NAME');

		$ret['originadde'] = new XLSTextBox($objParent);
		$ret['originadde']->Name = _sp('Origin Address');
		$ret['originadde']->Required = true;

		$ret['origincity'] = new XLSTextBox($objParent);
		$ret['origincity']->Name = _sp('Origin City');
		$ret['origincity']->Required = true;

		$ret['originpostcode'] = new XLSTextBox($objParent);
		$ret['originpostcode']->Name = _sp('Origin Zip/Postal code');
		$ret['originpostcode']->Required = true;

		// save the random control id
		$countryID = "isc" . time();
		_xls_stack_add("isc" , $countryID);

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
		$stateid = "iss" . time();
		_xls_stack_add("iss" , $stateid);
		$ret['originstate'] = new XLSListBox($objParent , $stateid);
		$ret['originstate']->Name = _sp('Origin State');

		$this->loadStates($objParent);

		$carriers = array(
			'ARB' => 'AirBorne',
			'DHL' => 'DHL World Wide Express',
			'FDX' => 'Federal Express',
			'UPS' => 'United Parcel Service',
			'USP' => 'U.S. Postal Service',
			'CAN' => 'Canada Post'
		);

		$ret['carriers'] = new XLSListBox($objParent);
		$ret['carriers']->Name = _sp('Carriers');
		$ret['carriers']->SelectionMode = QSelectionMode::Multiple;

		foreach($carriers as $code => $carrier)
			$ret['carriers']->AddItem($carrier , $code);

		// generate account and invoice fields for each carrier
		foreach($carriers as $code => $carrier) {
			$ret["carrier_account_$code"] = new XLSTextBox($objParent);
			$ret["carrier_account_$code"]->Name = _sp("$carrier Account ID (If Any)");

			$ret["carrier_invoiced_$code"] = new XLSListBox($objParent);
			$ret["carrier_invoiced_$code"]->AddItem("Yes" , 1);
			$ret["carrier_invoiced_$code"]->AddItem("No" , 0);
			$ret["carrier_invoiced_$code"]->Name = _sp("$carrier Invoices you? (You have a credit account with them)");
		}

		$ret['classes'] = new XLSListBox($objParent);
		$ret['classes']->Name = _sp('Service classes');
		$ret['classes']->SelectionMode = QSelectionMode::Multiple;
		$ret['classes']->AddItem('1st Day', '1DY');
		$ret['classes']->AddItem('2nd Day', '2DY');
		$ret['classes']->AddItem('3rd Day', '3DY');
		$ret['classes']->AddItem('Ground',  'GND');

		$ret['shipmethod'] = new XLSListBox($objParent);
		$ret['shipmethod']->Name = _sp('Ship Method');
		$ret['shipmethod']->AddItem('Schedule A Special Pickup', 'PCK');
		$ret['shipmethod']->AddItem('Drop-Off At Carrier Location', 'DRP');
		$ret['shipmethod']->AddItem('Regularly Scheduled Pickup', 'SCD');

		$ret['content'] = new XLSListBox($objParent);
		$ret['content']->Name = _sp('Content type');
		$ret['content']->AddItem('Other', 'OTR');
		$ret['content']->AddItem('Liquid', 'LQD');
		$ret['content']->AddItem('Accessible Hazmat', 'AHM');
		$ret['content']->AddItem('Inaccessible Hazmat', 'IHM');

		$ret["insure"] = new XLSListBox($objParent);
		$ret["insure"]->AddItem("No" , 0);
		$ret["insure"]->AddItem("Yes" , 1);
		$ret["insure"]->Name = _sp("Insurance Items");

		$ret['sort'] = new XLSListBox($objParent);
		$ret['sort']->Name = _sp('Sort Rates By');
		$ret['sort']->AddItem('Rate');
		$ret['sort']->AddItem('Carrier');
		$ret['sort']->AddItem('DeliveryDate');

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

		$stateid = _xls_stack_get("iss");
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
		$countryID = _xls_stack_get("isc");
		$stateid = _xls_stack_get("iss");

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
		$val = $fields['originname']->Text;

		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide sender name')");
			return false;
		}

		$val = $fields['originadde']->Text;

		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide originating address')");
			return false;
		}

		$val = $fields['origincity']->Text;

		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide originating city')");
			return false;
		}

		$val = $fields['originpostcode']->Text;

		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide originating postcode')");
			return false;
		}

		$val = $fields['carriers']->SelectedValues;

		if(count($val) == 0) {
			QApplication::ExecuteJavaScript("alert('Please select at least one carrier')");
			return false;
		}

		$val = $fields['classes']->SelectedValues;

		if(count($val) == 0) {
			QApplication::ExecuteJavaScript("alert('Please select at least one class')");
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

		// Return just a blank list box which will be filled up using the total
		$ret['carrier'] = new XLSListBox($objParent);
		$ret['carrier']->Name = _sp('Carrier');
		return $ret;
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
		$city = '', $address2 = '', $address1 = '', $company = '', $lname = '', $fname = '') {

		$config = $this->getConfigValues('intershipper');

		if(empty($config['origincountry'])  ||  empty($config['originpostcode'])  ||  empty($config['username'])  ||  empty($config['password']) )
			return false;

		// Build the query string to be sent to the IS server.
		//http://intershipper.com/Shipping/Intershipper/Website/MainPage.jsp?Page=Integrate
		// for additional information

		$url = 'www.intershipper.com';
		$uri = '/Interface/Intershipper/XML/v2.0/HTTP.jsp?';
		$uri .= 'Username=' . $config['username'];
		$uri .= '&Password=' . $config['password'];
		$uri .= '&Version=' . '2.0.0.0' ;
		$uri .= '&ShipmentID=' . $cart->Rowid ;
		$uri .= '&QueryID=' . $cart->Rowid ;

		$carriers = explode("\n" , $config['carriers']);

		$uri .= '&TotalCarriers=' . count($carriers) ;

		$i = 1;
		foreach($carriers as $code) {
			$uri .= "&CarrierCode$i=" . $code ;

			if(!isset($config["carrier_account_$code"]) || ($config["carrier_account_$code"] == '')){
				$i++;
				continue;
			}

			$uri .= "&CarrierAccount$i=" . $config["carrier_account_$code"] ;
			$uri .= "&CarrierInvoiced$i=" . $config["carrier_invoiced_$code"] ;
			$i++;
		}

		$classes = explode("\n" , $config['classes']);

		$uri .= '&TotalClasses=' . count($classes) ;

		$i = 1;
		foreach($classes as $class)
			$uri .= '&ClassCode' . ($i++) . '=' . $class ;

		$uri .= '&DeliveryType=' . ((trim($company)!= '')?'COM':'RES') ;
		$uri .= '&ShipMethod=' . $config['shipmethod'] ;
		$uri .= '&OriginationName=' . urlencode($config['originname'])  ;
		$uri .= '&OriginationAddress1=' . urlencode($config['originadde']) ;
		$uri .= '&OriginationCity=' . urlencode($config['origincity']) ;
		$uri .= '&OriginationState=' . $config['originstate'] ;
		$uri .= '&OriginationPostal=' . urlencode($config['originpostcode']) ;
		$uri .= '&OriginationCountry=' . $config['origincountry'] ;
		$uri .= '&DestinationName=' . urlencode("$fname $lname") ;
		$uri .= '&DestinationAddress1=' . urlencode("$address1 $address2") ;
		$uri .= '&DestinationCity=' . urlencode("$city") ;
		$uri .= '&DestinationState=' . $state ;
		$uri .= '&DestinationPostal=' . urlencode($zipcode) ;
		$uri .= '&DestinationCountry=' . $country ;
		$uri .= '&Currency=' . 'USD' ;
		$uri .= '&TotalPackages=' . '1' ;
		$uri .= '&BoxID1=' . '1' ;
		$uri .= '&Weight1=' . $cart->total_weight() ;
		$uri .= '&WeightUnit1=' . 'LB' ; // TODO ASHIK get user config!
		$uri .= '&Length1=' . $cart->total_length() ;
		$uri .= '&Width1=' . $cart->total_width() ;
		$uri .= '&Height1=' . $cart->total_height() ;
		$uri .= '&DimensionalUnit1=' . 'IN' ;  // TODO ASHIK get user config!
		$uri .= '&Packaging1=' . 'BOX' ;
		$uri .= '&Contents1=' . $config['content'] ;
		$uri .= '&Cod1=' . '0' ;
		$uri .= '&Insurance1=' . ($config['insure']?$cart->Subtotal:0) ;

		$fields['carrier']->Visible = false;

		//Send the socket request with the uri/url
		$fp = fsockopen ($url, 80, $errno, $errstr, 30);
		if (!$fp) {
			echo "Error: $errstr ($errno)<br>\n";
		}
		else {
			$depth = array();
			fputs ($fp, "GET $uri HTTP/1.0\r\nHost: $url\r\n\r\n");
			//define the XML parsing routines/functions to call
			//based on the handler state
			$xml_parser = xml_parser_create();
			xml_set_object ( $xml_parser, $this );
			xml_set_element_handler($xml_parser, "startElement", "endElement");
			xml_set_character_data_handler($xml_parser, "characterData");
			//now lets roll through the data
			while ($data = fread($fp, 4096)) {
				$newdata = $data;

				// fsockopen returns excess data we need to remove before parsing
				$newdata=trim($newdata);
				$newdata = preg_replace('/\r\n\r\n/', "", $newdata);
				$newdata = preg_replace('/HTTP.*\r\n/', "", $newdata);
				$newdata = preg_replace('/Server.*\r\n/', "", $newdata);
				$newdata = preg_replace('/Set.*/', "", $newdata);
				$newdata = preg_replace('/Con.*/', "", $newdata);
				$newdata = preg_replace('/Date.*\r\n/', "", $newdata);
				$newdata = preg_replace('/\r/', "", $newdata);
				$newdata = preg_replace('/\n/', "", $newdata);

				/* if we properl cleaned up the XML stream/data we can now hand it off
				 to an XML parser without error
				 */
				if (!xml_parse($xml_parser, $newdata, feof($fp))) {
					_xls_log(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
					break;
				}
			}
			//clean up the parser object
			xml_parser_free($xml_parser);
		}

		if(count($this->quotes) == 0) {
			_xls_log("intershipper: Could not get intershipper rate for  $country, $state , $zipcode .");
			return FALSE;
		}

		$selected = $fields['carrier']->SelectedValue;

		$quotes = array();

		$fields['carrier']->RemoveAllItems();
		$found = 0;

		while(list($quotedata, $boxID)=each($this->quotes)) {
			while(list($key, $bar)=each($boxID)) {
				$boxID[$key]['amount'] = $boxID[$key]['amount'] / 100;
				$service_name = $boxID[$key]['carrier_name'] . " " . $boxID[$key]['service_name'];

				$quotes[$service_name] = $boxID[$key]['amount'] +floatval($config['markup']);

				$fields['carrier']->AddItem($boxID[$key]['carrier_name'] . " " .
					$boxID[$key]['service_name'] . " " . _xls_currency($boxID[$key]['amount'] + floatval($config['markup'])),  $service_name);

				$found++;
			}
		}

		if($found <=0){
			_xls_log("intershipper: Could not get intershipper rate for  $country, $state , $zipcode .");
			$fields['carrier']->Visible = false;
			return false;
		}

		$fields['carrier']->Visible = true;
		$fields['carrier']->SelectedValue = $selected;

		$arr = array(
			'price' => false,
			'msg' => '',
			'markup' => floatval($config['markup']),
			'product' => $config['product']
		);

		if($selected && $quotes[$selected]){
			$arr['price'] = $quotes[$selected];
			$arr['msg'] = $selected;
		}else{
			reset($quotes);
			$fields['carrier']->SelectedIndex = 0;
			$c = key($quotes);
			$arr['price'] = $quotes[$c];
			$arr['msg'] = $c;
		}

		return $arr;
	}

	/**
	 * startElement, characterData, endElement
	 *
	 * XML Parsing routine
	 *
	 */
	// funtion to handle the start elements for the XML data
	function startElement(&$Parser, &$Elem, $Attr) {
		array_push ($this->state, $Elem);
		$states = join (' ',$this->state);
		//check what state we are in
		if ($states == "SHIPMENT PACKAGE") {
			$this->package_id = $Attr['ID'];
		}
		//check what state we are in
		elseif ($states == "SHIPMENT PACKAGE QUOTE") {

			$this->quote = array ( 'package_id' => $this->package_id, 'id' => $Attr['ID']);
		}
	}

	//function to parse the XML data. The routine does a series of conditional
	//checks on the data to determine where in the XML stack we are.
	function characterData($Parser, $Line) {
		$states = join (' ',$this->state);
		if ($states == "SHIPPMENT ERROR") {
			$error = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE BOXID") {
			$this->boxID = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE CARRIER NAME") {
			$this->quote['carrier_name'] = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE CARRIER CODE") {
			$this->quote['carrier_code'] = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE CLASS NAME") {
			$this->quote['class_name'] = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE CLASS CODE") {
			$this->quote['class_code'] = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE SERVICE NAME") {
			$this->quote['service_name'] = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE SERVICE CODE") {
			$this->quote['service_code'] = $Line;
		}
		elseif ($states == "SHIPMENT PACKAGE QUOTE RATE AMOUNT") {
			$this->quote['amount'] = $Line;
		}
	}

	// this function handles the end elements.
	// once encountered it puts the quote into the hash $quotes
	function endElement($Parser, $Elem) {
		$states = join (' ',$this->state);
		if ($states == "SHIPMENT PACKAGE QUOTE") {
			unset ($this->quote['id']);
			unset ($this->quote['package_id']);
			// the $key is a combo of the carrier_code and service_code
			// this is the logical way to key each quote returned
			$key = $this->quote['carrier_code'] . ' ' . $this->quote['service_code'];
			$this->quotes[$this->boxID][$key] = $this->quote;
		}
		array_pop($this->state);
	}

	/**
	 * check() verifies nothing has changed in the configuration since initial load
	 * @return boolean
	 *
	 *
	 */
	public function check(){
		if(defined('XLSWS_ADMIN_MODULE'))
			return true;

		$vals = $this->getConfigValues(get_class($this));

		// if nothing has been configed return null
		if(!$vals || count($vals) == 0)
			return false;
		return true;
	}
}
