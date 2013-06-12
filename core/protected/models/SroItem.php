<?php

/**
 * This is the model class for table "{{sro_item}}".
 *
 * @package application.models
 * @name SroItem
 *
 */
class SroItem extends BaseSroItem
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SroItem the static model class
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
			$this->datetime_added = new CDbExpression('NOW()');
		$this->datetime_mod = new CDbExpression('NOW()');


		return parent::beforeValidate();
	}
}