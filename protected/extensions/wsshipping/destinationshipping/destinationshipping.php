<?php

class destinationshipping extends WsShipping
{

	protected $defaultName = "Destination Shipping";

	public function run() {
	

		$unit = 1;
		$country = $this->CheckoutForm->shippingCountry;
		$state = $this->CheckoutForm->shippingState;
		$zipcode = $this->CheckoutForm->shippingPostal;


		Yii::log("DESTINATION TABLE: searching for $country/$state/$zipcode", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		if(!isset($this->config['per'])) {
			Yii::log("DESTINATION TABLE: could not get destination shipping unit.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		// Get the best matching destination
		$model = Destination::LoadMatching($country, $state, $zipcode);

		if(!isset($model)) {
			Yii::log("DESTINATION TABLE: No matching entry found for $country $state $zipcode .", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		if($this->config['per'] == 'item') {
			$unit = $this->objCart->TotalItemCount;
		} elseif($this->config['per'] == 'weight') {
			$unit = $this->objCart->Weight;
		} elseif($this->config['per'] == 'volume') {
			$unit = $this->objCart->Length * $this->objCart->Width * $this->objCart->Height;
		}

		if ($unit >= $model->ship_free)
			$unit -= $model->ship_free;

		if ($unit < 0)
			$unit = 0;

		// If the Base Charge is unset or lesser than 0, don't apply this module
		if ($model->base_charge == '' || $model->base_charge == null) {
			$label = Country::CodeById($model->country)."/".State::CodeById($model->state);
			Yii::log("DESTINATION TABLE: Base charge not set for entry '".$label."'", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}


		$desc = isset($this->config['offerservices']) ? $this->config['offerservices'] : Yii::t('global','Standard 3-5 Business Days');
		$ret[$desc] = $model->base_charge + ($unit*$model->ship_rate);


		return $this->convertRetToDisplay($ret);
	}

}