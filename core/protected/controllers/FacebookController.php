<?php

/**
 * Class FacebookController
 */
class FacebookController extends Controller
{
	/**
	 * 3600 seconds = 60 minutes = 1 hour
	 */
	const ONE_HOUR = 3600;

	/**
	 * Create Facebook session and login
	 *
	 * @return void
	 */
	public function actionCreate()
	{
		// Remove any prior session
		Yii::app()->user->logout();

		if (Yii::app()->user->isGuest)
		{
			$userid = Yii::app()->facebook->getUser();

			if ($userid > 0)
			{
				$results = Yii::app()->facebook->api('/'.$userid);

				if (!isset($results['email']))
				{
					// We've lost our authentication, user may have revoked
					Yii::app()->facebook->destroySession();
					$this->redirect(Yii::app()->createUrl("site/index"));
				}

				$identity = new FBIdentity($results['email'], $userid); // Set the userid as the password
				$identity->authenticate();

				if ($identity->errorCode === UserIdentity::ERROR_NONE)
				{
					Yii::app()->user->login($identity, self::ONE_HOUR);
					$this->redirect(Yii::app()->createUrl("site/index"));
				}
			}
		}
	}
}
