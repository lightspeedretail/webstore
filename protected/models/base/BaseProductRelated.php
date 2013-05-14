<?php

/**
 * This is the base model class for table "{{product_related}}".
 *
 * The followings are the available columns in table '{{product_related}}':
 * @property string $id
 * @property string $product_id
 * @property string $related_id
 * @property integer $autoadd
 * @property double $qty
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Product $related
 *
 * @package application.models.base
 * @name BaseProductRelated
 */
abstract class BaseProductRelated extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_related}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, related_id', 'required'),
			array('autoadd', 'numerical', 'integerOnly'=>true),
			array('qty', 'numerical'),
			array('product_id, related_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, related_id, autoadd, qty', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'related' => array(self::BELONGS_TO, 'Product', 'related_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'related_id' => 'Related',
			'autoadd' => 'Autoadd',
			'qty' => 'Qty',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('related_id',$this->related_id,true);
		$criteria->compare('autoadd',$this->autoadd);
		$criteria->compare('qty',$this->qty);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}