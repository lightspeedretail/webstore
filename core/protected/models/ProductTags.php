<?php

/**
 * This is the model class for table "{{product_tags}}".
 *
 * @package application.models
 * @name ProductTags
 *
 */
class ProductTags extends BaseProductTags
{

	public function primaryKey()
	{
		return "tag_id";
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductTags the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function DeleteProductTags($intProductId)
	{

		ProductTags::model()->deleteAll("product_id = ".$intProductId);


	}


}