<?php

class freeshipping extends WsShipping
{
	protected $defaultName = "Free Shipping";
	protected $version = "1.0";
	public $freeShipping = true;


	public function run() {


		$arrReturn = array();
		//cause it's free
		$desc = isset($config['offerservices']) ? $config['offerservices'] : Yii::t('global','Standard 3-5 Business Days');
		$arrReturn['price']=0;
		$arrReturn['level']=$desc;
		$arrReturn['label'] = $desc;


		return array($arrReturn);

	}


	public function qualifies()
	{
		$config = $this->getConfigValues(get_class($this));

		if ($this->objCart->subtotal < $config['rate']) {
			$userMsg = _sp("Subtotal does not qualify for free shipping, you must purchase at least " . _xls_currency($config['rate']) . " worth of merchandise.");
			return array('price' => -1, 'error' => $userMsg);

		}


	}

	public function getThreshold()
	{
		$config = $this->getConfigValues(get_class($this));
		return $config['rate'];

	}
	/**
	 * Check if the module is valid or not.
	 * Returning false here will exclude the module from checkout page
	 * Can be used for tests against cart conditions
	 *
	 * @return boolean
	 */
	public function check() {

		if (strlen($this->config['startdate'])>0 && $this->config['startdate'] != "0000-00-00")
			if ($this->config['startdate']>date("Y-m-d")) return false;
		if (strlen($this->config['enddate'])>0 && $this->config['enddate'] != "0000-00-00")
			if ($this->config['enddate']<date("Y-m-d")) return false;

		return parent::check();
	}

	public function install() {

		$config = $this->getConfigValues(get_class($this));
		//If there's a promo code entered from last time, is it one already in the table?
		if (strlen($config['promocode'])>0)
			$objPromoCode = PromoCode::LoadByCodeShipping($config['promocode']);

		//If not, do we have one with the class name we need to update?
		if (!$objPromoCode)
			$objPromoCode = PromoCode::LoadByCodeShipping(get_class($this).":");


		if (!$objPromoCode) { //If we're this far without an object, create one
			$objPromoCode = new PromoCode;
			$objPromoCode->Lscodes = "shipping:,";
			$objPromoCode->Except = 0;
			$objPromoCode->Enabled = 1;
		}

		$objPromoCode->Enabled=1;
		$objPromoCode->Save();

	}
	public function remove() {

		//When we're turning this module off, on our way out the door....
		$config = $this->getConfigValues(get_class($this));
		//If there's a promo code entered from last time, is it one already in the table?
		if (strlen($config['promocode'])>0)
			$objPromoCode = PromoCode::LoadByCodeShipping($config['promocode']);

		//If not, do we have one with the class name we need to update?
		if (!$objPromoCode)
			$objPromoCode = PromoCode::LoadByCodeShipping(get_class($this).":");


		if (!$objPromoCode) { //If we're this far without an object, create one
			$objPromoCode = new PromoCode;
			$objPromoCode->Lscodes = "shipping:,";
			$objPromoCode->Except = 0;
			$objPromoCode->Enabled = 1;
		}

		$objPromoCode->Enabled=0;
		$objPromoCode->Save();
	}

	private function syncPromoCode($vals) {

		$config = $this->getConfigValues(get_class($this));
		$strPromoCode=$vals['promocode']->Text; //Entered promo code

		//If there's a promo code entered from last time, is it one already in the table?
		if (strlen($config['promocode'])>0)
			$objPromoCode = PromoCode::LoadByCodeShipping($config['promocode']);

		//If not, do we have one with the class name we need to update?
		if (!$objPromoCode)
			$objPromoCode = PromoCode::LoadByCodeShipping(get_class($this).":");


		if (!$objPromoCode) { //If we're this far without an object, create one
			$objPromoCode = new PromoCode;
			$objPromoCode->Lscodes = "shipping:,";
			$objPromoCode->Except = 0;
			$objPromoCode->Enabled = 1;
		}

		//Sync any fields with the promo code table
		if (strlen($vals['promocode']->Text)==0)
			$strPromoCode=get_class($this).":";
		else
			$strPromoCode=$vals['promocode']->Text;


		$objPromoCode->ValidFrom = $vals['startdate']->Text;
		$objPromoCode->ValidUntil = $vals['enddate']->Text;
		$objPromoCode->Code = $strPromoCode;

		$objPromoCode->Amount = 0;
		$objPromoCode->Type = 1; //Needs to be 0% so UpdatePromoCode() returns valid test
		$objPromoCode->Threshold = ($vals['rate']->Text == "" ? "0" : $vals['rate']->Text);
		if ($vals['qty_remaining']->Text=='')
			$objPromoCode->QtyRemaining = -1;
		else
			$objPromoCode->QtyRemaining = $vals['qty_remaining']->Text;

		$objPromoCode->Save();



	}

	public function __get($strName) {
		switch ($strName) {
			case 'Name':
				return $this->name();

			default:
				return parent::__get($strName);

		}
	}
}
