<?php

/**
 * This is the base model class for table "{{gift_registry_receipents}}".
 *
 * The followings are the available columns in table '{{gift_registry_receipents}}':
 * @property string $id
 * @property integer $registry_id
 * @property integer $customer_id
 * @property string $receipent_name
 * @property string $receipent_email
 * @property integer $email_sent
 * @property string $created
 * @property string $modified
 *
 * @package application.models.base
 * @name BaseGiftRegistryReceipents
 */
abstract class BaseGiftRegistryReceipents extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gift_registry_receipents}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registry_id, receipent_name, receipent_email, created, modified', 'required'),
			array('registry_id, customer_id, email_sent', 'numerical', 'integerOnly'=>true),
			array('receipent_name, receipent_email', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registry_id, customer_id, receipent_name, receipent_email, email_sent, created, modified', 'safe', 'on'=>'search'),
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
			'registry_id' => 'Registry',
			'customer_id' => 'Customer',
			'receipent_name' => 'Receipent Name',
			'receipent_email' => 'Receipent Email',
			'email_sent' => 'Email Sent',
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
		$criteria->compare('registry_id',$this->registry_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('receipent_name',$this->receipent_name,true);
		$criteria->compare('receipent_email',$this->receipent_email,true);
		$criteria->compare('email_sent',$this->email_sent);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}