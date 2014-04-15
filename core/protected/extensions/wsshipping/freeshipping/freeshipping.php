<?php

class freeshipping extends WsShipping
{
	protected $defaultName = "Free Shipping";
	protected $version = 1;
	public $freeShipping = true;


	public function run() {


		$arrReturn = array();
		//cause it's free
		$desc = isset($this->config['offerservices']) ? $this->config['offerservices'] : Yii::t('global','Standard 3-5 Business Days');
		$arrReturn['price']=0;
		$arrReturn['level']=$desc;
		$arrReturn['label'] = $desc;


		return array($arrReturn);

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
			if ($this->config['startdate']>date("Y-m-d"))
			{
				Yii::log(get_class($this) . " startdate returns false", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		if (strlen($this->config['enddate'])>0 && $this->config['enddate'] != "0000-00-00")
			if ($this->config['enddate']<date("Y-m-d"))
			{
				Yii::log(get_class($this) . " enddate returns false", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}

		if ($this->threshold>0 && $this->objCart->subtotal<$this->threshold)
		{
			Yii::log(get_class($this) . " threshold returns false", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		return parent::check();
	}


	public function syncPromoCode() {

		$config = $this->getConfigValues(get_class($this));
		$strPromoCode=$config['promocode']; //Entered promo code

		$objPromoCode = PromoCode::LoadByShipping(get_class($this));


		if (!$objPromoCode) { //If we're this far without an object, create one
			$objPromoCode = new PromoCode();
			$objPromoCode->lscodes = "shipping:,";
			$objPromoCode->exception = 0;
			$objPromoCode->enabled = 1;
			$objPromoCode->module = get_class($this);
		}


		//Sync any fields with the promo code table
		if (strlen($strPromoCode)==0)
			$strPromoCode=get_class($this).":";
		$objPromoCode->code = $strPromoCode;

		$objPromoCode->valid_from =
			isset($config['startdate']) && !empty($config['startdate']) ?  $config['startdate'] :  null;
		$objPromoCode->valid_until =
			isset($config['enddate']) && !empty($config['enddate']) ? $config['enddate'] :  null;

		$objPromoCode->amount = 0;
		$objPromoCode->type = PromoCodeType::Percent; //Needs to be 0% so UpdatePromoCode() returns valid test
		$objPromoCode->threshold = ($config['rate'] == "" ? "0" : $config['rate']);
		if ($config['qty_remaining']=='')
			$objPromoCode->qty_remaining = -1;
		else
			$objPromoCode->qty_remaining = $config['qty_remaining'];

		$objPromoCode->save();



	}


}
