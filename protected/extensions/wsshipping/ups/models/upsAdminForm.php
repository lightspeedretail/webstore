<?php

class upsAdminForm extends CFormModel
{
	public $label;

	public $mode;
	public $origincountry;
	public $originstate;

	public $username;
	public $password;
	public $accesskey;

	public $customerclassification;
	public $originpostcode;
	public $ratecode;
	public $package;

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
			array('label,mode,
			originpostcode,originstate,origincountry,package,ratecode,customerclassification,
			offerservices,restrictcountry,product','required'),
			array('username,password,accesskey', 'validateUserInfo'),
			array('username,password,accesskey,markup','safe'),
		);
	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateUserInfo($attribute, $params)
	{
		if ($this->mode=="IUPS") //We haven't chosen from our address book
			if ( $this->$attribute == '' )
				$this->addError($attribute,
					Yii::t('yii','{attribute} cannot be blank.',
						array('{attribute}'=>$this->getAttributeLabel($attribute)))
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
			'mode'=>'Mode',
			'username'=>'Username (IUPS mode only)',
			'password'=>'Password  (IUPS mode only)',
			'accesskey'=>'Access Key (IUPS mode only)',
			'originpostcode'=>'Origin Zip/Postal Code',
			'origincountry'=>'Origin Country',
			'originstate'=>'Origin State',
			'customerclassification'=>'UPS Customer Type',
			'package'=>'Packaging',
			'ratecode'=>'Rate Code',
			'offerservices'=>'Offer these services',
			'restrictcountry'=>'Only allow this carrier to',
			'markup'=>'Mark up ($)',
			'product'=>'LightSpeed Product Code (case sensitive)',
		);
	}

	public function getAdminForm()
	{
		if (empty($this->origincountry))
			$this->origincountry = (int)_xls_get_conf('DEFAULT_COUNTRY',224);
		return array(
			'title' => 'Note: You can '.CHtml::link('Set Product Restrictions','#',array('class'=>'basic','id'=>get_class($this))) .' for this module.',

			'elements'=>array(
				'label'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'mode'=>array(
					'type'=>'dropdownlist',
					'items'=>array('UPS'=>'Domestic US UPS Mode','IUPS'=>'IUPS International UPS Mode'),
				),
				'username'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'password'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'accesskey'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'originpostcode'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'origincountry'=>array(
					'type'=>'dropdownlist',
					'items'=>CHtml::listData(Country::model()->findAllByAttributes(array('active'=>1),array('order'=>'sort_order,country')), 'id', 'country'),
					'ajax' => array(
						'type'=>'POST',
						'url'=>Yii::app()->controller->createUrl('ajax/getstates'),
						'data' => 'js:{"'.'country_id'.'": $("#'.CHtml::activeId($this,'origincountry').' option:selected").val()}',
						'update'=>'#'.CHtml::activeId($this,'originstate'),
						),
					),
				'originstate'=>array(
					'type'=>'dropdownlist',
					'items'=>CHtml::listData(State::model()->findAllByAttributes(
						array('country_id'=>$this->origincountry,'active'=>1),array('order'=>'sort_order,state')), 'id', 'code'),
				),
				'offerservices'=>array(
					'type'=>'checkboxlist',
					'items'=>ups::$service_types,
					'separator'=>'',
					'template'=>'<div class="offerservices">{input} {label}</div>',
					'label'=>'Offer these services<br><a onclick="selectall()">Select All</a><br><a onclick="selectnone()">Select None</a><br>'
				),
				'package'=>array(
					'type'=>'dropdownlist',
					'items'=>ups::$package_types,
				),
				'ratecode'=>array(
					'type'=>'dropdownlist',
					'items'=>ups::$rate_types,
				),
				'customerclassification'=>array(
					'type'=>'dropdownlist',
					'items'=>array('04'=>'Retail','03'=>'Occasional','01'=>'Wholesale'),
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