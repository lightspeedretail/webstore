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
 * UPS shipping module
 * Note: only works for US addresses
 *
 *
 */

class usps extends xlsws_class_shipping {
	private $uspsID;
	private $zipOrigination;
	private $zipDestination;
	private $pounds;
	private $ounces;
	private $country;
	private $value;
	private $markup;
	private $methods;
	private $response;

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
		return _sp("USPS");
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
	 * make_USPS_services populates with shipping options available through shipper
	 * @param &field (by reference)
	 * no return value since we're updating the reference
	 *
	 *
	 */
	protected function make_USPS_services($field){
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

		$ret['username'] = new XLSTextBox($objParent);
		$ret['username']->Name = _sp('Username');
		$ret['username']->Required = true;

		$ret['originpostcode'] = new XLSTextBox($objParent);
		$ret['originpostcode']->Name = _sp('Origin Zipcode');
		$ret['originpostcode']->Required = true;

		$ret['product'] = new XLSTextBox($objParent);
		$ret['product']->Name = _sp('LightSpeed Product Code');
		$ret['product']->Required = true;
		$ret['product']->Text = 'SHIPPING';

		$ret['markup'] = new XLSTextBox($objParent);
		$ret['markup']->Name = _sp('Mark up ($)');
		$ret['markup']->Required = true;
		$ret['markup']->Text = 3.00;

		$config = $this->getConfigValues(get_class($this));
		if(!empty($config['username']) && !empty($config['originpostcode'])) {
			$ret['shiptypes'] = new XLSTextBox($objParent);
			$ret['shiptypes']->Name = _sp('Methods of Shipment');
			$ret['shiptypes']->Display = false;

			$this->methods = explode(",", $config['shiptypes']);
			$this->init_admin_vars($config['username'] , $config['originpostcode'], $config['originpostcode'], "US");
			$req = $this->buildDomesticRateRequest();
			$page = $this->sendUSPSRateRequest($req);

			$rates = $this->getRate(true);
			$this->init_admin_vars($config['username'] , $config['originpostcode'], "H1M 2W4", "Canada");
			$req = $this->buildInternationalRateRequest();

			$page = $this->sendUSPSRateRequest($req);
			$rates_int = $this->getRate(true);

			$rates = array_merge($rates,$rates_int);
			if (!empty($rates)) {
                foreach ($rates as $key => $val) {
                    $key = $this->cleanMethodName($key);
					$ret[$key] = new XLS_OnOff($objParent);
					$ret[$key]->AddAction(new QClickEvent(), new QJavaScriptAction("addMethod('" . addslashes($key) . "', '" . $ret['shiptypes']->ControlId . "', '" . $ret[$key]->ControlId . "_a');"));
					$ret[$key]->Name = $key;
					if (array_search($key, $this->methods) !== false)
						$ret[$key]->Checked = true;
				}
			}
		}

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
			QApplication::ExecuteJavaScript("alert('Please provide zipcode')");
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
		$ret['service'] = new XLSListBox($objParent);
		$ret['service']->Name = _sp('Preference:');
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

		$selected = $fields['service']->SelectedValue;

		if(!$selected)
			$selected = _xls_stack_pop('usps_method');

		$config = $this->getConfigValues('USPS');

		$weight = $cart->total_weight();

		if(_xls_get_conf('WEIGHT_UNIT' , 'lb') != 'lb')
			$weight = $weight * 2.2;   // one KG is 2.2 pounds

		$countryObj = Country::LoadByCode($country);

		if($countryObj)
			$country = $countryObj->Country;
		else
			$country = "US";

		if(empty($config['username']) || empty($config['originpostcode']))
			return false;

		$this->init_vars($config['username'] , $config['originpostcode'] , $zipcode , $country , $config['markup']);
		$this->addItem(intval($weight) , round(($weight - intval($weight)) *16  , 0) , $cart->Total);

		$rates = $this->getRate();

		if(($rates === FALSE) || (count($rates) == 0 )) {
			$fields['service']->Visible = false;
			_xls_log("USPS: Could not get rates. " . print_r($this , true));
			return FALSE;
		}
		
		$fields['service']->Visible = true;

		$fields['service']->RemoveAllItems();
		asort($rates);
		foreach($rates as $service=>$rate) {
			$fields['service']->AddItem($service . " " . _xls_currency($rate)  ,  $service);
		}

		$ret = array(
			'price' => false,
			'msg' => '',
			'markup' => floatval($config['markup']),
			'product' => $config['product']
		);

		if($selected && isset( $rates[$selected])) {
			$fields['service']->SelectedValue = $selected;
			$ret['price'] = $rates[$selected];
			$ret['msg'] = $selected;
		} else {
			reset($rates);
			$c = key($rates);
			$fields['service']->SelectedValue = $c;
			$ret['price'] = $rates[$c];
			$ret['msg'] = $c;
		}

		_xls_stack_add('usps_method' , $fields['service']->SelectedValue);

		return $ret;
	}

