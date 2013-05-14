<?php

/**
 * This is the base model class for table "{{sro_repair}}".
 *
 * The followings are the available columns in table '{{sro_repair}}':
 * @property string $id
 * @property string $sro_id
 * @property string $family
 * @property string $description
 * @property string $purchase_date
 * @property string $serial_number
 * @property string $datetime_cre
 * @property string $datetime_mod
 *
 * The followings are the available model relations:
 * @property Sro $sro
 *
 * @package application.models.base
 * @name BaseSroRepair
 */
abstract class BaseSroRepair extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sro_repair}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sro_id, datetime_mod', 'required'),
			array('sro_id', 'length', 'max'=>20),
			array('family, description, serial_number', 'length', 'max'=>255),
			array('purchase_date', 'length', 'max'=>32),
			array('datetime_cre', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sro_id, family, description, purchase_date, serial_number, datetime_cre, datetime_mod', 'safe', 'on'=>'search'),
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
			'sro' => array(self::BELONGS_TO, 'Sro', 'sro_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sro_id' => 'Sro',
			'family' => 'Family',
			'description' => 'Description',
			'purchase_date' => 'Purchase Date',
			'serial_number' => 'Serial Number',
			'datetime_cre' => 'Datetime Cre',
			'datetime_mod' => 'Datetime Mod',
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
		$criteria->compare('sro_id',$this->sro_id,true);
		$criteria->compare('family',$this->family,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('purchase_date',$this->purchase_date,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('datetime_cre',$this->datetime_cre,true);
		$criteria->compare('datetime_mod',$this->datetime_mod,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}