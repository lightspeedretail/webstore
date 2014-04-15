<?php

class Theme extends CTheme
{

	/**
	 * Return the model name for our theme to be used for the Admin Panel
	 * @param $strThemeName
	 * @return bool
	 */
	public static function hasAdminForm($strThemeName)
	{
		$model = Yii::app()->getComponent('wstheme')->getAdminModel($strThemeName);
		if($model) return true; else return false;

	}

	/**
	 * Depending on if we have customized the file, return the URL
	 * @param $cssFile
	 * @return string
	 */
	public function getCssUrl($cssFile)
	{
		//Just in case someone passed the .css as part of the $cssFile
		if(substr($cssFile,strlen($cssFile)-4,4)==".css")
			$cssFile = substr($cssFile,0,-4);

		$customCss = Yii::app()->theme->config->customcss;
		if(empty($customCss) || !in_array($cssFile,$customCss))
			return parent::getBaseUrl()."/css/".$cssFile.".css";
		else
			return str_replace("http:","",_xls_custom_css_folder()).
			"_customcss/".Yii::app()->theme->name."/".$cssFile.".css";

	}

	/**
	 * Get CSS Url which may vary if we have customized. Since the getter doesn't pass parameters, we make our
	 * own here.
	 * @param $cssFile
	 * @return string
	 */
	public function cssUrl($cssFile)
	{
		return $this->getCssUrl($cssFile);

	}


	/**
	 * Pass along theme config from the xlsws_modules table
	 * @return ThemeConfig
	 */
	public function getConfig()
	{
		return new ThemeConfig();
	}

	/**
	 * Write theme configuration
	 * @return ThemeConfig
	 */
	public function setConfig($mix)
	{
		print_r($this);
		print_r($mix);
		die("");
		return new ThemeConfig();
	}

	/**
	 * @return ThemeInfo
	 */
	public function getInfo()
	{
		return new ThemeInfo();
	}


}

class ThemeConfig
{

	/*
	 * Get a key from the module. If it's not defined, use the xlsws_configuration as a backup
	 */
	public function __get($name)
	{

		switch ($name)
		{
			default:
				$arrConfig = Yii::app()->getComponent('wstheme')->getConfigValues(Yii::app()->theme->name);
				if(isset($arrConfig[$name]))
					return $arrConfig[$name];
				else return (_xls_get_conf($name,null));
		}


	}

	/*
	 * Get a key from the module. If it's not defined, use the xlsws_configuration as a backup
	 */
	public function __set($name,$mixValue)
	{

		$arrConfig = Yii::app()->getComponent('wstheme')->getConfigValues(Yii::app()->theme->name);
		$arrConfig[$name]=$mixValue;
		Yii::app()->getComponent('wstheme')->setConfigValues($arrConfig);

		return true;

	}


}
class ThemeInfo
{

	/*
	 * Get a key from the module. If it's not defined, use the xlsws_configuration as a backup
	 */
	public function __get($name)
	{
		switch($name)
		{

			case 'cssfiles':
				$model = Yii::app()->getComponent('wstheme')->getAdminModel(Yii::app()->theme->name);
				if(!$model) return array('base','style');
				$form = new $model;
				$arrCss= $form->cssfiles;
				return explode(",",$arrCss);
			break;

			default:
				$model = Yii::app()->getComponent('wstheme')->getAdminModel(Yii::app()->theme->name);
				if(!$model) return null;
				$form = new $model;
				return $form->$name;

		}


	}

}

