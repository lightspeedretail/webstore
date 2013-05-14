<?php

/**
 * This is the base model class for table "{{email_queue}}".
 *
 * The followings are the available columns in table '{{email_queue}}':
 * @property string $id
 * @property integer $sent_attempts
 * @property string $customer_id
 * @property string $cart_id
 * @property string $to
 * @property string $subject
 * @property string $plainbody
 * @property string $htmlbody
 * @property string $datetime_cre
 *
 * The followings are the available model relations:
 * @property Customer $customer
 * @property Cart $cart
 *
 * @package application.models.base
 * @name BaseEmailQueue
 */
abstract class BaseEmailQueue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{email_queue}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sent_attempts', 'numerical', 'integerOnly'=>true),
			array('customer_id, cart_id', 'length', 'max'=>20),
			array('subject', 'length', 'max'=>255),
			array('to, plainbody, htmlbody, datetime_cre', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sent_attempts, customer_id, cart_id, to, subject, plainbody, htmlbody, datetime_cre', 'safe', 'on'=>'search'),
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
			'sent_attempts' => 'Sent Attempts',
			'customer_id' => 'Customer',
			'cart_id' => 'Cart',
			'to' => 'To',
			'subject' => 'Subject',
			'plainbody' => 'Plainbody',
			'htmlbody' => 'Htmlbody',
			'datetime_cre' => 'Datetime Cre',
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
		$criteria->compare('sent_attempts',$this->sent_attempts);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('cart_id',$this->cart_id,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('plainbody',$this->plainbody,true);
		$criteria->compare('htmlbody',$this->htmlbody,true);
		$criteria->compare('datetime_cre',$this->datetime_cre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}