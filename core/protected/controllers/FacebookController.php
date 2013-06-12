<?php

/**
 * Class FacebookController
 */
class FacebookController extends Controller
{



	/**
	 * When we've detected
	 */
	public function actionLogin()
	{



	}

	/**
	 * Post authorization, set up an account
	 */
	public function actionCreate()
	{
		//Remove any prior session
		Yii::app()->user->logout();

		//Recreate session by calling this function which will hit our FBIdentity
		$userid = Yii::app()->facebook->getUser();
		$this->redirect(Yii::app()->homeUrl);
	}
}
