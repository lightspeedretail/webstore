<?php

class paypalproAdminForm extends CFormModel
{
	public $label;
	public $api_username;
	public $api_password;
	public $api_signature;
	public $api_username_sb;
	public $api_password_sb;
	public $api_signature_sb;
	public $live;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,live,restrictcountry,ls_payment_method','required'),
			array('api_username,api_password,api_signature,api_username_sb,api_password_sb,api_signature_sb','safe'),
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
			'api_username'=>'API Username',
			'api_password'=>'API Password',
			'api_signature'=>'API Signature',
			'api_username_sb'=>'API Username (Sandbox)',
			'api_password_sb'=>'API Password (Sandbox)',
			'api_signature_sb'=>'API Signature (Sandbox)',
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
				'api_username'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'api_password'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'api_signature'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'live'=>array(
					'type'=>'dropdownlist',
					'items'=>array('live'=>'Live','test'=>'Sandbox'),
				),
				'api_username_sb'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'api_password_sb'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'api_signature_sb'=>array(
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