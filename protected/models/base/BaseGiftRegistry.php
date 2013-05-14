<?php

/**
 * This is the base model class for table "{{gift_registry}}".
 *
 * The followings are the available columns in table '{{gift_registry}}':
 * @property string $id
 * @property string $registry_name
 * @property string $registry_password
 * @property string $registry_description
 * @property string $event_date
 * @property string $html_content
 * @property string $ship_option
 * @property integer $customer_id
 * @property string $gift_code
 * @property string $created
 * @property string $modified
 *
 * @package application.models.base
 * @name BaseGiftRegistry
 */
abstract class BaseGiftRegistry extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gift_registry}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registry_name, registry_password, event_date, html_content, customer_id, gift_code, created, modified', 'required'),
			array('customer_id', 'numerical', 'integerOnly'=>true),
			array('registry_name, registry_password, ship_option, gift_code', 'length', 'max'=>100),
			array('registry_description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registry_name, registry_password, registry_description, event_date, html_content, ship_option, customer_id, gift_code, created, modified', 'safe', 'on'=>'search'),
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
			'registry_name' => 'Registry Name',
			'registry_password' => 'Registry Password',
			'registry_description' => 'Registry Description',
			'event_date' => 'Event Date',
			'html_content' => 'Html Content',
			'ship_option' => 'Ship Option',
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
		$criteria->compare('registry_password',$this->registry_password,true);
		$criteria->compare('registry_description',$this->registry_description,true);
		$criteria->compare('event_date',$this->event_date,true);
		$criteria->compare('html_content',$this->html_content,true);
		$criteria->compare('ship_option',$this->ship_option,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('gift_code',$this->gift_code,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}