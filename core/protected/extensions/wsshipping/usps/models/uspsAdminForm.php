<?php

class uspsAdminForm extends CFormModel
{
	public $label;
	public $originpostcode;
	public $username;
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
			array('label,username,originpostcode,offerservices,restrictcountry,product','required'),
			array('markup','safe'),
			array('username','match', 'pattern'=>'/\d{3}\w{5}\w+/','message'=>'Invalid Account Number. It should look something like 123ABCDE1234.'),
			array('originpostcode','length','min'=>5,'max'=>10),
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
			'username'=>'Account Number',
			'originpostcode'=>'Origin Zip/Postal Code',
			'offerservices'=>'Offer these services',
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
				'username'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'originpostcode'=>array(
					'type'=>'text',
					'maxlength'=>10,
				),
				'offerservices'=>array(
					'type'=>'checkboxlist',
					'items'=>usps::getServiceTypes('usps',false),
					'separator'=>'',
					'template'=>'<div class="offerservices">{input} {label}</div>',
					'label'=>'Offer these services<br><a onclick="selectall()">Select All</a><br><a onclick="selectnone()">Select None</a><br>'
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