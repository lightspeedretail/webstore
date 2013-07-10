<?php

class tieredshippingAdminForm extends CFormModel
{
	public $label;
	public $tierbased;
	public $offerservices;
	public $restrictcountry;
	public $product;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,tierbased,offerservices,restrictcountry,product','required'),
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
			'tierbased'=>'Tiers based on',
			'offerservices'=>'Delivery Speed',
			'restrictcountry'=>'Only allow this carrier to',
			'product'=>'LightSpeed Product Code (case sensitive)',
		);
	}

	public function getAdminForm()
	{
		return array(
			'title' => 'After making changes and clicking Save, you can  '.
				CHtml::link('Edit Shipping Tiers','#',array('class'=>'settiers','id'=>get_class($this))) .' to define your ranges. You can '.CHtml::link('Set Product Restrictions','#',array('class'=>'basic','id'=>get_class($this))) .' for this module.',

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'tierbased'=>array(
					'type'=>'dropdownlist',
					'items'=>array('price'=>'Cart subtotal','weight'=>'Combined product weight'),
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