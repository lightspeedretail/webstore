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

	$arr['wstheme'] = array('class'=>'ext.wstheme.WsTheme');
	$arr['themeManager']=array('themeClass'=>'Theme');
	//Load any custom payment components
	$path = realpath(YiiBase::getPathOfAlias('webroot')."custom/extensions/payment");
	$arrCustom = glob($path.'/*', GLOB_ONLYDIR);
	if($arrCustom !== false && !empty($arrCustom))
		foreach ($arrCustom as $moduleDirectory)
			$arr[basename($moduleDirectory)] = array('class'=>'custom.extensions.payment.'.basename($moduleDirectory).'.'.basename($moduleDirectory));

	//Load any custom shipping components
	$path = realpath(YiiBase::getPathOfAlias('webroot')."custom/extensions/shipping");
	$arrCustom = glob($path.'/*', GLOB_ONLYDIR);
	if($arrCustom !== false && !empty($arrCustom))
		foreach ($arrCustom as $moduleDirectory)
			$arr[basename($moduleDirectory)] = array(
				'class'=>'custom.extensions.shipping.'.basename($moduleDirectory).'.'.basename($moduleDirectory));

	$arr['Smtpmail'] = array('class'=>'application.extensions.smtpmail.PHPMailer');


	$arr['log']=array(
		'class'=>'CLogRouter',
		'routes'=>array(
			array(
				'class'=>'CFileLogRoute',
				'levels'=>'error, warning',
			),
			array(
				'class'=>'CDbLogRoute',
				'levels'=>'error, warning',
				'logTableName'=>'xlsws_log',
				'connectionID'=>'db',
			),
		),
	);



	return $arr;

}


