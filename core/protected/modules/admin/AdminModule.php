<?php

class AdminModule extends CWebModule
{

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));

		Yii::app()->setComponents(array(
			'user' => array(
				'class' => 'AdminUser',
				'loginUrl' => Yii::app()->createUrl('admin/login'),
				'allowAutoLogin'=>true,
			)
		),true);


		$this->layout='application.modules.admin.views.layouts.column1';

		if (isset($_POST['url']) && isset($_POST['password']))
		{
			$model=new LoginForm;
			if ($model->loginLightspeed($_POST['user'],$_POST['password']))
				Yii::app()->getRequest()->redirect(Yii::app()->createUrl("/admin"));
			else
				die("You have an invalid password set in your eCommerce options. Cannot continue.");


		}
		if (!Yii::app()->user->isGuest)
			if (Yii::app()->user->shouldLogOut())
				Yii::app()->user->logout(false);
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
