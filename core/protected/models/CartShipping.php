<?php

/**
 * This is the model class for table "{{cart_shipping}}".
 *
 * @package application.models
 * @name CartShipping
 *
 */
class CartShipping extends BaseCartShipping
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CartShipping the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getShippingSell()
	{
		if ($this->shipping_taxable==1)
			return $this->shipping_sell_taxed;
		else
			return $this->shipping_sell;
	}


}