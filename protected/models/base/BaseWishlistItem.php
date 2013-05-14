<?php

/**
 * This is the base model class for table "{{wishlist_item}}".
 *
 * The followings are the available columns in table '{{wishlist_item}}':
 * @property string $id
 * @property string $registry_id
 * @property string $product_id
 * @property double $qty
 * @property integer $qty_received
 * @property integer $priority
 * @property string $comment
 * @property integer $qty_received_manual
 * @property string $cart_item_id
 * @property string $purchased_by
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property CartItem[] $cartItems
 * @property Wishlist $registry
 * @property Product $product
 * @property CartItem $cartItem
 * @property Customer $purchasedBy
 *
 * @package application.models.base
 * @name BaseWishlistItem
 */
abstract class BaseWishlistItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wishlist_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registry_id, product_id, created, modified', 'required'),
			array('qty_received, priority, qty_received_manual', 'numerical', 'integerOnly'=>true),
			array('qty', 'numerical'),
			array('registry_id, product_id, cart_item_id, purchased_by', 'length', 'max'=>20),
			array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registry_id, product_id, qty, qty_received, priority, comment, qty_received_manual, cart_item_id, purchased_by, created, modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'cartItems' => array(self::HAS_MANY, 'CartItem', 'wishlist_item'),
			'registry' => array(self::BELONGS_TO, 'Wishlist', 'registry_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'cartItem' => array(self::BELONGS_TO, 'CartItem', 'cart_item_id'),
			'purchasedBy' => array(self::BELONGS_TO, 'Customer', 'purchased_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'registry_id' => 'Registry',
			'product_id' => 'Product',
			'qty' => 'Qty',
			'qty_received' => 'Qty Received',
			'priority' => 'Priority',
			'comment' => 'Comment',
			'qty_received_manual' => 'Qty Received Manual',
			'cart_item_id' => 'Cart Item',
			'purchased_by' => 'Purchased By',
			'created' => 'Created',
			'modified' => 'Modified',
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
		$criteria->compare('registry_id',$this->registry_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('qty_received',$this->qty_received);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('qty_received_manual',$this->qty_received_manual);
		$criteria->compare('cart_item_id',$this->cart_item_id,true);
		$criteria->compare('purchased_by',$this->purchased_by,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}