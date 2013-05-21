<?php

class storepickup extends WsShipping
{
	public $defaultName = "Store Pickup";
	public $Version = 1.0;
	public $storePickup = true;

	/**
	 * The run() function is called from Web Store to actually do the calculation. It either returns a single
	 * price, indicating that there are no further service options, or it returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return float
	 * @return array
	 */
	public function run()
	{


		$price = 0;
		$desc = isset($this->config['offerservices']) ? $this->config['offerservices'] : Yii::t('global','Available during normal business hours');
		$ret[$desc] = $price;

		return $this->convertRetToDisplay($ret);

	}
}
