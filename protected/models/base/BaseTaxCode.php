<?php

/**
 * This is the base model class for table "{{tax_code}}".
 *
 * The followings are the available columns in table '{{tax_code}}':
 * @property string $id
 * @property string $lsid
 * @property string $code
 * @property integer $list_order
 * @property double $tax1_rate
 * @property double $tax2_rate
 * @property double $tax3_rate
 * @property double $tax4_rate
 * @property double $tax5_rate
 *
 * The followings are the available model relations:
 * @property Cart[] $carts
 * @property Destination[] $destinations
 *
 * @package application.models.base
 * @name BaseTaxCode
 */
abstract class BaseTaxCode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tax_code}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lsid, code', 'required'),
			array('list_order', 'numerical', 'integerOnly'=>true),
			array('tax1_rate, tax2_rate, tax3_rate, tax4_rate, tax5_rate', 'numerical'),
			array('lsid', 'length', 'max'=>11),
			array('code', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lsid, code, list_order, tax1_rate, tax2_rate, tax3_rate, tax4_rate, tax5_rate', 'safe', 'on'=>'search'),
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
			'carts' => array(self::HAS_MANY, 'Cart', 'tax_code_id'),
			'destinations' => array(self::HAS_MANY, 'Destination', 'taxcode'),
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
			'code' => 'Code',
			'list_order' => 'List Order',
			'tax1_rate' => 'Tax1 Rate',
			'tax2_rate' => 'Tax2 Rate',
			'tax3_rate' => 'Tax3 Rate',
			'tax4_rate' => 'Tax4 Rate',
			'tax5_rate' => 'Tax5 Rate',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('list_order',$this->list_order);
		$criteria->compare('tax1_rate',$this->tax1_rate);
		$criteria->compare('tax2_rate',$this->tax2_rate);
		$criteria->compare('tax3_rate',$this->tax3_rate);
		$criteria->compare('tax4_rate',$this->tax4_rate);
		$criteria->compare('tax5_rate',$this->tax5_rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}