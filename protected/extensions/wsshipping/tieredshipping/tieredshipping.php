<?php

class tieredshipping extends WsShipping
{

	protected $defaultName = "Tier-Based Shipping";

	public function run() {

		$fltCriteria = $this->objCart->subtotal;
		if ($this->config['tierbased']=="weight")
			$fltCriteria = $this->objCart->Weight;

		if(_xls_get_conf('DEBUG_SHIPPING' , false)) {
			Yii::log(get_class($this) . " evaluating ".$fltCriteria.' as '.$this->config['tierbased'], 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		$model =	ShippingTiers::model()->find(array(
				'condition'=>'class_name=:class and start_price <= :flt and end_price >= :flt',
				'params'=>array(':class'=>get_class($this), ':flt'=>$fltCriteria),
			));


		if (!isset($model))
		{ //Price falls into a tier table price gap, so tell user we can't calculate and report error.
			Yii::log(get_class($this) . "The cart ".$fltCriteria." does not fall into any defined tier.", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		} else {
			$desc = isset($this->config['offerservices']) ? $this->config['offerservices'] : Yii::t('global','Standard 3-5 Business Days');
			$ret[$desc] = $model->rate;

			return $this->convertRetToDisplay($ret);
		}

	}


}