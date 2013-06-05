<?php

$arrConfig = require(dirname(__FILE__).'/main.php');
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
				'connectionString' => 'mysql:host=localhost;dbname=copper-unittest',
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
			'application.controllers.*'
		),


	)
);
