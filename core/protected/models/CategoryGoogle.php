<?php

/**
 * This is the model class for table "{{category_google}}".
 *
 * @package application.models
 * @name CategoryGoogle
 *
 */
class CategoryGoogle extends BaseCategoryGoogle
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return CategoryGoogle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getInstructions()
	{
		return "Apparel requires additional dropdowns which appear at the bottom.";
	}

	/**
	 * Return the Google Category record based on the complete name (path)
	 * @param $strParam1
	 * @return CActiveRecord
	 */
	public static function LoadByName($strParam1) {
		// This will return a single GoogleCategories object
		return CategoryGoogle::model()->findByAttributes(array('name0'=>$strParam1));

	}


	/**
	 * Checks to see if we can pick this option. Google doesn't have any restrictions
	 * so this is always true
	 */
	public function getIsUsable()
	{
		return true;

	}
}