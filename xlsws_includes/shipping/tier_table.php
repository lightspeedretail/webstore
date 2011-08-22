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
 * Tier Table
 *
 *
 *
 */

class tier_table extends xlsws_class_shipping {
	public function admin_name() {
		return _sp("Tier Based Shipping");
	}

	public function name() {
		$config = $this->getConfigValues('tier_table');

		if(isset($config['label']))
			return $config['label'];

		return _sp("Tier Based Shipping");
	}

	// return the keys for this module
	public function config_fields($objParent) {
		$ret= array();

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = $this->admin_name();

		$ret['product'] = new XLSTextBox($objParent);
		$ret['product']->Name = _sp('LightSpeed Product Code');
		$ret['product']->Required = true;
		$ret['product']->Text = 'SHIPPING';

		return $ret;
	}

	public function check_config_fields($fields) {
		return true;
	}

	public function total($fields, $cart, $country = '', $zipcode = '', $state = '', $city = '', $address2 = '', $address1 = '', $company = '', $lname = '', $fname = '') {
		$config = $this->getConfigValues('tier_table');
		$price = 0;

		$db = ShippingTiers::GetDatabase();
		$results = $db->Query("select * from xlsws_shipping_tiers where start_price <= " . $cart->Subtotal . " and end_price >= " .$cart->Subtotal);
		$rate = ShippingTiers::InstantiateDbResult($results);
		$price = $rate[0]->Rate;
		if (!isset($rate[0])){ //Price falls into a tier table price gap, so tell user we can't calculate and report error.
			_xls_log("Tier Shipping: The subtotal ".$cart->Subtotal." does not fall into any defined tier.");
			$fields['service']->Visible = false;
			return FALSE;
		}

		return array('price' => $price, 'product' => $config['product']);
	}

	public function check() {
		return true;
	}
}
