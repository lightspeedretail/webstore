<?php

	/* Theme module */
class WsTheme extends WsExtension
{
	public $subformModel;
	public $moduleType = 'theme';

	/**
	 * The name of the payment module that will be displayed in Web Admin payments
	 * @return string
	 */
	public function admin_name()
	{
		return Yii::app()->theme->name;
	}

	public function getAdminModel($strTheme = null)
	{
		if(is_null($strTheme))
			$strTheme = Yii::app()->theme->name;

		$className = $strTheme."AdminForm";
		$filename = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.$strTheme.DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR.$className.'.php';

		if(file_exists($filename))
		{
			Yii::import('webroot.themes.'.$strTheme.'.models.*');
			return new $className;

		}
		else
			return null;

	}

	public function getDefaultConfiguration()
	{
		$adminModel = $this->getAdminModel();
		if (!is_null($adminModel))
		{
			$arrAttributes = $adminModel->attributes;
			$arrAttributes['label'] = strip_tags($this->AdminName);
			return serialize($arrAttributes);
		}
		else return false;
	}

	/**
	 * Build the specific filename based on the classname. The classname should be our payment/shipping class (in lower case) with AdminForm appended.
	 * The filename is the same with the addition of the .php extension in a subfolder called models
	 * @return string
	 */
	public function getAdminModelName()
	{
		$className = Yii::app()->theme->name."AdminForm";

		return $className;
	}


	/**
	 * Set configuration for selected theme
	 * @param $arr
	 * @return bool
	 */
	public function setConfigValues($arr) {

		$strClass = Yii::app()->theme->name;


		$objModule = Modules::model()->findByAttributes(array(
			'module'=>$strClass,
			'category'=>'theme',
			)
		);
		if ($objModule instanceof Modules) {

			try{
				$objModule->configuration = serialize($arr);
				$objModule->save();
				return true;
			}catch(Exception $e){
				Yii::log("Could not save " . $strClass. " . Error : " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}
		}

	}


	/**
	 * Returns theme configuration
	 * @param null $strClass
	 * @return array|mixed
	 */
	public function getConfigValues($strClass = null) {

		$strClass = Yii::app()->theme->name;
		$arr = array();

		$objModule = Modules::model()->findByAttributes(array(
				'module'=>$strClass,
				'category'=>'theme'
			)
		);
		if ($objModule instanceof Modules) {

			try
			{
				$arr = unserialize($objModule->configuration);
			}catch(Exception $e){
				Yii::log("Could not unserialize " . $strClass. " . Error : " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return array();
			}
		}

		return $arr;
	}


}