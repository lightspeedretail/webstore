<?php

class monerisAdminForm extends CFormModel
{
	public $label;
	public $store_id;
	public $api_token;
	public $live;
	public $ccv;
	public $avs;
	public $specialcode;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,store_id,api_token,ccv,live,restrictcountry,ls_payment_method','required'),
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
			'store_id'=>'Store ID',
			'api_token'=>'API token',
			'ccv'=>'Use CCV code',
			'avs'=>'Use AVS Address Verification',
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
				'store_id'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'api_token'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'ccv'=>array(
					'type'=>'dropdownlist',
					'items'=>array('1'=>'Yes','0'=>'No'),
				),
//				'avs'=>array(
//					'type'=>'dropdownlist',
//					'items'=>array('1'=>'Yes','0'=>'No'),
//				),
				'live'=>array(
					'type'=>'dropdownlist',
					'items'=>array('live'=>'Live','test'=>'Sandbox'),
					'title'=>"To use TEST MODE set to Sandbox.",
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