<?php

/**
 * UPS Module
 * created by LightSpeed
 */
class ups extends WsShipping
{
	/**
	 * @var string
	 */
	protected $defaultName = "UPS";
	protected $version = 1.0;


	public $my_service_types;

	public static $service_types = array (
		'03' => 'UPS Ground',
		'11' => 'UPS Standard',
		'12' => 'UPS 3 Day Select',
		'02' => 'UPS 2nd Day Air',
		'13' => 'UPS Next Day Air Saver',
		'01' => 'UPS Next Day Air',
		'14' => 'UPS Next Day Air Early A.M.',
		'65' => 'UPS Express Saver',
		'07' => 'UPS Worldwide Express',
		'08' => 'UPS Worldwide Expedited',
		'54' => 'UPS Worldwide Express Plus',
		'59' => 'UPS 2nd Day Air A.M.',
		'82' => 'UPS Today Standard',
		'83' => 'UPS Today Dedicated',
		'84' => 'UPS Today Intercity',
		'85' => 'UPS Today Express'
	);

	//A few of these are international and will not get used for a US destination
	public static $domestic_us_translation = array(
		'03' => 'GND',
		'11' => 'STD',
		'12' => '3DS',
		'02' => '2DA',
		'13' => '1DP',
		'01' => '1DA',
		'14' => '1DM',
		'65' => '65',
		'07' => 'XPR',
		'08' => 'XPD',
		'54' => 'XDM',
		'59' => '2DM',
		'82' => '82',
		'83' => '83',
		'84' => '84',
		'85' => '85'
	);


	public static $package_types = array(
		'CP'=>'Customer Packaging',
		'ULE'=>'UPS Letter Envelope',
		'UT'=>'UPS Tube',
		'UEB'=>'UPS Express Box',
		'UW25'=>'UPS Worldwide 10 kilo',
		'UW10'=>'UPS Worldwide 25 kilo',

	);

	public static $rate_types = array(
		'Regular Daily Pickup'=>'Regular Daily Pickup',
		'On Call Air'=>'On Call Air',
		'One Time Pickup'=>'One Time Pickup',
		'Letter Center'=>'Letter Center',
		'Customer Counter'=>'Customer Counter',
	);


	/* iUPS only */
	var $userid;
	var $passwd;
	var $accesskey;
	var $currency;
	var $upstool='https://www.ups.com/ups.app/xml/Rate';
	var $request;
	var $service;
	var $customerclassification;
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

	/* end iUPS only */

	public function run()
	{

		if (isset($this->config['mode'])) {

			$this->my_service_types = self::$service_types;
			if (isset($this->config['offerservices']))
				$this->my_service_types = array_intersect_key($this->my_service_types,array_combine($this->config['offerservices'],$this->config['offerservices']));


			$funcToRun = $this->config['mode']."total";
			return $this->$funcToRun();
		} else return false;

	}


	/**
	 * Based on address information, calculates the total shipping cost. Called from run()
	 * @return array|bool
	 */
	public function UPStotal() {

		$weight = $this->objCart->Weight;

		if(_xls_get_conf('WEIGHT_UNIT' , 'lb') != 'lb')
			$weight = $weight * 2.2;   // one KG is 2.2 pounds

		$length = $this->objCart->Length;
		$width = $this->objCart->Width;
		$height = $this->objCart->Height;

		if(_xls_get_conf('DIMENSION_UNIT' , 'in') != 'in') {
			$length = round($length / 2.54);
			$width = round($width / 2.54);
			$height = round($height / 2.54);
		}

		$found = 0;


		$arrServices = array();

		$zipcode=str_replace(" ","",$this->CheckoutForm->shippingPostal); //Remove spaces i.e. canada Z1Z 1Z1 to Z1Z1Z1
		if (strtoupper($this->CheckoutForm->shippingCountry)=="US")
			$zipcode=substr($zipcode,0,5); //UPS module doesn't support Zip+4 in US



		foreach($this->my_service_types as $type=>$desc) {
			if (isset($this->config['mode']))
				if ($this->config['mode']=="UPS")
					$type = self::$domestic_us_translation[$type];

			$upsAction =  "3";
			$url = join(
				"&",
				array(
					"http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
					"10_action=$upsAction",
					"13_product=" . $type,
					"14_origCountry=".Country::CodeById($this->config['origincountry']),
					"15_origPostal=".substr($this->config['originpostcode'],0,5),
					"19_destPostal=".$zipcode,
					"22_destCountry=".$this->CheckoutForm->shippingCountry,
					"23_weight=" . $weight,
					"24_value=" . $this->objCart->Total,
					"25_length=" . $length,
					"26_width=" . $width,
					"27_height=" . $height,
					"47_rateChart=".$this->config['ratecode'],
					"48_container=".$this->config['package'],
					"49_residential=".($this->CheckoutForm->shippingResidential==1 ? 1 : 0)
				)
			);

			if(_xls_get_conf('DEBUG_SHIPPING' , false))
				_xls_log(get_class($this) . " sending ".$url,true);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,15);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15); //timeout in seconds
			$result = curl_exec($ch);
			curl_close($ch);

			$result=explode("%",$result);
			if(_xls_get_conf('DEBUG_SHIPPING' , false))
				Yii::log("Receiving ".print_r($result,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);



			if(count($result) < 9)
				continue;

			$returnval = $result[10];


			$arrReturn['price']=floatval($returnval)+ floatval($this->config['markup']);
			$arrReturn['level']=$desc;
			$arrReturn['label'] = $desc;

			$arrServices[] = $arrReturn;

			$found++;
		}

