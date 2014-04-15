<?php

class cheque extends WsPayment
{

	protected $defaultName = "Cheque";
	protected $version = 1;
	public $cloudCompatible = false;

	//Define a subform
	public $subform = "chequeform";

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
		$arrReturn['result']=$this->defaultName; //transaction ID or error string
		$arrReturn['jump_url']=false;
		$arrReturn['api'] = 1;

		//This module just marks as paid
		return $arrReturn;
	}

	public function init()
	{
		parent::init();

		//US spelling for US stores
		if(_xls_get_conf('DEFAULT_COUNTRY')=='224')
			$this->defaultName = "Check";


	}

}
