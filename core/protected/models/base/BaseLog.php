<?php

/**
 * This is the base model class for table "{{log}}".
 *
 * The followings are the available columns in table '{{log}}':
 * @property integer $id
 * @property string $level
 * @property string $category
 * @property string $created
 * @property string $message
 * @property integer $logtime
 *
 * @package application.models.base
 * @name BaseLog
 */
abstract class BaseLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('logtime', 'numerical', 'integerOnly'=>true),
			array('level, category', 'length', 'max'=>128),
			array('created, message', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, level, category, created, message, logtime', 'safe', 'on'=>'search'),
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
			'level' => 'Level',
			'category' => 'Category',
			'created' => 'Created',
			'message' => 'Message',
			'logtime' => 'Logtime',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('logtime',$this->logtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}