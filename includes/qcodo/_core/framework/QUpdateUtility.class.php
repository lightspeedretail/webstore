<?php
	require(dirname(__FILE__) . "/_manifest_helpers.inc.php");

	/**
	 * The QUpdateUtility is used by the qcodo_updater.cli and qcodo_downloader.cli command line utilities.
	 * It will use the Qcodo Updater WebService at http://api.qcodo.com/ to perform updates to the installed
	 * Qcodo system.
	 */
	class QUpdateUtility {
		const ServiceUrl = 'http://release.qcodo.com/update_service/1_0.php/';

		const Interactive = 'interactive';
		const Rename = 'rename';
		const Force = 'force';
		const ReportOnly = 'report-only';

		static public $PrimaryInstance;
		static public $CurrentFilePath;

		protected $strVersion;
		protected $strVersionType;
		protected $intMajor;
		protected $intMinor;
		protected $intBuild;
		protected $blnGzCompress;
		
		protected $blnQuietMode = false;

		protected $strServerManifest = array();
		protected $objServerManifestDirectories = array();
		protected $strLocalManifest = array();
		protected $strTouchedFile = array();

		public $strAlertArray = array();
		protected $strNoticeArray = array();
		protected $blnNewManifestPrefixWarning = false;

		protected $strIgnoreArray = array();

		// Case 1 //
		protected $strShouldUpdateArray = array();

		// Case 2 //
		protected $strSuggestFixArray = array();

		// Case 3 //
		protected $strSuggestCoreUpdateArray = array();

		// Case 4 //
		protected $strSuggestNonCoreUpdateArray = array();

		// Case 5 //
		protected $strShouldDownloadArray = array();

		// Case 5 //
		protected $strExtraArray = array();

		// Case 6 //
		protected $strDeprecatedArray = array();
		
		public function __destruct() {
			fclose($this->objStdIn);
		}

		public function __construct($strVersion) {
			QUpdateUtility::$PrimaryInstance = $this;
			$this->objStdIn = fopen('php://stdin', 'r');

			// Check for gzcompress
			if (function_exists('gzuncompress'))
				$this->blnGzCompress = true;
			else
				$this->blnGzCompress = false;

			// Update $strVersion to correct format
			if (strtolower($strVersion) == 'stable')
				$strArgs = '?mav=s';
			else if (strtolower($strVersion) == 'development')
				$strArgs = '?mav=d';
			else {
				$intPosition = strpos($strVersion, '.');
				if ($intPosition === false)
					QUpdateUtility::Error('Invalid Version format: ' . $strVersion);
				$intMajor = substr($strVersion, 0, $intPosition);
				$strRemainder = substr($strVersion, $intPosition + 1);

				$intPosition = strpos($strRemainder, '.');
				if ($intPosition === false)
					QUpdateUtility::Error('Invalid Version format: ' . $strVersion);
				$intMinor = substr($strRemainder, 0, $intPosition);
				$intBuild = substr($strRemainder, $intPosition + 1);

				if (!strlen($intBuild))
					QUpdateUtility::Error('Invalid Version format: ' . $strVersion);

				$strArgs = sprintf('?mav=%s&miv=%s&bld=%s', $intMajor, $intMinor, $intBuild);
			}

			// Aggregate Server Manifest
			$strManifestXml = $this->RetrieveFromService('GetManifest' . $strArgs, 'Qcodo Version does not exist: ' . $strVersion);

			try {
				$objXml = new SimpleXMLElement($strManifestXml);
			} catch (Exception $objExc) {
				exit(sprintf("Invalid XML Response from Qcodo Update WebService: %s\r\n", $strManifestXml));
			}

			$this->strVersion = (string) $objXml->version[0];
			$this->strVersionType = (string) $objXml->type[0];
			$this->intMajor = (int) $objXml->major[0];
			$this->intMinor = (int) $objXml->minor[0];
			$this->intBuild = (int) $objXml->build[0];
			
			if ($this->intMajor == 0)
				if ($this->intMinor < 3)
					// UpdateUtility and Update WebService only supports 0.3.0 or higher
					throw new QCallerException('Qcodo Update utility can only be used for Qcodo 0.3.0 (Qcodo Beta 3) and higher');

			foreach ($objXml->files[0]->file as $objFile) {
				$strPath = (string) $objFile['path'];
				$strToken = (string) $objFile['directoryToken'];
				$strKey = $strToken . '|' . $strPath;
				$this->strServerManifest[$strKey] = (string) $objFile['md5'];
			}

			foreach ($objXml->directories[0]->directory as $objDirectory) {
				$objToken = new QDirectoryToken();
				$objToken->Token = (string) $objDirectory['token'];
				$objToken->RelativeFlag = QType::Cast($objDirectory['relativeFlag'], QType::Boolean);
				$objToken->CoreFlag = QType::Cast($objDirectory['coreFlag'], QType::Boolean);
				$this->objServerManifestDirectories[$objToken->Token] = $objToken;
			}

			// Aggregate Local Manifest
			$strManifestXml = file_get_contents(__QCODO_CORE__ . '/manifest/manifest.xml');
			$objXml = new SimpleXMLElement($strManifestXml);
			foreach ($objXml->files[0]->file as $objFile) {
				$strPath = (string) $objFile['path'];
				$strToken = (string) $objFile['directoryToken'];
				$strKey = $strToken . '|' . $strPath;
				$this->strLocalManifest[$strKey] = (string) $objFile['md5'];
			}
			$this->strTouchedFile[strtolower(__QCODO_CORE__ . '/manifest/manifest.xml')] = true;
		}

		/**
		 * Uses the cli_config.inc constants to convert a manifest-based file path
		 * to an actual file path on this system.
		 * 
		 * Will return NULL if the file being converted is specified to an actual path
		 * of "none" by the user.
		 * 
		 * Will return -1 if the file being converted does not have a path prefix
		 * (this will happen if a new path prefix has been added since the last time
		 * qcodo_updater was called)
		 * 
		 * An example $strManifestFilePath is (for example) __INCLUDES__|prepend.inc.php
		 */
		protected function GetActualFilePath($strManifestFilePath) {
			$strManifestFilePathArray = explode('|', $strManifestFilePath);
			$strToken = $strManifestFilePathArray[0];
			$strFile = $strManifestFilePathArray[1];

			if (!defined($strToken))
				// If we are here, then the manifest file's prefix wasn't defined.
				// Therefore, we're assuming this is a new path prefix that hasn't been configured for
				// Return -1
				return -1;

			// Check to see if we the user WANTS us to do a replace
			// (e.g. or else he has specified null for the actual location of this directory prefix
			if (constant($strToken)) {
				// Yep -- Return the actual file path
				if ($this->objServerManifestDirectories[$strToken]->RelativeFlag)
					return __DOCROOT__ . constant($strToken) . '/' . $strFile;
				else if ($strToken == '__DOCROOT__')
					return constant($strToken) . __SUBDIRECTORY__ . '/' . $strFile;
				else
					return constant($strToken) . '/' . $strFile;
			} else {
				// No -- the user specified null/nothing for this file path
				// Therefore, they either don't want the directory upgraded or they erased
				// the directory altogether
				return null;
			}
		}
		
		protected function IsCore($strManifestFilePath) {
			$strManifestFilePathArray = explode('|', $strManifestFilePath);
			$strToken = $strManifestFilePathArray[0];
			$strFile = $strManifestFilePathArray[1];

			if ($this->objServerManifestDirectories[$strToken]->CoreFlag)
				return true;
			if (strpos($strFile, '_core/') !== false)
				return true;
			return false;
		}

		public static function Error($strError) {
			printf("%s\r\n", $strError);
			exit(1);
		}
		
		protected function RetrieveFromService($strArgument, $strErrorMessage) {
			if (!ini_get('allow_url_fopen'))
				QUpdateUtility::Error('allow_url_fopen is disabled in your php.ini, please enable it');

			set_error_handler('QUpdateUtilityErrorHandler', E_ALL);
			$strToReturn = file_get_contents(QUpdateUtility::ServiceUrl . $strArgument);
			restore_error_handler();

			// Check for errors from within the response
			if (strpos($strToReturn, 'PHP Exception') !== false)
				QUpdateUtility::Error($strErrorMessage);
			return $strToReturn;
		}
		
		public function RunDownloader($strToken, $strFile, $strActualFilePath) {
			$strToReturn = $this->DownloadFile($strToken, $strFile, $strActualFilePath);
			if ($strToReturn)
				printf("File successfully downloaded and saved as %s\r\n", $strToReturn);
			else {
				foreach ($this->strAlertArray as $strAlert)
					printf("%s\r\n", $strAlert);
			}
		}

		public function RunUpdater($strInteractionType, $blnQuietMode) {
			$this->blnQuietMode = $blnQuietMode;

			// Check on Directories
			foreach ($this->objServerManifestDirectories as $objDirectoryToken)
				if (!defined($objDirectoryToken->Token))
					if (!$this->blnNewManifestPrefixWarning) {
						$this->strAlertArray[] = 'Additional Directory Prefix Constants have been added to configuration.inc.php since the last time the QCodo Update Service was run.';
						$this->strAlertArray[] = 'Be sure and download a new configuration.inc.php and update your local configuration.inc.php with any new Directory Prefix Constants.  Then re-run qcodo_updater.cli to complete the Qcodo update.';
						$this->blnNewManifestPrefixWarning = true;
					}

			// Let's first add the new manifest.xml file
			$this->strShouldUpdateArray['__QCODO_CORE__|manifest/manifest.xml'] = __QCODO_CORE__ . '/manifest/manifest.xml';

			// Iterate through the Server manifest, 
			foreach ($this->strServerManifest as $strFile => $strServerMd5) {
				$strActualFilePath = $this->GetActualFilePath($strFile);

				if ($strActualFilePath == -1) {
					// We have a Manifest File who's prefix isn't accounted for.
					if (!$this->blnNewManifestPrefixWarning) {
						$this->strAlertArray[] = 'Additional Directory Prefix Constants have been added to configuration.inc.php since the last time the QCodo Update Service was run.';
						$this->strAlertArray[] = 'Be sure and download a new configuration.inc.php and update your local configuration.inc.php with any new Directory Prefix Constants.  Then re-run qcodo_updater.cli to complete the Qcodo update.';
						$this->blnNewManifestPrefixWarning = true;
					}

				} else if ($strActualFilePath) {
					// We have a Valid File Path to Check For
					if (file_exists($strActualFilePath)) {
						$strActualMd5 = md5_file($strActualFilePath, false);

						if ($strActualMd5 == $strServerMd5) {
							// Current Version on System is the SAME as the Server Manifest
							// DO NOTHING!
						} else {
							// Current Version on System is DIFFERENT than the Server Manifest
							
							if (array_key_exists($strFile, $this->strLocalManifest))
								$strLocalManifestMd5 = $this->strLocalManifest[$strFile];
							else
								$strLocalManifestMd5 = null;

							if ($strActualMd5 == $strLocalManifestMd5) {
								/* CASE 1: ShouldUpdate
								 * This file has been updated in the repository.
								 * No changes have been made to this file locally since the last time update was run.
								 * Therefore, go ahead and replace the file with the new version.
								 */
								$this->strShouldUpdateArray[$strFile] = $strActualFilePath; 
							} else if ($strLocalManifestMd5 == $strServerMd5) {
								// No changes have been made from version to version in the official release

								if ($this->IsCore($strFile)) {
									/* CASE 2: SuggestFix
									 * This Qcodo Core file has NOT been revised from version to version in the official release.
									 * However, the file on the current system is DIFFERENT than the one on the official release.
									 * Therefore, suggest fixing the file with the existing version.
									 */
									$this->strSuggestFixArray[$strFile] = $strActualFilePath;
								} else {
									// It's Non Core -- therefore, ignore it
								}

							} else {
								if ($this->IsCore($strFile)) {
									/* CASE 3: SuggestCoreUpdate
									 * This CORE file has is either new or updated with this version of Qcodo.
									 * However, the file on the current system has been modified from the old version of Qcodo.
									 * Therefore, suggest replacing the file with the new version.
									 */
									$this->strSuggestCoreUpdateArray[$strFile] = $strActualFilePath;
								} else {
									/* CASE 3: SuggestNonCoreUpdate
									 * This NON-CORE file has is either new or updated with this version of Qcodo.
									 * However, the file on the current system has been modified from the old version of Qcodo.
									 * Therefore, suggest replacing the file with the new version.
									 */
									$this->strSuggestNonCoreUpdateArray[$strFile] = $strActualFilePath;
								}
							}
						}

					} else {
						/* CASE 5: ShouldDownload
						 * This file does not exist on the local system.
						 * Therefore, go ahead and download it.
						 */
						$this->strShouldDownloadArray[$strFile] = $strActualFilePath;
					}

				} else
					$this->strIgnoreArray[] = $strFile;

				// We've Dealt With This File in Some Way, Shape or Form
				$this->strTouchedFile[strtolower($strActualFilePath)] = true;
			}

			// Finally, go through the current system's core directory, and find any "extra" files
			$this->CheckCoreDirectoriesForExtras();

			if ($strInteractionType != QUpdateUtility::ReportOnly)
				printf("Updating to Qcodo Version %s%s...\r\n\r\n",
					$this->strVersion, ($this->strVersionType == 'Stable') ? '' : ' (' . $this->strVersionType . ')');

			switch ($strInteractionType) {
				case QUpdateUtility::Interactive:
					foreach ($this->strShouldUpdateArray as $strFile => $strActualFilePath) {
						printf('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated file', 'Saved');
					}

					foreach ($this->strShouldDownloadArray as $strFile => $strActualFilePath) {
						printf('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Saved new or missing file', 'Saved');
					}

					foreach ($this->strSuggestFixArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						print("\r\n  Modified locally since last update (no changes made to this file in Qcodo since last update)\r\n");
						$strInput = $this->Prompt('  [O]verwrite with original version, Download but [R]ename original version, or [I]gnore: ', array('o', 'r', 'i'));

						if ($strInput == 'o')
							$this->SaveFile($strFile, $strActualFilePath, 'Fixed (overwriting local changes)', 'Saved');
						else if ($strInput == 'r')
							$this->SaveFile($strFile, $strActualFilePath, 'Fixed file saved as', 'Saved as renamed file', true);
						else
							$this->IgnoreFile($strActualFilePath, 'trying to fix');
					}

					foreach ($this->strSuggestCoreUpdateArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						print("\r\n  (Core) Both the local file AND the Qcodo Package's file were modified since last update\r\n");
						$strInput = $this->Prompt('  [O]verwrite with new version, Download but [R]ename new version, or [I]gnore: ', array('o', 'r', 'i'));

						if ($strInput == 'o')
							$this->SaveFile($strFile, $strActualFilePath, 'Updated Core file (overwriting local changes)', 'Saved');
						else if ($strInput == 'r')
							$this->SaveFile($strFile, $strActualFilePath, 'Updated Core file saved as', 'Saved as renamed file', true);
						else
							$this->IgnoreFile($strActualFilePath, 'trying to update');
					}

					foreach ($this->strSuggestNonCoreUpdateArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						print("\r\n  (Non-Core) Both the local file AND the Qcodo Package's file were modified since last update\r\n");
						$strInput = $this->Prompt('  [O]verwrite with new version, Download but [R]ename new version, or [I]gnore: ', array('o', 'r', 'i'));

						if ($strInput == 'o')
							$this->SaveFile($strFile, $strActualFilePath, 'Updated Non-Core file (overwriting local changes)', 'Saved');
						else if ($strInput == 'r')
							$this->SaveFile($strFile, $strActualFilePath, 'Updated Non-Core file saved as', 'Saved as renamed file', true);
						else
							$this->IgnoreFile($strActualFilePath, 'trying to update');
					}

					foreach ($this->strDeprecatedArray as $strActualFilePath) {
						print("\r\n");
						print('File \'' . $strActualFilePath . '\'...');
						print("\r\n  Core file has been marked as Deprecated with this Qcodo Version\r\n");
						$strInput = $this->Prompt('  [D]elete deprecated file, [R]ename, or [I]gnore: ', array('d', 'r', 'i'));

						if ($strInput == 'd')
							$this->DeleteFile($strActualFilePath, 'deprecated');
						else if ($strInput == 'r')
							$this->AppendBaseSuffix($strActualFilePath, 'deprecated', 'deprecated');
						else
							$this->IgnoreFile($strActualFilePath, 'trying to delete deprecated');
					}

					foreach ($this->strExtraArray as $strActualFilePath) {
						print("\r\n");
						print('File \'' . $strActualFilePath . '\'...');
						print("\r\n  Non-Qcodo file found within includes/qcodo on local filesystem\r\n");
						$strInput = $this->Prompt('  [D]elete extraneous file, [R]ename, or [I]gnore: ', array('d', 'r', 'i'));

						if ($strInput == 'd')
							$this->DeleteFile($strActualFilePath, 'extraneous');
						else if ($strInput == 'r')
							$this->AppendBaseSuffix($strActualFilePath, 'extraneous', 'extraneous');
						else
							$this->IgnoreFile($strActualFilePath, 'trying to delete extraneous');
					}

					break;
				case QUpdateUtility::Rename:
					foreach ($this->strShouldUpdateArray as $strFile => $strActualFilePath) {
						printf('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated file', 'Saved');
					}

					foreach ($this->strShouldDownloadArray as $strFile => $strActualFilePath) {
						printf('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Saved new or missing file', 'Saved');
					}

					foreach ($this->strSuggestFixArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Fixed file saved as', 'Saved as renamed file', true);
					}

					foreach ($this->strSuggestCoreUpdateArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated Core file saved as', 'Saved as renamed file', true);
					}

					foreach ($this->strSuggestNonCoreUpdateArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated Non-Core file saved as', 'Saved as renamed file', true);
					}

					foreach ($this->strDeprecatedArray as $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->DeleteFile($strActualFilePath, 'deprecated');
					}

					foreach ($this->strExtraArray as $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->AppendBaseSuffix($strActualFilePath, 'extraneous', 'extraneous');
					}

					break;
				case QUpdateUtility::Force:
					foreach ($this->strShouldUpdateArray as $strFile => $strActualFilePath) {
						printf('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated file', 'Saved');
					}

					foreach ($this->strShouldDownloadArray as $strFile => $strActualFilePath) {
						printf('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Saved new or missing file', 'Saved');
					}

					foreach ($this->strSuggestFixArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Fixed (overwriting local changes)', 'Saved');
					}

					foreach ($this->strSuggestCoreUpdateArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated Core file (overwriting local changes)', 'Saved');
					}

					foreach ($this->strSuggestNonCoreUpdateArray as $strFile => $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->SaveFile($strFile, $strActualFilePath, 'Updated Non-Core file (overwriting local changes)', 'Saved');
					}

					foreach ($this->strDeprecatedArray as $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->DeleteFile($strActualFilePath, 'deprecated');
					}

					foreach ($this->strExtraArray as $strActualFilePath) {
						print('File \'' . $strActualFilePath . '\'...');
						$this->DeleteFile($strActualFilePath, 'extraneous');
					}

					break;
				default:
					if (QApplication::$Windows)
						$strCommentChar = '::';
					else
						$strCommentChar = '#';

					print($strCommentChar . " Qcodo Update \"Report Only\" file (Generated by the qcodo_updater script)\r\n");
					print($strCommentChar . " Note: the format of this file allows it to be run as a command line script\r\n");
					print($strCommentChar . " Commands will update the currently installed Qcodo system to version " . $this->strVersion);
					if ($this->strVersionType != 'Stable')
						print (' (' . $this->strVersionType . ')');
					print ("\r\n");

					if (count($this->strShouldUpdateArray)) {
						print("\r\n" . $strCommentChar . " The following commands will download updates for the following files.\r\n");
						print($strCommentChar . " It will overwrite already existing files which have been unmodified on your local filesystem.\r\n");
						print($strCommentChar . " Because they are unmodified, it should be safe to overwrite.\r\n");

						foreach ($this->strShouldUpdateArray as $strFile => $strActualFilePath)
							$this->PrintDownloadCommand($strFile, $strActualFilePath);
					}

					if (count($this->strShouldDownloadArray)) {
						print("\r\n" . $strCommentChar . " The following commands will download the following files.\r\n");
						print($strCommentChar . " These files currently do not exist on the local filesystem.\r\n");

						foreach ($this->strShouldDownloadArray as $strFile => $strActualFilePath)
							$this->PrintDownloadCommand($strFile, $strActualFilePath);
					}

					if (count($this->strSuggestFixArray)) {
						print("\r\n" . $strCommentChar . " The following commands will download fixes for the following files.\r\n");
						print($strCommentChar . " While no changes were made to this file in Qcodo since the last update,\r\n");
						print($strCommentChar . " they have been modified locally since the last time an update was performed.\r\n");
						print($strCommentChar . " These commands will overwrite any changes you have made to these files.\r\n");

						foreach ($this->strSuggestFixArray as $strFile => $strActualFilePath)
							$this->PrintDownloadCommand($strFile, $strActualFilePath);
					}

					if (count($this->strSuggestCoreUpdateArray)) {
						print("\r\n" . $strCommentChar . " The following commands will download updates for the following CORE files.\r\n");
						print($strCommentChar . " These files have been modified locally since the last time an update was performed.\r\n");
						print($strCommentChar . " These commands will overwrite any changes you have made to these files.\r\n");

						foreach ($this->strSuggestCoreUpdateArray as $strFile => $strActualFilePath)
							$this->PrintDownloadCommand($strFile, $strActualFilePath);
					}

					if (count($this->strSuggestNonCoreUpdateArray)) {
						print("\r\n" . $strCommentChar . " The following commands will download updates for the following NON-CORE files.\r\n");
						print($strCommentChar . " These files have been modified locally since the last time an update was performed.\r\n");
						print($strCommentChar . " Therefore, these commands will \"Save As\" these versions with a different name.\r\n");

						foreach ($this->strSuggestNonCoreUpdateArray as $strFile => $strActualFilePath)
							$strActualFilePath = $this->RenameWithVersion($strActualFilePath);
							$this->PrintDownloadCommand($strFile, $strActualFilePath);
					}

					if (count($this->strDeprecatedArray)) {
						print("\r\n" . $strCommentChar . " The following commands will DELETE the following files.\r\n");
						print($strCommentChar . " These files have marked as Deprecated with this version of Qcodo.\r\n");
						
						printf("rm %s\r\n", $strActualFilePath);
					}


					if (count($this->strExtraArray)) {
						print("\r\n" . $strCommentChar . " The following commands will DELETE the following CORE files.\r\n");
						print($strCommentChar . " These files are not part of Qcodo but have been found within the core.\r\n");
						print($strCommentChar . " Therefore, these files are extraneous, and should be deleted or moved outside of core.\r\n");

						foreach ($this->strExtraArray as $strFile)
						printf("rm %s\r\n", $strFile);
					}

					if (count($this->strIgnoreArray)) {
						print("\r\n" . $strCommentChar . " The following files are ignored in this Updater process because you\r\n");
						print($strCommentChar . " specified that either the directory no longer exists or that you do not want\r\n");
						print($strCommentChar . " Qcodo to manage the directory.  This configuration option can be changed by specifying\r\n");
						print($strCommentChar . " a valid directory for the directory token in configuration.inc.php.\r\n");

						foreach ($this->strIgnoreArray as $strFile)
							printf("# Ignoring %s\r\n", $strFile);
					}
					
					if (count($this->strAlertArray))
						print("\r\n" . $strCommentChar . " ERRORS/ALERTS WERE REPORTED.  PLEASE READ BELOW.\r\n# Be sure to remove/delete the alerts if you want to run this file as a command line script.\r\n\r\n");

					break;
			}

			// Print any errors (if applicable) no matter what!
			$this->ReportBack('The Following Errors/Alerts Were Reported', $this->strAlertArray);

			if (($strInteractionType != QUpdateUtility::ReportOnly) && !$this->blnQuietMode) {
				// REPORT BACK (if NOT in report-only mode and NOT in Quiet Mode)
				$this->ReportBack('Log of Updates Performed', $this->strNoticeArray);
				$this->ReportBack('These Files Were Ignored', $this->strIgnoreArray, 
					"The following files are ignored in this Updater process because you\r\n" .
					"specified that either the directory no longer exists or that you do not want\r\n" .
					"Qcodo to manage the directory.  This configuration option can be changed by specifying\r\n" .
					"a valid directory for the directory token in configuration.inc.php.\r\n");
			}
		}

		public static function IsSuffixedFile($strFilePath) {
			// Version Number Checking
			$strPattern = '/[ a-zA-Z0-9_\-\.]+ \([0-9]+\.[0-9]+\.[0-9]+\)(\.[a-zA-Z0-9_\-\.]+)?/';
			if (QUpdateUtility::IsSuffixedFileHelper($strFilePath, $strPattern))
				return true;

			// Deprecated checking
			$strPattern = '/[ a-zA-Z0-9_\-\.]+ \(deprecated\)(\.[a-zA-Z0-9_\-\.]+)?/';
			if (QUpdateUtility::IsSuffixedFileHelper($strFilePath, $strPattern))
				return true;

			// Extraneous checking
			$strPattern = '/[ a-zA-Z0-9_\-\.]+ \(extraneous\)(\.[a-zA-Z0-9_\-\.]+)?/';
			if (QUpdateUtility::IsSuffixedFileHelper($strFilePath, $strPattern))
				return true;

			// No found patterns -- return false
			return false;
		}
		
		protected static function IsSuffixedFileHelper($strFilePath, $strPattern) {
			$strMatches = array();
			preg_match($strPattern, $strFilePath, $strMatches);

			if (count($strMatches)) {
				$strMatch = $strMatches[0];

				// We found a match for this pattern
				// Let's make sure the match is at the end of the filepath
				if (strpos($strFilePath, $strMatch) == (strlen($strFilePath) - strlen($strMatch)))
					return true;
				else
					return false;
			}

			return false;
		}

		protected function PrintDownloadCommand($strManifestFile, $strActualFilePath) {
			$strManifestFileArray = explode('|', $strManifestFile);
			$strToken = $strManifestFileArray[0];
			$strFile = $strManifestFileArray[1];

			if (QApplication::$Windows)
				printf("%s\\qcodo.bat qcodo-downloader %s \"%s\" \"%s\" \"%s\"\r\n",
					str_replace('/', '\\', __DEVTOOLS_CLI__),
					$this->strVersion, $strToken, $strFile, $strActualFilePath);
			else
				printf("%s/qcodo qcodo-downloader %s \"%s\" \"%s\" \"%s\"\r\n",
					__DEVTOOLS_CLI__, $this->strVersion,
					$strToken, $strFile, $strActualFilePath);
		}

		protected function IgnoreFile($strActualFilePath, $strAction) {
			$this->strNoticeArray[] = sprintf('Ignored %s %s', $strAction, $strActualFilePath);
			print ("  Ignored\r\n");
		}

		protected function Prompt($strPrompt, $strValidLetterArray) {
			$blnValid = false;
			
			while (!$blnValid) {
				print($strPrompt);
				$strInput = trim(strtolower($this->ReadString()));

				foreach ($strValidLetterArray as $strLetter)
					if (strtolower($strLetter) == $strInput)
						$blnValid = true;
			}

			return $strInput;
		}

		protected function SaveFile($strManifestFileString, $strActualFilePath, $strVerbPhrase, $strAlertMessage, $blnRename = false) {
			$strManifestFileArray = explode('|', $strManifestFileString);
			$strToken = $strManifestFileArray[0];
			$strFile = $strManifestFileArray[1];

			$strSavedFile = $this->DownloadFile($strToken, $strFile, $strActualFilePath, $blnRename);
			if ($strSavedFile) {
				$this->strNoticeArray[] = $strVerbPhrase . ' ' . $strSavedFile;
				printf("  %s\r\n", $strAlertMessage);
			} else
				print("  ERROR: See Update Log for more information\r\n");
		}

		protected $objStdIn;
		protected function ReadString() {
			$strLine = fgets($this->objStdIn, 1024);
			return trim($strLine);
		}

		protected function DeleteFile($strActualFilePath, $strReason) {
			// Begin Error Handling Functionality
			QUpdateUtility::$CurrentFilePath = $strActualFilePath;
			set_error_handler('QUpdateUtilityFileSystemErrorHandlerForDelete', E_ALL);

			// Delete the File
			$blnSuccess = unlink($strActualFilePath);

			// End Error Handling Functionality
			restore_error_handler();

			if ($blnSuccess) {
				$this->strNoticeArray[] = sprintf('Deleted %s %s', $strReason, $strActualFilePath);
				print ("  Deleted\r\n");
			} else
				print("  ERROR: See Update Log for more information\r\n");
		}

		protected function AppendBaseSuffix($strActualFilePath, $strBaseSuffix, $strReason) {
			// Begin Error Handling Functionality
			QUpdateUtility::$CurrentFilePath = $strActualFilePath;
			set_error_handler('QUpdateUtilityFileSystemErrorHandlerForRename', E_ALL);

			// Delete the File
			$strNewFilePath = $this->RenameWithBaseSuffix($strActualFilePath, $strBaseSuffix);
			$blnSuccess = true;
			if (file_exists($strNewFilePath))
				$blnSuccess = unlink($strNewFilePath);

			if ($blnSuccess)
				$blnSuccess = rename($strActualFilePath, $strNewFilePath);

			// End Error Handling Functionality
			restore_error_handler();

			if ($blnSuccess) {
				$this->strNoticeArray[] = sprintf('Renamed %s file to %s', $strReason, $strNewFilePath);
				print ("  Renamed\r\n");
			} else
				print("  ERROR: See Update Log for more information\r\n");
		}

		protected function DownloadFile($strToken, $strFile, $strActualFilePath, $blnRename = false) {
			$strData = file_get_contents(
				sprintf('%sGetFile?mav=%s&miv=%s&bld=%s&pth=%s&tok=%s&cmp=%s',
					QUpdateUtility::ServiceUrl, $this->intMajor, $this->intMinor, $this->intBuild,
					urlencode($strFile), $strToken, ($this->blnGzCompress) ? 1 : 0));

			// perform checks
			if (strpos($strData, 'File Not Found: ') !== false) {
				$this->strAlertArray[] = sprintf(
					'Unable to download file from webservice for version %s: %s %s [File Not Found]',
					$this->strVersion, $strToken, $strFile);
				return null;
			}

			if (substr($strData, 0, 2) != 'OK') {
				$this->strAlertArray[] = sprintf(
					'Unable to download file from webservice for version %s: %s %s [Invalid Response]',
					$this->strVersion, $strToken, $strFile);
				return null;
			}

			$strData = substr($strData, strpos($strData, "\r\n") + 2);
			$strReportedFile = trim(substr($strData, 0, strpos($strData, "\r\n")));
			$strData = substr($strData, strpos($strData, "\r\n") + 2);
			$strReportedToken = trim(substr($strData, 0, strpos($strData, "\r\n")));
			$strData = substr($strData, strpos($strData, "\r\n") + 2);
			$intFullSize = trim(substr($strData, 0, strpos($strData, "\r\n")));
			$strData = substr($strData, strpos($strData, "\r\n") + 2);
			$intCompressedSize = trim(substr($strData, 0, strpos($strData, "\r\n")));
			$strData = trim(substr($strData, strpos($strData, "\r\n") + 2));

			if ($strFile != $strReportedFile) {
				$this->strAlertArray[] = sprintf(
					'Unable to download file from webservice for version %s: %s %s [Invalid Name Check]',
					$this->strVersion, $strToken, $strFile);
				return null;
			}

			if ($strToken != $strReportedToken) {
				$this->strAlertArray[] = sprintf(
					'Unable to download file from webservice for version %s: %s %s [Invalid Token Check]',
					$this->strVersion, $strToken, $strFile);
				return null;
			}

			if ($intCompressedSize != strlen($strData)) {
				$this->strAlertArray[] = sprintf(
					'Unable to download file from webservice for version %s: %s %s [Invalid Encoded Size Check]',
					$this->strVersion, $strToken, $strFile);
				return null;
			}

			if ($this->blnGzCompress)
				$strData = gzuncompress(base64_decode($strData));
			else
				$strData = base64_decode($strData);

			if ($intFullSize != strlen($strData)) {
				$this->strAlertArray[] = sprintf(
					'Unable to download file from webservice for version %s: %s %s [Invalid Decoded Size Check]',
					$this->strVersion, $strToken, $strFile);
				return null;
			}

			// If we're here, then we have a valid $strData data stream!
			if ($blnRename)
				$strActualFilePath = $this->RenameWithVersion($strActualFilePath);

			if ($this->FilePutContents($strActualFilePath, $strData))
				return $strActualFilePath;
			else
				return null;			
		}

		protected function FilePutContents($strFilePath, $strData) {
			// Begin Error Handling Functionality
			QUpdateUtility::$CurrentFilePath = $strFilePath;
			set_error_handler('QUpdateUtilityFileSystemErrorHandler', E_ALL);

			// Create the Directory
			$blnToReturn = false;

			$strDirectory = dirname($strFilePath);
			if (!is_dir($strDirectory))
				QApplication::MakeDirectory($strDirectory, null);

			$intBytes = file_put_contents($strFilePath, $strData);

			if ($intBytes)
				$blnToReturn = true;

			// End Error Handling Functionality
			restore_error_handler();

			return $blnToReturn;
		}

		protected function RenameWithVersion($strActualFilePath) {
			return $this->RenameWithBaseSuffix($strActualFilePath, sprintf('%s.%s.%s', $this->intMajor, $this->intMinor, $this->intBuild));
		}

		protected function RenameWithBaseSuffix($strActualFilePath, $strSuffix) {
			$strBaseName = basename($strActualFilePath);
			$intPosition = strpos($strBaseName, '.');
			if ($intPosition)
				return sprintf('%s/%s (%s).%s',
					dirname($strActualFilePath), substr($strBaseName, 0, $intPosition),
					$strSuffix,
					substr($strBaseName, $intPosition + 1));
			else
				return sprintf('%s/%s (%s)',
					dirname($strActualFilePath), $strBaseName, $strSuffix);
		}

		protected function ReportBack($strName, $strArray, $strNote = null) {
			if ($strArray && count($strArray)) {
				printf("\r\n%s\r\n----------------------------------------\r\n", $strName);

				if ($strNote)
					printf("%s\r\n", $strNote);

				foreach ($strArray as $strKey => $strValue)
					printf("%s\r\n", $strValue);
			}
		}

		protected function CheckCoreDirectoriesForExtras() {
			foreach ($this->objServerManifestDirectories as $objDirectoryToken) {
				if ($objDirectoryToken->Token != '__DOCROOT__') {
					// If we've got a locally defined version of this Token and it's defined...
					if (defined($objDirectoryToken->Token) && ($strDirectory = constant($objDirectoryToken->Token))) {
						if ($objDirectoryToken->RelativeFlag)
							$strBaseDirectory = __DOCROOT__ . $strDirectory . '/';
						else
							$strBaseDirectory = $strDirectory . '/';
						$this->CheckCoreDirectoriesHelper($strDirectory, $strBaseDirectory, $objDirectoryToken->Token, $objDirectoryToken->CoreFlag);
					}
				}
			}
		}

		protected function CheckCoreDirectoriesHelper($strDirectory, $strBaseDirectory, $strToken, $blnIsCore) {
			if (!is_dir($strDirectory))
				return;

			// Pull out the Files as an Array
			$objDirectory = opendir($strDirectory);
			$strFileArray = array();
			while ($strFile = readdir($objDirectory))
				array_push($strFileArray, $strFile);
			closedir($objDirectory);

			foreach ($strFileArray as $strFile) {
				if (($strFile != '.') && ($strFile != '..') &&
					($strFile != '.svn') &&
					($strFile != 'CVS') && ($strFile != '.cvsignore') &&
					(strtolower($strFile) != '.ds_store')) {
					$strFilePath = $strDirectory . '/' . $strFile;

					if (is_dir($strFilePath))
						$this->CheckCoreDirectoriesHelper($strFilePath, $strBaseDirectory, $strToken, $blnIsCore);
					else {
						if (array_key_exists(strtolower($strFilePath), $this->strTouchedFile) &&
							$this->strTouchedFile[strtolower($strFilePath)]) {
								// If we're here, then we've already accounted for this file.
								// Do Nothing
						} else {
							if (($blnIsCore) || (strpos($strFilePath, '/_core/') !== false)) {
								// We're Dealing with a CORE file
								$strManifestFileName = str_replace($strBaseDirectory, '', $strFilePath);

								// Check to see the "state" of this file
								$intReturnCode = file_get_contents(
									sprintf('%sGetFileState?mav=%s&miv=%s&bld=%s&pth=%s&tok=%s',
										QUpdateUtility::ServiceUrl, $this->intMajor, $this->intMinor, $this->intBuild,
										urlencode($strManifestFileName), $strToken));

								if ($intReturnCode == 0) {
									if (!QUpdateUtility::IsSuffixedFile($strFilePath))
										$this->strExtraArray[$strToken . '|' . $strManifestFileName] = $strFilePath;
								} else if ($intReturnCode == -1)
									$this->strDeprecatedArray[$strToken . '|' . $strManifestFileName] = $strFilePath;
								else
									$this->strAlertArray[] = 'Undecipherable issue with ' . $strFilePath . ' (a.k.a. ' . $strManifestFilePath . ')';
							}

							$this->strTouchedFile[strtolower($strFilePath)] = true;
						}
					}
				}
			}
		}
		
		static function PrintDownloaderInstructions() {
			if (QApplication::$Windows)
				$strCommandName = str_replace('/', '\\', __DEVTOOLS_CLI__) . '\\qcodo.bat qcodo-downloader';
			else
				$strCommandName = __DEVTOOLS_CLI__ . ' qcodo-downloader';

			print('Qcodo Downloader Service - ' . QCODO_VERSION . '
Copyright (c) 2005 - 2009, Quasidea Development, LLC
This program is free software with ABSOLUTELY NO WARRANTY; you may
redistribute it under the terms of The MIT License.

Usage:
    ' . $strCommandName . ' VERSION DIRECTORY_TOKEN QCODO_FILE LOCAL_FILE_PATH

Example:
    ' . $strCommandName . ' 0.3.4 __QCODO__ prepend.inc.php /home/web/includes/prepend_beta3.inc.php

Note:
    This utility is mostly used by the generated script from the qcodo_updater
    utility (in Report Only mode).  It is not intended to be used casually by
    end users.  It is made available to use as an aid/tool only for those who
    understand how to use it.  There are no safety checks that are performed
    by this utility, and misuse of it could seriously damage your installation
    of Qcodo.

    PLEASE USE AT YOUR OWN RISK.

VERSION:
    the \'x.y.z\' version/build information of Qcodo you want to update to
    (e.g. 0.3.11).  You can NOT use \'stable\' or \'development\' as a
    version number.

DIRECTORY_TOKEN:
    the Qcodo Server Manifest "directoryToken" attribute of hte Qcodo File you
    want to download (e.g. "__QCODO__")

QCODO_FILE:
    the Qcodo Server Manifest "path" attribute of the Qcodo File you want to
    download (e.g. "prepend.inc.php").

LOCAL_FILE_PATH:
    the absolute file path where you want this file saved (will overwrite any
    existing file at this location)

For more information, please go to www.qcodo.com
');
			exit();
		}
		
		static function PrintUpdaterInstructions($blnHelp = false) {
			if (QApplication::$Windows)
				$strCommandName = str_replace('/', '\\', __DEVTOOLS_CLI__) . '\\qcodo.bat qcodo-updater';
			else
				$strCommandName = __DEVTOOLS_CLI__ . ' qcodo-updater';

			if (!$blnHelp)
				$strDetails = "  'interactive' [DEFAULT] - prompt before overwriting/deleting
          'rename' - intended for developers who tend to modify core
          'force' - intended for developers who leave core untouched
          'report-only' - make no changes to the filesystem";
			else
				$strDetails = "'interactive' [DEFAULT]
           New, missing or unmodified files: Download and Save
           Modified CORE files: Prompt to Overwrite/Rename
           Modified NON-Core files: Prompt to Overwrite/Rename
           Deprecated Core files: Prompt to Delete/Rename
           Extraneous Core files: Prompt to Delete/Rename

        'rename'
           New, missing or unmodified files: Download and Save
           Modified CORE files: Download and Rename
           Modified NON-Core files: Download and Rename
           Deprecated Core files: Delete
           Extraneous Core files: Rename

        'force'
           New, missing or unmodified files: Download and Save
           Modified CORE files: Download and Overwrite
           Modified NON-Core files: Download and Rename
           Deprecated Core files: Delete
           Extraneous Core files: Delete

        'report-only'
           Make NO CHANGES to the filesystem.  Only report on
           new/missing files and recommended overwrites and
           deletions.  Note: this will leave your filesystem
           untouched / unupdated.

        Notes:
           'rename' is mostly intended for users of Qcodo who regularly
           find themselves working with, updating, or making changes to
           Qcodo Core files.  'force' is intended for most users of Qcodo
           who tend to leave Qcodo Core alone and untouched.

           New, missing or unmodified files refer to files in the entire Qcodo
           distribution (inside and outside of core) which are either new or
           missing in the version you are updating to, or which exist in the
           local filesystem but has not been modified since the last time
           qcodo_updater has been run.

           Modified CORE files refer to files within core (includes/qcodo)
           which have been manually modified since the last time qcodo_updater
           has been run.

           Modified NON-core files refer to files OUTSIDE of core
           (includes/qcodo) which have been manually modified since the last
           time qcodo_updater has been run, AND which has been updated in the
           official release.

           Deprecated Core files refer to files within core (includes/qcodo)
           which has been declared as deprecated in the version you ar
           updating to.";

			printf('Qcodo Updater Service - ' . QCODO_VERSION . '
Copyright (c) 2005 - 2009, Quasidea Development, LLC
This program is free software with ABSOLUTELY NO WARRANTY; you may
redistribute it under the terms of The MIT License.

Usage: ' . $strCommandName . ' [OPTIONS] VERSION

VERSION:
        the \'x.y.z\' version/build information of Qcodo you want to update to
        (e.g. 0.3.11).  You can also use \'stable\' for the current stable
        release or \'development\' for the latest development version.

OPTIONS:
  --help
        More detailed version of this help page.

  --interaction=LEVEL
        The user interaction level, which could be one of the following:
        %s

  --quiet
        Do NOT generate and output a final report of changes performed.
        (Ignored when interaction=report-only)

For more information, please go to www.qcodo.com
', $strDetails);
			exit();
		}
	}
?>