<?php

	/* Payment module */
class WsPayment extends WsExtension
{
	public $subformModel;

	/**
	 * Is this an Advanced Integration Method (AIM)?
	 * Used for display purposes and checkoutform validation
	 * @var bool
	 */
	public $advancedMode = false;

	/**
	 * Can Cloud Web Stores use this option?
	 * @var bool
	 */
	public $cloudCompatible = false;


	/**
	 * If we use this method, do we need to perform some
	 * additional steps to finalize the order?
	 * @var bool
	 */
	public $performInternalFinalizeSteps = true;

	/**
	 * The run() function is called from Web Store to actually do the process. It returns an array of the service
	 * levels and prices available to the customer (as keys and values in the array, respectively).
	 * @return array
	 */
	public function run()
	{

		//This is here for backwards compatibility. Generally you would make your own run() function
		if (!is_null($this->CheckoutForm))
		{
			$arrReturn = $this->process();

			if ($arrReturn === false)
			{
				return array();
			}

			return $arrReturn;
		}
		else
		{
			return array();
		}
	}

	public function init()
	{
		parent::init();
		// import the module-level models and components
		Yii::import('application.extensions.wspayment.'.get_class($this).'.models.*');
		Yii::import('custom.extensions.payment.'.get_class($this).'.models.*');
	}

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 */
	public function adminName()
	{

		if ($this->active)
		{
			$strName = "<span class='activemodule'>".$this->defaultName."</span>";
		}
		else
		{
			$strName = "<span class='inactivemodule'>".$this->defaultName."</span>";
		}

		if (isset($this->config['live']))
		{
			if ($this->config['live'] == "test" && $this->active)
			{
				$strName .= "<div class='testlabel'>&nbsp;</div>";
			}
		}

		return $strName;
	}

	public function getSubform()
	{
		return $this->subform;
	}


	public function setSubForm($mixForm = null)
	{

		$formName = $this->subform;

		//Pass the checkout form and put it in our object
		if ($mixForm instanceof $formName)
		{
			$this->subformModel = $mixForm;
			return $this;
		}
		else
		{
			throw new CException('SubForm not passed to module');
		}
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
		else
		{
			return false;
		}
	}

	/**
	 * Return the appropriate logging level string
	 *
	 * @return string
	 */
	public function getLogLevel()
	{
		if(_xls_get_conf('DEBUG_PAYMENTS', false) == 1)
		{
			return CLogger::LEVEL_ERROR;
		}

		return CLogger::LEVEL_INFO;
	}

	/**
	 * We use this function to know if the payment method is allowed to be
	 * used.
	 *
	 * @return bool True if we can display the payment method. False
	 * otherwise.
	 */
	public function isDisplayable()
	{
		$allowAdvancedPayments = CPropertyValue::ensureBoolean(
			Yii::app()->params['ALLOW_ADVANCED_PAY_METHODS']
		);

		if ($allowAdvancedPayments === false && $this->advancedMode === true)
		{
			return false;
		}

		return parent::isDisplayable();
	}
}
