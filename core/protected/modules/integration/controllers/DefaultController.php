<?php

class DefaultController extends CController
{
	/*
	 * This module isn't designed to have a display
	 */
	public function actionIndex()
	{
		_xls_404();
	}
}