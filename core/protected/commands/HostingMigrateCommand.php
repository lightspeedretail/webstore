<?php


Yii::import('system.cli.commands.MigrateCommand');

/**
 * Hosting Migrate, to use on hosting system where we need to pass db credentials
 */
class HostingMigrateCommand extends MigrateCommand
{

	/**
	 * database server host
	 * @var null
	 */
	public $dbhost = null;
	/**
	 * database user
	 * @var null
	 */
	public $dbuser = null;
	/**
	 * database password
	 * @var null
	 */
	public $dbpass = null;
	/**
	 * database name
	 * @var null
	 */
	public $dbname = null;

	/**
	 * Hosting mode (M for Multi-tenant, S for Single tenant, and T for Multi-tenant staging)
	 * @var null
	 */
	public $hosting = null;

	/**
	 * Verify our parameters have been passed before continuing, halt in case of errors.
	 *
	 * @param string $action
	 * @param array $params
	 * @return bool
	 */
	public function beforeAction($action, $params)
	{
		if ($this->validHostingSwitch($this->hosting) === false ||
			$this->validateDbCliArgs($action) === false
		)
		{
			return false;
		}

		$this->migrationTable = 'xlsws_migrations';

		return parent::beforeAction($action, $params);
	}

	/**
	 * Ensure the --hosting command line argument is present and valid.
	 * If not, display an error message.
	 *
	 * @param $str
	 * @return bool
	 */
	protected function validHostingSwitch($str)
	{
		$strError = "\n*error halting*\n";
		$strError .= "\t--hosting flag takes one of three values:\n";
		$strError .= "\tM for Multi-tenant (Retail), T for Multi-tenant staging (Retail), S for Single tenant (Onsite), and N for Non-Hosted (Onsite)\n";

		if (empty($str))
		{
			$strError .= "\t--hosting flag MUST be present.\n\n";
			echo $strError;
			return false;
		}

		$arrAllowed = array('M','T','S','N');
		if (in_array($str, $arrAllowed) === false)
		{
			echo $strError . "\n";
			return false;
		}

		return true;
	}


	/**
	 * Ensure the required database credentials are present if passed on the command line:
	 * If not, display an error
	 *
	 * @param $action
	 * @return bool
	 */
	protected function validateDbCliArgs($action)
	{
		// if any one of these is present...
		if (!empty($this->dbhost) ||
			!empty($this->dbuser) ||
			!empty($this->dbpass) ||
			!empty($this->dbname)
		)
		{
			// ...then they must ALL be present
			if (empty($this->dbhost) ||
				empty($this->dbuser) ||
				empty($this->dbpass) ||
				empty($this->dbname)
			)
			{
				echo "\n*error halting*\n";
				echo "\tusage: yiic hostingmigrate $action --dbhost=127.0.0.1 --dbuser=root --dbpass=mypass --dbname=webstore --hosting=M\n";
				echo "\t--hosting flag takes one of three values:\n";
				echo "\tM for Multi-tenant (Retail), T for Multi-tenant staging (Retail), S for Single tenant (Onsite), and N for Non-Hosted (Onsite)\n\n";
				echo "You must include all database credentials when passing them.\n";
				echo "Or else,\n\n\tusage: yiic hostingmigrate $action --hosting=M --interactive=0\n\n";
				return false;
			}

			$this->setDbForMigration();
			$this->connectionID = "dbmt";
		}

		return true;
	}
	/**
	 * Establish database component for passed in credentials.
	 *
	 * @return void
	 */
	protected function setDbForMigration()
	{

		Yii::app()->setComponent(
			'dbmt',
			array(
				'connectionString' => 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname,
				'username' => $this->dbuser,
				'password' => $this->dbpass,
				'class' => 'CDbConnection',
				'charset' => 'utf8'
			)
		);

	}

	/**
	 * Apply a single database upgrade step.
	 *
	 * @param $args
	 * @return void
	 */
	public function actionUp($args)
	{
		$component = $this->connectionID;

		switch ($this->hosting)
		{
			case 'S':

				$intVer = Yii::app()->$component->createCommand("SELECT key_value FROM xlsws_configuration WHERE `key_name` = 'DATABASE_SCHEMA_VERSION'")->queryScalar();
				if($intVer !== false && (int)$intVer < 447)
				{
					// Override certain migrations to support legacy schema order
					$this->migrationPath = Yii::getPathOfAlias('application.migrations.legacy');
					// Execute yii migrations
					parent::actionUp($args);

					// Point to standard migrations for next migration leg
					$this->migrationPath = Yii::getPathOfAlias('application.migrations');

					// Reset migration position to non-legacy
					Yii::app()->$component->createCommand()->truncateTable('xlsws_migrations');

					$this->actionMark(array('m140429_224114_update_configuration'));
				}

				parent::actionUp($args);
				Yii::app()->$component->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'LIGHTSPEED_HOSTING'")->execute();
				Yii::app()->$component->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'ENABLE_SSL'")->execute();
				break;

			case 'M':
			case 'T':
				parent::actionUp($args);
				Yii::app()->$component->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'LIGHTSPEED_MT'")->execute();
				Yii::app()->$component->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'LIGHTSPEED_HOSTING'")->execute();
				Yii::app()->$component->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'ENABLE_SSL'")->execute();
				break;

			case 'N':
				// yiic hostingmigrate up --hosted=N === yiic migrate up
				// i.e. if no command line arguments defining a specific database
				// are present, this does the same thing as yiic migrate up
				parent::actionUp($args);
				break;
		}
	}

