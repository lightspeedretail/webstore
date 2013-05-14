<?php

/**
 * This is the base model class for table "{{promo_code}}".
 *
 * The followings are the available columns in table '{{promo_code}}':
 * @property string $id
 * @property integer $enabled
 * @property integer $exception
 * @property string $code
 * @property integer $type
 * @property double $amount
 * @property string $valid_from
 * @property integer $qty_remaining
 * @property string $valid_until
 * @property string $lscodes
 * @property double $threshold
 * @property string $module
 *
 * @package application.models.base
 * @name BasePromoCode
 */
abstract class BasePromoCode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{promo_code}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amount', 'required'),
			array('enabled, exception, type, qty_remaining', 'numerical', 'integerOnly'=>true),
			array('amount, threshold', 'numerical'),
			array('code, module', 'length', 'max'=>255),
			array('valid_from, valid_until, lscodes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, enabled, exception, code, type, amount, valid_from, qty_remaining, valid_until, lscodes, threshold, module', 'safe', 'on'=>'search'),
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
			'enabled' => 'Enabled',
			'exception' => 'Exception',
			'code' => 'Code',
			'type' => 'Type',
			'amount' => 'Amount',
			'valid_from' => 'Valid From',
			'qty_remaining' => 'Qty Remaining',
			'valid_until' => 'Valid Until',
			'lscodes' => 'Lscodes',
			'threshold' => 'Threshold',
			'module' => 'Module',
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
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('exception',$this->exception);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('valid_from',$this->valid_from,true);
		$criteria->compare('qty_remaining',$this->qty_remaining);
		$criteria->compare('valid_until',$this->valid_until,true);
		$criteria->compare('lscodes',$this->lscodes,true);
		$criteria->compare('threshold',$this->threshold);
		$criteria->compare('module',$this->module,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}