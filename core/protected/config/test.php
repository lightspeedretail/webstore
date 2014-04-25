<?php

$_SESSION['DUMMY']="nothing"; //needed to force creation of $_SESSION which is used in some tests

// check for a testrun.ini
if (file_exists (realpath(dirname(__FILE__).'/../../../config').'/testrun.ini')){
	$_SERVER['testini'] = parse_ini_file(realpath(dirname(__FILE__).'/../../../config').'/testrun.ini');
	$_SERVER['SERVER_NAME'] = $_SERVER['testini']['SERVER_NAME'];
	$_SERVER['HTTP_HOST'] = $_SERVER['testini']['HTTP_HOST'];
	$_SERVER['db_user'] = $_SERVER['testini']['db_user'];
	$_SERVER['db_pass'] = $_SERVER['testini']['db_pass'];
}
else{ // we'll use the defaults
	// check to see if the file exists before we try to read it
	if (file_exists(dirname(__FILE__) . '/../tests/testrun.ini.defaults')){
		$_SERVER['testini'] = parse_ini_file(dirname(__FILE__) . '/../tests/testrun.ini.defaults');
		$_SERVER['SERVER_NAME'] = $_SERVER['testini']['SERVER_NAME'];
		$_SERVER['HTTP_HOST'] = $_SERVER['testini']['HTTP_HOST'];
		$_SERVER['db_user'] = $_SERVER['testini']['db_user'];
		$_SERVER['db_pass'] = $_SERVER['testini']['db_pass'];
	}
	else{
		$_SERVER['testini'] = NULL; // if prod code is trying to run this, bad things should happen.
	}
}

$arrConfig = require(dirname(__FILE__).'/../../../config/main.php');
//unset($arrConfig['components']['db']);
//print_r($arrConfig);
return CMap::mergeArray(
	$arrConfig,
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			/* uncomment the following to provide test database connection */
			'db'=>array(
				'connectionString' => $_SERVER['testini']['myconnect'],
				'schemaCachingDuration'=>0,
			),
		),
		// autoloading model and component classes
		//load everything for unit tests since PHPUnit controller tests will fail with lazy loading
		'import'=>array(
			'application.models.*',
			'application.models.base.*',
			'application.components.*',
			'application.helpers.*',
			'application.controllers.*',
            'application.modules.admin.controllers.*',
            'application.modules.admin.components.*',
            'application.modules.admin.models.*'
		),


	)
);
