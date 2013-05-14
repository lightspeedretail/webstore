<?php

/**
 * This is the base model class for table "{{sro_item}}".
 *
 * The followings are the available columns in table '{{sro_item}}':
 * @property string $id
 * @property string $sro_id
 * @property integer $cart_type
 * @property string $product_id
 * @property string $code
 * @property string $description
 * @property string $discount
 * @property double $qty
 * @property double $sell
 * @property double $sell_base
 * @property double $sell_discount
 * @property double $sell_total
 * @property string $serial_numbers
 * @property string $datetime_added
 * @property string $datetime_mod
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Sro $sro
 *
 * @package application.models.base
 * @name BaseSroItem
 */
abstract class BaseSroItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sro_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sro_id, product_id, code, description, qty, sell, sell_base, sell_discount, sell_total, datetime_added, datetime_mod', 'required'),
			array('cart_type', 'numerical', 'integerOnly'=>true),
			array('qty, sell, sell_base, sell_discount, sell_total', 'numerical'),
			array('sro_id, product_id', 'length', 'max'=>20),
			array('code, description, serial_numbers', 'length', 'max'=>255),
			array('discount', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sro_id, cart_type, product_id, code, description, discount, qty, sell, sell_base, sell_discount, sell_total, serial_numbers, datetime_added, datetime_mod', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'sro' => array(self::BELONGS_TO, 'Sro', 'sro_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sro_id' => 'Sro',
			'cart_type' => 'Cart Type',
			'product_id' => 'Product',
			'code' => 'Code',
			'description' => 'Description',
			'discount' => 'Discount',
			'qty' => 'Qty',
			'sell' => 'Sell',
			'sell_base' => 'Sell Base',
			'sell_discount' => 'Sell Discount',
			'sell_total' => 'Sell Total',
			'serial_numbers' => 'Serial Numbers',
			'datetime_added' => 'Datetime Added',
			'datetime_mod' => 'Datetime Mod',
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
		$criteria->compare('sro_id',$this->sro_id,true);
		$criteria->compare('cart_type',$this->cart_type);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('discount',$this->discount,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('sell',$this->sell);
		$criteria->compare('sell_base',$this->sell_base);
		$criteria->compare('sell_discount',$this->sell_discount);
		$criteria->compare('sell_total',$this->sell_total);
		$criteria->compare('serial_numbers',$this->serial_numbers,true);
		$criteria->compare('datetime_added',$this->datetime_added,true);
		$criteria->compare('datetime_mod',$this->datetime_mod,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}