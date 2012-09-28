<?php

class FreeShipping extends XLSModule {
	protected $strModuleType = 'shipping';

	/**
	 * Return a name we can rely on to remain constant, for internal use
	*/
	public function internal_name() {
	}

	/**
	 * Return the administrative name of the module for WS Admin Panel.
	 * It is different than the module name returned in front of the customer.
	 * @return string
	 */
	public function admin_name() {
		return _sp("Store Pickup - Sample");
	}

	/**
	 * The name of the module that will be displayed in the checkout page
	 * @return string
	 */
	public function name() {
		$config = $this->Config;

		if(isset($config['label'])) //if there is a label defined
			return $config['label']; // return the label

		return $this->admin_name(); //otherwise return administrative name
	}

	/**
	 * Return config fields (as array) for user configuration.
	 * The array key is the variable value holder
	 * For example if you wanted to have an admin-editable field called Message which is a textbox
	 *     $message = new XLSTextBox($parentObj);
	 *     $message->Text = "Default text"; /// this will be over-written by the user
	 *     $message->AddAction(new QFocusEvent(), new QAjaxControlAction('moduleActionProxy')); // You do not have to add action to a field. But if you wanted to this is how you would do it.
	 * 	   $message->Required = true; // This is optional. However if you wanted to make a field compulsory, this is what you would do.
	 * 	   return array('message' => $message);
	 *
	 *
	 * @param QPanel $parentObj
	 * @return array
	 */
    public function config_fields($parentObj) {
		$ret= array();


		$ret['label'] = new XLSTextBox($parentObj);
		$ret['label']->Name = _sp('Label');
		$ret['label']->Required = true;
		$ret['label']->Text = $this->admin_name();

		return $ret;
	}

    protected function GetAdminFields($ParentCtrl, $blnReset = false) {
        $arrFields = parent::GetAdminFields($ParentCtrl, $blnReset);

        if ($arrFields)
            return $arrFields;

        $arrFields['label'] = 
            $objControl = new XLSTextBox($ParentCtrl);
        $objControl->Name = _sp('Label');
        $objControl->Text = $this->GetName();
        $objControl->Required = true;

        $arrFields['product'] =
            $objControl = new XLSTextBox($ParentCtrl);
        $objControl->Name = _sp('LightSpeed Product Code');
        $objControl->Required = true;

        $arrFields['markup'] = 
            $objControl = new XLSTextBox($ParentCtrl);
        $objControl->Name = _sp('Mark up ($)');
        $objControl->Text = 3.00;
        $objControl->Required = true;

        return $arrFields;
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
	 * Return customer fields (as array) that will be shown in the checkout page. Fields can be any qcontrol elements
	 * The array key is the variable value holder
	 * For example if you wanted to have a service list box where customer will be choosing a type of service
	 *     $service = new XLSListBox($parentObj);
	 * 	   $service->Name = _sp('Choose your service type');
	 *     $service->AddItem(_sp('Service 1') , 'service1');
	 *     $service->AddItem(_sp('Service 2') , 'service2');
	 *	   $service->SelectedValue = $config['defaultproduct'];
	 * 	   return array('service' => $service);
	 *
	 *
	 * @param QPanel $parentObj
	 * @return array
	 */
	public function customer_fields($parentObj) {
		return array();
	}



	/**
	 * Check customer fields
	 *
	 * The fields generated and returned in customer_fields will be passed here for validity.
	 * Return true or false
	 *
	 * Checkout panel will ONLY continue to checkout if all the fields are valid.
	 *
	 * @param $fields[]
	 * @return boolean
	 */
	public function check_customer_fields($fields) {
		return true;
	}

	public function getConfigValues($classname) {
		return $this->GetConfigurationValues();
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

	public function Autoload($strClassName) {
		error_log(__FUNCTION__);
		if(file_exists(XLSWS_INCLUDES . 'shipping/' . $strClassName . ".php"))
			require_once(XLSWS_INCLUDES . 'shipping/' . $strClassName . ".php");
	}

	public function __autoload() {
		require_once __FILE__;
	}

	public function install() {
		return;
	}


	public function remove() {
		return;
	}

	/**
	 * Check if the module is valid or not.
	 * Returning false here will exclude the module from both admin panel and checkout page
	 *
	 * @return boolean
	 */
	public function check() {
		return true;
	}

	/**
	 * Return total for shipping.
	 *
	 *
	 * @param Cart $cart
	 * @param string $country
	 * @param string $zipcode
	 * @param string $state
	 * @param string $city
	 * @param string $address2
	 * @param string $address1
	 * @param string $company
	 * @param string $lname
	 * @param string $fname
	 * @return mixed
	 */
	public function total($fields, $cart, $country = '', $zipcode = '', $state = '',
		$city = '', $address2 = '', $address1= '' , $company = '', $lname = '', $fname = '') {

		return 0;
	}

	public function can_estimate() {
		return false;
	}

	public function estimate() {
		return 0;
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
		$desc = (str_replace("_" , " " , $cart->ShippingData ));
		return $this->name() . (($desc != '')?(' - ' . $desc):'');
	}

	public function ship_product($code = "SHIP") {
		return $code;
	}

	/**
	 * process
	 * Based on passed address information, calculates the total shipping cost
	 *
	 * @param array $cart
	 * @param reference array $fields
	 * @param price bool optional
	 * @param msg string optional
	 *
	*/
	public function process($cart , $fields , $price = FALSE , $msg = "" ) {
		if($price === FALSE)
			$price = $this->total($fields, $cart, $cart->ShipCountry, $cart->ShipZip, $cart->ShipState,
				$cart->ShipCity, $cart->ShipAddress2, $cart->ShipAddress1, $cart->ShipCompany, $cart->ShipLastname, $cart->ShipFirstname);

		$ret = "";

		foreach($fields as $key=>$field) {
			if($field instanceof QListBox ) {
				$msg .= " " .  $field->SelectedName;
				$ret .= $field->SelectedName . "  \n";
			} elseif($field instanceof QTextBox ) {
				$msg .= " " .  $field->Text;
				$ret .= $field->Text . "\n";
			}
		}

		if($ret == '')
			$ret = $this->name();

		return $ret;
	}
}
