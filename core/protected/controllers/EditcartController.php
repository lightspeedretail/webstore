<?php

class EditcartController extends Controller
{
	public function actionIndex()
	{
		$this->redirect(array('search/browse', 'editcart'=> 'true'));
	}
}