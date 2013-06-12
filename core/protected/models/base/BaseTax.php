<?php

/**
 * This is the base model class for table "{{tax}}".
 *
 * The followings are the available columns in table '{{tax}}':
 * @property string $id
 * @property string $lsid
 * @property string $tax
 * @property double $max_tax
 * @property integer $compounded
 *
 * @package application.models.base
 * @name BaseTax
 */
abstract class BaseTax extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tax}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lsid', 'required'),
			array('compounded', 'numerical', 'integerOnly'=>true),
			array('max_tax', 'numerical'),
			array('lsid', 'length', 'max'=>11),
			array('tax', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lsid, tax, max_tax, compounded', 'safe', 'on'=>'search'),
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
			'lsid' => 'Lsid',
			'tax' => 'Tax',
			'max_tax' => 'Max Tax',
			'compounded' => 'Compounded',
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
		$criteria->compare('lsid',$this->lsid,true);
		$criteria->compare('tax',$this->tax,true);
		$criteria->compare('max_tax',$this->max_tax);
		$criteria->compare('compounded',$this->compounded);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}