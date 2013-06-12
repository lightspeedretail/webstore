<?php

/**
 * CheckoutForm class.
 * CheckoutForm is the data structure for keeping
 * checkout form data. It is used by the 'checkout' action of 'CartController'.
 */
class BaseAdvancedSearchForm extends CFormModel
{

	public $q;
	public $startprice;
	public $endprice;
	public $cat;
	public $product_size;
	public $product_color;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('q', 'required'),
			array('q,startprice,endprice,cat,product_size,product_color', 'safe'),
			// rememberMe needs to be a boolean
			array('startprice,endprice,cat', 'numerical'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'q'=>Yii::t('global','Search Term'),
			'startprice'=>Yii::t('global','Start Price'),
			'endprice'=>Yii::t('global','End Price'),
			'cat'=>Yii::t('global','Restrict by category'),
		);
	}



}