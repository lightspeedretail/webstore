<?php

class fedexAdminForm extends CFormModel
{
	public $label;
	public $accnumber;
	public $meternumber;
	public $securitycode;
	public $authkey;
	public $originadde;
	public $origincity;
	public $originpostcode;
	public $origincountry;
	public $originstate;
	public $packaging;
	public $ratetype;
	public $customs;
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
			array('label,accnumber,meternumber,securitycode,authkey,originadde,
			origincity,originpostcode,originstate,origincountry,packaging,ratetype,customs
			offerservices,restrictcountry,product','required'),
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
			'accnumber'=>'Account Number',
			'meternumber'=>'Meter Number',
			'securitycode'=>'Security Code (Production Password)',
			'authkey'=>'Authentication Key',
			'originadde'=>'Origin Address',
			'origincity'=>'Origin City',
			'originpostcode'=>'Origin Zip/Postal Code',
			'origincountry'=>'Origin Country',
			'originstate'=>'Origin State',
			'packaging'=>'Packaging',
			'ratetype'=>'Rate Type',
			'customs'=>'Who handles customs for int\'l shipping',
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
				'accnumber'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'meternumber'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'securitycode'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'authkey'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'originadde'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'origincity'=>array(
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
					'items'=>fedex::$service_types,
					'separator'=>'',
					'template'=>'<div class="offerservices">{input} {label}</div>',
					'label'=>'Offer these services<br><a onclick="selectall()">Select All</a><br><a onclick="selectnone()">Select None</a><br>'
				),
				'packaging'=>array(
					'type'=>'dropdownlist',
					'items'=>array('YOUR_PACKAGING'=>'Your packaging','FEDEX_BOX'=>'FedEx Box','FEDEX_PAK'=>'FedEx Pak','FEDEX_TUBE'=>'FedEx Tube'),
				),
				'ratetype'=>array(
					'type'=>'dropdownlist',
					'items'=>array('RATED_LIST'=>'List Rates','RATED_ACCOUNT'=>'Negotiated rates'),
				),
				'customs'=>array(
					'type'=>'dropdownlist',
					'items'=>array('CLEARANCEFEE'=>'FedEx Handles Customs Clearance','NOCHARGE'=>'My store handles Customs Clearance'),
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