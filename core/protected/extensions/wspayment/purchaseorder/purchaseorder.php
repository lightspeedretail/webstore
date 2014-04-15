<?php

class purchaseorder extends WsPayment
{

	protected $defaultName = "Purchase Order";
	protected $version = 1;
	public $cloudCompatible = false;

	//Define a subform
	public $subform = "purchaseorderform"; //will be available as $this->subformModel during processing


	/**
	 * The process() function is called from Web Store to run the process.
	 * The return array should have two elements: the first is true/false if the transaction was successful. The second
	 * string is either the successful Transaction ID, or the failure Error String to display to the user.
	 * @return array
	 */
	public function run()
	{

		$arrReturn['success']=true;
		$arrReturn['amount_paid']=0;
		$arrReturn['result']=$this->subformModel->po; //for this module, use the entered PO number
		$arrReturn['jump_url']=false;
		$arrReturn['api'] = 1;

		return $arrReturn;
	}


}
