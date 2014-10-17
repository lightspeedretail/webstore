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


	public function markCompleted()
	{
		if($this->payment_amount>0 && is_null($this->payment_status))
		{
			$this->payment_status = OrderStatus::Completed;
			$this->save();
		}
	}

	public function beforeValidate()
	{
		//WS-2344 non-cash payment amount must be equal to the cart total
		if ($this->carts && $this->scenario === 'manual')
		{
			//When payment information is manually entered in the admin panel.
			//make sure the payment amount is equal to the cart total unless it's cash payment.
			if ( $this->payment_module !== 'cashondelivery' && $this->payment_amount !== $this->carts[0]->total)
			{
				$this->addError('payment_amount', "The payment amount must be equal to the cart total in non-cash payments.");
			}
		}

		return parent::beforeValidate();
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