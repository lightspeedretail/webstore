<?php

class wscartanimate extends CWidget
{

	public function run()
	{
		//creating clientScript instance
		$clientScript = Yii::app()->clientScript;
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$baseurl = Yii::app()->getAssetManager()->publish($dir . 'assets');
		$js_options = array();
		$assets=$dir.'assets';

		if(!is_dir($assets))
			throw new Exception(get_class($this).' error: Couldn\'t publish assets.');

		$jsCode = <<<SETUP
function animateAddToCart() {}
SETUP;

		if (Yii::app()->theme->config->animateAddToCart == "1")
			$clientScript->registerScriptFile($baseurl.'/cartanimate.js',CClientScript::POS_HEAD);
		else
			$clientScript->registerScript(get_class($this), $jsCode, CClientScript::POS_HEAD);



	}
}