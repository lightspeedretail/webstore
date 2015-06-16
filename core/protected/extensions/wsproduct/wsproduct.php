<?php

class wsproduct extends CWidget
{
	public function run()
	{
		//creating clientScript instance
		$clientScript = Yii::app()->clientScript;
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$baseurl = Yii::app()->getAssetManager()->publish($dir . 'assets');
		$assets = $dir.'assets';

		if(!is_dir($assets))
		{
			throw new Exception(get_class($this) . ' error: Couldn\'t publish assets.');
		}

		$clientScript->registerScriptFile($baseurl . '/Product.js', CClientScript::POS_HEAD);

	}
}