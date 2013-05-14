<?php

class phoneorderform extends CFormModel
{

	public function getSubform()
	{
		//No actual form fields, just need to display a note to the user when they choose this payment option

		return array(
			'title'=>Yii::t(get_class($this),'Please call us with your credit card details.'),

		);
	}

}
