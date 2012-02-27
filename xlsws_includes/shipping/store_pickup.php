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
 * Intershipper shipping module
 *
 *
 *
 */

class store_pickup extends xlsws_class_shipping {
	protected $strModuleName = "Store Pickup";
	
	/**
	 * The Web Admin panel for configuring this shipping option
	 *
	 * @param $parentObj (shipping panel object)
	 * @return array
	 *
	 */
	public function config_fields($objParent) {
		$ret = array();

		$ret['label'] = new XLSTextBox($objParent);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = $this->admin_name();

		$ret['msg'] = new XLSTextBox($objParent);
		$ret['msg']->Name = _sp('Store Pickup Message');
		$ret['msg']->Text = 'Please quote order ID %s with photo ID at the reception for collection.';

		$ret['product'] = new XLSTextBox($objParent);
		$ret['product']->Name = _sp('LightSpeed Product Code');
		$ret['product']->Required = true;

		$ret['markup'] = new XLSTextBox($objParent);
		$ret['markup']->Name = _sp('Handling Fee');
		$ret['markup']->Required = true;
		$ret['markup']->Text = 0.00;

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
	 * No return value, since it updates the passed reference
	 */
	public function total($fields, $cart, $country = '', $zipcode = '', $state = '',
		$city = '', $address2 = '', $address1= '', $company = '', $lname = '', $fname = '') {

		$config = $this->getConfigValues(get_class($this));
		return array(
			'markup' => ($config['markup'] + 0),
			'price' => ($config['markup'] + 0),
			'product' => $config['product']
		);
	}

	/**
	 * message
	 *
	 * Generic message function to return result string
	 *
	 * @param $cart[]
	 * @return string
	 *
	 */
	public function message($cart) {
		$config = $this->getConfigValues('store_pickup');

		return sprintf( $config['msg'], $cart->IdStr);
	}
}