	/**
	 * init_vars
	 * called by total(), sets initial values for calculation
	 *
	 * @param $usps_account string
	 * @param $usps_zip_origin string
	 * @param $destination string
	 * @param $country string
	 * @param $usps_markup string
	 * @return none, populates local scope $this->config
	 */
	function init_vars($usps_account, $usps_zip_origin, $destination, $country, $usps_markup) {
		$config = $this->getConfigValues(get_class($this));

		$this->uspsID = $usps_account;
		$this->zipOrigination = $usps_zip_origin;
		$this->zipDestination = $destination;
		$this->country = $country;

		$this->pounds = 0;
		$this->ounces = 0;
		$this->value = 0;
		$this->markup = $usps_markup;
		$this->methods = explode(",", $config['shiptypes']);
	}

	/**
	 * init_admin_vars
	 * called by total(), sets initial values for calculation
	 *
	 * @param $usps_account string
	 * @param $usps_zip_origin string
	 * @param $destination string
	 * @param $country string
	 * @param $usps_markup string
	 * @return none, populates local scope $this->config
	 */
	function init_admin_vars($usps_account, $usps_zip_origin, $destination, $country) {
		$this->uspsID = $usps_account;
		$this->zipOrigination = $usps_zip_origin;
		$this->zipDestination = $destination;
		$this->country = $country;

		if ($country == "US") {
			$this->pounds = 0;
			$this->ounces = 10;
			$this->value = 5;
		} else {
			$this->pounds = 1;
			$this->ounces = 0;
			$this->value = 20;
		}

		$this->markup = 0;
	}

	/**
	 * addItem
	 *
	 * adds an item to the package
	 *
	 * @param $p (pounds) int
	 * @param $o (ounces) int
	 * @param $v (value) int
	 * @return array
	 */
	public function addItem($p, $o, $v) {
		$p = (''==$p) ? 0 : $p;
		$o = (''==$o) ? 0 : $o;
		$this->pounds += $p;
		$this->ounces += $o;
		$this->value += $v;
	}

	public function getPounds() {
		return $this->pounds;
	}

	public function getOunces() {
		return $this->ounces;
	}

	public function getValue() {
		return $this->value;
	}

	public function setDestination($d) {
		$this->zipDestination = $d;
	}

    public function cleanMethodName($strName) {
        $strName = html_entity_decode($strName);
        $strName = strip_tags($strName);
        $strName = str_replace('reg', '', $strName);
        $strName = preg_replace("/[^A-Za-z0-9\-\ ]/", '', $strName);
        $strName = trim($strName);
        return $strName;
    }

