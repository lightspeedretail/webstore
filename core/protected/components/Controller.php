<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	public $menuwidget;

	protected $_canonicalUrl = null;
	protected $_returnUrl = null;

	/* These are public variables that are used in our layout, so we have to define them.
	*/
	public $pageDescription;
	public $pageCanonicalUrl;
	public $pageImageUrl;
	public $pageHeader;
	public $pageHeaderImage;
	public $pageAbsoluteHeaderImage;
	public $pageGoogleVerify;
	public $pageGoogleFonts;
	public $sharingHeader;
	public $sharingFooter;
	public $logoutUrl;
	public $headerImage;

	public $arrSidebars;

	/* These are partial renders of pieces of the web page to display */
	public $searchPnl;

	public $gridProductsPerRow = 3;
	public $gridProductsRows;

	// @codingStandardsIgnoreStart - this variable is used in view files,
	// cannot easily be changed.
	public $custom_page_content;
	// @codingStandardsIgnoreEnd

	/* Support lightweight repeated calls to getMenuTree by caching the result
	 * for the lifetime of the component object. */
	private $_objFullTree = null;

	/**
	 * Dynamically load the configuration settings for the client and
	 * establish Params to make everything faster
	 */
	public static function initParams()
	{
		defined('DEFAULT_THEME') or define('DEFAULT_THEME', 'brooklyn2014');

		$params = CHtml::listData(Configuration::model()->findAll(), 'key_name', 'key_value');

		foreach ($params as $key => $value)
		{
			Yii::app()->params->add($key, $value);
		}

		if(isset(Yii::app()->params['THEME']))
		{
			Yii::app()->theme = Yii::app()->params['THEME'];
		} else {
			Yii::app()->theme = DEFAULT_THEME;
		}

		Yii::app()->params->add('listPerPage', Yii::app()->params['PRODUCTS_PER_PAGE']);

		//Based on logging setting, set log level dynamically and possibly turn on debug mode
		switch (Yii::app()->params['DEBUG_LOGGING'])
		{
			case 'info':
				$logLevel = "error,warning,info";
				break;
			case 'trace':
				$logLevel = "error,warning,info,trace";
				defined('YII_DEBUG') or define('YII_DEBUG', true);
				defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
				break;
			case 'error':
			default:
				$logLevel = "error,warning";
				break;
		}

		foreach(Yii::app()->getComponent('log')->routes as $route)
		{
			$route->levels = $logLevel;
		}

		Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");

		Yii::app()->name = Yii::app()->params['STORE_NAME'];

		if(Yii::app()->params['LIGHTSPEED_CLOUD'] == '-1')
		{
			//We should never see this, this means our cloud cache file is bad
			$strHostfile = realpath(dirname(__FILE__)).'/../../../config/cloud/'.$_SERVER['HTTP_HOST'].".php";
			@unlink($strHostfile);
			Yii::app()->request->redirect(Yii::app()->createUrl('site/index'));
		}

	}
	/**
	 * Load anything we need globally, such as items we're going to use in our main.php template.
	 * If you create init() in any other controller, you need to run parent::init() too or this
	 * will be skipped. If you run your own init() and don't call this, you must call Controller::initParams();
	 * or nothing will work.
	 */
	public function init()
	{
		self::initParams();

		if(isset($_GET['nosni']))
		{
			Yii::app()->user->setFlash('warning', Yii::t('global', 'NOTE: Your older operating system does not support certain security features this site uses. You have been redirected to {link} for your session which will ensure your information is properly protected.', array('{link}' => "<b>".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']."</b>")));
		}

		$filename = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.DEFAULT_THEME;
		if(!file_exists($filename) && _xls_get_conf('LIGHTSPEED_MT', 0) == '0')
		{
			if(!downloadTheme(DEFAULT_THEME))
			{
				die("missing ".DEFAULT_THEME);
			}
			else
			{
				$this->redirect(Yii::app()->createUrl("site/index"));
			}
		}

		if(!Yii::app()->theme)
		{
			if(_xls_get_conf('THEME'))
			{
				//We can't find our theme for some reason, switch back to default
				_xls_set_conf('THEME', DEFAULT_THEME);
				_xls_set_conf('CHILD_THEME', 'light');
				Yii::log(
					"Couldn't find our theme, switched back to " . DEFAULT_THEME . " for emergency",
					'error',
					'application.' . __CLASS__ . "." . __FUNCTION__
				);
				$this->redirect(Yii::app()->createUrl('site/index'));
			}
			else
			{
				die("you have no theme set");
			}
		}

		if (isset($_GET['theme']) && isset($_GET['themekey']))
		{
			$strTheme = CHtml::encode($_GET['theme']);
			$strThemeKey = CHtml::encode($_GET['themekey']);

			if ($this->verifyPreviewThemeKey($strTheme, $strThemeKey))
			{
					Yii::app()->theme = $strTheme;
					$this->registerPreviewThemeScript($strTheme, $strThemeKey);
			}
			else
			{
				Yii::log(
					"Invalid theme preview link for" . $strTheme  . ". Navigate to Admin Panel to generate a new link.",
					'error',
					'application.' . __CLASS__ . "." . __FUNCTION__
				);
			}
		}

		$this->buildBootstrap();
		if(_xls_facebook_login())
		{
			$this->setFacebookComponent();
		}

		if (Yii::app()->params['STORE_OFFLINE'] != '0' || Yii::app()->params['INSTALLED'] != '1')
		{
			if (isset($_GET['offline']))
			{
				Yii::app()->session['STORE_OFFLINE'] = _xls_number_only($_GET['offline']);
			}

			//If uninstalled on a new Multitenant store, direct to license acceptance to get going
			if (Yii::app()->params['INSTALLED'] != '1' && Yii::app()->params['LIGHTSPEED_MT'] == '1')
			{
				$url = Yii::app()->createUrl("admin/license");
				$url = str_replace("https:", "http:", $url);
				$this->redirect($url, true);
			}

			if (Yii::app()->session['STORE_OFFLINE'] != Yii::app()->params['STORE_OFFLINE'] ||
				Yii::app()->params['INSTALLED'] != '1')
			{
				$this->render('/site/offline');
				Yii::app()->end();
			}
		}

		$this->logoutUrl = $this->createUrl("site/logout");

		$strViewset = Yii::app()->theme->info->viewset;
		if(!empty($strViewset))
		{
			Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-".$strViewset);
		}

		$strLayoutFile = Yii::app()->theme->config->layoutFile;
		if(empty($strLayoutFile))
		{
			$strLayoutFile = "column2"; //This is for backwards compatibility only
		}

		if(Yii::app()->theme && file_exists('webroot.themes.'.Yii::app()->theme->name.'.layouts.'.$strLayoutFile))
		{
			$this->layout = 'webroot.themes.'.Yii::app()->theme->name.'.layouts.'.$strLayoutFile;
		} else {
			$this->layout = $strLayoutFile;
		}

		//Set defaults
		$this->getUserLanguage();

		$this->pageTitle = Yii::app()->name." : ".Yii::app()->params['STORE_TAGLINE'];
		$this->pageCanonicalUrl = $this->getCanonicalUrl();
		$this->pageDescription = Yii::app()->params['STORE_TAGLINE'];
		$this->pageImageUrl = '';

		$pageHeaderImage = Yii::app()->params['HEADER_IMAGE'];

		if (substr($pageHeaderImage, 0, 4) != "http")
		{
			if (substr($pageHeaderImage, 0, 2) != "//")
			{
				$this->pageAbsoluteHeaderImage = Yii::app()->createAbsoluteUrl($pageHeaderImage, array(), Yii::app()->params['ENABLE_SSL'] ? 'https' : 'http');
				// we prefix with baseUrl to handle instances where Web Store is installed in a sub folder
				$this->pageHeaderImage = Yii::app()->baseUrl . $pageHeaderImage;
			}

			else
			{
				$this->pageAbsoluteHeaderImage = Yii::app()->params['ENABLE_SSL'] ? 'https:' . $pageHeaderImage : 'http:' . $pageHeaderImage;
				$this->pageHeaderImage = $pageHeaderImage;
			}
		} else {
			$this->pageAbsoluteHeaderImage = $pageHeaderImage;
			$this->pageHeaderImage = $pageHeaderImage;
		}

		Yii::app()->shoppingcart->updateMissingProducts();
		Yii::app()->shoppingcart->revalidatePromoCode();

		//Run other functions to create some data we always need
		$this->buildGoogle();
		$this->buildSidebars();
		if (_xls_get_conf('SHOW_SHARING', 0))
		{
			$this->buildSharing();
		}

		$this->buildAccessWarning();

		$this->gridProductsPerRow = _xls_get_conf('PRODUCTS_PER_ROW', 3);

		Yii::app()->clientScript->registerMetaTag(
			"Lightspeed Web Store " . XLSWS_VERSION,
			'generator',
			null,
			array(),
			'generator'
		);
	}

	/**
	 * Determine what the current Web Store's canonical hostname should be.
	 * Since Lightspeed hosted Web Stores have multiple URLs, we want the one
	 * that the customer *expects* to be the canonical url.  In our current
	 * hosting environment that means what we call the 'primary url' in
	 * sapphire, and the 'lightspeed hosting custom url' for retail stores.
	 * Once we transition to the new infrastructure, all Lightspeed hosted
	 * stores will have access to a single environment variable HTTP_PAYLOAD_URL
	 * which will always map to the customers "primary url."  Until then, we
	 * need to do a bit of checking to figure out the best hostname to use for
	 * the customer's Web Store.  Any non-hosted Web Stores should default to
	 * SERVER_NAME as this is what has been configured in the web server as the
	 * official name of the Web Store.
	 *
	 * @return string
	 */
	public function getCanonicalHostName() {
		$hostName = null;

		// HTTP_PAYLOAD_URL is set via the hosting infrastructure to always be
		// the customer's primary Web Store URL.  This should always be used to
		// determine a Lightspeed hosted Web Store's canonical url.
		if (getenv('HTTP_PAYLOAD_URL') !== false)
		{
			$hostName = getenv('HTTP_PAYLOAD_URL');
		}

		// Legacy retail Web Stores use LIGHTSPEED_HOSTING_CUSTOM_URL.
		// TODO: Once all Lightspeed hosted Web Stores are on the new
		// infrastructure we can remove this check.
		elseif (strlen(_xls_get_conf('LIGHTSPEED_HOSTING_CUSTOM_URL')) > 0)
		{
			$hostName = _xls_get_conf('LIGHTSPEED_HOSTING_CUSTOM_URL');
		}

		// Everyone else should use SERVER_NAME
		else
		{
			$hostName = $_SERVER['SERVER_NAME'];
		}

		return $hostName;
	}

	/**
	 * Default canonical url generator, will remove all get params beside 'id'
	 * and generate an absolute url.
	 * If the canonical url was already set in a child controller, it will be
	 * taken instead.
	 */
	public function getCanonicalUrl() {
		if ($this->_canonicalUrl !== null)
		{
			return $this->_canonicalUrl;
		}

		$params = array();
		if (isset($_GET['id']))
		{
			//just keep the id, because it identifies our model pages
			$params = array('id' => $_GET['id']);
		}

		$canonicalUrl = Yii::app()->createAbsoluteUrl($this->route, $params);
		$parsedUrl = parse_url($canonicalUrl);
		$host = $this->getCanonicalHostName();

		if ($parsedUrl['host'] !== $host)
		{
			$canonicalUrl = str_replace($parsedUrl['host'], $host, $canonicalUrl);
		}

		return $canonicalUrl;
	}

	/**
	 * Default return url generator, will remove all get params beside 'id'
	 * and generate an absolute url.
	 * If the return url was already set in a child controller, it will be
	 * taken instead.
	 */
	public function generateReturnUrl()
	{
		if ($this->_returnUrl === null)
		{
			$params = array();
			if (isset($_GET['id']))
			{
				//just keep the id, because it identifies our model pages
				$params = array('id' => $_GET['id']);
			}

			$this->_returnUrl = Yii::app()->createAbsoluteUrl($this->route, $params);
		}

		return $this->_returnUrl;
	}

	/**
	 * Override URL if needed
	 */
	public function setCanonicalUrl($strUrl) {
		$this->_canonicalUrl = $strUrl;
	}

	protected function getUserLanguage()
	{
		$app = Yii::app();

		if (isset($_POST['_lang']))
		{
			$app->language = $_POST['_lang'];
			$app->session['_lang'] = $app->language;
		}
		elseif (isset($_GET['_lang']))
		{
			$app->language = $_GET['_lang'];
			$app->session['_lang'] = $app->language;
		}
		elseif (isset($app->session['_lang']))
		{
			$app->language = $app->session['_lang'];
		}
		else
		{
			$preferredLanguage = Yii::app()->getRequest()->getPreferredLanguage();

			if ($preferredLanguage)
			{
				// 'fr_FR' becomes 'fr'
				$preferredLanguage = substr($preferredLanguage, 0, 2);
				$app->language = $preferredLanguage;
				$app->session['_lang'] = $preferredLanguage;
			}
		}
	}


	/**
	 * buildGoogle - Reads data needed for various Google services
	 * @param none
	 * @return none
	 */
	protected function buildGoogle() {

		$this->pageGoogleVerify = _xls_get_conf('GOOGLE_VERIFY');
		$this->pageGoogleFonts = _xls_get_conf('GOOGLE_FONTS_LINK');

		if (Yii::app()->theme->info->GoogleFonts)
		{
			$this->pageGoogleFonts .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.
				Yii::app()->theme->info->GoogleFonts.'">';
		}

		$this->pageGoogleFonts = str_replace("http://", "//", $this->pageGoogleFonts);
	}

	/**
	 * Read the sidebars from our modules table into an array for use in our main.php template
	 * @param none
	 * @return none
	 */
	protected function buildSidebars() {

		$this->arrSidebars = Modules::getModulesByCategory();

	}

	protected function buildSharing() {
		$this->sharingHeader = $this->renderPartial('/site/_sharing_header', null, true);
		$this->sharingFooter = $this->renderPartial('/site/_sharing_footer', null, true);
	}

	protected function buildBootstrap()
	{

		Yii::setPathOfAlias('bootstrap', null);
		$strBootstrap = Yii::app()->theme->info->bootstrap;

		if(!isset($strBootstrap))
		{
			Yii::app()->setComponent('bootstrap', array(
				'class' => 'ext.bootstrap.components.Bootstrap',
				'responsiveCss' => true,
			));
			Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
			Yii::app()->bootstrap->init();
		}
		elseif($strBootstrap == 'none')
		{
			// Don't load bootstrap at all.
			// Set the alias though, so the theme can use it for the widgets.
			Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
		}
		elseif(!empty($strBootstrap))
		{
			Yii::setPathOfAlias(
				'bootstrap',
				dirname(__FILE__) . '/../extensions/'.Yii::app()->theme->info->bootstrap
			);
			Yii::app()->setComponent('bootstrap', array(
				'class' => 'ext.' . Yii::app()->theme->info->bootstrap . '.components.Bootstrap'
			));
			Yii::app()->bootstrap->init();
		}

	}

	protected function buildAccessWarning()
	{
		try
		{
			Yii::app()->setComponent('wsaccesswarning', array(
				'class' => 'ext.wsaccesswarning.wsaccesswarning'
			));

			Yii::app()->wsaccesswarning->displayAccessWarning();
		}

		catch(Exception $ex)
		{
			Yii::log("Failed to load wsaccesswarning extension", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}
	}

	/**
	 * For backwards compatibility, provide dialog which redirects to login page
	 */
	protected function getloginDialog() {
		/* This is our modal login dialog box */
		if (Yii::app()->user->isGuest)
		{
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id' => 'LoginForm',
				'options' => array(
					'title' => Yii::t('global', 'Redirecting to Login...'),
					'autoOpen' => false,
					'modal' => 'true',
					'width' => '300',
					'height' => '0',
					'resizable' => false,
					'position' => 'center',
					'draggable' => false,
					'open' => 'js:function(){window.location.href="'.$this->createUrl("site/login").'";}',

				),
			));

			//$this->renderPartial('/site/_login',array('model'=>new LoginForm()));
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		}

	}

	protected function setFacebookComponent()
	{
		$fbArray = require(YiiBase::getPathOfAlias('application.config').'/_wsfacebook.php');
		$fbArray['appId'] = Yii::app()->params['FACEBOOK_APPID'];
		$fbArray['secret'] = Yii::app()->params['FACEBOOK_SECRET'];
		Yii::app()->setComponent('facebook', $fbArray);
	}

	public function setReturnUrl()
	{
		Yii::app()->session['returnUrl'] = $this->generateReturnUrl();
	}

	public function getReturnUrl()
	{
		return Yii::app()->session['returnUrl'];
	}

	/**
	 * Cycle through Product model for page and mark beginning and end of each row.
	 *
	 * Used for <div row> formatting in the view layer.
	 *
	 * @param $model
	 * @return mixed
	 */
	protected function createBookends($model)
	{
		if (count($model) == 0 || Yii::app()->theme->config->disableGridRowDivs)
		{
			return $model;
		}

		$ct = -1;
		$next = 0;
		foreach ($model as $item)
		{
			$ct++;
			if ($ct == 0)
			{
				$model[$ct]->rowBookendFront = true;
			}

			if ($next == 1)
			{
				$model[$ct]->rowBookendFront = true;
				$next = 0;
			}

			if ((1 + $ct) % $this->gridProductsPerRow == 0)
			{
				$model[$ct]->rowBookendBack = true;
				$next = 1;
			}
		}

		$model[count($model) - 1]->rowBookendBack = true; //Last item must always close div
		return $model;
	}


	protected function afterRender($view, &$output) {
		parent::afterRender($view, $output);
		//Yii::app()->facebook->addJsCallback($js); // use this if you are registering any $js code you want to run asyc

		if (_xls_facebook_login())
		{
			Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
		}

		return true;
	}

	/**
	 * Build array combining product categories, families and Custom Pages.
	 *
	 * @return array
	 */
	public function getMenuTree()
	{
		$categoryTree = Category::GetTree();
		$extraTopLevelItems = CustomPage::GetTree();

		// Whether the families (aka. manufacturers) are displayed the product menu.
		// ENABLE_FAMILIES:
		//	0     => No families in the product menu.
		//	1     => Separate menu item at bottom of product menu.
		//	2     => Separate menu item at top of product menu.
		//	3     => Blended into the top level of the menu.
		//
		if(CPropertyValue::ensureInteger(Yii::app()->params['ENABLE_FAMILIES']) > 0)
		{
			$familyTree = Family::GetTree();

			if(CPropertyValue::ensureInteger(Yii::app()->params['ENABLE_FAMILIES']) === 3)
			{
				$extraTopLevelItems += $familyTree;
			}
		}

		// The behaviour varies here because OnSite customers are able to
		// configure the menu_position of their categories. A more thorough
		// solution might modify the menu code to return the menu_position for
		// each menu item for sorting, however Categories should already be
		// sorted by menu_position.
		if (CPropertyValue::ensureInteger(Yii::app()->params['LIGHTSPEED_CLOUD']) !== 0)
		{
			// Retail: Sort the entire menu alphabetically.
			$objTree = $categoryTree + $extraTopLevelItems;
			ksort($objTree);
		} else {
			// OnSite: Only sort the extras alphabetically (categories are
			// already sorted by menu_position).
			ksort($extraTopLevelItems);
			$objTree = $categoryTree + $extraTopLevelItems;
		}

		if (CPropertyValue::ensureInteger(Yii::app()->params['ENABLE_FAMILIES']) === 1 ||
			CPropertyValue::ensureInteger(Yii::app()->params['ENABLE_FAMILIES']) === 2)
		{
			$familyMenu['families_brands_menu'] = array(
				'text' => CHtml::link(
					Yii::t(
						'category',
						Yii::app()->params['ENABLE_FAMILIES_MENU_LABEL']
					),
					$this->createUrl(
						"search/browse",
						array('brand' => '*')
					)
				),
				'label' => Yii::t('category', Yii::app()->params['ENABLE_FAMILIES_MENU_LABEL']),
				'link' => $this->createUrl("search/browse", array('brand' => '*')),
				'url' => $this->createUrl("search/browse", array('brand' => '*')),
				'id' => 0,
				'child_count' => count($familyTree),
				'hasChildren' => 1,
				'children' => $familyTree,
				'items' => $familyTree
			);

			if (CPropertyValue::ensureInteger(Yii::app()->params['ENABLE_FAMILIES']) === 1)
			{
				// The manufacturers menu is at the bottom.
				$objTree = $objTree + $familyMenu;
			}
			elseif (CPropertyValue::ensureInteger(Yii::app()->params['ENABLE_FAMILIES']) === 2)
			{
				// The manufacturers menu is at the top.
				$objTree = $familyMenu + $objTree;
			}
		}

		$this->_objFullTree = $objTree;
		return $this->_objFullTree;
	}

	/**
	 * Take our Menu array and remove subcategories.
	 *
	 * @return mixed
	 */
	public function getMenuTreeTop()
	{
		if ($this->_objFullTree === null)
		{
			$this->_objFullTree = $this->MenuTree;
		}

		foreach($this->_objFullTree as $key => $menuItem)
		{
			if(isset($menuItem['children']))
			{
				unset($this->_objFullTree[$key]['children']);
			}

			if(isset($menuItem['items']))
			{
				unset($this->_objFullTree[$key]['items']);
			}
		}

		return $this->_objFullTree;
	}

	/**
	 * We override our function here because for certain URLs, we can have them created securely
	 * and also handle our Shared SSL when needed
	 * @param string $route
	 * @param array $params
	 * @param string $schema
	 * @param string $ampersand
	 * @return string
	 */
	public function createAbsoluteUrl($route, $params = array(), $schema = '', $ampersand = '&')
	{
		return Yii::app()->createAbsoluteUrl($route, $params, $schema, $ampersand);
	}

	/**
	 * Boolean if this store is a Cloud store
	 * @return bool
	 */
	public function getIsCloud()
	{
		if (Yii::app()->params['LIGHTSPEED_CLOUD'] > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Boolen if this store is a Multitenant Store (could be Cloud or Pro)
	 * @return bool
	 */
	public function getIsMT()
	{
		if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Boolen if this store is a Hosted store
	 * @return bool
	 */
	public function getIsHosted()
	{
		if(Yii::app()->params['LIGHTSPEED_HOSTING'] > 0)
		{
			return true;
		}

		return false;
	}

	protected function verifyPreviewThemeKey($strThemeName, $strKey)
	{
		return ($strKey === substr(md5($strThemeName.gmdate('d')), 0, 10));
	}

	protected function registerPreviewThemeScript($strThemeName, $strkey)
	{
		$script = "$('a').each(function(elem) { $(this).attr('href', $(this).attr('href') + '?theme=$strThemeName&themekey=$strkey'); });\n";

		// tweaks
		$script .= "$('div.product_cell_label').each(function(elem) { $(this).attr('onclick', $(this).attr('onclick').substring(0,$(this).attr('onclick').length-1) + '?theme=$strThemeName&themekey=$strkey\"'); });";
		$script .= "$('div.wishlistnew').each(function(elem) { $(this).attr('onclick', $(this).attr('onclick').substring(0,$(this).attr('onclick').length-1) + '?theme=$strThemeName&themekey=$strkey\"'); });";

		Yii::app()->clientScript->registerScript('themepreview', $script, CClientScript::POS_END);
	}

	/**
	 * Renders JSON response.
	 *
	 * @param $data array containing the values that should be returned.
	 * @return string The JSON encode data.
	 */
	public function renderJSON($data)
	{
		ob_clean();
		header('Content-type: application/json');
		$encodedData = CJSON::encode($data);
		echo $encodedData;
		Yii::app()->end(0, false);

		return $encodedData;
	}
}