		if($found <=0) {
			Yii::log("UPS: Could not get ups rate ".$this->CheckoutForm->shippingCountry." ".$zipcode, 'warning', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}



		return $arrServices;
	}


	public function IUPStotal()
	{


		if(empty($this->config['originpostcode']) || empty($this->config['origincountry']) || empty($this->config['username']) || empty($this->config['accesskey']))
			return false;

		$this->s_zip = $this->config['originpostcode'];
		$this->s_state = $this->config['originstate'];
		$this->s_country = Country::CodeById($this->config['origincountry']);
		$this->userid = $this->config['username'];
		$this->passwd = $this->config['password'];
		$this->accesskey = $this->config['accesskey'];
		$this->customerclassification = $this->config['customerclassification'];
		$this->pickuptype = $this->config['ratecode'];
		$this->currency = $this->objCart->currency;
		$this->residential = ($this->CheckoutForm->shippingResidential==1 ? 1 : 0);
		$weight = $this->objCart->Weight;
		$this->weight_type = strtoupper(_xls_get_conf('WEIGHT_UNIT', 'lb'));
		$this->weight_type .= "S"; // Add KGS or LBS

		$length = $this->objCart->Length;
		$width = $this->objCart->Width;
		$height = $this->objCart->Height;
		$this->measurement_type = strtoupper(_xls_get_conf('DIMENSION_UNIT', 'in'));



		$found = 0;


		$ret = $this->rate();

		if($ret === false) {
			return false;
		}

		asort($ret,SORT_NUMERIC);

		if(count($ret)==0)
		{
			Yii::log('Could not get shipping information for '.
				$this->CheckoutForm->shippingState." ".$this->CheckoutForm->shippingPostal." ".
				$this->CheckoutForm->shippingCountry, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;

		}

		return $this->convertRetToDisplay($ret);


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

		$customer_classification = $this->customerclassification;

		if ($customer_classification=='')
			$customer_classification='04';

		if ($this->pickuptype=='')
			$this->pickuptype='01';

		$xml='<?xml version="1.0"?>
<AccessRequest xml:lang="en-US">
	<AccessLicenseNumber>'.$this->accesskey.'</AccessLicenseNumber>
	<UserId><![CDATA['.$this->userid.']]></UserId>
	<Password><![CDATA['.$this->passwd.']]></Password>
</AccessRequest>
<?xml version="1.0"?>
<RatingServiceSelectionRequest xml:lang=\'en-US\'>
  <Request>
	<TransactionReference>
	  <CustomerContext>Rating and Service</CustomerContext>
	  <XpciVersion>1.0001</XpciVersion>
	</TransactionReference>
	<RequestAction>Rate</RequestAction>
	<RequestOption>shop</RequestOption>
  </Request>
	<PickupType>
	<Code>'.$this->pickuptype.'</Code>
  </PickupType>
  <CustomerClassification>
		<Code>'.$customer_classification.'</Code>
	</CustomerClassification>
  <Shipment>
	<Shipper>
		<Address>
			<PostalCode>'.$this->s_zip.'</PostalCode>
			<CountryCode>'.$this->s_country.'</CountryCode>
		</Address>
	</Shipper>
	<ShipTo>
		<Address>
			<PostalCode>'.$this->t_zip.'</PostalCode>
			<CountryCode>'.$this->t_country.'</CountryCode>
			<ResidentialAddressIndicator>'.$this->residential.'</ResidentialAddressIndicator>
		</Address>
	</ShipTo>
	<ShipFrom>
		<Address>
			<PostalCode>'.$this->s_zip.'</PostalCode>
			<CountryCode>'.$this->s_country.'</CountryCode>
		</Address>
	</ShipFrom>
	<Service>
			<Code>'.$this->service.'</Code>
	</Service>
	<Package>
		<PackagingType>
			<Code>'.$this->package_type.'</Code>
		</PackagingType>
			<Dimensions>
				<UnitOfMeasurement>
				  <Code>'.$this->measurement_type.'</Code>
				</UnitOfMeasurement>
				<Length>'.$this->l.'</Length>
				<Width>'.$this->w.'</Width>
				<Height>'.$this->h.'</Height>
			</Dimensions>
		<PackageWeight>
			<UnitOfMeasurement>
				 <Code>'.$this->weight_type.'</Code>
			</UnitOfMeasurement>
			<Weight>'.$this->weight.'</Weight>
		</PackageWeight>
	</Package>
	<PackageServiceOptions>
		<InsuredValue>
			<CurrencyCode>'.$currency_code.'</CurrencyCode>
			<MonetaryValue>'.$this->value.'</MonetaryValue>
		</InsuredValue>
	</PackageServiceOptions>
  </Shipment>
</RatingServiceSelectionRequest>';

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
	function rate() {

		$this->service = 'shop';
		$this->t_zip = $this->CheckoutForm->shippingPostal;
		$this->t_state= $this->CheckoutForm->shippingState;
		$this->t_country = $this->CheckoutForm->shippingCountry;
		$this->weight = $this->objCart->Weight;
		$this->l = $this->objCart->Length;
		$this->w = $this->objCart->Width;
		$this->h = $this->objCart->Height;
		$this->value = $this->objCart->subtotal;

		$this->__runCurl();

		// Parse xml for response values
		$oXML = new SimpleXMLElement($this->xmlreturndata);

		if($oXML->Response->ResponseStatusDescription=="Failure") {
			//What we have is ... failure to communicate
			Yii::log("Could not get shipping information for UPS", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
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


		if(_xls_get_conf('DEBUG_SHIPPING' , false)) {
			_xls_log(get_class($this) . " sending ".$y,true);
			_xls_log(get_class($this) . " receiving ".$this->xmlreturndata,true);
		}

	}


}
