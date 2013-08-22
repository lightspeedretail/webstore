<?php

	/* Shipping module */
class WsShipping extends WsExtension
{
	/**
	 * Identify our free shipping module since we do special things with it
	 * @var bool
	 */
	public $freeShipping = false;

	/**
	 * Identify our store pickup module since it affects taxes
	 * @var bool
	 */
	protected $storePickup = false;

	/**
	 * The run() function is called from Web Store to actually do the calculation. It returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return array
	 */
	public function run()
	{

		if (!is_null($this->CheckoutForm)) {
			$arrReturn = $this->total(null,
				$this->objCart,
				$this->CheckoutForm['shippingCountry'],
				$this->CheckoutForm['shippingPostal'],
				$this->CheckoutForm['shippingState'],
				$this->CheckoutForm['shippingCity'],
				$this->CheckoutForm['shippingAddress2'],
				$this->CheckoutForm['shippingAddress1'],
				'',
				$this->CheckoutForm['shippingLastName'],
				$this->CheckoutForm['shippingFirstName']
			);

			if ($arrReturn===false) return array();
			return $arrReturn;
		} else  return array();
	}

	public function init()
	{
		parent::init();
		// import the module-level models and components
		Yii::import('application.extensions.wsshipping.'.get_class($this).'.models.*');
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 */
	public function admin_name()
	{

		if ($this->active)
			$strName = "<span class='activemodule'>".$this->defaultName."</span>";
		else
			$strName = "<span class='inactivemodule'>".$this->defaultName."</span>";

		if (isset($this->config['live']))
			if ($this->config['live']=="test" && $this->active) $strName .= "<div class='testlabel'>&nbsp;</div>";

		return $strName;
	}


	/**
	 * Check if the module is valid or not.
	 * Returning false here will exclude the module from checkout page
	 * Can be used for tests against cart conditions
	 *
	 * @return boolean
	 */
	public function check()
	{

		$blnCheckAffected = $this->checkAffected();
		$blnCheckCode = $this->checkCode();

		if (!$blnCheckAffected)
			Yii::log(get_class($this).' Shipping not shown due to product restrictions','info', 'application.'.__CLASS__.".".__FUNCTION__);
		if (!$blnCheckCode)
			Yii::log(get_class($this).' Shipping not shown due to promo code on shipper','info', 'application.'.__CLASS__.".".__FUNCTION__);

		if ($blnCheckAffected && $blnCheckCode)
		{
			if (!parent::check())
			{
				Yii::log(get_class($this).' Shipping not shown due to geographic restrictions','info', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
			else return true;

		}
		else return false;

	}

	protected function checkAffected()
	{

		$objPromoCode = PromoCode::LoadByShipping(get_class($this));
		if ($objPromoCode) {

			if (strpos($objPromoCode->code,":") !== false) {
				//This is restriction without actually using a code
				$cart = $this->objCart;

				$bolApplied = true;

				if ($objPromoCode)
					foreach ($cart->cartItems as $objItem)
						if (!$objPromoCode->IsProductAffected($objItem)) $bolApplied=false;
						elseif ($objPromoCode->exception==2) return true; //Scenario if just one item qualifies the shipping
				return $bolApplied;


			}
		}

		return true;

	}

	protected function checkCode()
	{
		//Does this item have a promo code?
		if (isset($this->config['promocode']))
			if (!empty($this->config['promocode']))
			{
				Yii::log(get_class($this) . " module requires promo code '".$this->config['promocode']."', checking...", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				$cart = $this->objCart;
				if ($cart->fk_promo_id > 0)
				{
					$pcode = PromoCode::model()->findbyPk($cart->fk_promo_id);
					if ($pcode->code == $this->config['promocode']) return true;

				}
				return false;

			}

		return true;
	}

	protected function convertRetToDisplay($ret)
	{


		$shipClass = get_class($this);

		$arrServices = array();
		if (isset($this->config['offerservices']))
		{
			if (is_array($this->config['offerservices']))
				$arrRestrictions = $this->config['offerservices'];
			else
				$arrRestrictions = array($this->config['offerservices']);

			$arrRestrictions = $shipClass::expandRestrictions($arrRestrictions);
		}
		else
			$arrRestrictions = null;

		asort($ret,SORT_NUMERIC);

		if (isset($shipClass::$service_types)) //phpstorm flags this as an error but it's fine
		{

			$serviceTypes = $shipClass::getServiceTypes($shipClass);
		}

		foreach($ret as $desc=>$returnval)
		{

			$arrReturn['price']=floatval($returnval)+ floatval($this->config['markup']);
			$arrReturn['level']=$desc;
			if (isset($serviceTypes[$desc]))
				$arrReturn['label'] = $serviceTypes[$desc];
			else
				$arrReturn['label'] =$desc;

			if (is_null($arrRestrictions) || in_array($desc,$arrRestrictions))
				$arrServices[] = $arrReturn;

		}

		return $arrServices;
	}

	public function getIsStorePickup()
	{
		return $this->storePickup;
	}


	public function getAdminModel()
	{

		$className = $this->getAdminModelName();
		$filename = Yii::getPathOfAlias('ext.wsshipping').DIRECTORY_SEPARATOR.get_class($this).DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR.$className.'.php';

		if(file_exists($filename))
			return new $className;
		else
			return null;

	}

	public function reportShippingFailure()
	{
		Yii::log('Could not get shipping: '.$this->CheckoutForm['shippingCountry']." ".$this->CheckoutForm['shippingPostal'], 'error', 'application.'.__CLASS__.".".__FUNCTION__);
	}

	public function getDefaultConfiguration()
	{
		$adminModel = $this->getAdminModel();
		if (!is_null($adminModel))
		{
			$arrAttributes = $adminModel->attributes;
			$arrAttributes['product'] = "SHIPPING";
			$arrAttributes['label'] = strip_tags($this->AdminName);
			$arrAttributes['offerservices'] = "Standard 3-5 Business Days";
			return serialize($arrAttributes);
		}
		else return false;
	}


	public function getLsProduct()
	{
		if(isset($this->config['product']))
			return $this->config['product'];
		else return "SHIPPING";

	}

	public static function getServiceTypes($class_name)
	{

		return $class_name::$service_types;
	}

	public static function expandRestrictions($arrRestrictions)
	{
		return $arrRestrictions;
	}
}