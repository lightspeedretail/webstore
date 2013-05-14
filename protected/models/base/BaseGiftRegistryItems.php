<?php

/**
 * This is the base model class for table "{{gift_registry_items}}".
 *
 * The followings are the available columns in table '{{gift_registry_items}}':
 * @property string $id
 * @property integer $registry_id
 * @property string $product_id
 * @property double $qty
 * @property string $registry_status
 * @property string $purchase_status
 * @property string $purchased_by
 * @property string $created
 * @property string $modified
 *
 * @package application.models.base
 * @name BaseGiftRegistryItems
 */
abstract class BaseGiftRegistryItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gift_registry_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registry_id, product_id, created, modified', 'required'),
			array('registry_id', 'numerical', 'integerOnly'=>true),
			array('qty', 'numerical'),
			array('product_id, purchase_status', 'length', 'max'=>20),
			array('registry_status', 'length', 'max'=>50),
			array('purchased_by', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registry_id, product_id, qty, registry_status, purchase_status, purchased_by, created, modified', 'safe', 'on'=>'search'),
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
			'registry_id' => 'Registry',
			'product_id' => 'Product',
			'qty' => 'Qty',
			'registry_status' => 'Registry Status',
			'purchase_status' => 'Purchase Status',
			'purchased_by' => 'Purchased By',
			'created' => 'Created',
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
		$criteria->compare('registry_id',$this->registry_id);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('registry_status',$this->registry_status,true);
		$criteria->compare('purchase_status',$this->purchase_status,true);
		$criteria->compare('purchased_by',$this->purchased_by,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}