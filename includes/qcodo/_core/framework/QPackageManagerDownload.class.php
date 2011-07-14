<?php
	class QPackageManagerDownload extends QPackageManager {
		protected $strQpmXml;
		protected $objQpmXml;

		protected $blnVersionMatch = false;
		protected $strPackageVersion;
		protected $strPackageVersionType;

		protected $objNewFileArray;
		protected $objOverwriteFileArray;
		protected $objModifiedFileArray;

		public function __construct($strPackageName, $strUsername, $blnLive, $blnForce) {
			$this->strPackageName = trim(strtolower($strPackageName));
			$this->strUsername = trim(strtolower($strUsername));
			$this->blnLive = $blnLive;
			$this->blnForce = $blnForce;

			$this->SetupManifestXml();
			$this->SetupDirectoryArray();
			$this->SetupFileArray();
			$this->SetupManifestVersion();
		}

		public function PerformDownload() {
			print 'Qcodo Package Manager (QPM) Downloader Tool v' . QCODO_VERSION . "\r\n\r\n";
			$strEndPoint = sprintf('%s/DownloadPackage?name=%s&u=%s&gz=', QPackageManager::QpmServiceEndpoint, $this->strPackageName, $this->strUsername); 

			if (function_exists('gzdecode')) {
				$strQpmXmlCompressed = QFileInManifest::DownloadFileFromWebWithStatusOutput($strEndPoint . '1');
				$this->strQpmXml = gzdecode($strQpmXmlCompressed);
			} else {
				$this->strQpmXml = trim(QFileInManifest::DownloadFileFromWebWithStatusOutput($strEndPoint . '0'));
			}

			if (!$this->strQpmXml) throw new Exception(sprintf('package not found: %s/%s', $this->strUsername, $this->strPackageName));

			$this->objQpmXml = new SimpleXMLElement($this->strQpmXml);

			$this->CheckVersion();

			if (!$this->blnVersionMatch && !$this->blnForce) {
				print $this->GetVersionMismatchWarningText();
			}

			$strErrorArray = $this->AnalyzePackage();

			if (count($strErrorArray)) {
				print ($this->GetErrorText($strErrorArray));
			}

			if (!$this->blnLive) {
				print $this->GetNonLiveText();
			} else if (($this->blnVersionMatch || $this->blnForce) && (!count($strErrorArray))) {
				$this->ExecuteDownload();
			}
		}

		protected function ExecuteDownload() {
			printf("  %-16s  %s\r\n", 'DIRECTORY', 'PATH');
			printf("  %-16s  %s\r\n", str_repeat('-', 16), str_repeat('-', 58));
			print "\r\n";

			print "new files installed:\r\n";
			foreach ($this->objNewFileArray as $objFile) {
				$objFile->SaveFileFromQpm();
				print $objFile->GetTextForQpmReport();
			}
			print "\r\n";

			print "existing files overwritten:\r\n";
			foreach ($this->objOverwriteFileArray as $objFile) {
				$objFile->SaveFileFromQpm();
				print $objFile->GetTextForQpmReport();
			}
			print "\r\n";
			
			print "existing files needing to be modified (these files need to be manually\r\n";
			print "reconciled with the version in your local filesystem):\r\n";
			foreach ($this->objModifiedFileArray as $objFile) {
				$objFile->SaveFileFromQpm($this->strPackageName);
				print $objFile->GetTextForQpmReportWithAlternateToken($this->strPackageName);
			}
			print "\r\n";
		}

		protected function AnalyzePackage() {
			$this->objNewFileArray = array();
			$this->objOverwriteFileArray = array();
			$this->objModifiedFileArray = array();

			// Return any error messages
			$strErrorArray = array();

			foreach ($this->objQpmXml->package->newFiles->children() as $objFileXml) {
				$strErrorMessage = $this->AnalyzeFile($objFileXml);
				if ($strErrorMessage)
					$strErrorArray[$strErrorMessage] = $strErrorMessage;
			}

			foreach ($this->objQpmXml->package->changedFiles->children() as $objFileXml) {
				$strErrorMessage = $this->AnalyzeFile($objFileXml);
				if ($strErrorMessage)
					$strErrorArray[$strErrorMessage] = $strErrorMessage;
			}

			return $strErrorArray;
		}

		/**
		 * Analyzes a FileXml from a QPM Manifest we are downloading to see what "category" it belongs to
		 * (e.g. is it a new file to be installed, or is it going to auto-overwrite an existing file in the filesystem,
		 * or is it going to have to modify and already-modified file in the filesystem)
		 * @param SimpleXMLElement $objFileXml
		 * @return string error message (if any) or null if none
		 */
		protected function AnalyzeFile(SimpleXMLElement $objFileXml) {
			$objFile = QFileInManifest::LoadFromQpmXml($objFileXml, $this->objDirectoryArray);

			// Is the DirectoryToken valid?
			if (!$objFile) {
				return 'directory token not defined in configuration.inc.php: ' . (string) $objFileXml['directoryToken'];
			}

			// Does this file currently exist in the filesystem?

			// Yep
			if ($objFile->RealPath) {
				
				// Does this file match the version with that in the QPM -- if so, add it to the "overwrite" array
				if ($objFile->IsMd5MatchWithFilesystem()) {
					$this->objOverwriteFileArray[] = $objFile;

				// Or does the file match the version with that in the Manifest (if applicable) -- if so, add it to the "overwrite" array
				} else if (array_key_exists($objFile->RealPath, $this->objFileArrayByRealPath) &&
							$this->objFileArrayByRealPath[$objFile->RealPath]->IsMd5MatchWithFilesystem()) {
					$this->objOverwriteFileArray[] = $objFile;

				// Otherwise, it doesn't match anything -- therefore, we will be modifying the local copy
				} else {
					$this->objModifiedFileArray[] = $objFile;
				}

			// Nope -- it's a new file
			} else {
				$this->objNewFileArray[] = $objFile;
			}
		}

		protected function GetVersionMismatchWarningText() {
			$strToReturn = null;
			if (!$this->blnVersionMatch) {
				$strToReturn .= "notice on mismatched Qcodo versions:\r\n";
				$strToReturn .= "  The local install of Qcodo is different than the Qcodo version in this package:\r\n";
				$strToReturn .= sprintf("      locally installed:           v%s (%s)\r\n", $this->strManifestVersion, $this->strManifestVersionType);
				$strToReturn .= sprintf("      package %-20s v%s (%s)\r\n", '"' . $this->strPackageName . '":', $this->strPackageVersion, $this->strPackageVersionType);

				$strText =
					'You can still download and install this QPM package, but note that the difference ' .
					'MAY cause some incompatability issues.  You can always use the Qcodo.com forums to ' .
					'ask the community or package author to see if any known issues exist with your locally ' .
					'installed version of Qcodo, or you can use the Qcodo Updater tool to udpate your locally ' .
					'installed version of Qcodo to match the version used by this package.';
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n\r\n";

				$strText =
					'If you still want to proceed with the download and installation of this QPM, be sure ' .
					'to specify the "-f" or "--force" flag to override this warning.';
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n\r\n";
			}
			return $strToReturn;
		}

		protected function GetErrorText($strErrorArray) {
			$strToReturn = null;
			$strToReturn .= "error on directory tokens:\r\n";
			foreach ($strErrorArray as $strText) {
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n";
			}

			$strToReturn .= "\r\n";

			$strText =
				'You MUST resolve this issue in order to proceed with download and installation.';
			$strText = wordwrap($strText, 76, "\r\n");
			$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
			$strToReturn .= $strText . "\r\n\r\n";

			return $strToReturn;
		}

		protected function GetNonLiveText() {
			$strToReturn = null;
			if (!$this->blnLive) {
				$strToReturn .= "notice on non-live mode:\r\n";

				$strText =
					'This is only a report of what WOULD be downloaded and installed.  To actually execute the download and installation ' .
					'in "live" mode, be sure to specify the "-l" or "--live" flag.';
				$strText = wordwrap($strText, 76, "\r\n");
				$strText = '  ' . str_replace("\r\n", "\r\n  ", $strText);
				$strToReturn .= $strText . "\r\n\r\n";

				$strToReturn .= sprintf("  %-16s  %s\r\n", 'DIRECTORY', 'PATH');
				$strToReturn .= sprintf("  %-16s  %s\r\n", str_repeat('-', 16), str_repeat('-', 58));
				$strToReturn .= "\r\n";
				
				if (count($this->objNewFileArray)) {
					$strToReturn .= "new files will to be installed:\r\n";
					foreach ($this->objNewFileArray as $objFile) $strToReturn .= $objFile->GetTextForQpmReport();
					$strToReturn .= "\r\n";
				}

				if (count($this->objOverwriteFileArray)) {
					$strToReturn .= "existing, non-modified files that will be overwritten:\r\n";
					foreach ($this->objOverwriteFileArray as $objFile) $strToReturn .= $objFile->GetTextForQpmReport();
					$strToReturn .= "\r\n";
				}

				if (count($this->objModifiedFileArray)) {
					$strToReturn .= "existing, locally modified files that will be \"saved as\" a different name\r\n";
					$strToReturn .= "(you will need to reconcile these with the current versions in use):\r\n";
					foreach ($this->objModifiedFileArray as $objFile) $strToReturn .= $objFile->GetTextForQpmReportWithAlternateToken($this->strPackageName);
					$strToReturn .= "\r\n";
				}
			}
			return $strToReturn;
		}

		protected function CheckVersion() {
			$this->strPackageVersion = (string) $this->objQpmXml->package['qcodoVersion'];
			$this->strPackageVersionType = (string) $this->objQpmXml->package['qcodoVersionType'];

			if (($this->strManifestVersion != $this->strPackageVersion)) {
				$this->blnVersionMatch = false;
			} else {
				$this->blnVersionMatch = true;
			}
		}
	}
?>