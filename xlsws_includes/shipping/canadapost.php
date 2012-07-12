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
 * Canada Post (Poste Canada) shipping module
 *
 *
 *
 */

class canadapost extends xlsws_class_shipping {
	public $service_types;
	protected $strModuleName = "Canada Post";
	
	
	/**
	 * check() verifies nothing has changed in the configuration since initial load
	 * @return boolean
	 *
	 *
	 */
	public function check() {
		$vals = $this->getConfigValues(get_class($this));
		
		// if nothing has been configed return null
		if(!$vals || count($vals) == 0)
			return false;
			
		//Check possible scenarios why we would not offer free shipping
		if ($vals['restrictcountry']) { //we have a country restriction
			
			switch($vals['restrictcountry']) {
				case 'CUS':
					if ($_SESSION['XLSWS_CART']->ShipCountry=="US" && 
						($_SESSION['XLSWS_CART']->ShipState =="AK" || $_SESSION['XLSWS_CART']->ShipState=="HI"))
						return false;
				break;
			
				default:
					if ($vals['restrictcountry']!=$_SESSION['XLSWS_CART']->ShipCountry) return false;
			}
		}

		return true;
	}


	/**
	 * make_CanadaPost_services populates with shipping options available through shipper
	 * @param &field (by reference)
	 * no return value since we're updating the reference
	 *
	 *
	 */
	protected function make_CanadaPost_services($field) {
		// valid values 'STANDARD', 'EXPRESS','AIR', 'SEA', 'ECONOMY'

		$this->service_types = array(
			'Priority Courier',
			'Xpresspost',
			'Regular',
			'Expedited',
			'Xpresspost USA',
			'Expedited US Business',
			'Small Packets Air',
			'Small Packets Surface'
		);

		foreach($this->service_types as $type)
			$field->AddItem( $type , $type);
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

		$ret['originpostcode'] = new XLSTextBox($objParent);
		$ret['originpostcode']->Name = _sp('Origin Postcode');
		$ret['originpostcode']->Required = true;

		$ret['cpc'] = new XLSTextBox($objParent);
		$ret['cpc']->Name = _sp('Canada Post Customer Number');
		$ret['cpc']->Required = true;

		$ret['defaultproduct'] = new XLSListBox($objParent);
		$ret['defaultproduct']->Name = _sp('Default shipping product');
		$this->make_CanadaPost_services($ret['defaultproduct']);

		$ret['restrictcountry'] = new XLSListBox($objParent);
		$ret['restrictcountry']->Name = _sp('Only allow '.$this->strModuleName.' to');
		$ret['restrictcountry']->AddItem('Everywhere (no restriction)', null);
		$ret['restrictcountry']->AddItem('My Country ('. _xls_get_conf('DEFAULT_COUNTRY').')', _xls_get_conf('DEFAULT_COUNTRY'));
		if (_xls_get_conf('DEFAULT_COUNTRY')=="US")
			$ret['restrictcountry']->AddItem('Continental US', 'CUS'); //Really common request, so make a special entry
		$ret['restrictcountry']->Enabled = true;
		$ret['restrictcountry']->SelectedIndex = 0;
           		
		$ret['product'] = new XLSTextBox($objParent);
		$ret['product']->Name = _sp('LightSpeed Product Code (case sensitive)');
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
		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide postcode')");
			return false;
		}

