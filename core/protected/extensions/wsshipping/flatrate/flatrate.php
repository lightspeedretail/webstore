<?php

class flatrate extends WsShipping
{
	public $defaultName = "Flat rate shipping";
	public $Version = 1.0;

	//Todo: have standard and express flat rate

	public function total($fields, $cart, $country = '', $zipcode = '', $state = '',
	                      $city = '', $address2 = '', $address1= '', $company = '', $lname = '', $fname = '') {

		$config = $this->getConfigValues(get_class($this));

		if($config['per'] == 'order')
			$price = floatval($config['rate']);
		elseif($config['per'] == 'item')
			$price = floatval($config['rate']) * $this->objCart->totalItemCount;
		elseif($config['per'] == 'weight')
			$price = floatval($config['rate']) * $this->objCart->Weight;
		else{
			Yii::log('FLAT RATE: Could not get per rate config.', 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}


		$arrReturn = array();

		$price = isset($config['markup']) ? ($price + $config['markup']) : $price;
		$desc = isset($config['offerservices']) ? $config['offerservices'] : Yii::t('global','Standard 3-5 Business Days');
		$arrReturn['price'] = $price;
		$arrReturn['level'] = $desc;
		$arrReturn['label'] = $desc;


		return array($arrReturn);


	}


}
