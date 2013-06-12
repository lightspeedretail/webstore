<?php

/**
 * This is the base model class for table "{{state}}".
 *
 * The followings are the available columns in table '{{state}}':
 * @property string $id
 * @property string $country_id
 * @property string $country_code
 * @property string $code
 * @property string $active
 * @property integer $sort_order
 * @property string $state
 *
 * The followings are the available model relations:
 * @property CustomerAddress[] $customerAddresses
 * @property Destination[] $destinations
 * @property Country $country
 *
 * @package application.models.base
 * @name BaseState
 */
abstract class BaseState extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{state}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_code, code, state', 'required'),
			array('sort_order', 'numerical', 'integerOnly'=>true),
			array('country_id', 'length', 'max'=>10),
			array('country_code', 'length', 'max'=>2),
			array('code', 'length', 'max'=>32),
			array('active', 'length', 'max'=>11),
			array('state', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country_id, country_code, code, active, sort_order, state', 'safe', 'on'=>'search'),
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
			'customerAddresses' => array(self::HAS_MANY, 'CustomerAddress', 'state_id'),
			'destinations' => array(self::HAS_MANY, 'Destination', 'state'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'country_id' => 'Country',
			'country_code' => 'Country Code',
			'code' => 'Code',
			'active' => 'Active',
			'sort_order' => 'Sort Order',
			'state' => 'State',
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
		$criteria->compare('country_id',$this->country_id,true);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}