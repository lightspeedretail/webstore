<?php

/**
 * UPS Module
 * created by LightSpeed
 */
class usps extends WsShipping
{

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

	protected $defaultName = "USPS";

	public static $service_types = array(

		'First-Class Mail Large Envelope',
		'First-Class Mail Letter',
		'First-Class Mail Parcel',
		//'First-Class Mail Postcards',
		'Priority Mail{0}',
		//'Priority Mail Express{0} Hold For Pickup',
        'Priority Mail Express{0}',
		'Standard Post',
		'Media Mail',
		'Library Mail'=>'Library Mail',
		'Priority Mail Express{0} Flat Rate Envelope',
		'First-Class Mail Large Postcards',
		'Priority Mail{0} Flat Rate Envelope',
		'Priority Mail{0} Medium Flat Rate Box',
		'Priority Mail{0} Large Flat Rate Box',
		'Priority Mail Express{0} Sunday/Holiday Delivery',
		'Priority Mail Express{0} Sunday/Holiday Delivery Flat Rate Envelope',
		//'Priority Mail Express{0} Flat Rate Envelope Hold For Pickup',
		'Priority Mail{0} Small Flat Rate Box',
		'Priority Mail{0} Padded Flat Rate Envelope',
		'Priority Mail Express{0} Legal Flat Rate Envelope',
		//'Priority Mail Express{0} Legal Flat Rate Envelope Hold For Pickup',
		'Priority Mail Express{0} Sunday/Holiday Delivery Legal Flat Rate Envelope',
		//'Priority Mail{0} Hold For Pickup',
		//'Priority Mail{0} Large Flat Rate Box Hold For Pickup',
		//'Priority Mail{0} Medium Flat Rate Box Hold For Pickup',
		//'Priority Mail{0} Small Flat Rate Box Hold For Pickup',
		//'Priority Mail{0} Flat Rate Envelope Hold For Pickup',
  		'Priority Mail{0} Gift Card Flat Rate Envelope',
		//'Priority Mail{0} Gift Card Flat Rate Envelope Hold For Pickup',
		'Priority Mail{0} Window Flat Rate Envelope',
		//'Priority Mail{0} Window Flat Rate Envelope Hold For Pickup',
        'Priority Mail{0} Small Flat Rate Envelope',
		//'Priority Mail{0} Small Flat Rate Envelope Hold For Pickup',
        'Priority Mail{0} Legal Flat Rate Envelope',
		//'Priority Mail{0} Legal Flat Rate Envelope Hold For Pickup',
		//'Priority Mail{0} Padded Flat Rate Envelope Hold For Pickup',
		'Priority Mail{0} Regional Rate Box A',
		//'Priority Mail{0} Regional Rate Box A Hold For Pickup',
		'Priority Mail{0} Regional Rate Box B',
		//'Priority Mail{0} Regional Rate Box B Hold For Pickup',
		//'First-Class Package Service Hold For Pickup',
		'Priority Mail Express{0} Flat Rate Boxes',
		//'Priority Mail Express{0} Flat Rate Boxes Hold For Pickup',
		'Priority Mail Express{0} Sunday/Holiday Delivery Flat Rate Boxes',
		'Priority Mail{0} Regional Rate Box C',
		//'Priority Mail{0} Regional Rate Box C Hold For Pickup',
		'First-Class Package Service',
		'Priority Mail Express{0} Padded Flat Rate Envelope',
		//'Priority Mail Express{0} Padded Flat Rate Envelope Hold For Pickup',
		'Priority Mail Express{0} Sunday/Holiday Delivery Padded Flat Rate Envelope',

		'Priority Mail Express International',
		'Priority Mail International',
		'Global Express Guaranteed (GXG)',
		'Global Express Guaranteed Document',
		'Global Express Guaranteed Non-Document Rectangular',
		'Global Express Guaranteed Non-Document Non-Rectangular',
		'Priority Mail International Flat Rate Envelope',
		'Priority Mail International Medium Flat Rate Box',
		'Priority Mail Express International Flat Rate Envelope',
		'Priority Mail International Large Flat Rate Box',
		'USPS GXG Envelopes',
		'First-Class Mail International Letter',
		'First-Class Mail International Large Envelope',
		'First-Class Package International Service',
		'Priority Mail International Small Flat Rate Box',
		'Priority Mail Express International Legal Flat Rate Envelope',
		'Priority Mail International Gift Card Flat Rate Envelope',
		'Priority Mail International Window Flat Rate Envelope',
		'Priority Mail International Small Flat Rate Envelope',
		'First-Class Mail International Postcard',
		'Priority Mail International Legal Flat Rate Envelope',
		'Priority Mail International Padded Flat Rate Envelope',
		'Priority Mail International DVD Flat Rate priced box',
		'Priority Mail International Large Video Flat Rate priced box',
		'Priority Mail Express International Flat Rate Boxes',
		'Priority Mail Express International Padded Flat Rate Envelope'
	);

