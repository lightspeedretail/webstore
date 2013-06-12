<?php

/**
 * This is the base model class for table "{{sro}}".
 *
 * The followings are the available columns in table '{{sro}}':
 * @property string $id
 * @property string $ls_id
 * @property string $customer_id
 * @property string $customer_name
 * @property string $customer_email_phone
 * @property string $zipcode
 * @property string $problem_description
 * @property string $printed_notes
 * @property string $work_performed
 * @property string $additional_items
 * @property string $warranty
 * @property string $warranty_info
 * @property string $status
 * @property string $linkid
 * @property string $datetime_cre
 * @property string $datetime_mod
 *
 * The followings are the available model relations:
 * @property Customer $customer
 * @property SroItem[] $sroItems
 * @property SroRepair[] $sroRepairs
 *
 * @package application.models.base
 * @name BaseSro
 */
abstract class BaseSro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sro}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_email_phone', 'required'),
			array('ls_id, customer_id', 'length', 'max'=>20),
			array('customer_name, customer_email_phone', 'length', 'max'=>255),
			array('zipcode', 'length', 'max'=>10),
			array('status', 'length', 'max'=>32),
			array('linkid', 'length', 'max'=>64),
			array('problem_description, printed_notes, work_performed, additional_items, warranty, warranty_info, datetime_cre, datetime_mod', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ls_id, customer_id, customer_name, customer_email_phone, zipcode, problem_description, printed_notes, work_performed, additional_items, warranty, warranty_info, status, linkid, datetime_cre, datetime_mod', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'sroItems' => array(self::HAS_MANY, 'SroItem', 'sro_id'),
			'sroRepairs' => array(self::HAS_MANY, 'SroRepair', 'sro_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ls_id' => 'Ls',
			'customer_id' => 'Customer',
			'customer_name' => 'Customer Name',
			'customer_email_phone' => 'Customer Email Phone',
			'zipcode' => 'Zipcode',
			'problem_description' => 'Problem Description',
			'printed_notes' => 'Printed Notes',
			'work_performed' => 'Work Performed',
			'additional_items' => 'Additional Items',
			'warranty' => 'Warranty',
			'warranty_info' => 'Warranty Info',
			'status' => 'Status',
			'linkid' => 'Linkid',
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
		$criteria->compare('ls_id',$this->ls_id,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_email_phone',$this->customer_email_phone,true);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('problem_description',$this->problem_description,true);
		$criteria->compare('printed_notes',$this->printed_notes,true);
		$criteria->compare('work_performed',$this->work_performed,true);
		$criteria->compare('additional_items',$this->additional_items,true);
		$criteria->compare('warranty',$this->warranty,true);
		$criteria->compare('warranty_info',$this->warranty_info,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('linkid',$this->linkid,true);
		$criteria->compare('datetime_cre',$this->datetime_cre,true);
		$criteria->compare('datetime_mod',$this->datetime_mod,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}