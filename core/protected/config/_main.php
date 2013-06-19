<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

require_once( dirname(__FILE__) . '/../core/protected/components/helpers.php');
//Web Store version
require_once( dirname(__FILE__) . '/../core/protected/config/wsver.php');
// Define default values
define('XLS_TRUNCATE_PUNCTUATIONS', ".!?:;,-");
define('XLS_TRUNCATE_SPACE', " ");
define('ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and');

//Create alias for custom includes
Yii::setPathOfAlias("config",dirname(__FILE__)."/../config");
Yii::setPathOfAlias("custom",dirname(__FILE__)."/../custom");
Yii::setPathOfAlias('editable', dirname(__FILE__).DIRECTORY_SEPARATOR.'../core/protected/extensions/x-editable');
Yii::setPathOfAlias('ext', dirname(__FILE__).DIRECTORY_SEPARATOR.'../core/protected/extensions');
Yii::setPathOfAlias('extensions', dirname(__FILE__).DIRECTORY_SEPARATOR.'../core/protected/extensions');

if (file_exists(dirname(__FILE__).'/wsconfig.php'))
	$wsconfig = require(dirname(__FILE__).'/wsconfig.php');
else $wsconfig = array();

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../core/protected',
		'runtimePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'../runtime',
		'name'=>'Web Store',
		//'theme'=>'brooklyn', //pulled from wsconfig
		//'language' => 'en', //pulled from wsconfig
		'sourceLanguage' => 'en',

		// preloading 'log' component
		'preload'=>array(
			'log',
			'bootstrap',
		),

		// autoloading model and component classes
		'import'=>array(
			'application.models.*',
			'application.models.base.*',
			'application.models.forms.*',
			'application.components.*',
			'application.components.wscontrollers.*',
			'application.helpers.*',
			'application.extensions.KEmail.KEmail',
			'application.extensions.wsborderlookup.Wsborderlookup',
			'application.extensions.wsshipping.WsShipping',
			'application.extensions.wspayment.WsPayment',
			'application.extensions.MissingMessages.MissingMessages',
			'custom.extensions.*',
			'custom.helpers.*',
			'custom.models.*',
			'custom.xml.*',
			'editable.*' //easy include of editable classes
		),


		'modules' =>
		array_merge(require(dirname(__FILE__).'/../core/protected/config/wsmodules.php'), //dynamically load all modules in /modules
			array(
				// uncomment the following to enable the Gii tool
//	        'gii' => array(
//	            'class' => 'system.gii.GiiModule',
//	            'password' => 'copper',
//	            'generatorPaths' => array(
//	                'application.gii.generators',
//		            'bootstrap.gii',
//	            ),
//	            'ipFilters' => array('127.0.0.1', '::1'),
//	        ),
			)
		),
		//sample of how you would add a custom controller in custom/controllers and map it for use
