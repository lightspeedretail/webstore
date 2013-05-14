<?php

	/* Payment module */
class WsPayment extends WsExtension
{
	public $advancedMode = false;
	public $subformModel;

	/**
	 * The run() function is called from Web Store to actually do the process. It returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return array
	 */
	public function run()
	{

		//This is here for backwards compatibility. Generally you would make your own run() function
		if (!is_null($this->CheckoutForm)) {
			$arrReturn = $this->process();

			if ($arrReturn===false) return array();
			return $arrReturn;

		} else return array();
	}

	public function init()
	{
		parent::init();
		// import the module-level models and components
		Yii::import('application.extensions.wspayment.'.get_class($this).'.models.*');
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

	public function getSubform()
	{
		return $this->subform;
	}


	public function setSubForm($mixForm = null)
	{

		$formName = $this->Subform;

		//Pass the checkout form and put it in our object
		if ($mixForm instanceof $formName) {
			$this->subformModel = $mixForm;
			return $this;
		}
		else throw new CException('SubForm not passed to module');
	}

	public function getAdminModel()
	{

		$className = $this->getAdminModelName();
		$filename = Yii::getPathOfAlias('ext.wspayment').DIRECTORY_SEPARATOR.get_class($this).DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR.$className.'.php';

		if(file_exists($filename))
			return new $className;
		else
			return null;

	}

	public function getDefaultConfiguration()
	{
		$adminModel = $this->getAdminModel();
		if (!is_null($adminModel))
		{
			$arrAttributes = $adminModel->attributes;
			$arrAttributes['ls_payment_method'] = "Web Credit Card";
			$arrAttributes['label'] = strip_tags($this->AdminName);
			return serialize($arrAttributes);
		}
		else return false;
	}

}