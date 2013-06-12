<?php

/**
 * This is the model class for table "{{cart_payment}}".
 *
 * @package application.models
 * @name CartPayment
 *
 */
class CartPayment extends BaseCartPayment
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CartPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array_merge(array(
			array('payment_module, payment_data, payment_amount', 'required', 'on'=>'manual'),
			array('payment_amount', 'safe'),
		),parent::rules());
	}

	public function __get($strName) {
		switch ($strName) {
			case 'payment_name':
				$c = Yii::app()->getComponent($this->payment_module);
				if ($c)
					return  $c->Name;
				else
					return "";
				break;


			default:
				return parent::__get($strName);
		}
	}
}