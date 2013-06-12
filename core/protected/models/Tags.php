<?php

/**
 * This is the model class for table "{{tags}}".
 *
 * @package application.models
 * @name Tags
 *
 */
class Tags extends BaseTags
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tags the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}    
}