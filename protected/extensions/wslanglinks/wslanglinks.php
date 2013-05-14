<?php


class wslanglinks extends CWidget {

	public $category = "PROCESSOR_LANGMENU";
	public $name = "Text language menu";

	/* Runs widget which simply loads the search template
	*/
	public function run()
	{
		$currentLang = Yii::app()->language;
		$this->render('index', array('currentLang' => $currentLang));

	}


}


?>