<?php

/**
 * This is the model class for table "{{shipping_tiers}}".
 *
 * @package application.models
 * @name ShippingTiers
 *
 */
class ShippingTiers extends BaseShippingTiers
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShippingTiers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_price' => 'Start Amount',
			'end_price' => 'End Amount',
			'rate' => 'Rate',
			'class_name' => 'Class Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAll()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'start_price ASC',
			),
			'pagination' => array(
				'pageSize' => 15,
			),
		));


	}
}