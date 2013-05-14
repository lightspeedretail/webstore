<?php

class LoginController extends AdminBaseController
{

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index'),
				'users'=>array('*'),
			),
		);
	}


	public function beforeAction($action)
	{
		return true;

	}
	public function actionIndex()
	{

		$this->layout = "login";

		$model=new LoginForm;

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->loginadmin())
				$this->redirect($this->createAbsoluteUrl("/admin/default"));
			else $model->addError('password',Yii::t('global','Invalid username or password.'));
		}
		// display the login form
		$this->render('index',array('model'=>$model));




	}


}