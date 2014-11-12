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
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $menuwidget;

	protected $_canonicalUrl;

	public $LoadSharing=0;

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
	public $custom_page_content;

	/* Support lightweight repeated calls to getMenuTree by caching the result
	 * for the lifetime of the component object. */
	private $objFullTree = null;

	/**
	 * Dynamically load the configuration settings for the client and
	 * establish Params to make everything faster
	 */
	public static function initParams()
	{
		defined('DEFAULT_THEME') or define('DEFAULT_THEME', 'brooklyn');

		$Params = CHtml::listData(Configuration::model()->findAll(),'key_name','key_value');

		foreach ($Params as $key => $value)
		{
			Yii::app()->params->add($key, $value);
		}

		if(isset(Yii::app()->params['THEME']))
			Yii::app()->theme=Yii::app()->params['THEME'];
		else Yii::app()->theme=DEFAULT_THEME;
		if(isset(Yii::app()->params['LANG_CODE']))
			Yii::app()->language=Yii::app()->params['LANG_CODE'];
		else Yii::app()->language = "en";
		Yii::app()->params->add('listPerPage',Yii::app()->params['PRODUCTS_PER_PAGE']);

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
			$route->levels = $logLevel;

		Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");

		Yii::app()->name =  Yii::app()->params['STORE_NAME'];

		if(Yii::app()->params['LIGHTSPEED_CLOUD']=='-1')
		{
			//We should never see this, this means our cloud cache file is bad
			$strHostfile = realpath(dirname(__FILE__)).'/../../../config/cloud/'.$_SERVER['HTTP_HOST'].".php";
			@unlink($strHostfile);
			Yii::app()->request->redirect('/');
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
			Yii::app()->user->setFlash('warning',Yii::t('global','NOTE: Your older operating system does not support certain security features this site uses. You have been redirected to {link} for your session which will ensure your information is properly protected.',array('{link}'=>"<b>".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']."</b>")));

		$filename = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.DEFAULT_THEME;
		if(!file_exists($filename) && _xls_get_conf('LIGHTSPEED_MT',0)=='0')
		{
			if(!downloadTheme(DEFAULT_THEME))
				die("missing ".DEFAULT_THEME);
			else
				$this->redirect("/");
		}
		if(!Yii::app()->theme)
		{
			if(_xls_get_conf('THEME'))
			{
				//We can't find our theme for some reason, switch back to default
				_xls_set_conf('THEME', DEFAULT_THEME);
				_xls_set_conf('CHILD_THEME','light');
				Yii::log(
					"Couldn't find our theme, switched back to " . DEFAULT_THEME . " for emergency",
					'error',
					'application.' . __CLASS__ . "." . __FUNCTION__
				);
				$this->redirect("/");

			} else
				die("you have no theme set");
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
			$this->getFacebookLogin();

		if (Yii::app()->params['STORE_OFFLINE'] != '0' || Yii::app()->params['INSTALLED'] != '1')
		{
			if (isset($_GET['offline']))
				Yii::app()->session['STORE_OFFLINE'] = _xls_number_only($_GET['offline']);

			//If uninstalled on a new Multitenant store, direct to license acceptance to get going
			if (Yii::app()->params['INSTALLED'] != '1' && Yii::app()->params['LIGHTSPEED_MT'] == '1')
			{
				$url = Yii::app()->createUrl("admin/license");
				$url = str_replace("https:","http:",$url);
				$this->redirect($url,true);
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
			Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-".$strViewset);

		$strLayoutFile = Yii::app()->theme->config->layoutFile;
		if(empty($strLayoutFile))
			$strLayoutFile = "column2"; //This is for backwards compatibility only

		if(Yii::app()->theme && file_exists('webroot.themes.'.Yii::app()->theme->name.'.layouts.'.$strLayoutFile))
			$this->layout='webroot.themes.'.Yii::app()->theme->name.'.layouts.'.$strLayoutFile;
		else
			$this->layout = $strLayoutFile;

		//Set defaults
		$this->getUserLanguage();

		$this->pageTitle = Yii::app()->name." : ".Yii::app()->params['STORE_TAGLINE'];
		$this->pageCanonicalUrl = $this->getCanonicalUrl();
		$this->pageDescription = Yii::app()->params['STORE_TAGLINE'];
		$this->pageImageUrl = '';

		$pageHeaderImage = Yii::app()->params['HEADER_IMAGE'];

		if (substr($pageHeaderImage,0,4) != "http")
		{
			if (substr($pageHeaderImage,0,2) != "//")
			{
				$this->pageAbsoluteHeaderImage = Yii::app()->createAbsoluteUrl($pageHeaderImage, array(), Yii::app()->params['ENABLE_SSL'] ? 'https' : 'http');
				$this->pageHeaderImage = $pageHeaderImage;
			}

			else
			{
				$this->pageAbsoluteHeaderImage = Yii::app()->params['ENABLE_SSL'] ? 'https:' . $pageHeaderImage : 'http:' . $pageHeaderImage;
				$this->pageHeaderImage = $pageHeaderImage;
			}
		}

		else
		{
			$this->pageAbsoluteHeaderImage = $pageHeaderImage;
			$this->pageHeaderImage = $pageHeaderImage;
		}

		Yii::app()->shoppingcart->UpdateMissingProducts();
		Yii::app()->shoppingcart->RevalidatePromoCode();

		//Run other functions to create some data we always need
		$this->buildGoogle();
		$this->buildSidebars();
		if (_xls_get_conf('SHOW_SHARING',0))
			$this->buildSharing();

		$this->buildAccessWarning();

		$this->gridProductsPerRow = _xls_get_conf('PRODUCTS_PER_ROW',3);



		Yii::app()->clientScript->registerMetaTag(
			"Lightspeed Web Store " . XLSWS_VERSION,
			'generator',
			null,
			array(),
			'generator'
		);
	}

	/**
	 * Default canonical url generator, will remove all get params beside 'id' and generates an absolute url.
	 * If the canonical url was already set in a child controller, it will be taken instead.
	 */
	public function getCanonicalUrl() {
		if ($this->_canonicalUrl === null) {
			$params = array();
			if (isset($_GET['id'])) {
				//just keep the id, because it identifies our model pages
				$params = array('id' => $_GET['id']);
			}
			$this->_canonicalUrl = Yii::app()->createAbsoluteUrl($this->route, $params);
		}
		return $this->_canonicalUrl;
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
			// 'fr_FR' becomes 'fr'
			$app->language = substr(Yii::app()->getRequest()->getPreferredLanguage(),0,2);
			$app->session['_lang'] = substr(Yii::app()->getRequest()->getPreferredLanguage(),0,2);
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
			$this->pageGoogleFonts .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.
				Yii::app()->theme->info->GoogleFonts.'">';

		$this->pageGoogleFonts = str_replace("http://","//",$this->pageGoogleFonts);
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
		$this->sharingHeader = $this->renderPartial('/site/_sharing_header',null,true);
		$this->sharingFooter = $this->renderPartial('/site/_sharing_footer',null,true);


	}

	protected function buildBootstrap()
	{

		Yii::setPathOfAlias('bootstrap',null);
		$strBootstrap = Yii::app()->theme->info->bootstrap;

		if(!isset($strBootstrap))
		{
			Yii::app()->setComponent('bootstrap',array(
				'class'=>'ext.bootstrap.components.Bootstrap',
				'responsiveCss'=>true,
			));
			Yii::setPathOfAlias('bootstrap', dirname(__FILE__).DIRECTORY_SEPARATOR.'../extensions/bootstrap');
			Yii::app()->bootstrap->init();
		}
		elseif($strBootstrap == 'none')
		{
			//don't load bootstrap at all
		}
		elseif(!empty($strBootstrap))
		{
			Yii::setPathOfAlias(
				'bootstrap',
				dirname(__FILE__).DIRECTORY_SEPARATOR.'../extensions/'.Yii::app()->theme->info->bootstrap
			);
			Yii::app()->setComponent('bootstrap',array(
				'class'=>'ext.'.Yii::app()->theme->info->bootstrap.'.components.Bootstrap'
			));
			Yii::app()->bootstrap->init();
		}


	}

	protected function buildAccessWarning()
	{
		try
		{
			Yii::app()->setComponent('wsaccesswarning' ,array(
				'class'=>'ext.wsaccesswarning.wsaccesswarning'
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
			$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
				'id'=>'LoginForm',
				'options'=>array(
					'title'=>Yii::t('global','Redirecting to Login...'),
					'autoOpen'=>false,
					'modal'=>'true',
					'width'=>'300',
					'height'=>'0',
					'resizable'=>false,
					'position'=>'center',
					'draggable'=>false,
					'open'=>'js:function(){window.location.href="'.$this->createUrl("site/login").'";}',

				),
			));

			//$this->renderPartial('/site/_login',array('model'=>new LoginForm()));
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		}

	}

	protected function getFacebookLogin()
	{

		//Facebook integration
		$fbArray = require(YiiBase::getPathOfAlias('application.config').'/_wsfacebook.php');
		$fbArray['appId']=Yii::app()->params['FACEBOOK_APPID'];
		$fbArray['secret']=Yii::app()->params['FACEBOOK_SECRET'];
		Yii::app()->setComponent('facebook',$fbArray);


		if (Yii::app()->user->isGuest)
		{
			$userid = Yii::app()->facebook->getUser();

			if ($userid>0)
			{
				$results = Yii::app()->facebook->api('/'.$userid);
				if(!isset($results['email']))
				{
					//we've lost our authentication, user may have revoked
					Yii::app()->facebook->destroySession();
					$this->redirect(array("/site"));
				}
				$identity=new FBIdentity($results['email'],$userid); //we user userid in the password field
				$identity->authenticate();
				if($identity->errorCode===UserIdentity::ERROR_NONE)
				{
					Yii::app()->user->login($identity,0);
					$this->redirect(array("/site"));
				}
			}
		}

		if(isset(Yii::app()->user->facebook))
			if(Yii::app()->user->facebook)
				$this->logoutUrl =  Yii::app()->facebook->getLogoutUrl();

	}

	public function setReturnUrl()
	{
		Yii::app()->session['returnUrl'] = $this->CanonicalUrl;
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
			return $model;

		$ct = -1;
		$next = 0;
		foreach ($model as $item)
		{
			$ct++;
			if ($ct == 0)
				$model[$ct]->rowBookendFront = true;
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
		parent::afterRender($view,$output);
		//Yii::app()->facebook->addJsCallback($js); // use this if you are registering any $js code you want to run asyc


		if (_xls_facebook_login())
			Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
		//Yii::app()->facebook->renderOGMetaTags(); //we don't need this because it was already in our _main.php
		return true;
	}

	/**
	 * Build array combining product categories, families and Custom Pages.
	 *
	 * @return array
	 */
	public function getMenuTree()
	{
		$objTree = Category::GetTree() + CustomPage::GetTree();
		ksort($objTree);

		if(_xls_get_conf('ENABLE_FAMILIES', 0)>0)
		{

			$families = Family::GetTree();
			$familyMenu['families_brands_menu'] = array(
				'text'=>CHtml::link(Yii::app()->params['ENABLE_FAMILIES_MENU_LABEL'],$this->createUrl("search/browse",array('brand'=>'*'))),
				'label'=>Yii::app()->params['ENABLE_FAMILIES_MENU_LABEL'],
				'link'=>$this->createUrl("search/browse",array('brand'=>'*')),
				'url'=>$this->createUrl("search/browse",array('brand'=>'*')),
				'id'=>0,
				'child_count'=>count($families),
				'hasChildren'=>1,
				'children'=>$families,
				'items'=>$families
			);

			switch (_xls_get_conf('ENABLE_FAMILIES', 0))
			{

				case 3:
					$objFullTree = $families + $objTree;
					ksort($objFullTree);
					break; //blended
				case 2:
					$objFullTree = $familyMenu + $objTree;
					break; //on top
				case 1:
					$objFullTree = $objTree + $familyMenu;
					break; //onbottom

			}

		} else $objFullTree = $objTree;

		$this->objFullTree = $objFullTree;
		return $this->objFullTree;
	}

	/**
	 * Take our Menu array and remove subcategories.
	 *
	 * @return mixed
	 */
	public function getMenuTreeTop()
	{
		if ($this->objFullTree === null)
			$this->objFullTree = $this->MenuTree;

		foreach($this->objFullTree as $key => $menuItem)
		{
			if(isset($menuItem['children']))
				unset($this->objFullTree[$key]['children']);
			if(isset($menuItem['items']))
				unset($this->objFullTree[$key]['items']);
		}

		return $this->objFullTree;
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
	public function createAbsoluteUrl($route,$params=array(),$schema='',$ampersand='&')
	{
		return Yii::app()->createAbsoluteUrl($route,$params,$schema,$ampersand);
	}

	/**
	 * Boolean if this store is a Cloud store
	 * @return bool
	 */
	public function getIsCloud()
	{
		if (Yii::app()->params['LIGHTSPEED_CLOUD'] > 0)
			return true;
		return false;
	}

	/**
	 * Boolen if this store is a Multitenant Store (could be Cloud or Pro)
	 * @return bool
	 */
	public function getIsMT()
	{
		if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
			return true;
		return false;
	}

	/**
	 * Boolen if this store is a Hosted store
	 * @return bool
	 */
	public function getIsHosted()
	{
		if(Yii::app()->params['LIGHTSPEED_HOSTING']>0)
			return true;
		return false;
	}

	protected function verifyPreviewThemeKey($strThemeName, $strKey)
	{
		return ($strKey === substr(md5($strThemeName.gmdate('d')),0,10));
	}

	protected function registerPreviewThemeScript($strThemeName, $strkey)
	{
		$script = "$('a').each(function(elem) { $(this).attr('href', $(this).attr('href') + '?theme=$strThemeName&themekey=$strkey'); });\n";

		// tweaks
		$script .= "$('div.product_cell_label').each(function(elem) { $(this).attr('onclick', $(this).attr('onclick').substring(0,$(this).attr('onclick').length-1) + '?theme=$strThemeName&themekey=$strkey\"'); });";
		$script .= "$('div.wishlistnew').each(function(elem) { $(this).attr('onclick', $(this).attr('onclick').substring(0,$(this).attr('onclick').length-1) + '?theme=$strThemeName&themekey=$strkey\"'); });";

		Yii::app()->clientScript->registerScript('themepreview',$script,CClientScript::POS_END);

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
