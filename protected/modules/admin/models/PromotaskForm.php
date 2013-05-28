<?php

/**
 * PromotaskForm class.
 * For running promo code
 */
class PromotaskForm extends CFormModel
{
	public $createCodes;
	public $existingCodes;

	public $deleteUsed;
	public $deleteExpired;
	public $deleteSingleUse;
	public $deleteEverything;



	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('createCodes,existingCodes','safe'),
			array('createCodes,existingCodes','required','on'=>'copy'),
			array('deleteUsed,deleteExpired,deleteSingleUse,deleteEverything','safe'),
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
			'createCodes'=>'Enter code(s)',
			'existingCodes'=>'Copy settings from code',
			'deleteEverything'=>'Delete All Promo Codes',

		);
	}

	public function getFormCreate()
	{
		return array(

			'title'=>'This feature is for creating one-time use promo codes in bulk for loyalty programs. Paste in your list of codes into the entry blank below, and select an existing promo code to use as a template. The following fields will be copied from this code: Amount, Percent or Money, optional Start and Stop date, Product restrictions and Good Above. Codes created with this process can be used once, then are rendered invalid.',

			'elements'=>array(
				'createCodes'=>array(
					'type'=>'textarea',
					'layout'=>'<div class="span4">{label}</div><div class="span4">{input}</div>{error}',
				),
				'existingCodes'=>array(
					'type'=>'dropdownlist',
					'items'=>CHtml::listData(PromoCode::model()->findAll(array(
						'condition'=>'enabled=:status AND code IS NOT NULL AND module IS NULL',
						'params'=>array(':status'=>1),
						'order'=>'code'
						)),'id','code'),
					'prompt'=>'Please select:',
					'layout'=>'<div class="span4">{label}</div><div class="span4">{input}</div>{error}',
				),
			),
		);
	}

	public function getFormTasks()
	{
		return array(

			'title'=>'This feature is for creating one-time use promo codes in bulk for loyalty programs. Pate in your list of codes into the entry blank below. Select an existing promo code to use as a template. The following fields will be copied from this code: Amount, Percent or Money, optional Start and Stop date, Product restrictions and Good Above. Codes created with this process can be used once then are rendered invalid.',

			'elements'=>array(
				'deleteUsed'=>array(
					'type'=>'text',
					'layout'=>'<div class="span4">{label}</div>',
				),
				'deleteExpired'=>array(
					'type'=>'text',
					'layout'=>'<div class="span4">{label}</div>',
				),
			),

			'buttons'=>array(
				'deleteSingleUse'=>array(
					'type'=>'submit',
					'label'=>'Delete Single Use',
				),
				'deleteEverything'=>array(
					'type'=>'submit',
					'label'=>'Delete Everything',
					'layout'=>'<div class="span4">{input}</div>',
				),
			),


		);
	}

}

