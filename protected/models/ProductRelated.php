<?php

/**
 * This is the model class for table "{{product_related}}".
 *
 * @package application.models
 * @name ProductRelated
 *
 */
class ProductRelated extends BaseProductRelated
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductRelated the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Load a Related record with a given product and related product id. Used only by Soap uploader since we
	 * have normal ORM relations.
	 * @param $intProductId
	 * @param $intRelatedId
	 * @return CActiveRecord
	 */
	public static function LoadByProductIdRelatedId($intProductId , $intRelatedId)
	{

		return ProductRelated::model()->findByAttributes(array('product_id'=>$intProductId,'related_id'=>$intRelatedId));


	}
}