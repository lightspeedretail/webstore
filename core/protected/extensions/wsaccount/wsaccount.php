<?php
class wsaccount extends CWidget
{
	public function run()
	{
		//creating clientScript instance
		$clientScript = Yii::app()->clientScript;
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets');
		$assets = $dir . 'assets';

		if(!is_dir($assets))
		{
			throw new Exception(get_class($this).' error: Couldn\'t publish assets.');
		}

		$clientScript->registerScriptFile($baseUrl . '/Helper.js', CClientScript::POS_END);
		$clientScript->registerScriptFile($baseUrl . '/Profile.js', CClientScript::POS_END);
		$clientScript->registerScriptFile($baseUrl . '/Address.js', CClientScript::POS_END);
		$clientScript->registerScriptFile($baseUrl . '/Password.js', CClientScript::POS_END);
	}
}