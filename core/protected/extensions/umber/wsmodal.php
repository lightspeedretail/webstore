<?php

class wsmodal extends CWidget
{
	public $htmlOptions = array();

	public function run()
	{
		$umberAssetsAlias = 'application.extensions.umber.assets';
		// Publish the asset folder
		Yii::app()->params['umber_assets'] = Yii::app()->assetManager->publish(Yii::getPathOfAlias($umberAssetsAlias));

		// Retrieves version of modal.css to be used from the theme's adminForm.
		// If version is not set the value will be picked from the ThemeForm parent by default set to "1.0.0".
		// In development please set modalVersion in your theme's adminForm to 'dev'.
		$version = Yii::app()->theme->info->modalVersion;

		if ($version === "dev")
		{
			// This does the scss conversion to css and returns the path to the compiled css path.
			// Developer can use the very latest version of modal.css as it is being compiled from scss.
			Yii::app()->params['modal_css'] =
				Yii::app()->sass->publish(
				dirname(__FILE__) . '/assetsToCompile/modal/modal-dev.scss',
					$umberAssetsAlias
				);
		}
		else
		{
			// If customers make a copy of Brooklyn2014, they will be "frozen" into the version modal.css they copied,
			// otherwise they will use the latest version released.
			// Customers who don't have the modalVersion variable set will automatically use the "1.0.0" version.
			Yii::app()->params['modal_css'] =
				Yii::app()->params['umber_assets'] . '/modal-' . $version . '.css';
		}

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
