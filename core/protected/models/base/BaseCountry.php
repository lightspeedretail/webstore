<?php

/**
 * This is the base model class for table "{{country}}".
 *
 * The followings are the available columns in table '{{country}}':
 * @property string $id
 * @property string $code
 * @property string $region
 * @property string $active
 * @property integer $sort_order
 * @property string $country
 * @property string $zip_validate_preg
 *
 * The followings are the available model relations:
 * @property CustomerAddress[] $customerAddresses
 * @property Destination[] $destinations
 * @property State[] $states
 *
 * @package application.models.base
 * @name BaseCountry
 */
abstract class BaseCountry extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{country}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, region, country', 'required'),
			array('sort_order', 'numerical', 'integerOnly'=>true),
			array('code, region', 'length', 'max'=>2),
			array('active', 'length', 'max'=>11),
			array('country, zip_validate_preg', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, region, active, sort_order, country, zip_validate_preg', 'safe', 'on'=>'search'),
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
			'customerAddresses' => array(self::HAS_MANY, 'CustomerAddress', 'country_id'),
			'destinations' => array(self::HAS_MANY, 'Destination', 'country'),
			'states' => array(self::HAS_MANY, 'State', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'region' => 'Region',
			'active' => 'Active',
			'sort_order' => 'Sort Order',
			'country' => 'Country',
			'zip_validate_preg' => 'Zip Validate Preg',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('zip_validate_preg',$this->zip_validate_preg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}