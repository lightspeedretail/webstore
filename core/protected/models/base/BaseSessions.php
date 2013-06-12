<?php

/**
 * This is the base model class for table "{{sessions}}".
 *
 * The followings are the available columns in table '{{sessions}}':
 * @property string $id
 * @property integer $expire
 * @property string $created
 * @property string $modified
 * @property string $data
 *
 * @package application.models.base
 * @name BaseSessions
 */
abstract class BaseSessions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sessions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, created', 'required'),
			array('expire', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>32),
			array('modified, data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, expire, created, modified, data', 'safe', 'on'=>'search'),
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
			'expire' => 'Expire',
			'created' => 'Created',
			'modified' => 'Modified',
			'data' => 'Data',
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
		$criteria->compare('expire',$this->expire);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}