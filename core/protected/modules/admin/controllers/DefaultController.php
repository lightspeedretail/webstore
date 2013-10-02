<?php

class DefaultController extends AdminBaseController
{

	public $controllerName = "Configuration";

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
	const  CARTS = 4 ;
	const  WISH_LIST = 7;
	const  SRO = 6;

	const SEO_URL = 21;
	const SEO_PRODUCT = 22;
	const SEO_CATEGORY = 23;
	const THEME_PHOTOS = 29;

	public function actions()
	{
		return array(
			'edit'=>'admin.edit',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('edit','index','sidebar','categorymeta','updatecategory','releasenotes'),
				'roles'=>array('admin'),
			),
			array('allow',
				'actions'=>array('logout'),
				'roles'=>array('*'),
			),
		);
	}

	public function beforeAction($action)
	{

		$this->menuItems = array(
			array('label'=>'Store', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'Store Information', 'url'=>array('default/edit', 'id'=>self::STORE_INFORMATION)),
				array('label'=>'Email Sending Options', 'url'=>array('default/edit', 'id'=>self::EMAIL_SENDING_OPTIONS)),
				array('label'=>'Localization', 'url'=>array('default/edit', 'id'=>self::LOCALIZATION)),
				array('label'=>'Customer Registration', 'url'=>array('default/edit', 'id'=>self::CUSTOMER_REGISTRATION)),
				//array('label'=>'Captcha Setup', 'url'=>array('site/contact')),
			array('label'=>'Appearance', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'Display Options', 'url'=>array('default/edit', 'id'=>self::TEMPLATE_OPTIONS)),
				array('label'=>'Products', 'url'=>array('default/edit', 'id'=>self::PRODUCTS)),
				array('label'=>'Inventory', 'url'=>array('default/edit', 'id'=>self::INVENTORY)),
				array('label'=>'Product Photos', 'url'=>array('default/edit', 'id'=>self::PRODUCT_PHOTOS)),
				array('label'=>'Carts', 'url'=>array('default/edit', 'id'=>self::CARTS)),
				array('label'=>'Wish Lists', 'url'=>array('default/edit', 'id'=>self::WISH_LIST)),
				array('label'=>'SROs', 'url'=>array('default/edit', 'id'=>self::SRO)),
			array('label'=>'SEO', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'URL Options', 'url'=>array('default/edit', 'id'=>self::SEO_URL)),
				array('label'=>'Product Meta Data', 'url'=>array('default/edit', 'id'=>self::SEO_PRODUCT)),
				array('label'=>'Category/Custom Title Format', 'url'=>array('default/edit', 'id'=>self::SEO_CATEGORY)),
				array('label'=>'Category Meta Data', 'url'=>array('default/categorymeta')),


		);

		//run parent init() after setting menu so highlighting works
		return parent::beforeAction($action);
	}

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
				return "Photo sizes are now under the ".CHtml::link("Themes->Set Photo Sizes",$this->createUrl('theme/edit',array('id'=>self::THEME_PHOTOS)))." menu. Please note these settings affect photos as they are uploaded. You will need to reflag a product photo and Update Store to see the changes.";

			case self::SEO_PRODUCT:
				return "<P>These settings control the Page Title and Meta Description using keys that represent product information. Each of these keys is wrapped with a percentage ({) sign. Most represent fields in the Product Card. {crumbtrail} and {rcrumbtrail} (reverse crumbtrail) are the product's category path. Below is the available list of keys:</p><P>{storename}, {name}, {description}, {shortdescription}, {longdescription}, {price}, {family}, {class}, {crumbtrail}, {rcrumbtrail}</p>You can use {storename} and {storetagline} for the homepage.";

			case self::SEO_CATEGORY:
				return "<P>These settings control the Category and Custom Page Titles and Meta Descriptions using keys that represent category name or store information. Each of these keys is wrapped with a percentage ({) sign. {crumbtrail} and {rcrumbtrail} (reverse crumbtrail) are the product's category path. Below is the available list of keys:</p>
<P>{storename}, {name}, {crumbtrail}, {rcrumbtrail}{</p>";

			default:
				return parent::getInstructions($id);

		}


	}


	public function actionIndex()
	{

	
		$oXML = json_decode(_xls_check_version());

		if (!empty($oXML))
		{
			if($oXML->webstore->version>XLSWS_VERSIONBUILD)
			{
				if(_xls_get_conf('AUTO_UPDATE','1')=='1' && $oXML->webstore->autopathfile)
					$this->redirect($this->createUrl("upgrade/index",array('patch'=>$oXML->webstore->autopathfile)));
				else
					$this->render("newversion",array('oXML'=>$oXML->webstore));
				return;
			}
			elseif(isset($oXML->webstore->schema) && $oXML->webstore->schema != "current")
			{
				$this->redirect($this->createUrl("upgrade/index")); //update without patch file
			}
			elseif(isset($oXML->webstore->themedisplayversion))
			{
				$this->render("newtemplate",array('oXML'=>$oXML->webstore));
				return;
			}

		}

		$this->render("index",array('inls'=>(Yii::app()->user->fullname=="LightSpeed" ? "1" : "0")));
	}


	public function actionSidebar()
	{
		$id = Yii::app()->getRequest()->getQuery('id');

		$model = Modules::model()->findByPk($id);
		if ($model)
		{
			if(isset($_POST['Modules']))
			{

				$model->attributes=$_POST['Modules'];
				if($model->validate())  {

					if (!$model->save())
						Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));

					else Yii::app()->user->setFlash('success',Yii::t('admin','Configuration updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));

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


	public function actionCategorymeta()
	{
		$model = new Category();


		$this->render("categorymeta", array('model'=>$model));

	}

	public function actionUpdateCategory()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');
		if ($value=='') $value=null;


		Category::model()->updateByPk($pk,array($name=>$value));
		echo "success";


	}

	public function actionReleasenotes()
	{
		$oXML = json_decode(_xls_check_version(true));

//print_r($oXML);die();
		$this->render("releasenotes", array('oXML'=>$oXML->webstore));

	}


}