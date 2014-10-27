<?php

class portlandAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Portland";
	protected $beta = true;
	protected $thumbnail = "portland.png";
	protected $version = 4;
	protected $description = "Where the dream of the 90's is still alive.";
	protected $credit = "Designed by Lightspeed";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $bootstrap = null;
	protected $viewset = "cities";
	protected $cssfiles = "style,portland";

	/*
	 * IMAGE SIZES
	 */
	 
	protected $DETAIL_IMAGE_HEIGHT = 512;
	protected $DETAIL_IMAGE_WIDTH = 512;
	protected $LISTING_IMAGE_HEIGHT = 400;
	protected $LISTING_IMAGE_WIDTH = 400;
	protected $MINI_IMAGE_HEIGHT = 100;
	protected $MINI_IMAGE_WIDTH = 100;
	protected $PREVIEW_IMAGE_HEIGHT = 45;
	protected $PREVIEW_IMAGE_WIDTH = 45;
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
	public $CHILD_THEME = "green"; //Required, to be backwards compatible with CHILD_THEME key
	public $PRODUCTS_PER_PAGE = 12;


	public $disableGridRowDivs = true;

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
					'items'=>array('green'=>'Green', 'blue'=>'Blue'),
				),

				'animateAddToCart'=>array(
					'type'=>'dropdownlist',
					'items'=>array(false=>'Off',true=>'On'),
				),


			),
		);
	}




}
