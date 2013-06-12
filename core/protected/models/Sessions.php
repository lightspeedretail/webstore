<?php

/**
 * This is the model class for table "{{sessions}}".
 *
 * @package application.models
 * @name Sessions
 *
 */
class Sessions extends BaseSessions
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Sessions the static model class
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
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}

}