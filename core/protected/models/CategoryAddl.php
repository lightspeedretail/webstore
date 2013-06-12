<?php

/**
 * This is the model class for table "{{category_addl}}".
 *
 * @package application.models
 * @name CategoryAddl
 *
 */
class CategoryAddl extends BaseCategoryAddl
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CategoryAddl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function LoadByNameParent($strName, $intParentId) {

		return CategoryAddl::model()->findByAttributes(array('name'=>$strName,'parent',$intParentId));

	}
}