<?php

/**
 * This is the model class for table "{{transaction_log}}".
 *
 * @package application.models
 * @name TransactionLog
 *
 */
class TransactionLog extends BaseTransactionLog
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TransactionLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}    
}