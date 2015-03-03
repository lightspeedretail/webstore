<?php

class wsmodal extends CWidget
{
	public $htmlOptions = array();

	public function run()
	{
		$umberAssetsAlias = 'application.extensions.umber.assets';
		// Publish the asset folder
		Yii::app()->params['umber_assets'] = Yii::app()->assetManager->publish(Yii::getPathOfAlias($umberAssetsAlias));

		// This does the scss conversion to css and returns the path to the compiled css path
		Yii::app()->params['modal_css'] = Yii::app()->sass->publish(dirname(__FILE__) . '/assetsToCompile/modal/modal.scss', $umberAssetsAlias);

		static::_createSprites();

		$jsAssets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');
		Yii::app()->clientScript->registerScriptFile($jsAssets . '/wsmodal.js', CClientScript::POS_END);
	}

	/**
	 * Create sprite images and CSS files for the current theme
	 */
	private static function _createSprites() {

		Yii::app()->sprite->imageFolderPath = array(
			Yii::getPathOfAlias('ext.umber.assets.images') . '/cartandcheckout/sprites',
			Yii::getPathOfAlias('webroot') . '/themes/' .  Yii::app()->theme->name . '/css/images/sprites',
		);

		Yii::app()->sprite->retinaImageFolderPath = array(
			Yii::getPathOfAlias('ext.umber.assets.images') . '/cartandcheckout/retina-sprites',
			Yii::getPathOfAlias('webroot') . '/themes/' .  Yii::app()->theme->name . '/css/images/retina-sprites',
		);

		Yii::app()->sprite->registerSpriteCss();
	}

}