<?php


class canadapost extends WsShipping
{

	protected $defaultName = "Canada Post";
	public static $service_types = array(
		'Regular'=>'Regular',
		'Xpresspost'=>'Xpresspost',
		'Priority Courier'=>'Priority Courier',
		'Expedited'=>'Expedited',
		'Xpresspost USA'=>'Xpresspost USA',
		'Expedited US Business'=>'Expedited US Business',
		'Tracked Packet - USA'=>'Tracked Packet - USA',
		'Small Packets Air'=>'Small Packets Air',
		'Small Packets Surface'=>'Small Packets Surface'
	);


	public function run()
	{
		if(!isset($this->config['offerservices'])) return false;

		$weight = $this->objCart->Weight;

		if(_xls_get_conf('WEIGHT_UNIT', 'kg') != 'kg')
			$weight = $weight / 2.2;   // one KG is 2.2 pounds

		$length = $this->objCart->Length;
		$width = $this->objCart->Width;
		$height = $this->objCart->Height;

		if(_xls_get_conf('DIMENSION_UNIT', 'cm') != 'cm') {
			$length = round($length *2.54);
			$width = round($width *2.54);
			$height = round($height *2.54);
		}



		$found = 0;
		$ret = array();
		$url = "http://sellonline.canadapost.ca:30000";

		$xml =
			"<?xml version=\"1.0\" ?>
		<eparcel>
			<language>en</language>
			<ratesAndServicesRequest>
				<merchantCPCID>" . $this->config['cpc'] . "</merchantCPCID>
				<turnAroundTime>120</turnAroundTime>
				<itemsPrice>" . $this->objCart->subtotal . "</itemsPrice>
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
				" .  "<city>" . $this->CheckoutForm['shippingCity'] . "</city>\n" .
				"<provOrState>" . $this->CheckoutForm['shippingState'] . "</provOrState>\n" .
				"<country>" . $this->CheckoutForm['shippingCountry']. "</country>\n".
				"<postalCode>" . $this->CheckoutForm['shippingPostal'] . "</postalCode>\n".
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
			Yii::log(get_class($this) . " sending ".$xml, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log(get_class($this) . " receiving ".$result, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		$oXML = new SimpleXMLElement($result);

		if($oXML->error) {
			//What we have is ... failure to communicate
			Yii::log('Could not get shipping for Canada Post: '.$oXML->error->statusMessage, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}


		foreach($oXML->ratesAndServicesResponse->product as $key=>$val) {
			$strKey = $val->name;
			$strRate = $val->rate;
			$strKey = $this->cleanMethodName($strKey);
			$ret[$strKey] = floatval($this->cleanPrice($strRate));
			$found++;
		}

		if($found <=0) {

			$this->reportShippingFailure();
			return false;
		}

		return $this->convertRetToDisplay($ret);



	}

	public function cleanMethodName($strName) {
		$strName = html_entity_decode($strName);
		$strName = strip_tags($strName);
		$strName = str_replace('reg', '', $strName);
		$strName = preg_replace("/[^A-Za-z0-9\-\ ]/", '', $strName);
		$strName = trim($strName);
		return $strName;
	}

	public function cleanPrice($strName) {
		$strName = html_entity_decode($strName);
		$strName = strip_tags($strName);
		$strName = str_replace('reg', '', $strName);
		$strName = preg_replace("/[^A-Za-z0-9\.\-\ ]/", '', $strName);
		$strName = trim($strName);
		return $strName;
	}

}