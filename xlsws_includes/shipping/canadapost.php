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
		return _sp("Canada Post");
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
		$config = $this->getConfigValues('CanadaPost');

		$ret['service'] = new QListBox($objParent);
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

		$config = $this->getConfigValues('CanadaPost');

		$weight = $cart->total_weight();

		if(_xls_get_conf('WEIGHT_UNIT', 'kg') != 'kg')
			$weight = $weight / 2.2;   // one KG is 2.2 pounds

		$length = $cart->total_length();
		$width = $cart->total_width();
		$height = $cart->total_height();

		if(_xls_get_conf('DIMENSION_UNIT', 'cm') != 'cm') {
			$length = round($length *2.54);
			$width = round($width *2.54);
			$height = round($height *2.54);
		}

		$selected = $fields['service']->SelectedValue;

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

		$values = array();
		$index = array();
		$array = array();

		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $result, $values, $index);
		xml_parser_free($parser);
		$index = 0;

		$name = false;

		foreach ($values as $k => $v) {
			if(!isset($v['tag']) || !isset($v['value']) )
				continue;

			if($v['tag'] == 'name')
				$name = $v['value'];

			if(($v['tag'] == 'rate') && $name) {
				$vindex = $index+1;
				$fields['service']->AddItem("$name (" . _xls_currency(floatval($v['value']) + floatval($config['markup'])) . ")" , $name);
				$ret[$name] = floatval($v['value']) + floatval($config['markup']);
				$found++;
				$name = false;
			}
			$index++;
		}

		if($found <=0) {
			_xls_log("Canada Post: Could not get rates $country  , $zipcode .");
			_xls_log("Canada Post request: " . print_r($xml,true));
			_xls_log("Canada Post response: " . print_r($result,true));

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

		if(isset($ret[$selected])) {
			$fields['service']->SelectedValue = $selected;
			$arr['price'] = $ret[$selected];
		} else {
			reset($ret);
			$arr['price'] = current($ret);
		}

		return $arr;
	}
}
