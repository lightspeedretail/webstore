<?php

/**
 * This is the base model class for table "{{transaction_log}}".
 *
 * The followings are the available columns in table '{{transaction_log}}':
 * @property string $id
 * @property string $cart_id
 * @property string $logline
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Cart $cart
 *
 * @package application.models.base
 * @name BaseTransactionLog
 */
abstract class BaseTransactionLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{transaction_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created', 'required'),
			array('cart_id', 'length', 'max'=>20),
			array('logline', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cart_id, logline, created', 'safe', 'on'=>'search'),
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
			'cart' => array(self::BELONGS_TO, 'Cart', 'cart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cart_id' => 'Cart',
			'logline' => 'Logline',
			'created' => 'Created',
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
		$criteria->compare('cart_id',$this->cart_id,true);
		$criteria->compare('logline',$this->logline,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}