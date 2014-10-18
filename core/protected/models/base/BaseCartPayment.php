<?php

/**
 * This is the base model class for table "{{cart_payment}}".
 *
 * The followings are the available columns in table '{{cart_payment}}':
 * @property string $id
 * @property string $payment_method
 * @property string $payment_module
 * @property string $payment_data
 * @property string $payment_card
 * @property double $payment_amount
 * @property string $payment_status
 * @property string $datetime_posted
 * @property string $card_digits
 * @property string $promocode
 *
 * The followings are the available model relations:
 * @property Cart[] $carts
 *
 * @package application.models.base
 * @name BaseCartPayment
 */
abstract class BaseCartPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cart_payment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('payment_amount', 'numerical'),
			array('payment_method, payment_data, promocode', 'length', 'max' => 255),
			array('payment_card', 'length', 'max' => 50),
			array('payment_module', 'length', 'max' => 64),
			array('payment_status', 'length', 'max' => 100),
			array('datetime_posted', 'safe'),
			array('card_digits', 'length', 'is' => 4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, payment_method, payment_module, payment_data, payment_card, payment_amount, payment_status, datetime_posted, card_digits, promocode', 'safe', 'on' => 'search'),
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
			'carts' => array(self::HAS_MANY, 'Cart', 'payment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'payment_method' => 'Payment Method',
			'payment_module' => 'Payment Module',
			'payment_data' => 'Payment Data',
			'payment_card' => 'Card Type',
			'payment_amount' => 'Payment Amount',
			'payment_status' => 'Payment Status',
			'datetime_posted' => 'Datetime Posted',
			'card_digits' => 'Card Last 4 Digits',
			'promocode' => 'Promocode',
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
		$criteria->compare('payment_method',$this->payment_method,true);
		$criteria->compare('payment_module',$this->payment_module,true);
		$criteria->compare('payment_data',$this->payment_data,true);
		$criteria->compare('payment_card',$this->payment_card,true);
		$criteria->compare('payment_amount',$this->payment_amount);
		$criteria->compare('payment_status',$this->payment_status,true);
		$criteria->compare('datetime_posted',$this->datetime_posted,true);
		$criteria->compare('card_digits',$this->card_digits,true);
		$criteria->compare('promocode',$this->promocode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}