<?php

$strPath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
// check for a testrun.ini
if (file_exists ($strPath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'testrun.ini')){
	$_SERVER['testini'] = parse_ini_file($strPath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'testrun.ini');
	$_SERVER['HTTP_HOST'] = $_SERVER['testini']['HTTP_HOST'];
}

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>$strPath.'/core'.DIRECTORY_SEPARATOR.'protected',
	'runtimePath'=>realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'runtime'),

	'name'=>'Web Store Updater',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.base.*',
		'application.models.forms.*',
		'application.components.*',
		'application.components.wscontrollers.*',
		'application.helpers.*'
	),

	'commandMap' => array(
		'migrate' => array(
			'class' => 'system.cli.commands.MigrateCommand',
			'migrationTable' => 'xlsws_migrations'
		)
	),

	// application components
	'components'=>array(

		// uncomment the following to use a MySQL database
		'db'=> require(realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config').DIRECTORY_SEPARATOR.'wsdb.php'),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);