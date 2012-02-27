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
 * destination_table, called from xls_admin.php Modules::LoadByFileType('destination_table.php' , 'shipping')
 *
 * Calculates shipping based on predefined destination shipping from Web Admin panel
 *
 */

class destination_table extends xlsws_class_shipping {
	protected $strModuleName = "Destination Shipping";
	
	
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

		$ret['per'] = new XLSListBox($objParent);
		$ret['per']->Name = _sp('Calculate Shipping on');
		$ret['per']->AddItem('Item Count' , 'item');
		$ret['per']->AddItem('Weight' , 'weight');
		$ret['per']->AddItem('Volume' , 'volume');

		$ret['product'] = new XLSTextBox($objParent);
		$ret['product']->Name = _sp('LightSpeed Product Code');
		$ret['product']->Required = true;
		$ret['product']->Text = 'SHIPPING';

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
		return true;
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
	public function total($fields, $cart, $country = '', $zipcode = '', $state = '', $city = '', $address2 = '', $address1= '', $company = '', $lname = '', $fname = '') {
		$config = $this->getConfigValues('destination_table');

		$unit = 1;

		if(!isset($config['per'])) {
			_xls_log("DESTINATION TABLE: could not get destination shipping unit.");
			return FALSE;
		}

		// Get the best matching destination
		$dest = Destination::LoadMatching($country, $state, $zipcode);

		if(!isset($dest)) {
			_xls_log("DESTINATION TABLE: No matching entry found for $country $state $zipcode .");
			return false;
		}

		if($config['per'] == 'item') {
			$unit = $cart->Count;
		} elseif($config['per'] == 'weight') {
			$unit = $cart->total_weight();
		} elseif($config['per'] == 'volume') {
			$unit = $cart->total_length() * $cart->total_width() * $cart->total_height();
		}

		if($unit>$dest->ShipFree)
			$unit -= $dest->ShipFree;

        // If the Base Charge is unset or lesser than 0, don't apply this module
        if ($dest-BaseCharge == '' or $dest->BaseCharge < 0)
            return false;

		return array('price' => $dest->BaseCharge + ($unit*$dest->ShipRate)  ,  'product' =>  $config['product']);
	}

	/**
	 * Array sort used for ranking shipping destinations by order applied.
	 * @param array[]
	 * @param on sort key
	 * @param order sort order
	 * @return array
	 *
	 *
	 */
	private function array_sort($array, $on, $order=SORT_ASC) {
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}

	/**
	 * check() verifies nothing has changed in the configuration since initial load
	 * @return boolean
	 *
	 *
	 */
	public function check(){
		return true;
	}
}
