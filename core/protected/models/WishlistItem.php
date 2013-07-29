<?php

/**
 * This is the model class for table "{{wishlist_item}}".
 *
 * @package application.models
 * @name WishlistItem
 *
 */
class WishlistItem extends BaseWishlistItem
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return WishlistItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	const NOT_PURCHASED = 0;
	const PURCHASED_BY_CURRENT_GUEST = 2;
	const PURCHASED_BY_ANOTHER_GUEST = 4;
	const INCART_BY_CURRENT_GUEST = 8;
	const INCART_BY_ANOTHER_GUEST = 16;
	const MULTIPLE_ITEMS_REMAIN = 24;
	const ALL_QTY_PURCHASED = 32;

	protected $intPurchaseCount = 0;
	protected $intAddedCount = 0;



	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function editSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,false);
		$criteria->compare('registry_id',$this->registry_id,false);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



	public function getPriority()
	{
		$form = new WishlistEditForm();
		$arrPriorities = $form->getPriorities();
		if (!isset($this->priority)) {
			$this->priority=1;
			$this->save();
		}
		return $arrPriorities[$this->priority];
	}

	public function getPurchaseStatusLabel()
	{

		if (is_null($this->cart_item_id) && !$this->product->web)
				return Yii::t('wishlist','Item no longer for sale');
		elseif (is_null($this->cart_item_id) && (!$this->product->IsDisplayable || !$this->product->IsAddable))
				return Yii::t('wishlist','Item Unavailable');
		elseif (is_null($this->cart_item_id))
				return Yii::t('wishlist','Not purchased');
		elseif ($this->cartItem->cart->cart_type == CartType::cart)
			return Yii::t('wishlist','Being Purchased');
		elseif ($this->purchased_by == Yii::app()->user->id)
			return Yii::t('wishlist','Purchased on {date}',
				array('{date}'=>date(_xls_get_conf('DATE_FORMAT','Y-m-d'),strtotime($this->cartItem->datetime_added))));
		else
			return Yii::t('wishlist','Gifted on {date}',
				array('{date}'=>date(_xls_get_conf('DATE_FORMAT','Y-m-d'),strtotime($this->cartItem->datetime_added))));


	}


	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord) {
			$this->created = new CDbExpression('NOW()');
			$this->priority = 1;
		}
		$this->modified = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}

	// Override or Create New Properties and Variables
	// For performance reasons, these variables and __set and __get override methods
	// are commented out.  But if you wish to implement or override any
	// of the data generated properties, please feel free to uncomment them.
	protected $strSomeNewProperty;

	public function __get($strName) {
		switch ($strName) {
			case 'PurchasedQty':
				return $this->intPurchaseCount;
			case 'AddedQty':
				return $this->intAddedCount;

			case 'PurchaseStatus':
				return $this->getPurchaseStatusLabel();

			case 'Priority':
				return $this->getPriority();

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

}