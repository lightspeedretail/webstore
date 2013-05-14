<?php

/**
 * This is the base model class for table "{{credit_card}}".
 *
 * The followings are the available columns in table '{{credit_card}}':
 * @property string $id
 * @property string $label
 * @property string $numeric_length
 * @property string $prefix
 * @property integer $sort_order
 * @property integer $enabled
 * @property string $validfunc
 * @property string $modified
 *
 * @package application.models.base
 * @name BaseCreditCard
 */
abstract class BaseCreditCard extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{credit_card}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prefix, enabled, modified', 'required'),
			array('sort_order, enabled', 'numerical', 'integerOnly'=>true),
			array('label, validfunc', 'length', 'max'=>32),
			array('numeric_length', 'length', 'max'=>16),
			array('prefix', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, numeric_length, prefix, sort_order, enabled, validfunc, modified', 'safe', 'on'=>'search'),
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
			'label' => 'Label',
			'numeric_length' => 'Numeric Length',
			'prefix' => 'Prefix',
			'sort_order' => 'Sort Order',
			'enabled' => 'Enabled',
			'validfunc' => 'Validfunc',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('numeric_length',$this->numeric_length,true);
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('validfunc',$this->validfunc,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}