<?php

class wsmodal extends CWidget
{
	public $htmlOptions = array();

	public function run()
	{
		// This does the scss conversion to css
		Yii::app()->params['modal_css'] = Yii::app()->sass->publish(dirname(__FILE__) . '/assets/modal/modal.scss');

		$jsAssets= Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');
		Yii::app()->clientScript->registerScriptFile($jsAssets . '/wsmodal.js', CClientScript::POS_END);

	}

}