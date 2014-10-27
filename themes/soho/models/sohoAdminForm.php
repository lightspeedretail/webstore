<?php

class sohoAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "SoHo";
	protected $viewset = "cities2";
	protected $beta =true;
	protected $thumbnail = "soho.png";
	protected $version = 2;
	protected $description = "Our default template, suitable for any type of business.";
	protected $credit = "Designed by Lightspeed";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $bootstrap = "bootstrap3";
	protected $cssfiles = "style";


	protected $CATEGORY_IMAGE_HEIGHT = 180;
	protected $CATEGORY_IMAGE_WIDTH = 180;
	protected $DETAIL_IMAGE_HEIGHT = 256;
	protected $DETAIL_IMAGE_WIDTH = 256;
	protected $LISTING_IMAGE_HEIGHT = 190;
	protected $LISTING_IMAGE_WIDTH = 190;
	protected $MINI_IMAGE_HEIGHT = 30;
	protected $MINI_IMAGE_WIDTH = 30;
	protected $PREVIEW_IMAGE_HEIGHT = 30;
	protected $PREVIEW_IMAGE_WIDTH = 30;
	protected $SLIDER_IMAGE_HEIGHT = 90;
	protected $SLIDER_IMAGE_WIDTH = 90;

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
	public $CHILD_THEME = "light"; //Required, to be backwards compatible with CHILD_THEME key

	/*
	 * ATTENTION THEME DESIGNERS: These values below are NOT live, they are defaults. If you are experimenting
	 * and wish to change these values to see the effect, after changing them here, go into Admin Panel, under
	 * the Configuration panel for your theme, and click Save. This will write these values to the
	 * xlsws_module table for your themes, which is where Web Store looks for them at runtime.
	 */

	public $PRODUCTS_PER_PAGE = 12;

	public $disableGridRowDivs = true;
	//public $testvar;

	public $menuposition = "left";
	public $column2file = "column2";

	public $animateAddToCart=false;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('CHILD_THEME','required'),
			array('menuposition,column2file','safe'),
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
			'column2file'=>'Place shopping cart',
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
				'CHILD_THEME'=>array(
					'type'=>'dropdownlist',
					'items'=>array('light'=>'Light','dark'=>'Dark'),
				),

				'column2file'=>array(
					'type'=>'dropdownlist',
					'items'=>array('column2'=>'Left side','column2r'=>'Right side'),
				),

				'animateAddToCart'=>array(
					'type'=>'dropdownlist',
					'items'=>array(false=>'Off',true=>'On'),
				),
			),
		);
	}




}