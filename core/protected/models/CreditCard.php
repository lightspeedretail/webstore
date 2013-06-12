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
}