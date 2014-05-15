<?php
class wsaccesswarning extends WsExtension
{
	public function init()
	{
		parent::init();

		Yii::import('ext.wsaccesswarning.models.*');
	}

	public function displayAccessWarning()
	{
		if(Yii::app()->isCommonSSL)
			return;

		$access_warning_cookie = Yii::app()->request->cookies['access_warning'];
		if ($access_warning_cookie === null || $access_warning_cookie->value !== 'false')
		{
			$objModule = Modules::model()->LoadByName('wsaccesswarning');
			if (!$objModule)
			{
				Yii::import('ext.wsaccesswarning.models.*');

				$arrDefaultConfig = $this->getAdminModel()->getDefaultConfiguration();

				$objModule = new Modules();
				$objModule->module = 'wsaccesswarning';
				$objModule->category = 'extension';
				$objModule->name = 'Site Access Warning';
				$objModule->version = 1;
				$objModule->active = 0;
				$objModule->configuration =  serialize($arrDefaultConfig);
				$objModule->save();
			}

			if ($objModule->active)
			{
				$arrConfig = $objModule->GetConfigValues();

				$globalScript = sprintf(
					"var accessWarningMessage = %s;",
					json_encode($this->_transformMessageForDisplay($arrConfig['message']))
				);

				// No HTML tags are allowed in the button caption so we encode to HTML entities.
				$globalScript .= sprintf(
					"var accessWarningButtonCaption = %s;",
					json_encode(CHtml::encode($arrConfig['button_caption']))
				);

				$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets');
				$cs = Yii::app()->getClientScript();

				$cs->registerScript('_', $globalScript, CClientScript::POS_HEAD);
				$cs->registerCssFile($assets . '/css/wsaccesswarning.css');
				$cs->registerScriptFile($assets . '/thirdparty/carhartl-jquery-cookie/jquery.cookie.js');
				$cs->registerScriptFile($assets . '/js/wsaccesswarning.js');
			}
		}
	}

	/**
	* Make necessary transformations to the message before displaying it to the user.
	* @param string $message String The original message.
	* @return string The modified message.
	*/
	private function _transformMessageForDisplay($message)
	{
		// Even after replacing newlines with linebreaks, the string still
		// contains carriage returns. This is a bit odd, but easily dealt with.
		$newMessage = $message;
		$newMessage = str_replace("\n", '<br>', $newMessage);
		$newMessage = str_replace("\r", '', $newMessage);
		return $newMessage;
	}
}