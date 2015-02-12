<?php

class jqueryHistoryJs extends CWidget
{
	public $assetUrl;
	public function init()
	{

		$this->assetUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR );

	}
	public function run()
	{
		Yii::app()->clientScript->registerScriptFile( $this->assetUrl . '/jquery.history.js' );
	}
}
