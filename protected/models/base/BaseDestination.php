<?php

/**
 * This is the base model class for table "{{destination}}".
 *
 * The followings are the available columns in table '{{destination}}':
 * @property string $id
 * @property string $country
 * @property string $state
 * @property string $zipcode1
 * @property string $zipcode2
 * @property string $taxcode
 * @property string $label
 * @property double $base_charge
 * @property double $ship_free
 * @property double $ship_rate
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property State $state0
 * @property Country $country0
 * @property TaxCode $taxcode0
 *
 * @package application.models.base
 * @name BaseDestination
 */
abstract class BaseDestination extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{destination}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified', 'required'),
			array('base_charge, ship_free, ship_rate', 'numerical'),
			array('country, state, taxcode', 'length', 'max'=>11),
			array('zipcode1, zipcode2', 'length', 'max'=>10),
			array('label', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country, state, zipcode1, zipcode2, taxcode, label, base_charge, ship_free, ship_rate, modified', 'safe', 'on'=>'search'),
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
			'state0' => array(self::BELONGS_TO, 'State', 'state'),
			'country0' => array(self::BELONGS_TO, 'Country', 'country'),
			'taxcode0' => array(self::BELONGS_TO, 'TaxCode', 'taxcode'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'country' => 'Country',
			'state' => 'State',
			'zipcode1' => 'Zipcode1',
			'zipcode2' => 'Zipcode2',
			'taxcode' => 'Taxcode',
			'label' => 'Label',
			'base_charge' => 'Base Charge',
			'ship_free' => 'Ship Free',
			'ship_rate' => 'Ship Rate',
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
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zipcode1',$this->zipcode1,true);
		$criteria->compare('zipcode2',$this->zipcode2,true);
		$criteria->compare('taxcode',$this->taxcode,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('base_charge',$this->base_charge);
		$criteria->compare('ship_free',$this->ship_free);
		$criteria->compare('ship_rate',$this->ship_rate);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}