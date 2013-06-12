<?php

/**
 * This is the base model class for table "{{category_google}}".
 *
 * The followings are the available columns in table '{{category_google}}':
 * @property string $id
 * @property string $name0
 * @property string $name1
 * @property string $name2
 * @property string $name3
 * @property string $name4
 * @property string $name5
 * @property string $name6
 * @property string $name7
 * @property string $name8
 * @property string $name9
 *
 * @package application.models.base
 * @name BaseCategoryGoogle
 */
abstract class BaseCategoryGoogle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{category_google}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name0, name1, name2, name3, name4, name5, name6, name7, name8, name9', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name0, name1, name2, name3, name4, name5, name6, name7, name8, name9', 'safe', 'on'=>'search'),
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
			'name0' => 'Name0',
			'name1' => 'Name1',
			'name2' => 'Name2',
			'name3' => 'Name3',
			'name4' => 'Name4',
			'name5' => 'Name5',
			'name6' => 'Name6',
			'name7' => 'Name7',
			'name8' => 'Name8',
			'name9' => 'Name9',
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
		$criteria->compare('name0',$this->name0,true);
		$criteria->compare('name1',$this->name1,true);
		$criteria->compare('name2',$this->name2,true);
		$criteria->compare('name3',$this->name3,true);
		$criteria->compare('name4',$this->name4,true);
		$criteria->compare('name5',$this->name5,true);
		$criteria->compare('name6',$this->name6,true);
		$criteria->compare('name7',$this->name7,true);
		$criteria->compare('name8',$this->name8,true);
		$criteria->compare('name9',$this->name9,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}