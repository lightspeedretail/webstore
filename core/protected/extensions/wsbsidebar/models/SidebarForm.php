<?php

/**
 * SidebarForm class.
 * This is just purely a sample. There is nothing special about this class, it's the same as any other
 * custom model, just demoing you can have models inside an extension
 */
class SidebarForm extends CFormModel
{
	public $strExampleText;
	public $strOtherText;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('strExampleText, strOtherText','required'),

		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'strExampleText'=>'Example Field',
			'strOtherText'=>'Other Field',

		);
	}



}