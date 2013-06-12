<?php

class IntegrationController extends AdminBaseController
{
	public $controllerName = "Integration";

	//Codes for this controller
	const AMAZON = 29;
	const FACEBOOK = 26;
	const GOOGLE = 20;
	const MAILCHIMP = 30;


	public function actions()
	{
		return array(
			'edit'=>'admin.edit',
			'integration'=>'admin.integration',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','edit','integration','googlematch','amazonmatch','amazonqueue'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{
		$arrModules =  Modules::model()->findAllByAttributes(array('category'=>'CEventCustomer'),array('order'=>'name')); //Get active and inactive

		$menuSidebar = array();
		foreach ($arrModules as $module)
		{

			try {
				Yii::import('application.extensions.'.$module->module.'.'.$module->module);
				$objC = new $module->module;
				$menuSidebar[] = array('label'=>$objC->name, 'url'=>array('integration/integration', 'id'=>$module->module));
			}
			catch (Exception $e) {
				Yii::log("Missing widget ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}

		$this->menuItems = array_merge(
			array(
				array('label'=>'Amazon', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Amazon MWS Settings', 'url'=>array('integration/integration', 'id'=>'wsamazon')),
					array('label'=>'Match Amazon Categories to WS', 'url'=>array('integration/amazonmatch')),
					array('label'=>'Queue for Amazon Tasks', 'url'=>array('integration/amazonqueue')),
				array('label'=>'Email Managers', 'linkOptions'=>array('class'=>'nav-header')),
			),
				$menuSidebar,
			array(
				array('label'=>'Facebook', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Facebook Settings', 'url'=>array('integration/edit', 'id'=>self::FACEBOOK)),
				array('label'=>'Google', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Google Settings', 'url'=>array('integration/edit', 'id'=>self::GOOGLE)),
					array('label'=>'Match Google Categories to WS', 'url'=>array('integration/googlematch')),
			));

		return parent::beforeAction($action);

	}

	/**
	 * Return Instructions to be displayed in admin panel. This function should be overridden in each controller
	 * @param $id
	 * @return null
	 */
	protected function getInstructions($id)
	{

		switch($id)
		{
			case self::FACEBOOK:
				return "<P>To properly set up Facebook functionality, you need to register your site as an \"app\" at https://developers.facebook.com/apps and get an ID. Please consult our documentation for specifics.</p>";

			case self::GOOGLE:
				return "<P>If you are using Google Shopping (Google Merchant Center), your store data feed URL is: <strong>".$this->createAbsoluteUrl('/')."/googlemerchant.xml</strong></p>";



			default:
				return null;
		}

	}

	public function actionIndex()
	{
		$this->render("index");
	}


	public function actionAmazonmatch()
	{
		$this->matchGrid('amazon');

	}

	public function actionAmazonqueue()
	{
		$model = new TaskQueue;
		$model->module = "integration";
		$model->controller = "amazon";

		$this->render('queue',array('model'=>$model));

	}

	public function actionGooglematch()
	{
		$this->matchGrid('google');

	}

	protected function matchGrid($strService)
	{
		$strModelName = "Category".ucfirst($strService); //Model for 3rd party service
		$model = new Category();

		$this->registerAsset("js/integratedcats.js");
		Yii::app()->clientScript->registerScript('setservice', '
	          service = "'.$strService.'";
	      ',CClientScript::POS_HEAD);
		$this->registerAsset('css/integratedcats.css');
		$this->render("integratedmatch", array('model'=>$model,'service'=>$strService,'strModelName'=>$strModelName));


	}



}