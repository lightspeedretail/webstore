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

	$arr['wstheme'] = array('class' => 'ext.wstheme.WsTheme');
	$arr['wsadvcheckout'] = array('class' => 'ext.wsadvcheckout.wsadvcheckout');
	$arr['themeManager'] = array('themeClass' => 'Theme');
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
				'class'=>'CDbLogRoute',
				'levels'=>'error, warning',
				'logTableName'=>'xlsws_log',
				'connectionID'=>'db',
			),
			array(
				'class'=>'ext.syslogroute.ESysLogRoute',
				'levels'=>'error, warning',
				'categories'=>'application.*,system.*',
			),
			array(
				'class'=>'CFileLogRoute',
				'levels'=>'error, warning',
			),
		),
	);

	// Enable CSRF validation for forms
	$arr['request'] = array(
		'class' => 'HttpRequest',
		'enableCsrfValidation' => true,
		'enableCsrfValidationRoutes' =>
			array(
				'/myaccount',
				'/cart/checkout'
			),
	);

	$arr['sass'] = array(
		// Path to the SassHandler class
		'class' => 'application.extensions.yii-sass.SassHandler',

		// Path and filename of scss.inc.php
		'compilerPath' => dirname(__FILE__) . '/../vendors/scssphp/scss.inc.php',

		// Path and filename of compass.inc.php
		// Required only if Compass support is required
		'compassPath' => dirname(__FILE__) . '/../vendors/scssphp-compass/compass.inc.php',

		// Enable Compass support, defaults to false
		'enableCompass' => true,

		// Path for the cache files
		'cachePath' => 'webroot.runtime.sass-cache',

		// Path to the directory with compiled CSS files
		'sassCompiledPath' => 'webroot.runtime.sass-compiled'
	);

	$arr['clientScript'] = array(
		'class' => 'application.extensions.minifyclientscript.MinifyClientScript'
	);

	$arr['sprite'] = array(
		'class' => 'application.extensions.NSprite.NSprite'
	);

	return $arr;
}
