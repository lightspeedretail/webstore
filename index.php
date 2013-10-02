<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/core/framework/yii.php';
$config=dirname(__FILE__).'/config/main.php';

if(!file_exists($config))
{
	die("Web Store is not installed");
	exit;
}

//To put Web Store in Debug mode (Required for trace logging), uncomment the following two lines
//defined('YII_DEBUG') or define('YII_DEBUG',true);
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

Yii::createWebApplication($config)->run();