	public function runTask($id)
	{
		$configFile = YiiBase::getPathOfAlias('webroot')."/../../config/main.php";

		Yii::log("Running upgrade task $id.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		switch($id)
		{

			case 417:
				//Remove wsconfig.php reference from /config/main.php

				if (Yii::app()->params['LIGHTSPEED_MT'] == 1)
					return;	//only applies to single tenant
				$main_config = file_get_contents($configFile);

				// @codingStandardsIgnoreStart
				$main_config=str_replace('if (file_exists(dirname(__FILE__).\'/wsconfig.php\'))
	$wsconfig = require(dirname(__FILE__).\'/wsconfig.php\');
else $wsconfig = array();','//For customization, let\'s look in custom/config for a main.php which will be merged
//Use this instead of modifying this main.php directly
if(file_exists(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\'))
	$arrCustomConfig = require(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\');
else
	$arrCustomConfig = array();',$main_config);

				$main_config = str_replace('),$wsconfig);','),
	$arrCustomConfig
);',$main_config);
				// @codingStandardsIgnoreEnd
				file_put_contents($configFile,$main_config);
				break;

			case 423:
				// add cart/cancel url rule for sim payment methods (ex. moneris) that require hardcoded cancel urls

				$main_config = file_get_contents($configFile);

				// check to see if the entry is already there and write it if it isn't
				$position = strpos($main_config,'cart/cancel');
				if (!$position)
				{
					$comments = "\r\r\t\t\t\t\t\t// moneris simple integration requires a hardcoded cancel URL\r\t\t\t\t\t\t// any other methods that require something similar we can add a cart/cancel rule like this one\r\t\t\t\t\t\t";

					$pos = strpos($main_config, "sro/view',") + strlen("sro/view',");
					$main_config = substr_replace($main_config, $comments."'cart/cancel/<order_id:\WO-[0-9]+>&<cancelTXN:(.*)>'=>'cart/cancel',\t\t\t\t\t\t",$pos,0);
					file_put_contents($configFile,$main_config);
				}
				break;

			case 427:
				// Add URL mapping for custom pages

				// If the store's on multi-tenant server, do nothing
				if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
				{
					return;
				}

				$main_config = file_get_contents($configFile);
				$search_string = "'<id:(.*)>/pg'";

				// Check if the entry already exists. If not, add the mapping.
				if (strpos($main_config, $search_string) === false)
				{
					$position = strpos($main_config, "'<feed:[\w\d\-_\.()]+>.xml' => 'xml/<feed>', //xml feeds");
					$custompage_mapping = "'<id:(.*)>/pg'=>array('custompage/index', 'caseSensitive'=>false,'parsingOnly'=>true), //Custom Page\r\t\t\t\t\t\t";
					$main_config = substr_replace($main_config, $custompage_mapping, $position, 0);
					file_put_contents($configFile,$main_config);
				}
				break;

			case 447:
				// Remove bootstrap, add in separate main.php

				// If the store's on multi-tenant server, do nothing
				if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
				{
					return;
				}

				$main_config = file_get_contents($configFile);
				// @codingStandardsIgnoreStart

				//Remove preloading bootstrap, loaded now in Controller.php on demand if needed
				$main_config=str_replace("\t\t\t'bootstrap',\n","",$main_config);

				//Bootstrap is loaded on demand now
				$main_config=str_replace("//Twitter bootstrap
				'bootstrap'=>array(
					'class'=>'ext.bootstrap.components.Bootstrap',
					'responsiveCss'=>true,
				),","",$main_config);

				//Remove old email strings and facebook strings, they're loaded elsewhere now
				$main_config=str_replace("//Email handling\n\t\t\t\t'email'=>require(dirname(__FILE__).'/wsemail.php'),\n","",$main_config);

				//Remove old email strings and facebook strings, they're loaded elsewhere now
				$main_config=str_replace("//Facebook integration\n\t\t\t\t'facebook'=>require(dirname(__FILE__).'/wsfacebook.php'),\n","",$main_config);


				//for any main.php that was missing all of this before
				$main_config = str_replace('),array());','),
	$arrCustomConfig
);',$main_config);
				$main_config = str_replace('	\'params\'=>array(
		// this is used in contact page
		\'mainfile\'=>\'yes\',
	),

);','	\'params\'=>array(
		// this is used in contact page
		\'mainfile\'=>\'yes\',
	)),
	$arrCustomConfig
);',$main_config);
				$main_config = str_replace('// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(','// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	array(',$main_config);

				$search_string = "//For customization,";

				// Check if the entry already exists. If not, add the mapping.
				if (strpos($main_config, $search_string) === false)
					$main_config = str_replace('Yii::setPathOfAlias(\'extensions\', dirname(__FILE__).DIRECTORY_SEPARATOR.\'../core/protected/extensions\');

','Yii::setPathOfAlias(\'extensions\', dirname(__FILE__).DIRECTORY_SEPARATOR.\'../core/protected/extensions\');

//For customization, let\'s look in custom/config for a main.php which will be merged
//Use this instead of modifying this main.php directly
if(file_exists(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\'))
	$arrCustomConfig = require(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\');
else
	$arrCustomConfig = array();

',$main_config);
				// @codingStandardsIgnoreEnd


				file_put_contents($configFile,$main_config);

				@unlink(YiiBase::getPathOfAlias('webroot')."/../../config/wsemail.php");
				@unlink(YiiBase::getPathOfAlias('webroot')."/../../config/wsfacebook.php");

				break;

		}
	}
}



