<?php

class destinationshippingAdminForm extends CFormModel
{
	public $label;
	public $per;
	public $offerservices;
	public $restrictcountry;
	public $product;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,per,offerservices,restrictcountry,product','required'),
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
			'per'=>'Calculate cost as Rate multiplied by each',
			'offerservices'=>'Delivery Speed',
			'restrictcountry'=>'Only allow this carrier to',
			'product'=>'LightSpeed Product Code (case sensitive)',
		);
	}

	public function getAdminForm()
	{
		return array(
			'title' => 'Note: After saving the module, click '.
				CHtml::link('Set Destination Rates','#',array('class'=>'destinationrates','id'=>get_class($this))) .' to define your prices. You can '.CHtml::link('Set Product Restrictions','#',array('class'=>'basic','id'=>get_class($this))) .' for this module',

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'per'=>array(
					'type'=>'dropdownlist',
					'items'=>array('item'=>'Item','weight'=>'Weight unit'), //,'volume'=>'Combined product volume'
				),
				'offerservices'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'restrictcountry'=>array(
					'type'=>'dropdownlist',
					'items'=>Country::getAdminRestrictionList(true),
				),
				'product'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
			),
		);
	}




}