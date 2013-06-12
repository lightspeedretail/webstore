<?php

class axiaAdminForm extends CFormModel
{
	public $label;
	public $source_key;
	public $source_key_pin;
	public $live;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,source_key,live,restrictcountry,ls_payment_method','required'),
			array('source_key_pin','safe'),
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
			'source_key'=>'Axia account key',
			'source_key_pin'=>'PIN for Source Key (if set)',
			'live'=>'Deployment Mode',
			'restrictcountry'=>'Only allow this processor',
			'ls_payment_method'=>'LightSpeed Payment Method',
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
				'source_key'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'source_key_pin'=>array(
					'type'=>'text',
					'maxlength'=>10,
				),
				'live'=>array(
					'type'=>'dropdownlist',
					'items'=>array('live'=>'Live','test'=>'Test'),
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