<?php

/**
 * This is the model class for table "{{cart_shipping}}".
 *
 * @package application.models
 * @name CartShipping
 *
 */
class CartShipping extends BaseCartShipping
{
	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className
	 * @return CActiveRecord the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * Return the shipping price.
	 * The only scenario where the shipping price with tax is returned is
	 * in Tax-inclusive environments (taxable shipping must also be enabled).
	 * In non tax-inclusive environments where taxable shipping is enabled,
	 * the shipping taxes are added to the overall cart taxes, so we still
	 * return the shipping price without tax.
	 *
	 * @return float
	 */
	public function getShippingSell()
	{
		if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == 1 && Yii::app()->params['SHIPPING_TAXABLE'] == 1)
		{
			return $this->shipping_sell_taxed;
		}

		return $this->shipping_sell;
	}

	public function getIsStorePickup()
	{
		if (isset($this->shipping_module))
		{
			return Yii::app()->getComponent($this->shipping_module)->IsStorePickup;
		}

		return false;
	}

	/**
	 * Update the cart shipping (xlsws_cart_shipping) based on selected
	 * shipping scenario from the session. Before calling this make sure that
	 * the shipping scenarios in the session are up to date and the
	 * checkoutForm in the session uses the desired providerId and
	 * priorityLabel.
	 *
	 * If no cart shipping already exists, one will be created.
	 * If an error occurs it will be added to $this->errors in the Yii's model error format.
	 * @See CModel::getErrors().
	 *
	 * @return bool true if the shipping was updated, false otherwise.
	 */
	public function updateShipping()
	{
		$selectedCartScenario = Shipping::getSelectedCartScenarioFromSession();
		if ($selectedCartScenario === null)
		{
			Yii::log('Cannot update shipping, no scenario selected', 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		Yii::log("Shipping Product " . $selectedCartScenario['shippingProduct'], 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		// Populate the shipping object with default data.
		$this->shipping_method = $selectedCartScenario['shippingProduct'];
		$this->shipping_module = $selectedCartScenario['module'];
		$this->shipping_data = $selectedCartScenario['shippingLabel'];
		$this->shipping_cost = $selectedCartScenario['shippingPrice'];
		$this->shipping_sell = $selectedCartScenario['shippingPrice'];
		$this->shipping_sell_taxed = $selectedCartScenario['shippingPriceWithTax'];
		$this->shipping_taxable = Yii::app()->params['SHIPPING_TAXABLE'] == '1' ? 1 : 0;

		if ($this->save() === false)
		{
			Yii::log(
				"Error saving Cart Shipping:\n" . print_r($this->getErrors()),
				'error',
				'application.'.__CLASS__.'.'.__FUNCTION__.'.'.__LINE__
			);

			return false;
		}

		Yii::app()->shoppingcart->shipping_id = $this->id;
		Yii::app()->shoppingcart->recalculateAndSave();
		return true;
	}

	/**
	 * Either saves or return a CartShipping model based
	 * on the user's shopping cart.
	 *
	 * @return CartShipping|null
	 */
	public static function getOrCreateCartShipping()
	{
		$objShipping = null;
		// If we have a shipping object already, update it, otherwise create it.
		if (Yii::app()->shoppingcart->shipping_id !== null)
		{
			$objShipping = CartShipping::model()->findByPk(Yii::app()->shoppingcart->shipping_id);
		}
		else
		{
			$objShipping = new CartShipping();
			if ($objShipping->save() === false)
			{
				Yii::log(
					"Error saving Cart Shipping:\n" . print_r($objShipping->getErrors(), true),
					'error',
					'application.'.__CLASS__.'.'.__FUNCTION__.'.'.__LINE__
				);
			}
		}

		return $objShipping;
	}

}
