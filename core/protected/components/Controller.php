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
	public $pageGoogleVerify;
	public $lnkNameLogout;
	public $sharingHeader;
	public $sharingFooter;
	public $logoutUrl;

	public $arrSidebars;

	/* These are partial renders of pieces of the web page to display */
	public $searchPnl;

	public $gridProductsPerRow = 3;
	public $gridProductsRows;
    public $custom_page_content;


	/**
	 * Load anything we need globally, such as items we're going to use in our main.php template.
	 * If you create init() in any other controller, you need to run parent::init() too or this
	 * will be skipped.
	 */
	public function init()
	{

		Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");

		$this->logoutUrl = $this->createUrl("site/logout");

		$filename = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.'brooklyn';
		if(!file_exists($filename))
		{
			if(!downloadBrooklyn())
				die("missing Brooklyn");
			else
				$this->redirect("/");
		}
		if(!Yii::app()->theme)
		{
			if(_xls_get_conf('theme'))
			{
				//We can't find our theme for some reason, switch back to brookyn
				_xls_set_conf('theme','brooklyn');
				_xls_set_conf('CHILD_THEME','light');
				Yii::log("Couldn't find our theme, switched back to Brooklyn for emergency",
					'error', 'application.'.__CLASS__.".".__FUNCTION__);
				$this->redirect("/");

			} else
				die("you have no theme set");
		}


		if (Yii::app()->params['STORE_OFFLINE']>0 || Yii::app()->params['INSTALLED'] != '1')
		{
			if (isset($_GET['offline']))
				Yii::app()->session['STORE_OFFLINE'] = _xls_number_only($_GET['offline']);

			if (Yii::app()->session['STORE_OFFLINE'] != Yii::app()->params['STORE_OFFLINE'] || Yii::app()->params['INSTALLED'] != '1')
			{
				$this->render('/site/offline');
				Yii::app()->end();
			}
		}

		$this->logoutUrl = $this->createUrl("site/logout");

		$strViewset = "cities";
		if(!empty($strViewset)) Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-".$strViewset);



		if ( Yii::app()->theme && file_exists('webroot.themes.'.Yii::app()->theme->name.'.layouts.column2'))
			$this->layout='webroot.themes.'.Yii::app()->theme->name.'.layouts.column2';



		// filter out garbage requests
		$uri = Yii::app()->request->requestUri;
		if (strpos($uri, 'favicon') || strpos($uri, 'robot'))
			Yii::app()->end();

		//Set defaults
		Yii::app()->params['listPerPage'] = _xls_get_conf('PRODUCTS_PER_PAGE'); //different code may use either
		$this->getUserLanguage();

		$this->pageTitle =
			Yii::app()->name =  _xls_get_conf('STORE_NAME', 'LightSpeed Web Store')." : ".
			_xls_get_conf('STORE_TAGLINE');
		$this->pageCanonicalUrl = $this->getCanonicalUrl();
		$this->pageDescription = _xls_get_conf('STORE_TAGLINE');
		$this->pageImageUrl ='';

		$this->pageHeaderImage = CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), array('site/index'));



		try {
			$this->lnkNameLogout = CHtml::link(CHtml::image(Yii::app()->baseUrl."css/images/loginhead.png").
				Yii::app()->user->name, array('myaccount/pg'));
		}
		catch(Exception $e) {
			Yii::log("Site failure, has Web Store been set up? Error: " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			echo ("Site failure, has Web Store been set up?<P>");
			Yii::app()->end();
		}

		Yii::app()->shoppingcart->UpdateMissingProducts();
		Yii::app()->shoppingcart->RevalidatePromoCode();

		//Run other functions to create some data we always need
		$this->buildGoogle();
		$this->buildSidebars();
		if (_xls_get_conf('SHOW_SHARING',0))
			$this->buildSharing();

		$this->gridProductsPerRow = _xls_get_conf('PRODUCTS_PER_ROW',3);

		if(_xls_facebook_login())
			$this->getFacebookLogin();

		Yii::app()->clientScript->registerMetaTag(
			"LightSpeed Web Store ".XLSWS_VERSION,'generator',null,array(),'generator');
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
		else if (isset($app->session['_lang']))
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

	}

	/**
	 * Read the sidebars from our modules table into an array for use in our main.php template
	 * @param none
	 * @return none
	 */
	protected function buildSidebars() {

		$this->arrSidebars = Modules::getSidebars();

	}

	protected function buildSharing() {
		$this->sharingHeader = $this->renderPartial('/site/_sharing_header',null,true);
		$this->sharingFooter = $this->renderPartial('/site/_sharing_footer',null,true);


	}


	protected function getloginDialog() {
		/* This is our modal login dialog box */
		if (Yii::app()->user->isGuest)
		{
			$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
				'id'=>'LoginForm',
				'options'=>array(
					'title'=>Yii::t('global','Login'),
					'autoOpen'=>false,
					'modal'=>'true',
					'width'=>'350',
					'height'=>'365',
					'resizable'=>false,
					'position'=>'center',
					'draggable'=>false,
				),
			));

			$this->renderPartial('/site/_login',array('model'=>new LoginForm()));
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		}

	}

	protected function getFacebookLogin()
	{
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
	 * Cycle through Product model for page and mark beginning and end of each row. Used for <div row> formatting in
	 * the view layer.
	 * @param $model Product
	 * @return $model
	 */
	protected function createBookends($model)
	{
		if(count($model)==0) return $model;

		$ct=-1;
		$next = 0;
		foreach ($model as $item)
		{
			$ct++;
			if ($ct==0) $model[$ct]->rowBookendFront=true;
			if ($next==1) { $model[$ct]->rowBookendFront=true; $next=0; }
			if ((1+$ct) % $this->gridProductsPerRow == 0) { $model[$ct]->rowBookendBack=true; $next=1; }
		}
		$model[count($model)-1]->rowBookendBack=true; //Last item must always close div
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

}

