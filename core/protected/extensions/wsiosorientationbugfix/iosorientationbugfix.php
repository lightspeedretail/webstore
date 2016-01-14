<?php

class iosorientationbugfix extends CWidget
{
	public $assetUrl;
		public function init()
		{

			$this->assetUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');

		}
	public function run()
	{
		Yii::app()->clientScript->registerMetaTag(
			'initial-scale=1.0001, minimum-scale=1.0001, ' .
				'maximum-scale=1.0001, user-scalable=no',
			'viewport'
		);
		Yii::app()->clientScript->registerCssFile( $this->assetUrl . '/css/iosbugfix.css' );
		Yii::app()->clientScript->registerScriptFile( $this->assetUrl . '/js/iosbugfix.js' );
	}
}
