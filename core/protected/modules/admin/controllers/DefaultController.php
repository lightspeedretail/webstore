<?php

class DefaultController extends AdminBaseController
{

	public $controllerName = "Store Settings";

	//Codes for this controller
	const STORE_INFORMATION = 2;
	const EMAIL_SENDING_OPTIONS = 24;
	const LOCALIZATION = 15;
	const CUSTOMER_REGISTRATION = 3;
	const CAPTCHA_SETUP = 18;

	const  TEMPLATE_OPTIONS = 19;
	const  HEADER_EMAIL_IMAGE = 27;
	const  PRODUCTS = 8;
	const  INVENTORY = 11;
	const  PRODUCT_PHOTOS = 17;
	const  CARTS = 4;
	const  WISH_LIST = 7;
	const  SRO = 6;

	const SEO_URL = 21;
	const SEO_PRODUCT = 22;
	const SEO_CATEGORY = 23;
	const THEME_PHOTOS = 29;

	const ACCESS_WARNING = 32;

	/**
	 * Short Description.
	 *
	 * @return array
	 */
	public function actions()
	{
		return array(
			'edit'=>'admin.edit',
		);
	}

	/**
	 * Short Description.
	 *
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('edit','index','sidebar','categorymeta','updatecategory','releasenotes','accesswarning'),
				'roles'=>array('admin'),
			),
			array('allow',
				'actions'=>array('logout'),
				'roles'=>array('*'),
			),
		);
	}

	/**
	 * Short Description.
	 *
	 * @param CAction $action
	 * @return bool
	 */
	public function beforeAction($action)
	{
			$this->menuItems = array(
				array('label'=>'Store', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'Store Information', 'url'=>array('default/edit', 'id'=>self::STORE_INFORMATION)),
				array('label'=>'Email Sending Options', 'url'=>array('default/edit', 'id'=>self::EMAIL_SENDING_OPTIONS)),
				array('label'=>'Localization', 'url'=>array('default/edit', 'id'=>self::LOCALIZATION)),
				array('label'=>'Customer Registration', 'url'=>array('default/edit', 'id'=>self::CUSTOMER_REGISTRATION)),
				array('label'=>'Access Warning', 'url'=>array('default/accesswarning')),
				//array('label'=>'Captcha Setup', 'url'=>array('site/contact')),
				array('label'=>'Appearance', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'Display Options', 'url'=>array('default/edit', 'id'=>self::TEMPLATE_OPTIONS)),
				array('label'=>'Products', 'url'=>array('default/edit', 'id'=>self::PRODUCTS)),
				array('label'=>'Inventory', 'url'=>array('default/edit', 'id'=>self::INVENTORY)),
				array('label'=>'Product Photos', 'url'=>array('default/edit', 'id'=>self::PRODUCT_PHOTOS)),
				array('label'=>'Carts', 'url'=>array('default/edit', 'id'=>self::CARTS)),
				array('label'=>'Wish Lists', 'url'=>array('default/edit', 'id'=>self::WISH_LIST)),
				array('label'=>'SROs', 'url'=>array('default/edit', 'id'=>self::SRO), 'visible'=>!(_xls_get_conf("LIGHTSPEED_CLOUD")>0)),
				array('label'=>'SEO', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'URL Options', 'url'=>array('default/edit', 'id'=>self::SEO_URL)),
				array('label'=>'Product Meta Data', 'url'=>array('default/edit', 'id'=>self::SEO_PRODUCT)),
				array('label'=>'Category/Custom Title Format', 'url'=>array('default/edit', 'id'=>self::SEO_CATEGORY)),
				array('label'=>'Category Meta Data', 'url'=>array('default/categorymeta')),



			);


		//run parent init() after setting menu so highlighting works
		return parent::beforeAction($action);
	}

