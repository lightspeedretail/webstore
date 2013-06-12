<?php

/**
 * This is the base model class for table "{{cart_shipping}}".
 *
 * The followings are the available columns in table '{{cart_shipping}}':
 * @property string $id
 * @property string $shipping_method
 * @property string $shipping_module
 * @property string $shipping_data
 * @property double $shipping_cost
 * @property double $shipping_sell
 * @property string $tracking_number
 *
 * The followings are the available model relations:
 * @property Cart[] $carts
 *
 * @package application.models.base
 * @name BaseCartShipping
 */
abstract class BaseCartShipping extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cart_shipping}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipping_cost, shipping_sell', 'numerical'),
			array('shipping_method, shipping_data, tracking_number', 'length', 'max'=>255),
			array('shipping_module', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shipping_method, shipping_module, shipping_data, shipping_cost, shipping_sell, tracking_number', 'safe', 'on'=>'search'),
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
			'carts' => array(self::HAS_MANY, 'Cart', 'shipping_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shipping_method' => 'Shipping Method',
			'shipping_module' => 'Shipping Module',
			'shipping_data' => 'Shipping Data',
			'shipping_cost' => 'Shipping Cost',
			'shipping_sell' => 'Shipping Sell',
			'tracking_number' => 'Tracking Number',
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
		$criteria->compare('shipping_method',$this->shipping_method,true);
		$criteria->compare('shipping_module',$this->shipping_module,true);
		$criteria->compare('shipping_data',$this->shipping_data,true);
		$criteria->compare('shipping_cost',$this->shipping_cost);
		$criteria->compare('shipping_sell',$this->shipping_sell);
		$criteria->compare('tracking_number',$this->tracking_number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}