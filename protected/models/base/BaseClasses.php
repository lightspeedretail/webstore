<?php

/**
 * This is the base model class for table "{{classes}}".
 *
 * The followings are the available columns in table '{{classes}}':
 * @property string $id
 * @property string $class_name
 * @property integer $child_count
 * @property string $request_url
 *
 * The followings are the available model relations:
 * @property Product[] $products
 *
 * @package application.models.base
 * @name BaseClasses
 */
abstract class BaseClasses extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{classes}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('child_count', 'numerical', 'integerOnly'=>true),
			array('class_name, request_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, class_name, child_count, request_url', 'safe', 'on'=>'search'),
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
			'products' => array(self::HAS_MANY, 'Product', 'class_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'class_name' => 'Class Name',
			'child_count' => 'Child Count',
			'request_url' => 'Request Url',
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
		$criteria->compare('class_name',$this->class_name,true);
		$criteria->compare('child_count',$this->child_count);
		$criteria->compare('request_url',$this->request_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}