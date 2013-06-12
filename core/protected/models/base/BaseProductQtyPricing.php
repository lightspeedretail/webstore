<?php

/**
 * This is the base model class for table "{{product_qty_pricing}}".
 *
 * The followings are the available columns in table '{{product_qty_pricing}}':
 * @property string $id
 * @property string $product_id
 * @property string $pricing_level
 * @property double $qty
 * @property double $price
 *
 * The followings are the available model relations:
 * @property PricingLevels $pricingLevel
 * @property Product $product
 *
 * @package application.models.base
 * @name BaseProductQtyPricing
 */
abstract class BaseProductQtyPricing extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_qty_pricing}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id', 'required'),
			array('qty, price', 'numerical'),
			array('product_id', 'length', 'max'=>20),
			array('pricing_level', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, pricing_level, qty, price', 'safe', 'on'=>'search'),
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
			'pricingLevel' => array(self::BELONGS_TO, 'PricingLevels', 'pricing_level'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'pricing_level' => 'Pricing Level',
			'qty' => 'Qty',
			'price' => 'Price',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('pricing_level',$this->pricing_level,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('price',$this->price);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}