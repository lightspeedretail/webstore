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

	public static function sendCategoriesUpOneLevel($categoryId){
		// Find row with categoryId
		$parentRecord = CategoryAddl::model()->findByPk($categoryId);

		// Get the category's parent column,
		// in case the category to delete has a parent
		$parentRecordParentId = $parentRecord->parent;

		$attributes = array('parent' => $parentRecordParentId);
		$condition = 'parent = :categoryId';
		$params = array(':categoryId' => $categoryId);

		CategoryAddl::model()->updateAll(
			$attributes,
			$condition,
			$params
		);
	}

}