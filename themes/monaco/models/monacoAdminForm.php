<?php

class monacoAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Monaco";
	protected $thumbnail = "monaco.png";
	protected $version = 5;
	protected $description = "A template for fashion";
	protected $credit = "Designed by Skyrocket Digital Inc.";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $bootstrap = null;
	protected $viewset = "cities";
	protected $cssfiles = "style,monaco";
	protected $showCustomIndexOption = true;

	protected $CATEGORY_IMAGE_HEIGHT = 511;
	protected $CATEGORY_IMAGE_WIDTH = 511;
	protected $DETAIL_IMAGE_HEIGHT = 256;
	protected $DETAIL_IMAGE_WIDTH = 256;
	protected $LISTING_IMAGE_HEIGHT = 400;
	protected $LISTING_IMAGE_WIDTH = 400;
	protected $MINI_IMAGE_HEIGHT = 100;
	protected $MINI_IMAGE_WIDTH = 100;
	protected $PREVIEW_IMAGE_HEIGHT = 120;
	protected $PREVIEW_IMAGE_WIDTH = 120;
	protected $SLIDER_IMAGE_HEIGHT = 100;
	protected $SLIDER_IMAGE_WIDTH = 100;

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

	//This theme doesn't have color options, so just set child to custom which removes the css from _head (clever trick)
	public $CHILD_THEME = "custom"; //Required, to be backwards compatible with CHILD_THEME key

	/*
	 * ATTENTION THEME DESIGNERS: These values below are NOT live, they are defaults. If you are experimenting
	 * and wish to change these values to see the effect, after changing them here, go into Admin Panel, under
	 * the Configuration panel for your theme, and click Save. This will write these values to the
	 * xlsws_module table for your themes, which is where Web Store looks for them at runtime.
	 */

	public $PRODUCTS_PER_PAGE = 20;

	public $disableGridRowDivs = true;

	public $headerSurtitle;
	public $headerSubtitle;

	public $topLandscape;
	public $topPortrait;
	public $bottomLandscape;
	public $bottomPortrait;

	public $topLandscapeText = "2013 modern fit collection";
	public $topPortraitText = "This is the hover";
	public $bottomLandscapeText = "View the collection";
	public $bottomPortraitText = "View Text on Hover";

	public $topLandscapeUrl;
	public $topPortraitUrl;
	public $bottomLandscapeUrl;
	public $bottomPortraitUrl;

	public $menuposition = "left";
	public $column2file = "column2";

	public $animateAddToCart=false;

	function monacoAdminForm() {
		$this->topLandscape = Yii::app()->theme->baseUrl . "/css/assets/image01.jpg";
		$this->topPortrait = Yii::app()->theme->baseUrl . "/css/assets/image02.jpg";
		$this->bottomLandscape = Yii::app()->theme->baseUrl . "/css/assets/image04.jpg";
		$this->bottomPortrait = Yii::app()->theme->baseUrl . "/css/assets/image03.jpg";

		$this->topLandscapeUrl = Yii::app()->baseUrl;
		$this->topPortraitUrl = Yii::app()->baseUrl;
		$this->bottomLandscapeUrl = Yii::app()->baseUrl;
		$this->bottomPortraitUrl = Yii::app()->baseUrl;
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('CHILD_THEME','safe'),

			array('headerSurtitle', 'default', 'value' => 'Collection II'),
			array('headerSubtitle', 'default', 'value' => 'High End Luxury Fashion Theme'),

			array('topLandscapeText', 'required'),
			array('topLandscapeUrl', 'required'),
			array('topLandscape', 'required'),
			
			array('topPortraitText', 'required'),
			array('topPortraitUrl', 'required'),
			array('topPortrait', 'required'),
			
			array('bottomLandscapeText', 'required'),
			array('bottomLandscapeUrl', 'required'),
			array('bottomLandscape', 'required'),

			array('bottomPortraitText', 'required'),
			array('bottomPortraitUrl', 'required'),
			array('bottomPortrait', 'required'),

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
//			'title' => 'Set your funky options for this theme!',

			'elements'=>array(

//				'CHILD_THEME'=>array(
//					'type'=>'dropdownlist',
//					'items'=>array('light'=>'Light','dark'=>'Dark'),
//				),
				
				'headerSurtitle'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'headerSubtitle'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'topLandscapeText'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'topLandscapeUrl'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'topLandscape'=>array(
					'type'=>'dropdownlist',
					'items'=> Gallery::ImageList(1),
				),


				'topPortraitText'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'topPortraitUrl'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'topPortrait'=>array(
					'type'=>'dropdownlist',
					'items'=> Gallery::ImageList(1),
				),

				'bottomLandscapeText'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'bottomLandscapeUrl'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'bottomLandscape'=>array(
					'type'=>'dropdownlist',
					'items'=> Gallery::ImageList(1),
				),

				'bottomPortraitText'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'bottomPortraitUrl'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'bottomPortrait'=>array(
					'type'=>'dropdownlist',
					'items'=> Gallery::ImageList(1),
				),
				'animateAddToCart'=>array(
					'type'=>'dropdownlist',
					'items'=>array(false=>'Off',true=>'On'),
				),
			),

			
		);
	}




}