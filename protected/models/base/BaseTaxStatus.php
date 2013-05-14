<?php

/**
 * This is the base model class for table "{{tax_status}}".
 *
 * The followings are the available columns in table '{{tax_status}}':
 * @property string $id
 * @property string $lsid
 * @property string $status
 * @property integer $tax1_status
 * @property integer $tax2_status
 * @property integer $tax3_status
 * @property integer $tax4_status
 * @property integer $tax5_status
 *
 * The followings are the available model relations:
 * @property Product[] $products
 *
 * @package application.models.base
 * @name BaseTaxStatus
 */
abstract class BaseTaxStatus extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tax_status}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lsid, status', 'required'),
			array('tax1_status, tax2_status, tax3_status, tax4_status, tax5_status', 'numerical', 'integerOnly'=>true),
			array('lsid', 'length', 'max'=>11),
			array('status', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lsid, status, tax1_status, tax2_status, tax3_status, tax4_status, tax5_status', 'safe', 'on'=>'search'),
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
			'products' => array(self::HAS_MANY, 'Product', 'tax_status_id'),
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
			'status' => 'Status',
			'tax1_status' => 'Tax1 Status',
			'tax2_status' => 'Tax2 Status',
			'tax3_status' => 'Tax3 Status',
			'tax4_status' => 'Tax4 Status',
			'tax5_status' => 'Tax5 Status',
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
		$criteria->compare('status',$this->status,true);
		$criteria->compare('tax1_status',$this->tax1_status);
		$criteria->compare('tax2_status',$this->tax2_status);
		$criteria->compare('tax3_status',$this->tax3_status);
		$criteria->compare('tax4_status',$this->tax4_status);
		$criteria->compare('tax5_status',$this->tax5_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}