//	'controllerMap'=>array(
//		'test'=>array(
//			'class'=>'custom.controllers.TestController',
//		),
//	),
		// application components
		'components'=>
		array_merge(require(dirname(__FILE__).'/../core/protected/config/wscomponents.php'), //dynamically load all modules in /modules
			array(
//		'request' => array(
//			'baseUrl' => '',
//		),
				'user'=>array(
					// enable cookie-based authentication
					'allowAutoLogin'=>true,
					'class' => 'WebUser',
				),
				'authManager'=>array(
					'class'=>'CDbAuthManager',
					'connectionID'=>'db',
				),
				//Image manipulation for all the product photo resizing we do
				'image'=>array(
					'class'=>'application.extensions.image.CImageComponent',
					// GD or ImageMagick
					'driver'=>'GD',
					// ImageMagick setup path
					'params'=>array('directory'=>'/opt/local/bin'),
				),
				//This is for foreign language translation
				'messages'=>array(
					'class'=>'CDbMessageSource',
					'sourceMessageTable'=>'xlsws_stringsource',
					'translatedMessageTable'=>'xlsws_stringtranslate',
					'cachingDuration'        => 1200,
					'onMissingTranslation' => array('MissingMessages', 'load'),
				),
				//Component for converting html to plain text (used for email templates)
				'html2text'=>array(
				),
				//This is our own Web Store shopping cart component
				'shoppingcart'=>array(
					'class'=>'ShoppingCart',
				),
				//Twitter bootstrap
				'bootstrap'=>array(
					'class'=>'ext.bootstrap.components.Bootstrap',
					'responsiveCss'=>true,
				),

				'urlManager'=>array(
					'urlFormat'=>'path',
					'caseSensitive' => false,
					'rules'=>array(
						'xls_soap.php' => 'legacysoap/index', //soap
						'/xls_admin.php' => array('admin', 'caseSensitive'=>false,'parsingOnly'=>true),
						'/xls_admin.php/<controller:\w+>/<action:\w+>'=>array('admin/<controller>/<action>', 'caseSensitive'=>false,'parsingOnly'=>true),
						'admin/<controller:\w+>/<action:\w+>'=>'admin/<controller>/<action>',
						'xls_image_upload.php/<type:\w+>/<id:\w+>/<key:\w+>/<position:\w+>' => 'legacysoap/image', //soap
						'brand/<brand:(.*)>' => 'search/browse', //display product
						'<name:(.*)>/dp/<id:[0-9]+>' => 'product/view', //display product
						'<feed:[\w\d\-_\.()]+>.xml' => 'xml/<feed>', //xml feeds
						'wishlist/<action:\w+>/<code:[\w\d\-_\.()]+>' => 'wishlist/<action>',
						'cart/receipt/<getuid:[\w\d\-_\.()]+>' => 'cart/receipt',
						'cart/share/<code:[\w\d\-_\.()]+>' => 'cart/share',
						'cart/quote/<code:[\w\d\-_\.()]+>' => 'cart/quote',
						'sro/view/<code:[\w\d\-_\.()]+>' => 'sro/view',

						//Backwards compatibility, to be removed eventually
						'order-track/pg'=>array('cart/receipt', 'caseSensitive'=>false,'parsingOnly'=>true),

						'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
						'wsborderlookup/<controller:\w+>/<action:\w+>'=>'wsborderlookup/<controller>/<action>',
						'cart/payment/<id:[a-z0-9\-_\.]+>'=>'cart/payment',
						'/custompage'=>'<controller>/<action>',

						array(
							'class' => 'application.components.CustomPageUrlRule', //if we're to this point, we may have a custom page or a category
							'connectionID' => 'db',
						),

						//Backwards compatibility, to be removed eventually
						'<controller:\w+>/pg'=>array('<controller>/index', 'caseSensitive'=>false,'parsingOnly'=>true),

						//Default for everything else
						'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
						'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',

					),
					'showScriptName'=>false,
				),

//		'cache'=>array(
//			'class'=>'system.caching.CFileCache',
//		),

				'cronJobs'=>array(
					'class'=>'application.extensions.wscron.wscron'
				),

				// MySQL database credentials
				'db'=>require(dirname(__FILE__).'/wsdb.php'),

				'errorHandler'=>array(
					// use 'site/error' action to display errors
					'errorAction'=>'site/error',
				),
				'session' => array (
					'sessionName' => 'WebStore',
					'class'=> 'CDbHttpSession',
					'autoCreateSessionTable'=> false,
					'connectionID' => 'db',
					'sessionTableName' => 'xlsws_sessions',
					'autoStart' => 'true',
					'cookieMode' => 'only',
					'timeout' => 3600
				),
				//Email handling
				'email'=>require(dirname(__FILE__).'/wsemail.php'),

				//Facebook integration
				'facebook'=>require(dirname(__FILE__).'/wsfacebook.php'),

				//X-editable config
				'editable' => array(
					'class'     => 'editable.EditableConfig',
					'form'      => 'bootstrap',        //form style: 'bootstrap', 'jqueryui', 'plain'
					'mode'      => 'popup',            //mode: 'popup' or 'inline'
					'defaults'  => array(              //default settings for all editable elements
						'emptytext' => 'Click to set',
					)
				),
			)

		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			// this is used in contact page
			'listPerPage'=>6,
			'mainfile'=>'yes',
		),

	),$wsconfig);