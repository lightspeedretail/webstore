<?php
class ThemeForm extends CFormModel
{
	protected $viewset = "cities";
	protected $beta = false; //Display beta tag in Theme Gallery
	protected $name = "Default";
	protected $thumbnail = ""; //filename.png expected to be in the theme folder
	protected $version = "0"; //use single digits, not 0.0.0
	protected $description = "";
	protected $noupdate = false;
	protected $credit = "Designed by Lightspeed";

	protected $GoogleFonts; // use this value to load Google Fonts for your design, i.e. $GoogleFonts = "Tangerine|Inconsolata|Droid+Sans"
	protected $bootstrap = "bootstrap2"; // use this value to load new bootstrap i.e. $bootstrap = "bootstrap3" or "none";

	// Note that all themes will also attempt to load the defined CHILD_THEME and custom.css
	protected $cssfiles = "base,style"; //CSS files that are loaded by the loop in _head.php, can be customer disabled

	/**
	Override this to true in the themes that offer custom home page.
	 */
	protected $showCustomIndexOption = false;

	/**
	 * Use second CMenu object for mobile (requires CSS to hide it).
	 *
	 * This was added in 3.1 to assist with mobile devices but to ensure backwards compatibility
	 * with 3.0 templates, we had to hide it by default.
	 */
	protected $showSeparateMobileMenu = true;

	/**
	 * Store custom.css in custom folder.
	 *
	 * For stock themes from Lightspeed, save custom.css into the custom folder instead of the
	 * theme folder, so upgrades can be done more easily. If this is missing or false,
	 * custom.css will be expected to be with the other css files in /css
	 */
	protected $useCustomFolderForCustomcss = true;

	/**
	 * Whether this theme uses the new checkout process or not
	 * @var bool
	 */
	protected $advancedCheckout = false;

	/*
	 * IMAGE SIZES
	 */
	protected $CATEGORY_IMAGE_HEIGHT = 180;
	protected $CATEGORY_IMAGE_WIDTH = 180;
	protected $DETAIL_IMAGE_WIDTH = 256; //Image size used on product detail page
	protected $DETAIL_IMAGE_HEIGHT = 256;
	protected $LISTING_IMAGE_WIDTH = 180; //Image size used on grid view
	protected $LISTING_IMAGE_HEIGHT = 190;
	protected $MINI_IMAGE_WIDTH = 30; //Image size used in shopping cart
	protected $MINI_IMAGE_HEIGHT = 30;
	protected $PREVIEW_IMAGE_WIDTH = 30;
	protected $PREVIEW_IMAGE_HEIGHT = 30;
	protected $SLIDER_IMAGE_WIDTH = 90; //Image used on a slider appearing on a custom page
	protected $SLIDER_IMAGE_HEIGHT = 90;

	//Public options (config keys)
	public $CHILD_THEME = "light";
	public $PRODUCTS_PER_PAGE = 12; //deprecated, not used by most themes unless they implement it

	public $menuposition = "left";

	//Public options (additional framework settings)
	public $disableGridRowDivs = false;

	/**
	 * @property The version of modal.css to use. By default, use the legacy version.
	 */
	protected $modalVersion = "1.0.0";

	public function __get($name)
	{
		$vars = get_class_vars(get_class($this));
		if(array_key_exists($name,$vars))
			return $this->$name;
		else
			try {
				return parent::__get($name);
			}
			catch(Exception $e) {
				return null;
			}

	}

	public function getDefaultConfiguration()
	{
		$arrAttributes = $this->attributes;
		return serialize($arrAttributes);
	}


	protected $parent; //Used when a theme is a copy of another theme to control inheritance
	protected $versionCheckUrl = ""; //for future use
}
