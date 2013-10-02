<?php

return searchForComponents();

/**
 * Dynamically load any Web Store Payment and Shipping extensions (wsp and wss prefixed)
 * @return array
 */
function searchForComponents()
{

	$arr = array();
	//$arr['Wsshipping'] = array('class'=>'ext.Wsshipping.Wsshipping');
	foreach (glob(dirname(__FILE__).'/../extensions/wspayment/*', GLOB_ONLYDIR) as $moduleDirectory)
		$arr[basename($moduleDirectory)] = array('class'=>'ext.wspayment.'.basename($moduleDirectory).'.'.basename($moduleDirectory));

	foreach (glob(dirname(__FILE__).'/../extensions/wsshipping/*', GLOB_ONLYDIR) as $moduleDirectory)
		$arr[basename($moduleDirectory)] = array('class'=>'ext.wsshipping.'.basename($moduleDirectory).'.'.basename($moduleDirectory));

	if (file_exists(dirname(__FILE__).'/../../../config/wslogging.php'))
		$arr['log']=require(dirname(__FILE__).'/../../../config/wslogging.php');
	else
		$arr['log']=array(
		'class'=>'CLogRouter',
		'routes'=>array(
			array(
				'class'=>'CFileLogRoute',
				'levels'=>'error, warning',
			),
			array(
				'class'=>'CDbLogRoute',
				'levels'=>'error,warning,info',
				'logTableName'=>'xlsws_log',
				'connectionID'=>'db',
			),
		),
	);



	return $arr;

}


