<?php

class paypalAdminForm extends CFormModel
{
	public $label;
	public $login;
	public $address;
	public $live;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,login,address,live,restrictcountry,ls_payment_method','required'),
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
			'login'=>'Business Email Address',
			'address'=>'Prompt for shipping address again on PayPal',
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
				'address'=>array(
					'type'=>'dropdownlist',
					'items'=>array(1=>'off',0=>'on'), //This is purposely backwards, don't freak out
					'title'=>"Turns on shipping address on PayPal checkout. Turn on if you wish to receive PayPal's Confirmed Address from the user account. May be confusing to user to be prompted for shipping info twice.",
					'hint'=>"Hover over field for instructions",
				),
				'md5hash'=>array(
					'type'=>'text',
					'maxlength'=>64,
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