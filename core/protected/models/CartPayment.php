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
	public static function model($className = __CLASS__)
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
			array('payment_module, payment_data, payment_amount', 'required', 'on' => 'manual'),
			array('payment_amount', 'safe'),
		), parent::rules());
	}


	public function markCompleted()
	{
		if($this->payment_amount > 0 && is_null($this->payment_status))
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
			if ($this->payment_module !== 'cashondelivery' && $this->payment_amount !== $this->carts[0]->total)
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
				{
					return  $c->Name;
				}
				else
				{
					return "";
				}
				break;

			case 'instructions':
				if (Yii::app()->getComponent($this->payment_module)->advancedMode)
				{
					return null;
				}
				elseif (Yii::app()->getComponent($this->payment_module)->uses_credit_card && $this->payment_module !== 'paypal')
					return "<strong>You'll pay on the next page.</strong><br>These details will be forwarded to our secure payment processor: ";
				elseif ($this->payment_module === 'paypal')
					return "You'll pay on the next page.";
				else
				{
					$module = Modules::LoadByName($this->payment_module);

					return $module->getConfig('customeralert');
				}

			default:
				return parent::__get($strName);
		}
	}

	/**
	 * If a Cart Payment exists for the shopping cart it is returned
	 * Otherwise we create one for the shopping cart.
	 *
	 * @return bool|CartPayment
	 */
	public static function getOrCreateCartPayment()
	{
		$objPayment = null;
		if (is_null(Yii::app()->shoppingcart->payment_id) === false)
		{
			$objPayment = CartPayment::model()->findByPk(Yii::app()->shoppingcart->payment_id);
		}
		else
		{
			$objPayment = new CartPayment();

			if ($objPayment->save() === false)
			{
				Yii::log("Error saving payment:\n" . print_r($objPayment->getErrors(), true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
				return false;
			}
		}

		return $objPayment;
	}

	/**
	 * Updates the customer's shopping cart with the selected payment
	 *
	 * @param MultiCheckoutForm $checkoutForm
	 * @param array $subForm
	 * if the option had a separate form to fill in, this is the array
	 * of fields and corresponding user input
	 * ex. array('po' => '12345') where 'po' is the field
	 * and '12345' is the user input
	 *
	 * @return bool
	 * false if something went wrong updating
	 * true if the update was possible
	 */
	public function updateCartPayment($checkoutForm, $subForm = null)
	{
		$objPaymentModule = Modules::model()->findByPk($checkoutForm->paymentProvider);

		$this->payment_module = $objPaymentModule->module;
		$this->payment_method = $objPaymentModule->payment_method;

		if (is_null($subForm) === false && is_array($subForm))
		{
			// erase any old data first
			$this->payment_data = null;

			foreach ($subForm as $data)
			{
				$this->payment_data .= $data . "\n";
			}
		}

		// prevent an error with card_digits which expects 4 characters exactly or null
		if (Yii::app()->getComponent($this->payment_module)->advancedMode)
		{
			$this->payment_card = strtolower($checkoutForm->cardType);
			$this->card_digits = $checkoutForm->cardNumberLast4;
		}
		else
		{
			// in the rare case someone enters credit card details, and
			// then goes back and chooses a SIM method, remove irrelevant
			// values from the cart payment
			$this->payment_card = null;
			$this->card_digits = null;
		}

		if ($this->save() === false)
		{
			Yii::log("Error saving payment:\n" . print_r($this->getErrors(), true), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return false;
		}

		Yii::app()->shoppingcart->payment_id = $this->id;
		if (Yii::app()->shoppingcart->save() === false)
		{
			Yii::log("Error saving Cart:\n" . print_r(Yii::app()->shoppingcart->getErrors()), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			// Special case where we use this models error handler to pass in the one of the shopping cart
			$this->addErrors(Yii::app()->shoppingcart->getErrors());
			return false;
		}

		return true;
	}

	/**
	 * Did the payment have a subform?
	 *
	 * @return bool
	 */
	public function hasSubForm()
	{
		$form = MultiCheckoutForm::loadFromSessionOrNew();
		$objModule = Modules::LoadByName($this->payment_module);

		return array_key_exists($objModule->id, $form->getAlternativePaymentMethodsThatUseSubForms());
	}
}
