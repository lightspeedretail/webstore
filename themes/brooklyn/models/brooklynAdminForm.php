<?php

class brooklynAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Brooklyn";
	protected $thumbnail = "brooklyn.png";
	protected $version = 5;
	protected $description = "Our default template, suitable for any type of business.";
	protected $credit = "Designed by Lightspeed";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $bootstrap = null;
	protected $viewset = "cities";
	protected $cssfiles = "base,style";
	protected $GoogleFonts = "Dosis:700,500,400|Ropa+Sans"; // use this value to load Google Fonts for your design, i.e. $GoogleFonts = "Tangerine|Inconsolata|Droid+Sans"

	/*
	 * IMAGE SIZES
	 */
	protected $DETAIL_IMAGE_WIDTH = 256; //Image size used on product detail page
	protected $DETAIL_IMAGE_HEIGHT = 256;
	protected $LISTING_IMAGE_WIDTH = 190; //Image size used on grid view
	protected $LISTING_IMAGE_HEIGHT = 190;
	protected $MINI_IMAGE_WIDTH = 30; //Image size used in shopping cart
	protected $MINI_IMAGE_HEIGHT = 30;
	protected $PREVIEW_IMAGE_WIDTH = 45;
	protected $PREVIEW_IMAGE_HEIGHT = 45;
	protected $SLIDER_IMAGE_WIDTH = 90; //Image used on a slider appearing on a custom page
	protected $SLIDER_IMAGE_HEIGHT = 90;


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

	public $activecss = array('base','style','light'); //Required for fresh installations
	public $CHILD_THEME = "light"; //Required, to be backwards compatible with CHILD_THEME key
	public $PRODUCTS_PER_PAGE = 12;


	public $disableGridRowDivs = true;
	//public $testvar;

	public $menuposition = "left";
	public $column2file = "column2";


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('CHILD_THEME','required'),
			//array('testvar','required'), //you can also stack items i.e. array('CHILD_THEME,testvar','required'),
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
					'items'=>array('light'=>'Light','dark'=>'Dark', 'blue'=>'Blue'),
				),

//				'testvar'=>array(
//					'type'=>'text',
//					'maxlength'=>64,
//				),

			),
		);
	}




}