	/**
	 * Short Description.
	 *
	 * @param $id
	 * @return null|string
	 */
	public function getInstructions($id)
	{
		switch($id)
		{
			case self::EMAIL_SENDING_OPTIONS:
				return "<p>These settings control under what circumstances emails are sent out. If you are looking for SMTP server settings, those can be configured under ".CHtml::link("System->Setup->Email Servers",$this->createUrl('system/edit',array('id'=>5))).".</p><p>For subject lines, the following variables are available: {storename}, {orderid}, {customername}</p>";

			case self::CUSTOMER_REGISTRATION:
				return "You can edit the Customer database including access levels by clicking ".CHtml::link("Edit Customers",$this->createUrl("databaseadmin/customers"));

			case self::TEMPLATE_OPTIONS:
				return "If you want to choose a new Theme for your site, they can be found under ".CHtml::link("Themes",$this->createUrl("theme/index"));

			case self::INVENTORY:
				return "You can use the code <strong>{qty}</strong> in Inventory Messages to display the actual quantity available.";

			case self::LOCALIZATION:
				return "To edit the actual language strings, use the Translation menu options under ".CHtml::link("Database",$this->createUrl("databaseadmin/index"));

			case self::PRODUCT_PHOTOS:
				if(Yii::app()->params['LIGHTSPEED_MT']==1)
					return "Change settings for how product images are processed for your Web Store.";
					else
						return "Please note these settings affect photos as they are uploaded. You will need to reflag a product photo and Update Store to see the changes.";

			case self::SEO_PRODUCT:
				return "<P>These settings control the Page Title and Meta Description using keys that represent product information. Each of these keys is wrapped with a percentage ({) sign. Most represent fields in the Product Card. {crumbtrail} and {rcrumbtrail} (reverse crumbtrail) are the product's category path. Below is the available list of keys:</p><P>{storename}, {name}, {description}, {shortdescription}, {longdescription}, {price}, {family}, {class}, {crumbtrail}, {rcrumbtrail}</p>You can use {storename} and {storetagline} for the homepage.";

			case self::SEO_CATEGORY:
				return "<P>These settings control the Category and Custom Page Titles and Meta Descriptions using keys that represent category name or store information. Each of these keys is wrapped with a percentage ({) sign. {crumbtrail} and {rcrumbtrail} (reverse crumbtrail) are the product's category path. Below is the available list of keys:</p>
<P>{storename}, {name}, {crumbtrail}, {rcrumbtrail}{</p>";

			default:
				return parent::getInstructions($id);

		}


	}

	/**
	 * Default action when no other routes specified.
	 *
	 * This action also checks to see if there are Web Store updates available and if so, installs (or prompts)
	 * to keep Web Store up to date.
	 *
	 * @return void
	 */
	public function actionIndex()
	{


		$oXML = json_decode(_xls_check_version());

		if (!empty($oXML))
		{
			//We check for schema updates first to make sure our current version is up to date before pulling more code
			if(isset($oXML->webstore->schema) && $oXML->webstore->schema != "current")
			{
				$strUpdateUrl = $this->createAbsoluteUrl("upgrade/index",array(),'http');//update without patch file
				//Some circumstances because of domain switching could get https url, so double check here
				$strUpdateUrl = str_replace("https://","http://",$strUpdateUrl);
				$this->redirect($strUpdateUrl);
			}
			elseif($oXML->webstore->version>XLSWS_VERSIONBUILD)
			{

				$strUpdateUrl = $this->createAbsoluteUrl("upgrade/index",array('patch'=>$oXML->webstore->autopathfile),'http');
				//Some circumstances because of domain switching could get https url, so double check here
				$strUpdateUrl = str_replace("https://","http://",$strUpdateUrl);

				if( _xls_get_conf('LIGHTSPEED_HOSTING','0') != "1" &&
					_xls_get_conf('AUTO_UPDATE','1')=='1' &&
					$oXML->webstore->autopathfile
				)
				{

					$this->redirect($strUpdateUrl);
				}
				else
				{
					$strVersion = (string)$oXML->webstore->version;
					$strDashVersion = $strVersion[0] . '-' . $strVersion[1] . '-' . $strVersion[2];
					$strReleaseNotesUrl = 'https://www.lightspeedretail.com/release-notes/webstore/web-store-' . $strDashVersion . '/?hide=yes';
					$this->render("newversion",array('oXML'=>$oXML->webstore,'strUpdateUrl'=>$strUpdateUrl,'strReleaseNotesUrl'=>$strReleaseNotesUrl));
				}
				return;
			}
			elseif(isset($oXML->webstore->themedisplayversion) && Yii::app()->params['LIGHTSPEED_HOSTING'] == 0)
			{
				// only self hosted customers should see this
				$this->render("newtemplate",array('oXML'=>$oXML->webstore));
				return;
			}

		}

		if (Yii::app()->params['LIGHTSPEED_SHOW_RELEASENOTES'])
			$this->redirect(Yii::app()->createUrl('admin/default/releasenotes'));

		$this->render("index",array('inls'=>(Yii::app()->user->fullname=="Lightspeed" ? "1" : "0")));

		// Deleting install.php for security reasons.
		$installFile =  YiiBase::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'install.php';
		if (file_exists($installFile))
			unlink($installFile);
	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	public function actionSidebar()
	{
		$id = Yii::app()->getRequest()->getQuery('id');

		$model = Modules::model()->findByPk($id);
		if ($model)
		{
			if(isset($_POST['Modules']))
			{

				$model->attributes=$_POST['Modules'];
				if($model->validate())
				{

					if (!$model->save())
						Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));

					else Yii::app()->user->setFlash('success',Yii::t('admin','Store Settings updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));

				} else Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));
			}

			$this->registerOnOff($model->id,'Modules_active',$model->active);

			$this->render('admin.views.default.sidebar', array('model'=>$model));
		}

	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(false);
		$this->redirect(Yii::app()->createUrl('admin/default'));
	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	public function actionCategorymeta()
	{
		$model = new Category();


		$this->render("categorymeta", array('model'=>$model));

	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	public function actionUpdateCategory()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');
		if ($value=='')
			$value=null;


		Category::model()->updateByPk($pk,array($name=>$value));
		echo "success";


	}

	/**
	 * Show release notes (pulls from live site using a formatted URL).
	 *
	 * Customer can view this by using the menu option, and they are shown
	 * automatically following an upgrade.
	 *
	 * @return void
	 */
	public function actionReleasenotes()
	{
		//Turn off flag now that we've seen release notes
		_xls_set_conf('LIGHTSPEED_SHOW_RELEASENOTES',0);

		$strDashVersion = str_replace('.', '-', XLSWS_VERSION);

		$url = '//www.lightspeedretail.com/release-notes/webstore/web-store-' . $strDashVersion . '/?hide=yes';

		$this->render("releasenotes", array('url'=>$url));

	}

	public function actionAccessWarning()
	{
		$objModule = Modules::model()->findByAttributes(array('module' => 'wsaccesswarning'));

		//Setting this cookie here to prevent site access warning
		//message from coming up on admin panel
		Yii::app()->request->cookies['access_warning'] = new CHttpCookie('access_warning', 'false');

		Yii::app()->setComponent('wsaccesswarning', array(
			'class'=>'ext.wsaccesswarning.wsaccesswarning'
		));

		$objComponent = Yii::app()->getComponent('wsaccesswarning');
		$model = $objComponent->getAdminModel();

		if (isset($_POST[$objComponent->getAdminModelName()]))
		{
			$model->attributes = $_POST[$objComponent->getAdminModelName()];
			if ($model->validate() === true)
			{
				$objModule->configuration = serialize($model->attributes);
				$objModule->active = $_POST['Modules']['active'];
				$objModule->save();
				Yii::app()->user->setFlash('success',Yii::t('admin','Configuration updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));
			}
		}
		else
		{
			$model->attributes = $objModule->GetConfigValues();
		}

		if ($objModule instanceof Modules)
		{
			$formDefinition = $model->getAdminForm();

			// Copied from AdminBaseController.php actionModule - is this really necessary?
			// TODO: Review/fix this.
			foreach ($formDefinition['elements'] as $key => $value)
				$formDefinition['elements'][$key]['layout']=
					'<div class="span5 optionlabel">{label}</div><div class="span5 optionvalue">{input}</div>{error}<div class="span2 maxhint">{hint}</div>';

			$this->registerOnOff($objModule->id,'Modules_active',$objModule->active);
			$this->render(
				'admin.views.default.moduleedit',
				array('objModule' => $objModule,
					'model' => $model,
					'form' => new CForm($formDefinition, $model)
				)
			);
		}
		else
		{
			echo 'module not found';
			// Render a 'module not found' page.
		}
	}
}