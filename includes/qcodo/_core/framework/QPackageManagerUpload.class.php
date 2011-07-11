<?php
	class QPackageManagerUpload extends QPackageManager {
		protected $strPassword;
		protected $strNotes;

		protected $strSettingsFilePath;

		protected $strSeenRealPath;
		protected $objNewFileArray;
		protected $objChangedFileArray;

		protected $blnVersionMatch = false;
		protected $blnValidCredential = false;
		protected $blnValidPackage = false;
		protected $intNewVersionNumber;

		protected $strCurrentStableVersion;
		protected $strCurrentDevelopmentVersion;

		public function __construct($strPackageName, $strUsername, $strPassword, $blnLive, $blnForce, $strSettingsFilePath, $strNotes, $strNotesPath) {
			$this->strPackageName = trim(strtolower($strPackageName));
			$this->strUsername = trim(strtolower($strUsername));
			$this->strPassword = $strPassword;
			$this->blnLive = $blnLive;
			$this->blnForce = $blnForce;
			$this->strSettingsFilePath = $strSettingsFilePath;

			$this->SetupSettings();
			$this->SetupNotes($strNotes, $strNotesPath);
			$this->SetupManifestXml();
			$this->SetupDirectoryArray();
			$this->SetupFileArray();
			$this->SetupManifestVersion();
			$this->CheckVersion();
			$this->CheckCredentialsAndPackage();
		}

		protected function SetupNotes($strNotes, $strNotesPath) {
			if ($strNotesPath) {
				if (!is_file($strNotesPath)) {
					throw new Exception('QPM Upload notes file does not exist: ' . $strNotesPath);
				} else {
					$this->strNotes = trim(file_get_contents($strNotesPath));
				}
			} else {
				$this->strNotes = trim($strNotes);
			}
		}

		protected function SetupSettings() {
			// If they specified it, make sure it exists
			if ($this->strSettingsFilePath && !is_file($this->strSettingsFilePath)) {
				throw new Exception('QPM Settings XML file does not exist: ' . $this->strSettingsFilePath);
			}

			// If they didn't specify it, then check to see if the default location one exists
			if (!$this->strSettingsFilePath) {
				if (is_file(__DEVTOOLS_CLI__ . '/settings_qpm.xml'))
					$this->strSettingsFilePath = __DEVTOOLS_CLI__ . '/settings_qpm.xml';
				else
					return;
			}

			// Let's parse the file
			try {
				$objXml = @(new SimpleXMLElement(file_get_contents($this->strSettingsFilePath)));
				if (is_null($this->strUsername)) $this->strUsername = (string) $objXml->qcodoWebsite['username'];
				if (is_null($this->strPassword)) $this->strPassword = (string) $objXml->qcodoWebsite['password'];
			} catch (Exception $objExc) {
				throw new Exception('QPM Settings XML file is not valid: ' . $this->strSettingsFilePath);
			}
		}


		public function CheckVersion() {
			$this->strCurrentStableVersion = trim(file_get_contents(QPackageManager::QpmServiceEndpoint . '/GetCurrentQcodoVersion'));
			if (!$this->strCurrentStableVersion) throw new Exception('Unable to access information at ' . QPackageManager::QpmServiceEndpoint);
			$this->strCurrentDevelopmentVersion = trim(file_get_contents(QPackageManager::QpmServiceEndpoint . '/GetCurrentQcodoVersion?dev=1'));

			if (($this->strManifestVersion != $this->strCurrentStableVersion) && ($this->strManifestVersion != $this->strCurrentDevelopmentVersion)) {
				$this->blnVersionMatch = false;
			} else {
				$this->blnVersionMatch = true;
			}
		}

		public function CheckCredentialsAndPackage() {
			$intPersonId = trim(file_get_contents(QPackageManager::QpmServiceEndpoint . '/Login?u=' . urlencode($this->strUsername) . '&p=' . urlencode($this->strPassword)));
			$intPackageId = trim(file_get_contents(QPackageManager::QpmServiceEndpoint . '/GetPackageId?name=' . urlencode($this->strPackageName)));
			if ($intPersonId) $this->blnValidCredential = true;
			if ($intPackageId) $this->blnValidPackage = true;
			
			if ($this->blnValidCredential && $this->blnValidPackage) {
				$this->intNewVersionNumber = 1 +
					trim(file_get_contents(QPackageManager::QpmServiceEndpoint . '/GetPackageVersionCount?name=' . urlencode($this->strPackageName) . '&u=' . urlencode($this->strUsername)));
			}
		}


		public function GetInvalidCredentialOrPackageErrorText() {
			$strToReturn = null;
			if ((!$this->blnValidCredential) || (!$this->blnValidPackage)) {
				$strToReturn .= "error(s):\r\n";

				if (!$this->blnValidCredential)
					$strToReturn .= '  cannot log in to QPM service with username "' . $this->strUsername . '"' . "\r\n";

				if (!$this->blnValidPackage)
					$strToReturn .= '  package does not exist: "' . $this->strPackageName . '"' . "\r\n";

				$strToReturn .= "\r\n";
			}
			return $strToReturn;
		}

		public function GetVersionMismatchWarningText() {
			$strToReturn = null;
			if (!$this->blnVersionMatch) {
				$strToReturn .= "notice on out-of-date Qcodo version:\r\n";
				$strToReturn .= "  The local install of Qcodo is not the most up-to-date version:\r\n";
				$strToReturn .= sprintf("      locally installed:     v%s (%s)\r\n", $this->strManifestVersion, $this->strManifestVersionType);
				$strToReturn .= sprintf("      current Qcodo release: v%s (Development)\r\n", $this->strCurrentDevelopmentVersion);
				$strToReturn .= sprintf("                             v%s (Stable)\r\n", $this->strCurrentStableVersion);

				$strText =
					'You can still upload your QPM package, but note that because the installation ' .
					'is older than what is currently available to the community, it may limit the ' .
					'interest and/or comptability for this QPM package.';
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n\r\n";

				$strText =
					'If you still want to proceed with uploading this QPM, be sure to specify the ' .
					'"-f" or "--force" flag to override this warning.';
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n\r\n";
			}
			return $strToReturn;
		}

		public function GetNonLiveText() {
			$strToReturn = null;
			if (!$this->blnLive) {
				$strToReturn .= "notice on non-live mode:\r\n";

				$strText =
					'This is only a report of what WOULD be uploaded.  To actually execute the upload(s) ' .
					'in "live" mode, be sure to specify the "-l" or "--live" flag.';
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n\r\n";

				if (count($this->objNewFileArray)) {
					$strToReturn .= "new files to be included in this QPM package:\r\n";
					foreach ($this->objNewFileArray as $objFile) {
						$strToReturn .= sprintf("  %-16s  %s\r\n", $objFile->DirectoryToken, $objFile->Path);
					}
					$strToReturn .= "\r\n";
				}
				
				if (count($this->objChangedFileArray)) {
					$strToReturn .= "changed files to be included in this QPM package:\r\n";
					foreach ($this->objChangedFileArray as $objFile) {
						$strToReturn .= sprintf("  %-16s  %s\r\n", $objFile->DirectoryToken, $objFile->Path);
					}
					$strToReturn .= "\r\n";
				}
			}
			return $strToReturn;
		}

		public function PerformUpload() {
			$this->strSeenRealPath = array();
			$this->objNewFileArray = array();
			$this->objChangedFileArray = array();

			foreach ($this->objDirectoryArray as $objDirectoryToken) {
				// Make sure this is a directory token that is still in use
				if (constant($objDirectoryToken->Token)) {
					// Figure out the actual Path of the directory
					$strPath = $objDirectoryToken->GetFullPath();

					// Make sure it exists
					if (is_dir($strPath)) {
						$this->ProcessDirectory($strPath, $objDirectoryToken);
					}
				}
			}

			print 'Qcodo Package Manager (QPM) Uploader Tool v' . QCODO_VERSION . "\r\n\r\n";
			if ((!$this->blnValidCredential) || (!$this->blnValidPackage)) {
				print $this->GetInvalidCredentialOrPackageErrorText();
			}

			if (!$this->blnVersionMatch && !$this->blnForce) {
				print $this->GetVersionMismatchWarningText();
			}

			if (!count($this->objNewFileArray) && !count($this->objChangedFileArray)) {
				print "error: no new or altered files in your local Qcodo installation to package\r\n";	
			} else if (!$this->blnLive) {
				print $this->GetNonLiveText();
			} else if (($this->blnVersionMatch || $this->blnForce) &&
						($this->blnValidCredential) &&
						($this->blnValidPackage)) {
				$this->ExecuteUpload();
			}
		}


		protected function ExecuteUpload() {
			// Setup QPM XML
			$strQpmXml = '<?xml version="1.0" encoding="UTF-8" ?>';
			$strQpmXml .= "\r\n";
			$strQpmXml .= '<qpm version="1.0">';
			$strQpmXml .= "\r\n";
			$strQpmXml .= sprintf('<package name="%s" user="%s" version="%s" qcodoVersion="%s" qcodoVersionType="%s" submitted="%s">',
				$this->strPackageName, $this->strUsername, $this->intNewVersionNumber,
				$this->strManifestVersion, $this->strManifestVersionType, QDateTime::Now()->__toString(QDateTime::FormatRfc822));
			$strQpmXml .= "\r\n";
			$strQpmXml .= sprintf('<notes>%s</notes>', QString::XmlEscape($this->strNotes));
			$strQpmXml .= "\r\n";

			$strQpmXml .= '<newFiles>';
			$strQpmXml .= "\r\n";
			foreach ($this->objNewFileArray as $objFile) {
				$strQpmXml .= sprintf('<file directoryToken="%s" path="%s" md5="%s">%s</file>',
					$objFile->DirectoryToken, $objFile->Path, $objFile->Md5, base64_encode(file_get_contents($objFile->GetFullPath())));
				$strQpmXml .= "\r\n";
			}
			$strQpmXml .= '</newFiles>';
			$strQpmXml .= "\r\n";
			$strQpmXml .= '<changedFiles>';
			$strQpmXml .= "\r\n";
			foreach ($this->objChangedFileArray as $objFile) {
				$strQpmXml .= sprintf('<file directoryToken="%s" path="%s" md5="%s">%s</file>',
					$objFile->DirectoryToken, $objFile->Path, $objFile->Md5, base64_encode(file_get_contents($objFile->GetFullPath())));
				$strQpmXml .= "\r\n";
			}
			$strQpmXml .= '</changedFiles>';
			$strQpmXml .= "\r\n";
			$strQpmXml .= '</package>';
			$strQpmXml .= "\r\n";
			$strQpmXml .= '</qpm>';

			if (function_exists('gzuncompress')) {
				$blnGzCompress = true;
				$strQpmXml = gzcompress($strQpmXml, 9);
			} else {
				$blnGzCompress = false;
			}

			print "Uploading QPM package (" . strlen($strQpmXml) . " bytes)...\r\n";

			$strEndpoint = substr(QPackageManager::QpmServiceEndpoint, strlen('http://'));
			$strHost = substr($strEndpoint, 0, strpos($strEndpoint, '/'));
			$strPath = substr($strEndpoint, strpos($strEndpoint, '/'));
			$strHeader = sprintf("GET %s/UploadPackage?name=%s&u=%s&p=%s&gz=%s HTTP/1.1\r\nHost: %s\r\nContent-Length: %s\r\n\r\n",
				$strPath, $this->strPackageName, $this->strUsername, $this->strPassword, $blnGzCompress, $strHost, strlen($strQpmXml));
			$objSocket = fsockopen($strHost, 80);
			fputs($objSocket, $strHeader);
			fputs($objSocket, $strQpmXml);
			fputs($objSocket, "\r\n\r\n");
			$strResponse = null;
			while (($chr = fgetc($objSocket)) !== false)
				$strResponse .= $chr;
			$strResponseArray = explode("\r\n\r\n", trim($strResponse));
			print '  ' . $strResponseArray[1] . "\r\n";
			fclose($objSocket);
		}


		/**
		 * Given the path of a directory, process all the directories and files in it that have NOT been seen in SeenRealPath.
		 * Assumes: the path is a valid directory that exists and has NOT been SeenRealPath
		 * @param string $strPath
		 * @return void
		 */
		protected function ProcessDirectory($strPath, QDirectoryToken $objDirectoryToken) {
			$strRealPath = realpath($strPath);
			$this->strSeenRealPath[$strRealPath] = true;

			$objDirectory = opendir($strPath);
			while ($strName = readdir($objDirectory)) {
				// Only Process Files/Folders that do NOT start with a single "."
				if (QString::FirstCharacter($strName) != '.') {
					// Put Together the Entire Full Path of the File in Question
					$strFullPath = $strPath . '/' . $strName;

					// Process if it's a file
					if (is_file($strFullPath)) {
						$this->ProcessFile($strFullPath, $objDirectoryToken);

					// Process if it's a directory
					} else if (is_dir($strFullPath)) {
						// Only continue if we haven't visited it and it's not a folder that we are ignoring
						$strRealPath = realpath($strFullPath);
						if (!array_key_exists($strRealPath, $this->strSeenRealPath) && !array_key_exists(strtolower($strName), $this->blnIgnoreFolderArray))
							$this->ProcessDirectory($strFullPath, $objDirectoryToken);

					// It's neither a file nor a directory?!
					} else {
						throw new Exception('Not a valid file or folder: ' . $strFullPath);
					}
				}
			}
		}

		protected function ProcessFile($strFullPath, QDirectoryToken $objDirectoryToken) {
			// Calculate the RealPath and ensure we haven't visited it yet
			$strRealPath = realpath($strFullPath);
			if (array_key_exists($strRealPath, $this->strSeenRealPath)) throw new Exception('Somehow already visited file: ' . $strFullPath);
			$this->strSeenRealPath[$strRealPath] = true;

			// If in the list of "ignore", let's ignore it
			$strRelativePath = $objDirectoryToken->GetRelativePathForFile($strFullPath);
			if (array_key_exists($strRelativePath, $this->blnIgnoreFileArray) &&
				($this->blnIgnoreFileArray[$strRelativePath] == $objDirectoryToken->Token))
				return;

			// Calculate the MD5
			$strMd5 = md5_file($strFullPath);

			// Does this File Exist in the Manifest
			if (array_key_exists($strRealPath, $this->objFileArrayByRealPath)) {
				// Ensure that the FileInManifest Matches the Directory we are in
				$objFile = $this->objFileArrayByRealPath[$strRealPath];
				if ($objFile->DirectoryToken != $objDirectoryToken->Token)
					throw new Exception('Mismatched Directory Token: ' . $strFullPath . ' ' . $objFile->DirectoryToken . ' ' . $objDirectoryToken->Token);
				if ($objFile->Path != $objDirectoryToken->GetRelativePathForFile($strFullPath))
					throw new Exception('Mismatched File Path: ' . $strFullPath);

				// Do the MD5's match?
				if ($strMd5 != $objFile->Md5) {
					// NO -- we have an ALTERED FILE
					$objChangedFile = clone($objFile);
					$objChangedFile->Md5 = $strMd5;
					$this->objChangedFileArray[] = $objChangedFile;
				}
			} else {
				// Does NOT exist in Manifest -- it is NEW
				$objNewFile = new QFileInManifest();
				$objNewFile->RealPath = $strRealPath;
				$objNewFile->DirectoryToken = $objDirectoryToken->Token;
				$objNewFile->DirectoryTokenObject = $objDirectoryToken;
				$objNewFile->Path = $objDirectoryToken->GetRelativePathForFile($strFullPath);
				$objNewFile->Md5 = $strMd5;
				$this->objNewFileArray[] = $objNewFile;
			}
		}
	}
?>