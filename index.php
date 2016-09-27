<?php

// change the following paths if necessary
$config=dirname(__FILE__).'/config/main.php';

if(!file_exists($config))
{
	header("Location: install.php");
	exit;
}

//To put Web Store in Debug mode (Required for trace logging), uncomment the following two lines
//defined('YII_DEBUG') or define('YII_DEBUG',true);
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// include the customized Yii class described below
require(__DIR__ . '/core/protected/components/Yii.php');

require(__DIR__ . '/vendor/autoload.php');

$yii2Config = require(__DIR__ . '/config/yii2/web.php');
new yii\web\Application($yii2Config);

// configuration for Yii 1 app
$config=dirname(__FILE__).'/config/main.php';
require_once(dirname(__FILE__).'/core/protected/components/WsWebApplication.php');
Yii::createApplication('WsWebApplication',$config)->run();
