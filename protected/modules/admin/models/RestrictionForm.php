<?php

/**
 * RestrictionForm class.
 * For setting promo code restrictions
 */
class RestrictionForm extends CFormModel
{
	public $id;

	public $promocode;
	public $exception;
	public $categories;
	public $families;
	public $classes;
	public $keywords;
	public $codes;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('id,categories,families,classes,keywords,codes','safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'categories'=>'Categories',
			'families'=>'Families',
			'classes'=>'Classes',
			'keywords'=>'Keywords',
			'codes'=>'Product Codes',
		);
	}


	public function getExceptionList()
	{

		return array(
			0=>'products match any of the following criteria',
			1=>'products match anything BUT the following criteria'
		);

	}



}
