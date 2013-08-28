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

		switch($online)
		{
			case 10: $this->actionDownload(); break;
			case 20: $this->actionVerifyVersion();break;
			case 30: $this->actionVerifyWriteAccess();break;
			case 40: $this->actionPlaceFiles();break;

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
				$this->actionDatabaseUpgrade($online);break;
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
		if (version_compare(XLSWS_VERSION,$VersionFrom) < 0 || version_compare(XLSWS_VERSION,$VersionTo) > 0 ) {
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
							if (isset($_GET['check'])) echo $v->filename." matched on hash ".$hash."<br>";
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



			if (!file_exists($strPathToCreate)) {
				if (!mkdir($strPathToCreate, 0775, true)) {
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


	public function actionPlaceFiles()
	{

		$oXML=$this->loadXml();
		$arrErrors = array();


		//If we reach this point, we're good to actually do the upgrade. Let's go!
		$intCount = 0;
		$errCount = 0;
		foreach ($oXML->item as $v) {
			$intCount++;

			$strOrigFileName= str_replace("./", YiiBase::getPathOfAlias('webroot')."/", $v->filename);
			$strUpgradeFileName = str_replace("./", YiiBase::getPathOfAlias('webroot.runtime.upgrade')."/", $v->filename);

			if (!file_exists($strUpgradeFileName)) {
				$v->action = 'skip';
				if (XLSWS_VERSION != $oXML->version_to)
				{ //We may run this multiple times after upgrading, don't show skips after initial upg
					$arrErrors[] = $v->filename . " upgrade file not found, skipping"; //we don't care
				}
			}


			switch ($v->action) {

				case 'install':
					//These are easy, just move new file
					if (!@copy($strUpgradeFileName, $strOrigFileName)) {
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
						foreach($v->original_hash as $hash)
						{
							if (md5_file($strOrigFileName) == $hash) $blnReplace = true;
							if ((md5_file($strOrigFileName) != $hash && $v->status == 'critical') || isset($v->ignore)) $blnReplace = true;
						}
					}


					if ($blnReplace) {
						//Remove old file first, then copy new one, then remove upgrade copy
						if (file_exists($strOrigFileName) && !@unlink($strOrigFileName)) {
							$arrErrors[] = $strOrigFileName . " could not be removed";
							$errCount++; //This is a severe error
						} else {
							if (!@copy($strUpgradeFileName, $strOrigFileName)) {
								$arrErrors[] = $strOrigFileName . " could not be copied";
								$errCount++; //This is a severe error
							} else {
								@unlink($strUpgradeFileName);
							}
						}
					} else {
						$arrErrors[] = $strOrigFileName . " (optional) modified, not replaced.";
					}

					break;


				case 'delete':
					//These are easy, just remove old file
					if (!@unlink($strOrigFileName)) {
						$arrErrors[] = $strOrigFileName . " could not be removed";
						//Error but not enough to halt progress
					}
					break;

				case 'rename':
					//Less destructive than a delete
					if (!@rename($strOrigFileName, $strOrigFileName . '-old')) {
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

	public function actionDatabaseUpgrade($online = 50, $total=100, $tag='')
	{

		$oXML = $this->checkForDatabaseUpdates();

		if ($oXML->schema == "current")
		{
			echo json_encode(array('result'=>"success",'makeline'=>$total,'tag'=>$tag,'total'=>$total));
			return;
		}

		//If we're here, we have something to do

		switch($oXML->changetype)
		{
			case 'add_column':
				$elements = explode(".",$oXML->elementname);
				$res = Yii::app()->db->createCommand("SHOW COLUMNS FROM ".$elements[0]." WHERE Field='".$elements[1]."'")->execute();
				if(!$res)
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
		_xls_set_conf('DATABASE_SCHEMA_VERSION',$oXML->schema);

		$oXML = $this->checkForDatabaseUpdates();

		if ($oXML->schema == "current")
		{
			echo json_encode(array('result'=>"success",'makeline'=>$total,'tag'=>$tag,'total'=>$total));
			return;
		} else {
			$tag .= " ".$oXML->schema;
			$makeline = ($online+5);
			if ($makeline>=$total) $makeline -= 5; //keep it from artificially ending
			echo json_encode(array('result'=>"success",'makeline'=>$makeline,'tag'=>$tag,'total'=>$total));
		}

	}

	protected function checkForDatabaseUpdates()
	{
		$url = "http://updater.lightspeedretail.com/webstore";
		//$url = "http://www.lsvercheck.site/webstore";

		$storeurl = $this->createAbsoluteUrl("/");
		$storeurl = str_replace("http://","",$storeurl);
		$storeurl = str_replace("https://","",$storeurl);

		$data['wdb'] = array(
			'schema'      => _xls_get_conf('DATABASE_SCHEMA_VERSION'),
			'version'      => XLSWS_VERSIONBUILD,
			'customer'    => $storeurl,
			'type'       => (_xls_get_conf('LIGHTSPEED_HOSTING')==1 ? "hosted" : "self")
		);
		$json = json_encode($data);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER,
			array("Content-type: application/json"));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		$resp = curl_exec($ch);
		curl_close($ch);


		$oXML= json_decode($resp);
		return $oXML->wsdb;
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






	}


}