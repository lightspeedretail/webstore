<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class chequeform extends CFormModel
{

	public function getSubform()
	{
		//No actual form fields, just need to display a note to the user when they choose this payment option

		if(_xls_get_conf('DEFAULT_COUNTRY')=='224')
			$strAlert = Yii::t(get_class($this),'Please note your order will be pending until the check has cleared.');
		else $strAlert = Yii::t(get_class($this),'Please note your order will be pending until the cheque has cleared.');

		$objModule = Modules::LoadByName(substr(get_class($this),0,-4));
		if ($objModule)
		{
			$arrConfig = $objModule->ConfigValues;
			if (isset($arrConfig['customeralert']))
				$strAlert = $arrConfig['customeralert'];
		}

		return array(
			'title'=>Yii::t(get_class($this),$strAlert),

		);

	}


}
