<?php

class UpgradeController extends CController
{

	public $controllerName = "Upgrade";

	public function actionIndex()
	{

		$patch = Yii::app()->getRequest()->getQuery('patch');

		if(!isset($patch))
			$patch = "";
		echo $this->renderPartial("index",array('patch'=>$patch),true);

	}

	/*
	 * Spelled thusly, for a "double-dose" of Web Store.
	 */
	public function actionUpgrayedd()
	{
		$online = _xls_number_only($_POST['online']);

		switch ($online)
		{
			case 10:
				$this->actionDownload();
				break;
			case 20:
				$this->actionVerifyVersion();
				break;
			case 30:
				$this->actionVerifyWriteAccess();
				break;
			case 40:
				$this->actionPlaceFiles();
				break;

			case 50:
			case 55:
			case 60:
			case 65:
			case 70:
			case 75:
			case 80:
			case 85:
			case 90:
			case 95:
				$this->actionDatabaseUpgrade($online);
				break;
		}

	}



	protected function actionDownload()
	{
		$patch = Yii::app()->getRequest()->getQuery('patch');
		if(empty($patch))
		{
			//If we hit this with no patch file, probably triggered by db updates that need to be applied
			echo json_encode(array('result'=>"success",'makeline'=>50,'tag'=>'Applying any database modifications.','total'=>100));
			return;
		}
		Yii::log("Downloading $patch", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		//regex filename to make sure we're not passing something wrong

		$d = YiiBase::getPathOfAlias('webroot')."/runtime/upgrade";
		@mkdir($d,0777,true);
		@unlink($d."/upgrade.zip");
		@unlink($d."/upgrade.xml");
		$data = $this->getFile($patch);
		$f=file_put_contents($d."/upgrade.zip", $data);

		if ($f)
		{
			$blnExtract = $this->unzipFile($d,"upgrade.zip");
			if($blnExtract)
			{
				@unlink($d."/upgrade.zip");
			}
		}

		echo json_encode(array('result'=>"success",'makeline'=>20,'tag'=>'Verifying Version Eligibility','total'=>100));

	}

	public function actionVerifyVersion()
	{


		$oXML=$this->loadXml();

		$VersionFrom = $oXML->version_from;
		$VersionTo = $oXML->version_to;

		//This installer will upgrade versions at least matching our From
		if (version_compare(XLSWS_VERSION,$VersionFrom) < 0 || version_compare(XLSWS_VERSION,$VersionTo) > 0 )
		{
			echo
				"ERROR: This updater can only update Web Store versions from $VersionFrom to $VersionTo and you have version "
				. XLSWS_VERSION;
		}
		else echo json_encode(array('result'=>"success",'makeline'=>30,'tag'=>'Verifying Write Access','total'=>100));

	}


	/*
	 * Verify that we have write access to any file that we need to
	 */
	public function actionVerifyWriteAccess()
	{

		$oXML=$this->loadXml();
		$blnError = false;

		//Step 1 - Preflight check, are all the critical files we will replace unmodified and writeable
		foreach ($oXML->item as $v)
		{
			$strUpgradeFileName = str_replace("./", YiiBase::getPathOfAlias('webroot')."/", $v->filename);

			$path_parts = pathinfo(substr($v->filename, 2, 999));
			$strPathToCreate = $path_parts['dirname'];


			if (($v->action == 'replace' || $v->action == 'delete') && $v->status == 'critical' && file_exists($strUpgradeFileName))
			{
				if (!isset($v->ignore))
				{
					$blnError = 1;
					foreach($v->original_hash as $hash)
						if (md5_file($v->filename) == $hash)
						{
							if (isset($_GET['check']))
								echo $v->filename." matched on hash ".$hash."<br>";
							$blnError=0;
						} //If one of our hashes matches, clear errorflag

					if ($blnError==1)
						$arrErrors[] = $v->filename . " (" . $v->status . ") ".$v->original_hash." has been modified, cannot be upgraded";

				}

				//Even if we ignore changed files, we still have to be able to write critical files
				if ($v->action == 'replace' && file_exists($v->filename) && !is_writable($v->filename))
				{
					$blnError = 1;
					$arrErrors[] = $v->filename." (".$v->status.") doesn't have permission to write, cannot be upgraded";
				}
				if ($v->action == 'replace' && !file_exists($v->filename) && file_exists($strPathToCreate) && !is_writable($strPathToCreate))
				{
					$blnError = 1;
					$arrErrors[] = $v->filename." (".$v->status. ") doesn't have permission to write, cannot be upgraded";
				}
			}



			if (!file_exists($strPathToCreate))
			{
				if (!mkdir($strPathToCreate, 0775, true))
				{
					$arrErrors[]
						= $v->filename . " Error attempting to create folder " . $strPathToCreate;
					$blnError = 1;
				}
			}


		}


		if ($blnError)
		{
			Yii::log("Auto upgrade errors ".print_r($arrErrors,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			_xls_set_conf('AUTO_UPDATE',0);
			echo json_encode(array('result'=>'Auto-updating has failed due to write access problems updating certain files. Errors have been logged in the System Log and you should contact technical support. We have disabled Auto-updating for now until this can be resolved. Your original version is still active.','makeline'=>0,'tag'=>'Error upgrading.','total'=>100));
		}
		else
			echo json_encode(array('result'=>"success",'makeline'=>40,'tag'=>'Moving upgraded files into place','total'=>100));
	}

	public function actionMigrateDatabase()
	{
		_runMigrationTool(1);
	}




	public function actionPlaceFiles()
	{

		$oXML=$this->loadXml();
		$arrErrors = array();


		//If we reach this point, we're good to actually do the upgrade. Let's go!
		$intCount = 0;
		$errCount = 0;
		foreach ($oXML->item as $v)
		{
			$intCount++;

			$strOrigFileName= str_replace("./", YiiBase::getPathOfAlias('webroot')."/", $v->filename);
			$strUpgradeFileName = str_replace("./", YiiBase::getPathOfAlias('webroot.runtime.upgrade')."/", $v->filename);

			if (!file_exists($strUpgradeFileName))
			{
				$v->action = 'skip';
				if (XLSWS_VERSION != $oXML->version_to)
				{ //We may run this multiple times after upgrading, don't show skips after initial upg
					$arrErrors[] = $v->filename . " upgrade file not found, skipping"; //we don't care
				}
			}


			switch ($v->action) {

				case 'install':
					//These are easy, just move new file
					if (!@copy($strUpgradeFileName, $strOrigFileName))
					{
						$arrErrors[] = $strOrigFileName . " could not be copied";
						$errCount++; //This is a severe error
					} else {
						@unlink($strUpgradeFileName);
					}


					break;


				case 'replace':
					$blnReplace = false;

					//If the file doesn't exist (can happen for large jumps in upgrading), set replace flag
					if (!file_exists($strOrigFileName))
						$blnReplace = true;
					else
					{
						foreach ($v->original_hash as $hash)
						{
							if (md5_file($strOrigFileName) == $hash)
								$blnReplace = true;
							if ((md5_file($strOrigFileName) != $hash && $v->status == 'critical') || isset($v->ignore))
								$blnReplace = true;
						}
					}

					if ($blnReplace)
					{
						//Remove old file first, then copy new one, then remove upgrade copy
						if (file_exists($strOrigFileName) && !@unlink($strOrigFileName))
						{
							$arrErrors[] = $strOrigFileName . " could not be removed";
							$errCount++; //This is a severe error
						}
						else
						{
							if (!@copy($strUpgradeFileName, $strOrigFileName))
							{
								$arrErrors[] = $strOrigFileName . " could not be copied";
								$errCount++; //This is a severe error
							}
							else
							{
								@unlink($strUpgradeFileName);
							}
						}
					}
					else
					{
						$arrErrors[] = $strOrigFileName . " (optional) modified, not replaced.";
					}

					break;


				case 'delete':
					//These are easy, just remove old file
					if (!@unlink($strOrigFileName))
					{
						$arrErrors[] = $strOrigFileName . " could not be removed";
						//Error but not enough to halt progress
					}
					break;

				case 'rename':
					//Less destructive than a delete
					if (!@rename($strOrigFileName, $strOrigFileName . '-old'))
					{
						$arrErrors[] = $strOrigFileName . " could not be renamed";
						$errCount++; //This is a severe error
					}
					break;


				case 'skip':
					//Only exists if the original has been removed
					break;


			}

		}
		//We should actually never get errors here because our pre-flight check should have caught anything that couldn't be updated. But if we did, STOP and get help
		if ($errCount>0)
		{
			Yii::log("Auto upgrade errors ".print_r($arrErrors,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			echo json_encode(array('result'=>'Auto-updating has failed due to errors after the preflight check. Errors have been logged in the System Log and you should contact technical support immediately. We have disabled Auto-updating for now until this can be resolved. Because files were partially replaced, this may result in a non-fuctional system until this can be resolved.','makeline'=>0,'tag'=>'Error upgrading.','total'=>100));
		}
		else
			echo json_encode(array('result'=>"success",'makeline'=>50,'tag'=>'Applying any database modifications.','total'=>100));

	}

	public function actionDatabaseInstall()
	{
		$this->actionDatabaseUpgrade(44,50,'Applying latest database changes...');
	}

	public function actionDatabaseUpgrade($online=50, $total=100, $tag='')
	{

		Yii::log("Checking db to see if we're current", 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		$oXML = $this->isDatabaseCurrent();

		if ($oXML->schema == 'current')
		{
			echo json_encode(array('result'=>"success",'makeline'=>$total,'tag'=>$tag,'total'=>$total));
			return;
		}

		//If we're here, we have something to do

		switch($oXML->changetype)
		{

			case 'yii':
				//Don't do anything here, it was already done by the framework
				break;

			case 'run_task':

				$this->runTask($oXML->schema);
				break;

			case 'add_column':
				$elements = explode(".",$oXML->elementname);
				$res = Yii::app()->db->createCommand("SHOW COLUMNS FROM ".$elements[0]." WHERE Field='".$elements[1]."'")->execute();
				if(!$res)
					Yii::app()->db->createCommand($oXML->sqlstatement)->execute();
				break;

			case 'drop_column':
				$elements = explode(".",$oXML->elementname);
				$res = Yii::app()->db->createCommand("SHOW COLUMNS FROM ".$elements[0]." WHERE Field='".$elements[1]."'")->execute();
				if($res)
					Yii::app()->db->createCommand($oXML->sqlstatement)->execute();
				break;


			case 'add_configuration_key':
				$objKey = Configuration::LoadByKey($oXML->elementname);
				if (!$objKey)
					Yii::app()->db->createCommand($oXML->sqlstatement)->execute();
				break;

			default:
				Yii::app()->db->createCommand($oXML->sqlstatement)->execute();



		}

		$oXML = $this->isDatabaseCurrent(true);

		if ($oXML->schema == "current")
		{

			echo json_encode(array('result'=>"success",'makeline'=>$total,'tag'=>$tag,'total'=>$total));
			return;
		} else {
			$tag .= " ".$oXML->schema;
			$makeline = ($online+5);
			if ($makeline>=$total)
				$makeline -= 5; //keep it from artificially ending
			echo json_encode(array('result'=>"success",'makeline'=>$makeline,'tag'=>$tag,'total'=>$total));
		}

	}

	/**
	 * Check to see if any schema updates need to be applied.
	 *
	 * Note this routine checks the Updater (deprecated, will be removed later) and
	 * now uses the local Yii db migration routine.
	 *
	 * @param bool $blnCheckOnly optionally check but do not perform yii migration.
	 * @return mixed
	 */
	protected function isDatabaseCurrent($blnCheckOnly=false)
	{

		$url = "http://"._xls_get_conf('LIGHTSPEED_UPDATER','updater.lightspeedretail.com')."/webstore";

		$storeurl = $this->createAbsoluteUrl("/");
		$storeurl = str_replace("http://","",$storeurl);
		$storeurl = str_replace("https://","",$storeurl);

		$data['wdb'] = array(
			// Despite the DATABASE_SCHEMA_VERSION config key no longer existing, we must
			// include the schema index key in the array since Updater is expecting it.
			'schema'      => _xls_get_conf('DATABASE_SCHEMA_VERSION', 447),
			'version'      => XLSWS_VERSIONBUILD,
			'customer'    => $storeurl,
			'type'       => (_xls_get_conf('LIGHTSPEED_HOSTING') == 1 ? "hosted" : "self")
		);

		if (Yii::app()->params['LIGHTSPEED_MT'] == '1')
		{
			//Since we could have two urls on multitenant, just grab the original one
			$data['customer']=Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];
			$data['type']="mt-pro";
			if(Yii::app()->params['LIGHTSPEED_CLOUD']>0)
				$data['type']="mt-cloud";

		}

		$json = json_encode($data);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		$resp = curl_exec($ch);
		curl_close($ch);

		$oXML= json_decode($resp);
		$mixSchema = $oXML->wsdb->schema;

		if ($mixSchema != 'current')
		{
			return $oXML->wsdb;
		}

		$oXML->wsdb->changetype = "yii";
		$oXML->wsdb->schema = Yii::t('admin','Updating Database'); //Just because this is displayed during an update

		/*
		 * Before the first time we run this upgrade via the new method, we need to mark
		 * the existing database up to a certain point so it doesn't think it's a new install
		 */
		$this->checkForMigrationTable();

		/*
		 * New db update routine, this will become the only item in this function later
		 * That's why we're just adding instead of calling another function
		 */
		if (!$blnCheckOnly)
		{
			$strMigrationResults = _runMigrationTool(1);
			if (stripos($strMigrationResults, "No new migration found") > 0 )
			{
				$oXML->wsdb->schema = 'current';
			}

		}

		return $oXML->wsdb;
	}

	/**
	 * See if the Migration tracking table exists.
	 *
	 * For a new install, this should always now exist because this is how new installs are made.
	 * If we have a store that was originally <= 3.1.5, then we have to mark the migration
	 * to catch it up.
	 *
	 * @return void
	 */
	protected function checkForMigrationTable()
	{
		$strQuery = "SHOW TABLES LIKE 'xlsws_migrations'";
		$strResult = Yii::app()->db->createCommand($strQuery)->queryScalar();

		//We don't have this table, meaning this is a recent upgrade but not a new install
		if ($strResult != "xlsws_migrations")
		{
			Yii::log("Forcing creation of migrations table", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			_runMigrationTool('upgrade');
		}


	}
	protected function getFile($url)
	{
		$url = "http://cdn.lightspeedretail.com/webstore/webstore-incremental/".$url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$resp = curl_exec($ch);
		curl_close($ch);
		return $resp;


	}

	protected function unzipFile($path,$file)
	{
		$path = str_replace("/core/protected","/runtime/upgrade",Yii::app()->basePath);
		require_once( YiiBase::getPathOfAlias('application.components'). '/zip.php');

		extractZip($file,'',$path);

		return true;
	}
	protected function loadXml()
	{
		//Get the XML document loaded into a variable
		$oFile = YiiBase::getPathOfAlias('webroot.runtime.upgrade').'/upgrade.xml';
		if(file_exists($oFile))
		{
			try
			{
				$xml = file_get_contents($oFile);
				$oXML = new SimpleXMLElement($xml);
				return $oXML;

			}
			catch (Exception $e)
			{
				echo "No upgrade files can be found. Is your /upgrade folder readable?";
				Yii::log("Upgrade.xml error $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
		}
		else
		{
			Yii::log("Upgrade.xml error No upgrade files can be found. Update failed.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			echo json_encode(array('result'=> "No upgrade files can be found. Update failed.",'makeline'=>30,'tag'=>'Final cleanup','total'=>100));
			Yii::app()->end();
		}

		return false;
	}


	/**
	 * Triggers to perform upgrade tasks using a special key from the db upgrade routine
	 * If we have to move files or remove something specifically during an upgrade
	 * @param $id
	 */
	public function runTask($id)
	{
		Yii::log("Running upgrade task $id.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		switch($id)
		{
			case 416:
				//Place any header images in our new gallery library
				Gallery::LoadGallery(1);
				$d = dir(YiiBase::getPathOfAlias('webroot')."/images/header");
				while (false!== ($filename = $d->read()))
					if ($filename[0] != ".")
					{
						$model = new GalleryPhoto();
						$model->gallery_id = 1;
						$model->file_name = $filename;
						$model->name = '';
						$model->description = '';
						$model->save();
						$arrImages["/images/header/".$filename] =
							CHtml::image(Yii::app()->request->baseUrl."/images/header/".$filename);

						$src = YiiBase::getPathOfAlias('webroot')."/images/header/".$filename;

						$fileinfo = mb_pathinfo($filename);

						$model->thumb_ext = $fileinfo['extension'];
						$model->save();

						$imageFile = new CUploadedFile(
							$filename,
							$src,
							"image/".$fileinfo['extension'],
							getimagesize($src),
							null
						);

						if(Yii::app()->params['LIGHTSPEED_MT']=='1')
							$model->setS3Image($imageFile);
						else
							$model->setImage($imageFile);

					}

				break;


			case 417:
				//Remove wsconfig.php reference from /config/main.php

				if(Yii::app()->params['LIGHTSPEED_MT']==1)
					return;	//only applies to single tenant
				$main_config = file_get_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php");

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
				file_put_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php",$main_config);
				break;

			case 423:
				// add cart/cancel url rule for sim payment methods (ex. moneris) that require hardcoded cancel urls

				$main_config = file_get_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php");

				// check to see if the entry is already there and write it if it isn't
				$position = strpos($main_config,'cart/cancel');
				if (!$position)
				{
					$comments = "\r\r\t\t\t\t\t\t// moneris simple integration requires a hardcoded cancel URL\r\t\t\t\t\t\t// any other methods that require something similar we can add a cart/cancel rule like this one\r\t\t\t\t\t\t";

					$pos = strpos($main_config,"sro/view',")+strlen("sro/view',");
					$main_config = substr_replace($main_config, $comments."'cart/cancel/<order_id:\WO-[0-9]+>&<cancelTXN:(.*)>'=>'cart/cancel',\t\t\t\t\t\t",$pos,0);
					file_put_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php",$main_config);

				}

				break;

			case 427:
				// Add URL mapping for custom pages

				// If the store's on multi-tenant server, do nothing
				if(Yii::app()->params['LIGHTSPEED_MT']>0)
					return;

				$main_config = file_get_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php");
				$search_string = "'<id:(.*)>/pg'";

				// Check if the entry already exists. If not, add the mapping.
				if (strpos($main_config, $search_string) ===false)
				{
					$position = strpos($main_config, "'<feed:[\w\d\-_\.()]+>.xml' => 'xml/<feed>', //xml feeds");
					$custompage_mapping = "'<id:(.*)>/pg'=>array('custompage/index', 'caseSensitive'=>false,'parsingOnly'=>true), //Custom Page\r\t\t\t\t\t\t";
					$main_config = substr_replace($main_config, $custompage_mapping, $position, 0);
					file_put_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php",$main_config);
				}

				break;

			case 447:
				// Remove bootstrap, add in separate main.php

				// If the store's on multi-tenant server, do nothing
				if(Yii::app()->params['LIGHTSPEED_MT']>0)
					return;

				$main_config = file_get_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php");
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


				file_put_contents(YiiBase::getPathOfAlias('webroot')."/config/main.php",$main_config);

				@unlink(YiiBase::getPathOfAlias('webroot')."/config/wsemail.php");
				@unlink(YiiBase::getPathOfAlias('webroot')."/config/wsfacebook.php");

				if (Yii::app()->theme)
				{
					$arrActiveCss = Yii::app()->theme->info->cssfiles;
					$arrActiveCss[] = Yii::app()->theme->config->CHILD_THEME;
					Yii::app()->theme->config->activecss = $arrActiveCss;
				}

				break;

		}
	}
}