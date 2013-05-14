<?php

/**
 * This is the base model class for table "{{shipping_tiers}}".
 *
 * The followings are the available columns in table '{{shipping_tiers}}':
 * @property string $id
 * @property double $start_price
 * @property double $end_price
 * @property double $rate
 * @property string $class_name
 *
 * @package application.models.base
 * @name BaseShippingTiers
 */
abstract class BaseShippingTiers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shipping_tiers}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_price, end_price, rate', 'numerical'),
			array('class_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, start_price, end_price, rate, class_name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_price' => 'Start Price',
			'end_price' => 'End Price',
			'rate' => 'Rate',
			'class_name' => 'Class Name',
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
		$criteria->compare('start_price',$this->start_price);
		$criteria->compare('end_price',$this->end_price);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('class_name',$this->class_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}