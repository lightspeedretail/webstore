<?php

class authorizedotnetsimAdminForm extends CFormModel
{
	public $label;
	public $login;
	public $trans_key;
	public $md5hash;
	public $live;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,login,trans_key,live,restrictcountry,ls_payment_method','required'),
			array('md5hash', 'safe'),
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
			'login'=>'Login ID',
			'trans_key'=>'Transaction Key',
			'md5hash'=>'MD5 Hash Value',
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
				'login'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'trans_key'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'md5hash'=>array(
					'type'=>'text',
					'maxlength'=>20,
				),
				'live'=>array(
					'type'=>'dropdownlist',
					'items'=>array('live'=>'Live','test'=>'Sandbox'),
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