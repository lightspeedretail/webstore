<?php

/**
 * LookupForm class.
 * LookupForm is the data structure for the order lookup sidebar module.
 * It is used by the 'index' action of 'DefaultController'.
 */
class LookupForm extends CFormModel
{
	public $orderId;
	public $emailPhone;
	public $orderType;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('orderId, emailPhone','required'),
			array('emailPhone','email'),
			array('orderId, emailPhone','customValidation'),
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
			'orderId'=>'Order/Repair ID',
			'emailPhone'=>'Customer Email',

		);
	}




	public function customValidation($attribute,$params)
	{

		if ($this->emailPhone != '' && $this->orderId != '')
		{
			$intCustomer = null;
			$objCustomer = null;
			$objCart = null;

			$found = 0;

			//Is this a customer email address, or possible an email attached to an SRO
			if (strtolower(substr($this->orderId,0,2))=="s-") //SRO lookup
			{
				$objSro = Sro::model()->findByAttributes(array('customer_email_phone'=>$this->emailPhone,'ls_id'=>$this->orderId));
				if ((!$objSro instanceof SRO))
				{
					$this->addError($this->orderId,Yii::t('yii','Order/Email combination not found'));
					Yii::app()->clientScript->registerScript('orderalert', 'alert("Order/Email combination not found");');
				} else $this->orderType = CartType::sro;


			}
			else
			{ //Regular Order
				$objCustomer = Customer::LoadByEmail($this->emailPhone);
				if(!($objCustomer instanceof Customer))
				{
						$this->addError($this->emailPhone,Yii::t('yii','Email address not found'));
						Yii::app()->clientScript->registerScript('emailalert', 'alert("Email address not found");');
				}
				else
				{
					$objCart = Cart::model()->findByAttributes(array('id_str'=>$this->orderId,'customer_id'=>$objCustomer->id));

					if(!($objCart instanceof Cart))
					{

						$this->addError($this->orderId,Yii::t('yii','Order/Email combination not found'));
						Yii::app()->clientScript->registerScript('orderalert', 'alert("Order/Email combination not found");');


					} else $this->orderType = CartType::order;
				}
			}


		}
	}

}