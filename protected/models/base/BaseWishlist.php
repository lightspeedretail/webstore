<?php

/**
 * This is the base model class for table "{{wishlist}}".
 *
 * The followings are the available columns in table '{{wishlist}}':
 * @property string $id
 * @property string $registry_name
 * @property string $registry_description
 * @property integer $visibility
 * @property string $event_date
 * @property string $html_content
 * @property string $ship_option
 * @property integer $after_purchase
 * @property string $customer_id
 * @property string $gift_code
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Customer $customer
 * @property WishlistItem[] $wishlistItems
 *
 * @package application.models.base
 * @name BaseWishlist
 */
abstract class BaseWishlist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wishlist}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registry_name, html_content, after_purchase, customer_id, gift_code, created, modified', 'required'),
			array('visibility, after_purchase', 'numerical', 'integerOnly'=>true),
			array('registry_name, ship_option, gift_code', 'length', 'max'=>100),
			array('customer_id', 'length', 'max'=>20),
			array('registry_description, event_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registry_name, registry_description, visibility, event_date, html_content, ship_option, after_purchase, customer_id, gift_code, created, modified', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'wishlistItems' => array(self::HAS_MANY, 'WishlistItem', 'registry_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'registry_name' => 'Registry Name',
			'registry_description' => 'Registry Description',
			'visibility' => 'Visibility',
			'event_date' => 'Event Date',
			'html_content' => 'Html Content',
			'ship_option' => 'Ship Option',
			'after_purchase' => 'After Purchase',
			'customer_id' => 'Customer',
			'gift_code' => 'Gift Code',
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
		$criteria->compare('registry_name',$this->registry_name,true);
		$criteria->compare('registry_description',$this->registry_description,true);
		$criteria->compare('visibility',$this->visibility);
		$criteria->compare('event_date',$this->event_date,true);
		$criteria->compare('html_content',$this->html_content,true);
		$criteria->compare('ship_option',$this->ship_option,true);
		$criteria->compare('after_purchase',$this->after_purchase);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('gift_code',$this->gift_code,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}