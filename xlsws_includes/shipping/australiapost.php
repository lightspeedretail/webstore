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
 * Australia Post shipping module
 *
 *
 *
 */

class australiapost extends xlsws_class_shipping {
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
		return _sp("Australia Post");
	}

	/**
	 * make_AustraliaPost_services populates with shipping options available through shipper
	 * @param &field (by reference)
	 * no return value since we're updating the reference
	 *
	 *
	 */
	protected function make_AustraliaPost_services($field) {
		// valid values 'STANDARD', 'EXPRESS','AIR', 'SEA', 'ECONOMY'

		$this->service_types = array('STANDARD'=>'Australia Post Standard','EXPRESS'=>'Australia Post Express','AIR'=>'Australia Post Air','SEA'=>'Australia Post Sea','ECONOMY'=>'Australia Post Economy');

		foreach($this->service_types as $type=>$desc)
			$field->AddItem( $desc , $type);
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

		$ret['defaultproduct'] = new XLSListBox($objParent);
		$ret['defaultproduct']->Name = _sp('Default shipping product');
		$this->make_AustraliaPost_services($ret['defaultproduct']);

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
		$config = $this->getConfigValues('AustraliaPost');

		$ret['service'] = new XLSListBox($objParent);
		$this->make_AustraliaPost_services($ret['service']);
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
		$city = '', $address2 = '',  $address1= '', $company = '', $lname = '',   $fname = '') {

		$config = $this->getConfigValues('AustraliaPost');

		$weight = $cart->total_weight();

		if(_xls_get_conf('WEIGHT_UNIT' , 'kg') != 'kg')
			$weight = $weight / 2.2;   // one KG is 2.2 pounds

		$weight = round($weight * 1000 , 0);

		$length = $cart->total_length();
		$width = $cart->total_width();
		$height = $cart->total_height();

		if(_xls_get_conf('DIMENSION_UNIT' , 'cm') != 'cm') {
			$length = round($length * 2.54);
			$width = round($width * 2.54);
			$height = round($height * 2.54);
		}

		// Convert to mm
		$length = $length*10;
		$width = $width*10;
		$height = $height*10;

		/*
			Note, sometimes larges boxes may not return a shipping price. Please
			see http://www.auspost.com.au/BCP/0,1467,CH4497%257EMO19,00.html
			for additional information
		*/

		$selected = $fields['service']->SelectedValue;

		$this->make_AustraliaPost_services($fields['service']);

		$fields['service']->RemoveAllItems();

		if(empty($config['originpostcode']) )
			return FALSE;

		$found = 0;
		$ret = array();

		foreach($this->service_types as $type=>$desc) {
			$url = join(
				"&",
				array("http://drc.edeliver.com.au/ratecalc.asp?Pickup_Postcode=". $config['originpostcode'],
					"Destination_Postcode=" . $zipcode,
					"Country=" . $country,
					"Weight=$weight",
					"Length=$length",
					"Width=$width",
					"Height=$height",
					"Service_Type=" . $type ,
					"Quantity=1"
				)
			);

			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_HEADER, FALSE);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($c);
			curl_close($c);

			/*
			  Output sample:
			  charge=4.2
			  days=1
			  err_msg=OK
			 */

			$parts = explode("\n", $result);

			if(count($parts) < 2) {
				_xls_log("Error with Australia Post shipping type: " . print_r($result,true));
				continue;
			}
			$segments = explode("=",$parts[2]);

			if(strtoupper(trim($segments[1])) != 'OK') {
				_xls_log("Error with Australia Post shipping type: " . print_r($result,true));
				continue;
			}

			$segments = explode("=",$parts[0]);

			$fields['service']->AddItem("$desc (" . _xls_currency(floatval($segments[1]) + floatval($config['markup'])) . ")" , $type);

			$ret[$type] = floatval($segments[1]) + floatval($config['markup']);

			$found++;
		}

		if($found <=0) {
			_xls_log("AusPost: Could not get rates $country  , $zipcode .");
			_xls_log("AusPost Request: " . $url);

			$fields['service']->Visible = false;
			return FALSE;
		}

		$fields['service']->Visible = true;

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
}
