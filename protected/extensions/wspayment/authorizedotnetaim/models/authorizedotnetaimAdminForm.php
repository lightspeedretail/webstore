<?php

class authorizedotnetaimAdminForm extends CFormModel
{
	public $label;
	public $login;
	public $trans_key;
	public $live;
	public $ccv;
	public $specialcode;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,login,trans_key,live,ccv,restrictcountry,ls_payment_method','required'),
			array('specialcode','safe'),
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
			'live'=>'Deployment Mode',
			'ccv'=>'Use CCV code Verification',
			'specialcode'=>'Special Transaction Code (if any)',
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
				'ccv'=>array(
					'type'=>'dropdownlist',
					'items'=>array(0=>'off',1=>'on'),
					'title'=>"If you have enabled CCV/CVC (Enhanced CCV Handling Filter) in your Authorize.net account, turn this on. Otherwise this should remain off.",
					'hint'=>"Hover over field for instructions",
				),
				'specialcode'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'live'=>array(
					'type'=>'dropdownlist',
					'items'=>array('live'=>'Live','test'=>'Sandbox'),
					'title'=>"To use (TEST MODE) in your regular account, leave this as Live and instead set Test Mode in your Authorize.net account settings on their site. Sandbox should only be used with Authorize.net Sandbox testing servers.",
					'hint'=>"Hover over field for instructions",
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