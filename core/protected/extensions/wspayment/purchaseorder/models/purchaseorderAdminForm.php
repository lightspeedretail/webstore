<?php

class purchaseorderAdminForm extends CFormModel
{
	public $label;
	public $restrictcountry;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,restrictcountry','required'),
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
			'label'=>'Label',
			'restrictcountry'=>'Only allow this processor',
		);
	}

	public function getAdminForm()
	{
		return array(
			'title'=>'This method will collect a PO number on checkout.',

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'restrictcountry'=>array(
					'type'=>'dropdownlist',
					'items'=>Country::getAdminRestrictionList(),
				),
			),
		);
	}




}