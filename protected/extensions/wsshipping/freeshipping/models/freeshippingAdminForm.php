<?php

class freeshippingAdminForm extends CFormModel
{
	public $label;
	public $rate;
	public $startdate;
	public $enddate;
	public $promocode;
	public $qty_remaining;
	public $offerservices;
	public $restrictcountry;
	public $product;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label,restrictcountry,offerservices,product','required'),
			array('rate,startdate,enddate,promocode,qty_remaining','safe'),
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
			'rate'=>'Threshold Amount ($)',
			'startdate'=>'Optional Start Date',
			'enddate'=>'Optional End Date',
			'promocode'=>'Optional Promo Code',
			'qty_remaining'=>'Optional Promo Code Qty (blank=unlimited)',
			'offerservices'=>'Delivery Speed',
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
				'rate'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'startdate'=>array(
					'type' => 'zii.widgets.jui.CJuiDatePicker',
					'attributes' => array(
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							'altField' => '#self_pointing_id',
							'altFormat'=>_xls_convert_date_to_js(_xls_get_conf('DATE_FORMAT','Y-m-d')),
						),
						'htmlOptions' => array( // A list of HTML attributes associated to the date picker input element.
							'style' => 'height: 20px;'
						),
					),
				),
				'enddate'=>array(
					'type' => 'zii.widgets.jui.CJuiDatePicker',
					'attributes' => array(
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							'altField' => '#self_pointing_id2',
							'altFormat'=>_xls_convert_date_to_js(_xls_get_conf('DATE_FORMAT','Y-m-d')),
						),
						'htmlOptions' => array( // A list of HTML attributes associated to the date picker input element.
							'style' => 'height: 20px;'
						),
					),
				),
				'promocode'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'qty_remaining'=>array(
					'type'=>'text',
					'maxlength'=>64,
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