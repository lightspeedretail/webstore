<?php

class santacruzAdminForm extends ThemeForm
{

	/*
	 * Information keys that are used for display in Admin Panel
	 * and other functionality.
	 *
	 * These can all be accessed by Yii::app()->theme->info->keyname
	 *
	 * for example: echo Yii::app()->theme->info->version
	 */
	protected $name = "Santa Cruz";
	protected $beta = true;
	protected $thumbnail = "santacruz.png";
	protected $version = 5;
	protected $description = "Keep Santa Cruz Weird";
	protected $credit = "Designed by Lightspeed";
	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $bootstrap = "bootstrap3";
	protected $cssfiles = "style,santacruz";

	protected $CATEGORY_IMAGE_HEIGHT = 512;
	protected $CATEGORY_IMAGE_WIDTH = 512;
	protected $DETAIL_IMAGE_HEIGHT = 512;
	protected $DETAIL_IMAGE_WIDTH = 512;
	protected $LISTING_IMAGE_HEIGHT = 400;
	protected $LISTING_IMAGE_WIDTH = 400;
	protected $MINI_IMAGE_HEIGHT = 100;
	protected $MINI_IMAGE_WIDTH = 100;
	protected $PREVIEW_IMAGE_HEIGHT = 128;
	protected $PREVIEW_IMAGE_WIDTH = 128;
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
	//public $CHILD_THEME = "style"; //Required, to be backwards compatible with CHILD_THEME key


	public $PRODUCTS_PER_PAGE = 12;

	public $disableGridRowDivs = true;

	public $CATEGORY_MENU_ITEM1 = array();
    public $CATEGORY_MENU_ITEM2 = array();
    public $CATEGORY_MENU_ITEM3 = array();
    public $CATEGORY_MENU_ITEM4 = array();
//    public $CATEGORY_MENU_ITEM5 = array();

//    public $CATEGORY_MENU_ITEM = array();
    public $FOOTER_COLOUR;
    public $LINK_COLOUR;

	public $animateAddToCart=false;

    /**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
//			array('CHILD_THEME','required'),
			array('CATEGORY_MENU_ITEM1','safe'), //you can also stack items i.e. array('CHILD_THEME,testvar','required'),
            array('CATEGORY_MENU_ITEM2','safe'),
            array('CATEGORY_MENU_ITEM3','safe'),
            array('CATEGORY_MENU_ITEM4','safe'),
            array('FOOTER_COLOUR','required'),
            array('LINK_COLOUR','required'),
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
            'CATEGORY_MENU_ITEM1'=>'Category Menu Item 1',
            'CATEGORY_MENU_ITEM2'=>'Category Menu Item 2',
            'CATEGORY_MENU_ITEM3'=>'Category Menu Item 3',
            'CATEGORY_MENU_ITEM4'=>'Category Menu Item 4',
            'FOOTER_COLOUR'=>'Secondary Color (footer, headings, etc.)',
            'LINK_COLOUR'=>'Primary Color (links, buttons, etc.)',
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

        $categoryList = array(0=>null) + Category::getTopLevelSearch();

		return array(
			//'title' => 'Set your funky options for this theme!',

			'elements'=>array(
//				'CHILD_THEME'=>array(
//					'type'=>'dropdownlist',
//					'items'=>array('style'=>'style'),
//				),

				'CATEGORY_MENU_ITEM1'=>array(
					'type'=>'dropdownlist',
					'items'=>$categoryList,
				),
                'CATEGORY_MENU_ITEM2'=>array(
                    'type'=>'dropdownlist',
                    'items'=>$categoryList,
                ),
                'CATEGORY_MENU_ITEM3'=>array(
                    'type'=>'dropdownlist',
                    'items'=>$categoryList,
                ),
                'CATEGORY_MENU_ITEM4'=>array(
                    'type'=>'dropdownlist',
                    'items'=>$categoryList,
                ),
//                'CATEGORY_MENU_ITEM5'=>array(
//                    'type'=>'dropdownlist',
//                    'items'=>$categoryList,
               'LINK_COLOUR'=>array(
                   'type'=>'ext.SMiniColors.SActiveColorPicker',
               ),
                'FOOTER_COLOUR'=>array(
                    'type'=>'ext.SMiniColors.SActiveColorPicker',
//                    'htmlOptions'=>array('class'=>'col-sm-2'),
                ),
				'animateAddToCart'=>array(
					'type'=>'dropdownlist',
					'items'=>array(false=>'Off',true=>'On'),
				),
            ),
		);
	}




}