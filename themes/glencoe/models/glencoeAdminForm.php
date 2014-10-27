<?php

class glencoeAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Glencoe";
	protected $thumbnail = "glencoe.png";
	protected $version = 5;
	protected $viewset = "cities2";
	protected $description = "Simple yet Sleek.";
	protected $credit = "Designed by Lightspeed";
	protected $bootstrap = "bootstrap3";
	protected $cssfiles = "style,glencoe";

	protected $CATEGORY_IMAGE_HEIGHT = 511;
	protected $CATEGORY_IMAGE_WIDTH = 511;
	protected $DETAIL_IMAGE_HEIGHT = 256;
	protected $DETAIL_IMAGE_WIDTH = 256;
	protected $LISTING_IMAGE_HEIGHT = 360;
	protected $LISTING_IMAGE_WIDTH = 360;
	protected $MINI_IMAGE_HEIGHT = 100;
	protected $MINI_IMAGE_WIDTH = 100;
	protected $PREVIEW_IMAGE_HEIGHT = 120;
	protected $PREVIEW_IMAGE_WIDTH = 120;
	protected $SLIDER_IMAGE_HEIGHT = 256;
	protected $SLIDER_IMAGE_WIDTH = 256;

	/*
	 * Define any keys here that should be available for the theme
	 * These can be accessed via Yii::app()->theme->config->keyname
	 *
	 * for example: echo Yii::app()->theme->config->CHILD_THEME
	 *
	 * The values specified here are defaults for your theme
	 *
	 * keys that are in ALL CAPS are written as xlsws_configuration keys as well for
	 * backwards compatibility.
	 *
	 * If you wish to have values that are part of config, but not available to the user (i.e. hardcoded values),
	 * you can add them to this as well. Anything "public" will be saved as part of config, but only
	 * items that are listed in the getAdminForm() function below are available to the user to change
	 *
	 */


	public $PRODUCTS_PER_PAGE = 12;

	public $disableGridRowDivs = true;

	public $animateAddToCart=false;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('CHILD_THEME','safe'),
			array('animateAddToCart','safe'),
		);
	}


	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'CHILD_THEME'=>ucfirst(_xls_regionalize('color')).' set',
			'menuposition'=>'Products menu position',
			'animateAddToCart'=>'Add To Cart animation',
		);
	}

	/*
	 * Form definition here
	 *
	 * See http://www.yiiframework.com/doc/guide/1.1/en/form.builder#creating-a-simple-form
	 * for additional information
	 */
	public function getAdminForm()
	{

		return array(
			//'title' => 'Set your funky options for this theme!',

			'elements'=>array(
//				'CHILD_THEME'=>array(
//					'type'=>'dropdownlist',
//					'items'=>array('light'=>'Light'),
//				),
				'animateAddToCart'=>array(
					'type'=>'dropdownlist',
					'items'=>array(false=>'Off',true=>'On'),
				),
			),

		);
	}




}