<?php

class AdminModule extends CWebModule
{

	public function init()
	{
		Controller::initParams();
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		Yii::app()->setComponent('bootstrap',array(
				'class' => 'ext.bootstrap.components.Bootstrap',
				'responsiveCss' => true,
		));
		Yii::setPathOfAlias('bootstrap', dirname(__FILE__).DIRECTORY_SEPARATOR.'../../extensions/bootstrap');
		Yii::app()->bootstrap->init();

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));

		Yii::app()->setComponents(array(
			'user' => array(
				'class' => 'AdminUser',
				'loginUrl' => Yii::app()->createAbsoluteUrl('admin/login'),
				'allowAutoLogin' => true,
			)
		),true);

		$this->layout = 'application.modules.admin.views.layouts.column1';

		if(Yii::app()->params['STORE_OFFLINE'] == '-1')
			die('Admin Panel unavailable due to account suspension.');

		if (isset($_POST['url']) && isset($_POST['password']))
		{
			$model = new LoginForm();
			if ($model->loginLightspeed($_POST['user'],$_POST['password']))
				Yii::app()->getRequest()->redirect(Yii::app()->createUrl("/admin"));
			else
				die("You have an invalid password set in your eCommerce options. Cannot continue.");


		}
		if (!Yii::app()->user->isGuest)
			if (Yii::app()->user->shouldLogOut())
				Yii::app()->user->logout(false);

		_xls_set_conf('ADMIN_PANEL',date("Y-m-d H:i:s"));

		parent::init();

	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
