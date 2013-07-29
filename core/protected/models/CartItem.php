<?php

/**
 * This is the model class for table "{{cart_item}}".
 *
 * @package application.models
 * @name CartItem
 *
 */
class CartItem extends BaseCartItem
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CartItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	// String representation of the object
	public function __toString() {
		return sprintf('CartItem Object %s',  $this->id." ".$this->code);
	}


	public function getDiscounted() {
		if ($this->discount > 0)
			return true;
		return false;
	}

	public function GetPriceField() {
		if ($this->getDiscounted())
			return 'sell_discount';
		return 'sell';

	}

	protected function GetPriceValue() {
		$strPriceField = $this->GetPriceField();
		return $this->$strPriceField;
	}


	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		$this->datetime_mod = new CDbExpression('NOW()');

		if($this->isNewRecord) {
			if (is_null($this->discount))
				$this->discount = 0;
			if (is_null($this->sell_discount))
				$this->sell_discount = 0;
			if (is_null($this->sell) && !is_null($this->sell_base))
				$this->sell = $this->sell_base;
			if (is_null($this->sell))
				$this->sell = 0;
			if (is_null($this->sell_total))
				$this->sell_total = $this->sell_total = 0;
		}


		return parent::beforeValidate();
	}

	/** Recalculate item prices before saving
	 * @return boolean from parent
	 */
	protected function beforeSave() {
		if ($this->discount>0) {
			$this->sell_discount = $this->sell - $this->discount;
			$this->sell_total = $this->sell_discount*$this->qty;
		}
		else {
			$this->sell_discount=0;
			$this->sell_total = $this->sell*$this->qty;
		}


		return parent::beforeValidate();
	}

	/** If we had any valiation errors in saving, log them
	 * @return boolean from parent
	 */
	protected function afterValidate() {
		$arrErrors = $this->GetErrors();
		if (!empty($arrErrors))
			Yii::log(print_r($arrErrors,true), 'error', __class__);
		return parent::afterValidate();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Prod':
//				//QApplication::Log(E_USER_NOTICE, 'legacy', $strName);
//				if(!$this->objProduct)
//					$this->objProduct = Product::model()->findByPk($this->product_id);
				return $this->product;

			case 'Price':
				return $this->GetPriceValue();

			case 'link':
			case 'Link':
				return $this->product->link;

//			case 'MiniImage':
//				return $this->product->MiniImage;


			//Boolean to check to see if an item's tax has already been removed from tax inclusive pricing
			case 'blnWebTaxRemoved':
				return $this->tax_in;

			default:
				return parent::__get($strName);
		}
	}

	public function __set($strName, $mixValue) {
		$mixReturn = '';

		try {
			switch ($strName) {

				case 'blnWebTaxRemoved':
					$this->tax_in = $mixValue;
					break;

				case 'Discount':
					$mixValue = round($mixValue, 2);
					parent::__set('discount', $mixValue);
					$this->sell_discount = $this->sell - $mixValue;
					break;

				case 'Qty':
					parent::__set($strName, $mixValue);
					$this->discount = 0;
					if ($this->product)
						$this->sell = $this->product->GetPrice($mixValue);
					break;

				case 'Sell':
				case 'SellDiscount':
					$mixValue = round($mixValue, 2);
					parent::__set($strName, $mixValue);
					$this->sell_total = $this->GetPriceValue() * $this->qty;
					break;

				default:
					parent::__set($strName, $mixValue);
					break;
			}
		}
		catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		return $mixReturn;
	}
}