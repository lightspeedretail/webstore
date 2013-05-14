<?php

class merchantwareAdminForm extends CFormModel
{
	public $label;
	public $name;
	public $site_id;
	public $trans_key;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,name,site_id,trans_key,restrictcountry,ls_payment_method','required'),
			array('trans_key','match', 'pattern'=>'/\w{5}-\w{5}-\w{5}-\w{5}-\w{5}/','message'=>'Invalid Account Number. It should look something like XXXXX-XXXXX-XXXXX-XXXXX-XXXXX.'),
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
			'name'=>'Your assigned account name',
			'site_id'=>'Account Site ID',
			'trans_key'=>'Account Key',
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
				'name'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'site_id'=>array(
					'type'=>'text',
					'maxlength'=>10,
				),
				'trans_key'=>array(
					'type'=>'text',
					'maxlength'=>30,
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