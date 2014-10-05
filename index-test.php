<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

// change the following paths if necessary
$yii=dirname(__FILE__).'/core/framework/yii.php';
$wsbootstrap=dirname(__FILE__).'/core/protected/components/WsWebApplication.php';
$config=dirname(__FILE__).'/core/protected/config/test.php';

require_once($yii);
require_once($wsbootstrap);
$app = Yii::createApplication('WsWebApplication',$config);

$objTheme = Configuration::model()->find('key_name=?', array('THEME'));
if ($objTheme instanceof Configuration)
	$theme = $objTheme->key_value; else $theme = "brooklyn";
$objLangCode = Configuration::model()->find('key_name=?', array('LANG_CODE'));
if ($objLangCode instanceof Configuration)
	$lang = $objLangCode->key_value; else $lang = "en";
$app->theme=$theme;
$app->language = $lang;

$Params = CHtml::listData(Configuration::model()->findAllByAttributes(array('param'=>1)),'key_name','key_value');

foreach($Params as $key=>$value)
	$app->params->add($key, $value);
if($app->params['DEBUG_LOGGING']=="trace")
{
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

}
$app->run();
