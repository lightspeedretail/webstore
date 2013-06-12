<?php

/**
 * This is the model class for table "{{category_amazon}}".
 *
 * @package application.models
 * @name CategoryAmazon
 *
 */
class CategoryAmazon extends BaseCategoryAmazon
{


	/**
	 * Returns the static model of the specified AR class.
	 * @return CategoryAmazon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getInstructions()
	{
		return "Note: Computers and Cameras/Photo are under Electronics.";
	}


	/**
	 * Checks to see if we can pick this option for Amazon. A few options aren't allowed, you must
	 * continue to drill down in the list. Any usable item should have an item_keyword_type set
	 */
	public function getIsUsable()
	{
		preg_match("/item_type_keyword:(.*)/", $this->item_type, $matches);

		if (isset($matches[1]))
			return true;

		return false;

	}

}