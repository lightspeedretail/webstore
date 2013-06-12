<?php

/**
 * Custom form for extra fields for a payment model. This is identical to any other
 * model with the addition of a getSubform() function which Web Store will call to get
 * the form definition.
 * See www.yiiframework.com/doc/guide/1.1/en/form.builder#creating-a-simple-form for help
 * on the expected array format.
 */
class purchaseorderform extends CFormModel
{
	public $po;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('po', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'po'=>Yii::t('CheckoutForm','Purchase Order'),
		);
	}


	public function getSubform()
	{
		return array(
			'title'=>Yii::t(get_class($this),'Enter PO Number'),

			'elements'=>array(
				'po'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
			),
		);
	}

}
