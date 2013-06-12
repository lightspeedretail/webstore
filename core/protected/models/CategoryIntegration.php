<?php

/**
 * This is the model class for table "{{category_integration}}".
 *
 * @package application.models
 * @name CategoryIntegration
 *
 */
class CategoryIntegration extends BaseCategoryIntegration
{
	public $integrated;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CategoryIntegration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}    
}