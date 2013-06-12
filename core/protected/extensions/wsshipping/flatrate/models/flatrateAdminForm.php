<?php

class flatrateAdminForm extends CFormModel
{
	public $label;
	public $per;
	public $rate;
	public $offerservices;
	public $restrictcountry;
	public $markup;
	public $product;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,per,rate,offerservices,restrictcountry,product','required'),
			array('markup','safe'),
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
			'per'=>'Calculate cost as Rate($) multiplied by each',
			'rate'=>'Rate ($)',
			'offerservices'=>'Delivery Speed',
			'restrictcountry'=>'Only allow this carrier to',
			'markup'=>'Mark up ($)',
			'product'=>'LightSpeed Product Code (case sensitive)',
		);
	}

	public function getAdminForm()
	{
		return array(
			'title' => 'Note: You can '.CHtml::link('Set Product Restrictions','#',array('class'=>'basic','id'=>get_class($this))) .' for this module.',

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'per'=>array(
					'type'=>'dropdownlist',
					'items'=>array('order'=>'Order','item'=>'Item','weight'=>'Weight unit'),
				),
				'rate'=>array(
					'type'=>'text',
					'maxlength'=>8,
				),
				'offerservices'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'specialcode'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'restrictcountry'=>array(
					'type'=>'dropdownlist',
					'items'=>Country::getAdminRestrictionList(true),
				),
				'markup'=>array(
					'type'=>'hidden',
					'value'=>0,
				),
				'product'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
			),
		);
	}




}