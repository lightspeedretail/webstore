<?php

class DefaultController extends CController
{

	/*
	 * This module isn't designed to have a display
	 */
	public function actionIndex()
	{
		throw new CHttpException(404,'The requested page does not exist.');

	}

}