<?php

/**
 * This is the model class for table "{{product_text}}".
 *
 * @package application.models
 * @name ProductText
 *
 */
class ProductText extends BaseProductText
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductText the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}    
}