	/**
	 * The run() function is called from Web Store to actually do the calculation. It returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return array
	 */
	public function run()
	{

		if(!isset($this->config['offerservices'])) return false;

		$weight = $this->objCart->Weight;
		if(_xls_get_conf('WEIGHT_UNIT' , 'lb') != 'lb')
			$weight = $weight * 2.2;   // one KG is 2.2 pounds

		//USPS wants a full country name
		$countryObj = Country::LoadByCode($this->CheckoutForm->shippingCountry);
		if($countryObj)
			$country = $countryObj->country;
		else
			$country = "US";

		if (empty($country)) $country = "US";

		if(empty($this->config['username']) || empty($this->config['originpostcode']))
			return false;

		$this->init_vars(
			$this->config['username'],
			$this->config['originpostcode'],
			$this->CheckoutForm->shippingPostal,
			$country,
			$this->config['markup']);
		$this->addItem(intval($weight) , round(($weight - intval($weight)) *16  , 0) , $this->objCart->total);

		$rates = $this->getRate();

		if(($rates === FALSE) || (count($rates) == 0 )) {
			Yii::log("USPS: Could not get rates. " . print_r($this , true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		return $this->convertRetToDisplay($rates);
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

//		$config = $this->config;
//
//		$weight = $cart->Weight;

		if(_xls_get_conf('WEIGHT_UNIT' , 'lb') != 'lb')
			$weight = $this->objCart->Weight * 2.2;   // one KG is 2.2 pounds

		//USPS wants a full country name
		$countryObj = Country::LoadByCode($this->CheckoutForm->shippingCountry);
		if($countryObj)
			$country = $countryObj->country;
		else
			$country = "US";

		if (empty($country)) $country = "US";

		if(empty($this->config['username']) || empty($this->config['originpostcode']))
			return false;

		$this->init_vars(
			$this->config['username'],
			$this->config['originpostcode'],
			$this->CheckoutForm->shippingPostal,
			$country,
			$this->config['markup']);
		$this->addItem(intval($weight) , round(($weight - intval($weight)) *16  , 0) , $this->objCart->total);

		$rates = $this->getRate();

		if(($rates === FALSE) || (count($rates) == 0 )) {
			Yii::log("USPS: Could not get rates. " . print_r($this , true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		return $this->convertRetToDisplay($rates);

//		asort($rates);
//		$arrServices = array();
//		foreach($rates as $desc=>$returnval) {
//			$arrReturn['price']=floatval($returnval);
//			$arrReturn['level']=$desc;
//			$arrReturn['label'] = $desc;
//
//			$arrServices[] = $arrReturn;
//
//		}
//
//
//		return $arrServices;
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
		$config = $this->config;

		$this->uspsID = $usps_account;
		$this->zipOrigination = $usps_zip_origin;
		$this->zipDestination = $destination;
		$this->country = $country;

		$this->pounds = 0;
		$this->ounces = 0;
		$this->value = 0;
		$this->markup = $usps_markup;
		$this->methods = $this->config['offerservices'];
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
		$strName = str_replace('#8482', '', $strName);
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
			$this->pounds=1;
		$config = $this->getConfigValues(get_class($this));
		$request = ($this->isDomestic()) ? $this->buildDomesticRateRequest() : $this->buildInternationalRateRequest() ;
		$this->response = $this->sendUSPSRateRequest($request);

		if(_xls_get_conf('DEBUG_SHIPPING' , false)) {
			_xls_log(get_class($this) . " sending ".print_r($request,true),true);
			_xls_log(get_class($this) . " receiving ".$this->response,true);
		}

		// Parse xml for response values
		$oXML = new SimpleXMLElement($this->response);

		if($oXML->Package->Error) {
			//What we have is ... failure to communicate
			Yii::log('Could not get shipping for USPS: '.$oXML->Package->Error->Description, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
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

		return $retval;


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
		$r.= '<RateV3Request USERID="'.urlencode($this->uspsID).'">';
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
		$r.= '<IntlRateRequest USERID="'.urlencode($this->uspsID).'">';
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


	public static function getServiceTypes($class_name,$process_days=true)
	{
		if($process_days)
		{
			$arr = array();

			foreach(self::$service_types as $value)
			{
				if(stripos($value,"{0}") !== false)
				{
					for($x=1; $x<=5; $x++)
						$arr[] = Yii::t('usps',$value,array('{0}'=>$x."-Day"));
				} else $arr[] = $value;
			}

			return array_combine($arr,$arr);
		}

		else
			return array_combine(self::$service_types,self::$service_types);

	}

	public static function expandRestrictions($arrRestrictions)
	{

		$arr = array();

		foreach($arrRestrictions as $key=>$value)
		{
			if(stripos($value,"{0}") !== false)
			{
				for($x=1; $x<=5; $x++)
					$arr[] = Yii::t('usps',$value,array('{0}'=>" ".$x."-Day"));
			} else $arr[] = $value;
		}

		return $arr;
	}

}