		$val = $fields['cpc']->Text;
		if(trim($val) == '') {
			QApplication::ExecuteJavaScript("alert('Please provide your customer number')");
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
		$config = $this->getConfigValues(get_class($this));

		$ret['service'] = new XLSListBox($objParent,'ModuleMethod');
		$this->make_CanadaPost_services($ret['service']);
		$ret['service']->Name = _sp('Preference:');
		$ret['service']->SelectedValue = $config['defaultproduct'];
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
		return;
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
	public function total($fields, $cart, $country = '', $zipcode  = '', $state = '',
		$city = '', $address2 = '', $address1 = '', $company = '', $lname = '', $fname = '' ) {

		

		$weight = $cart->Weight;

		if(_xls_get_conf('WEIGHT_UNIT', 'kg') != 'kg')
			$weight = $weight / 2.2;   // one KG is 2.2 pounds

		$length = $cart->Length;
		$width = $cart->Width;
		$height = $cart->Height;

		if(_xls_get_conf('DIMENSION_UNIT', 'cm') != 'cm') {
			$length = round($length *2.54);
			$width = round($width *2.54);
			$height = round($height *2.54);
		}

		$selected = $fields['service']->SelectedValue;
	
		$strShipData=serialize(array(__class__,$weight,$address1,$zipcode));	
		if (_xls_stack_get('ShipBasedOn') != $strShipData) {
			_xls_stack_put('ShipBasedOn',$strShipData);
	
			$config = $this->getConfigValues(get_class($this));
			$this->make_CanadaPost_services($fields['service']);
	
			$fields['service']->RemoveAllItems();
	
			$found = 0;
			$ret = array();
			$url = "http://sellonline.canadapost.ca:30000";
	
			$xml =
			"<?xml version=\"1.0\" ?>
			<eparcel>
				<language>en</language>
				<ratesAndServicesRequest>
					<merchantCPCID>" . $config['cpc'] . "</merchantCPCID>
					<turnAroundTime>120</turnAroundTime>
					<itemsPrice>" . $cart->Subtotal . "</itemsPrice>
					<lineItems>
						<item>
							<quantity>1</quantity>
							<weight>" . $weight  . "</weight>
							<length>" . $length  . "</length>
							<width>" . $width  . "</width>
							<height>" . $height  . "</height>
							<description>Canada Post Shipping</description>
							<readyToShip />
						</item>
					</lineItems>
					" .  "<city>" . $city . "</city>\n" .
			"<provOrState>" . $state . "</provOrState>\n" .
			"<country>" . $country. "</country>\n".
			"<postalCode>" . $zipcode . "</postalCode>\n".
			"</ratesAndServicesRequest>
			</eparcel>
			";
	
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec ($ch);
			
			if(_xls_get_conf('DEBUG_SHIPPING' , false)) {
				_xls_log(get_class($this) . " sending ".$xml,true);
				_xls_log(get_class($this) . " receiving ".$result,true);
			}
			
			$oXML = new SimpleXMLElement($result);		
			
			if($oXML->error) {
				//What we have is ... failure to communicate
				QApplication::Log(E_ERROR, __CLASS__,
								'Canada Post: '.$oXML->error->statusMessage);
				$fields['service']->Visible = false;				
				return false;
			}
	
	
			foreach($oXML->ratesAndServicesResponse->product as $key=>$val) {
	              $strKey = $val->name;
	              $strRate = $val->rate;
	              $strKey = $this->cleanMethodName($strKey);
				  $ret[$strKey] = floatval($strRate) + floatval($config['markup']);
				  
				  $found++;
				}
				
			asort($ret,SORT_NUMERIC);
	
			foreach ($ret as $key=>$val)
				$fields['service']->AddItem("$key (" . _xls_currency(floatval($val) + floatval($config['markup'])) . ")" , $key);
	
			
			if($found <=0) {
				_xls_log("Canada Post: Could not get rates $country  , $zipcode .");
	
				$fields['service']->Visible = false;
				return false;
			}
	
			$fields['service']->Visible = true;
			_xls_stack_put('ShipBasedResults',serialize($ret));
		}
		else 
			$ret = unserialize(_xls_stack_get('ShipBasedResults'));



		$arr = array(
			'price' => false,
			'msg' => '',
			'markup' => floatval($config['markup']),
			'product' => $config['product']
		);

		if(isset($ret[$selected])) {
			$fields['service']->SelectedValue = $selected;
			$arr['price'] = $ret[$selected];
		} else {
			reset($ret);
			$arr['price'] = current($ret);
		}

		return $arr;
	}
	
	 public function cleanMethodName($strName) {
        $strName = html_entity_decode($strName);
        $strName = strip_tags($strName);
        $strName = str_replace('reg', '', $strName);
        $strName = preg_replace("/[^A-Za-z0-9\-\ ]/", '', $strName);
        $strName = trim($strName);
        return $strName;
    }
    
}
