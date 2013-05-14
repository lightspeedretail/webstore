<?php


class wslangflags extends CWidget {

	public $category = "PROCESSOR_LANGMENU";
	public $name = "Flag language menu";

	public $assetUrl;

	public function init()
	{

		$this->assetUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');

	}


	public function run()
	{
		$currentLang = Yii::app()->language;
		$this->render('index', array('currentLang' => $currentLang));
	}


}


?>