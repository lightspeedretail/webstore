<?php

/**
 * This is the model class for table "{{wishlist}}".
 *
 * @package application.models
 * @name Wishlist
 *
 */
class Wishlist extends BaseWishlist
{
	const PUBLICLIST = 2; //anyone can search by email
	const PERSONALLIST = 1; //anyone can view but must have direct link
	const PRIVATELIST = 0; //only creator can view

	const LEAVEINLIST = 1;
	const DELETEFROMLIST = 2;

	public $deleteMe;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Wishlist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Override from base class
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'registry_name' => 'Name your Wish List',
			'registry_description' => 'Description (Optional)',
			'visibility' => 'Visibility',
			'event_date' => 'Event Date (Optional)',
			'html_content' => 'Html Content',
			'ship_option' => 'Ship Option',
			'customer_id' => 'Customer',
			'gift_code' => 'Gift Code',
			'created' => 'Created',
			'modified' => 'Modified',
			'after_purchase' => 'After purchase',
			'deleteMe' => 'Check to DELETE this Wish List on next Submit',
		);
	}

	/**
	 * @param string $attribute
	 * @return string
	 */
	public function getAttributeLabel($attribute)
	{
		$baseLabel = parent::getAttributeLabel($attribute);
		return Yii::t(get_class($this), $baseLabel);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registry_name,visibility,ship_option,after_purchase', 'required'),
			array('visibility', 'numerical', 'integerOnly'=>true),
			array('registry_name, ship_option, gift_code', 'length', 'max'=>100),
			array('customer_id', 'length', 'max'=>20),
			array('registry_description,event_date,deleteMe', 'safe'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('registry_name',$this->registry_name,false);
		$criteria->compare('event_date',$this->event_date,false);
		$criteria->compare('customer_id',$this->customer_id,false);
		$criteria->compare('gift_code',$this->gift_code,false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getDataItems()
	{
		$objItems = new WishlistItem();
		$objItems->registry_id = $this->id;

		return $objItems->editSearch();



	}

	public static function LoadUserLists()
	{
		if (Yii::app()->user->isGuest)
			return array();

		return Wishlist::model()->findAllByAttributes(array('customer_id'=>Yii::app()->user->id));



	}

	public static function LoadFirstCode()
	{
		$objLists = Wishlist::LoadUserLists();
		if (count($objLists)==0) return null;
		else return $objLists[0]->gift_code;
	}
	/**
	 * Determine whether the current user should be able to see this wish list
	 * @return bool
	 */
	protected function getVisible()
	{
		if ($this->visibility == Wishlist::PRIVATELIST && $this->customer_id != Yii::app()->user->id)
			return false;
		else return true;
	}

	/**
	 * Determine whether the list is sharable
	 * @return bool
	 */
	protected function getSharable()
	{
		if ($this->visibility == Wishlist::PRIVATELIST)
			return false;
		else return true;
	}

	/**
	 * Determine whether the list is sharable
	 * @return bool
	 */
	protected function getIsMine()
	{
		if ($this->customer_id == Yii::app()->user->id)
			return true;
		else return false;
	}

	/**
	 * @return array
	 */
	public function getVisibilities()
	{

		return array(
			Wishlist::PUBLICLIST=> Yii::t('wishlist','Public, searchable by my email address'),
			Wishlist::PERSONALLIST=> Yii::t('wishlist','Personal, shared only by a special URL'),
			Wishlist::PRIVATELIST=> Yii::t('wishlist','Private, only viewable with my login'),
		);

	}
	/**
	 * @return array
	 */
	public function getShipOptions()
	{

		$arrReturn = array(
			'0'=> Yii::t('wishlist','None')
		);

		$objAddresses = CustomerAddress::model()->findAllByAttributes(
			array('customer_id'=>Yii::app()->user->id,
				  'active'=>1));
		foreach ($objAddresses as $objAddress)
			$arrReturn[$objAddress->id] =
				$objAddress->fullname.", ".
				$objAddress->address1.($objAddress->address2 != '' ? " " : "").$objAddress->address2.", ".
				$objAddress->city." ".$objAddress->state." ".$objAddress->postal;

		return $arrReturn;
	}
	/**
	 * @return array
	 */
	public function getAfterPurchase()
	{

		return array(
			Wishlist::LEAVEINLIST=> Yii::t('wishlist','Leave the item in the Wish List, marked as Purchased'),
			Wishlist::DELETEFROMLIST=> Yii::t('wishlist','Delete the item automatically from Wish List'),
		);

	}


	/**
	 * Because visitor gift purchases are on a time limit to complete, remove any pending incomplete purchases
	 */
	public static function GarbageCollect() {

		$intResetHours = _xls_get_conf('RESET_GIFT_REGISTRY_PURCHASE_STATUS',6);
		if ($intResetHours<1) $intResetHours=1; //cannot set to 0
		$cutoffDate = date('YmdHis', strtotime("-".$intResetHours." hours"));


		$arrProducts=Yii::app()->db->createCommand(
			'SELECT * FROM '.Cart::model()->tableName().' WHERE cart_type='.CartType::cart.' ORDER BY id DESC'
		)->query();

		while(($arrItem=$arrProducts->read())!==false)
		{
			$objCart = Cart::model()->findByPk($arrItem['id']);
			//Go through outstanding carts and see if we have any expiring gift items
			foreach($objCart->cartItems as $item)
			{
				if (!is_null($item->wishlist_item))
				{
					$cartCustomerId  = is_null($objCart->customer_id) ? 0 : $objCart->customer_id;
					$wishCustomerId = $item->wishlistItem->registry->customer_id;

					if ($cartCustomerId != $wishCustomerId) //Wish list item was purchased by a visitor
					{
						if (date('YmdHis', strtotime($item->datetime_mod) < $cutoffDate))
						{
							$objMessage = new CartMessages();
							$objMessage->cart_id = $objCart->id;
							$objMessage->message =
								Yii::t('cart','You attempted to purchase the product {product} as a gift from the wish list for {wishname}. However, because wish list gift purchases must be completed within {hours} hour(s), the item has been removed from your cart. If it is still available on the wish list, it can be re-added.',
								array('{wishname}'=>$item->wishlistItem->registry->customer->fullname,
										'{product}'=>$item->product->Title,
										'{hours}'=>$intResetHours));
							$objMessage->save();

							$item->wishlist_item = null;
							$item->save();
							$item->wishlistItem->cart_item_id = null;
							$item->wishlistItem->save();
							$item->delete();

							$objCart->refresh();
							$objCart->UpdateCountAndSubtotal();
							$objCart->UpdateTotal();
							$objCart->save();
						}



					}

				}
			}


		}

	}
	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		if($this->event_date=='') $this->event_date = null;

		return parent::beforeValidate();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Visible':
				return $this->getVisible();

			case 'Sharable':
				return $this->getSharable();

			case 'IsMine':
				return $this->getIsMine();

			default:
				try {
					return (parent::__get($strName));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}


}