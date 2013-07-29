<?php

class phoneorderAdminForm extends CFormModel
{
	public $label;
	public $customeralert = 'Please call us with your credit card details.';
	public $restrictcountry;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,customeralert,restrictcountry','required'),
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
			'customeralert'=>'Notice displayed to customer',
			'restrictcountry'=>'Only allow this processor',
		);
	}

	public function getAdminForm()
	{
		return array(
			'title'=>'This method will mark an order to be downloaded without collecting additional payment information.',

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'customeralert'=>array(
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