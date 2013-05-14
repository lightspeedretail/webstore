<?php

/**
 * This is the model class for table "{{email_queue}}".
 *
 * @package application.models
 * @name EmailQueue
 *
 */
class EmailQueue extends BaseEmailQueue
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmailQueue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord) {
			$this->datetime_cre = new CDbExpression('NOW()');
			$this->sent_attempts = 0;
		}


		//ToDo: if tax id has not been specified, get global and place it in
		return parent::beforeValidate();
	}

}