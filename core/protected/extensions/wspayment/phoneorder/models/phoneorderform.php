<?php

class phoneorderform extends CFormModel
{

	public function getSubform()
	{
		//No actual form fields, just need to display a note to the user when they choose this payment option
		$strAlert = "Please call us with your credit card details";
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
