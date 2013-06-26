<?php

/**
 * This is the model class for table "{{credit_card}}".
 *
 * @package application.models
 * @name CreditCard
 *
 */
class CreditCard extends BaseCreditCard
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CreditCard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



}