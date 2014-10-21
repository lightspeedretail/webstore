<?php

class beanstreamsimAdminForm extends CFormModel
{
	public $label;
	public $login;
	public $sha1hash;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,login,restrictcountry,ls_payment_method','required'),
			array('sha1hash', 'safe'),
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
			'login'=>'Merchant ID',
			'sha1hash'=>'Optional SHA-1 Hash Key',
			'restrictcountry'=>'Only allow this processor',
			'ls_payment_method'=>'Lightspeed Payment Method',
		);
	}

	public function getAdminForm()
	{
		return array(

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'login'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'sha1hash'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'restrictcountry'=>array(
					'type'=>'dropdownlist',
					'items'=>Country::getAdminRestrictionList(),
				),
				'ls_payment_method'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
			),
		);
	}
}