	/**
	 * getRate
	 *
	 * Calling function to get the USPS rate from their website
	 * param bool - true will return all values (used by Admin panel)
	 * @return array
	*/
	public function getRate($showall=false) {
		if (($this->ounces + $this->pounds) == 0)
			return false;
        $config = $this->getConfigValues('usps');
		$request = ($this->isDomestic()) ? $this->buildDomesticRateRequest() : $this->buildInternationalRateRequest() ;
		$this->response = $this->sendUSPSRateRequest($request);

		// Parse xml for response values
		$oXML = new SimpleXMLElement($this->response);

		if($oXML->Package->Error) {
			//What we have is ... failure to communicate
			QApplication::Log(E_ERROR, __CLASS__,
							'Could not get shipping for USPS: '.$oXML->Package->Error->Description);
			return false;
		}

		$retval = array();

		if($this->isDomestic()) {
            foreach($oXML->Package->Postage as $key=>$val) {
              $strKey = $val->MailService;
              $strRate = $val->Rate;
              $strKey = $this->cleanMethodName($strKey);
			  $retval[$strKey] = floatval($strRate) + floatval($config['markup']);
			}
		} else {
			foreach($oXML->Package->Service as $key=>$val) {
              $strKey = $val->SvcDescription;
              $strRate = $val->Postage;
              $strKey = $this->cleanMethodName($strKey);
			  $retval[$strKey] = floatval($strRate) + floatval($config['markup']);
			}
		}

		$arrMethods = array_fill_keys($this->methods, '');


		if($showall)
		  return $retval;
		else
			return array_intersect_key($retval, $arrMethods);
	}

	/**
	 * isDomestic
	 *
	 * Is shipping address in our outside the US
	 * @return bool
	*/
	private function isDomestic() {
		$c = strtoupper($this->country);
		if($c == '' or $c == 'US' or $c == 'USA' or $c == 'AMERICA' or $c == 'US OF A'or $c == 'UNITED STATES')
			return true;

		return false;
	}

	/**
	 * buildDomesticRateRequest
	 *
	 * Build XML request for US shipping
	 * @return string
	*/
	private function buildDomesticRateRequest() {
		$r ='API=RateV3&XML=<?xml version="1.0"?>';
		$r.= '<RateV3Request USERID="'.$this->uspsID.'">';
		$r.='<Package ID="0">';
		$r.='<Service>ALL</Service>';
		$r.='<ZipOrigination>'.substr($this->zipOrigination,0,5).'</ZipOrigination>';
		$r.='<ZipDestination>'.substr($this->zipDestination,0,5).'</ZipDestination>';
		$r.='<Pounds>'.$this->pounds.'</Pounds>';
		$r.='<Ounces>'.$this->ounces.'</Ounces>';
		$r.='<Size>Regular</Size>';
		$r.='<Machinable>true</Machinable>';
		$r.='</Package>';
		$r.='</RateV3Request>';


		return $r;
	}

	/**
	 * buildInternationalRateRequest
	 *
	 * Build XML request for US shipping
	 * @return string
	*/
	private function buildInternationalRateRequest() {
		$r ='API=IntlRate&XML=<?xml version="1.0"?>';
		$r.= '<IntlRateRequest USERID="'.$this->uspsID.'">';
		$r.='<Package ID="0">';
		$r.='<Pounds>'.$this->pounds.'</Pounds>';
		$r.='<Ounces>'.$this->ounces.'</Ounces>';
		$r.='<MailType>Package</MailType>';
		$r.='<ValueOfContents>'.$this->value.'</ValueOfContents>';
		$r.='<Country>'.$this->country.'</Country>';
		$r.='</Package>';
		$r.='</IntlRateRequest>';

		return $r;
	}

	/**
	 * sendUSPSRateRequest
	 *
	 * cURL string to actually send request
	 * @return string
	*/
	private function sendUSPSRateRequest($req) {
		$url = 'http://Production.ShippingAPIs.com/ShippingAPI.dll';
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $req);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec($c);
		curl_close($c);

		return $page;
	}
}
