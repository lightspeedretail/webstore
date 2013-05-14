<?php

class storepickupAdminForm extends CFormModel
{
	public $label;
	public $offerservices = 'Available during normal business hours. Please bring your Order ID';
	public $restrictcountry;
	public $markup;
	public $product;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,offerservices,restrictcountry,product','required'),
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
			'offerservices'=>'Order Message',
			'markup'=>'Handling Fee ($)',
			'restrictcountry'=>'Only allow this carrier to',
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
				'offerservices'=>array(
					'type'=>'text',
					'maxlength'=>128,
					'class'=>'nobottommargin',
				),
				'restrictcountry'=>array(
					'type'=>'dropdownlist',
					'items'=>Country::getAdminRestrictionList(true),
				),
				'markup'=>array(
					'type'=>'text',
					'maxlength'=>4,
				),
				'product'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
			),
		);
	}




}