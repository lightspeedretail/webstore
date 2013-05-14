<?php

class cashondelivery extends WsPayment
{

	protected $defaultName = "Cash on Delivery";
	protected $version = "1.0";


	/**
	 * The run() function is called from Web Store to run the process.
	 * The return array should have two elements: the first is true/false if the transaction was successful. The second
	 * string is either the successful Transaction ID, or the failure Error String to display to the user.
	 * @return array
	 */
	public function run()
	{

		$arrReturn['success']=true;
		$arrReturn['amount_paid']=0;
		$arrReturn['result']=$this->defaultName; //transaction ID or error string
		$arrReturn['api'] = 1;

		//This module just returns a success
		return $arrReturn;
	}

}
