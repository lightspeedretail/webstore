<?php

class australiapost extends WsShipping
{

	protected $defaultName = "Australia Post";

	public static $service_types = array(
		'AUS_PARCEL_REGULAR'=>'Parcel Regular',
		'AUS_PARCEL_REGULAR_SATCHEL_3KG'=>'Parcel Regular Satchel 3kg',
		'AUS_PARCEL_EXPRESS'=>'Parcel Express',
		'AUS_PARCEL_EXPRESS_SATCHEL_3KG'=>'Parcel Express Satchel 3kg',
		'INTL_SERVICE_ECI_PLATINUM'=>'Express Courier Intl Platinum',
		'INTL_SERVICE_ECI_M'=>'Express Courier Intl Merchandise',
		'INTL_SERVICE_ECI_D'=>'Express Courier Intl Documents',
		'INTL_SERVICE_EPI'=>'Express Post Intl',
		'INTL_SERVICE_PTI'=>'Pack and Track Intl',
		'INTL_SERVICE_RPI'=>'Registered Post Intl',
		'INTL_SERVICE_AIR_MAIL'=>'Air Mail',
		'INTL_SERVICE_SEA_MAIL'=>'Sea Mail',



	);


	public function run()
	{
		$service_types = self::$service_types;

		$weight = $this->objCart->Weight;

		if(_xls_get_conf('WEIGHT_UNIT' , 'kg') != 'kg')
			$weight = $weight / 2.2;   // one KG is 2.2 pounds

		$length = $this->objCart->Length;
		$width = $this->objCart->Width;
		$height = $this->objCart->Height;

		if(_xls_get_conf('DIMENSION_UNIT' , 'cm') != 'cm') {
			$length = round($length * 2.54);
			$width = round($width * 2.54);
			$height = round($height * 2.54);
		}

		//set 15cm box size by 1kg min weight
		if ($length<15) $length=15;
		if ($width<15) $width=15;
		if ($height<15) $height=15;
		if ($weight<1) $weight=1;

		if(empty($this->config['originpostcode']) || empty($this->config['offerservices']) )
			return FALSE;

		$ret = array();


		if ($this->CheckoutForm['shippingCountry']=="AU")
			$url = "https://auspost.com.au/api/postage/parcel/domestic/service.xml".
			"?from_postcode=".$this->config['originpostcode'].
			"&to_postcode=".$this->CheckoutForm['shippingPostal'].
			"&length=".$length.
			"&width=".$width.
			"&height=".$height.
			"&weight=".$weight;
		else
			$url = "https://auspost.com.au//api/postage/parcel/international/service.xml".
				"?country_code=".$this->CheckoutForm['shippingCountry'].
				"&weight=".$weight;

		if(_xls_get_conf('DEBUG_SHIPPING' , false)=="1")
			Yii::log(get_class($this) . " sending ".$url, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Auth-Key: ' . $this->config['api_key']
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		if(_xls_get_conf('DEBUG_SHIPPING' , false)=="1")
			Yii::log(get_class($this) . " receiving ".$result, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		$oXML = new SimpleXMLElement($result);

		if(isset($oXML->errorMessage))
		{
			Yii::log($oXML->errorMessage, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}
		else
		{

			if(count($oXML->service) <=0) {
				$this->reportShippingFailure();
				return false;
			}


			foreach($oXML->service as $value)
			{
				$ret[(string)$value->code] =
					floatval($value->price);
			}

			return $this->convertRetToDisplay($ret);
		}



	}



}
