<?php
	class QDirectoryToken extends QBaseClass {
		public $Token;
		public $RelativeFlag;
		public $CoreFlag;
		
		public function GetFullPath() {
			return ($this->RelativeFlag) ? __DOCROOT__ . constant($this->Token) : constant($this->Token);
		}

		public function GetRelativePathForFile($strFullPath) {
			$strDirectory = realpath($this->GetFullPath());
			$strFullPath = realpath($strFullPath);
			if (substr($strFullPath, 0, strlen($strDirectory)) == $strDirectory)
				return substr($strFullPath, strlen($strDirectory) + 1);
			else
				throw new Exception('Cannot calculate relative path in ' . $this->Token . ' for file: ' . $strFullPath);
		}
	}

	class QFileInManifest extends QBaseClass {
		public $RealPath;
		public $DirectoryToken;
		public $Path;
		public $Md5;
		public $DirectoryTokenObject;
		public $Base64Data;

		public function GetFullPath() {
			return $this->DirectoryTokenObject->GetFullPath() . '/' . $this->Path;
		}

		public static function DownloadFileFromWebWithStatusOutput($strUrl) {
			$objFile = fopen($strUrl, 'r');
			$strMetaDataArray = stream_get_meta_data($objFile);
			$intContentLength = null;
			foreach ($strMetaDataArray['wrapper_data'] as $strHeader) {
				$strHeader = strtolower($strHeader);
				if (strpos($strHeader, 'content-length: ') === 0) {
					$intContentLength = intval(substr($strHeader, 16));
				}
			}
			if (!$intContentLength) {
				return null;
			}
			print 'Downloading package (' . $intContentLength . ' bytes)...';
			print "\r\n";

			$strData = null;
			$strLinePrint = null;
			for ($intIndex = 0; $intIndex < $intContentLength; $intIndex++) {
				if (($intIndex % 1024) == 0) {
					print (str_repeat(chr(8), strlen($strLinePrint)));
					$intPercent = floor(($intIndex / $intContentLength) * 100);
					$strLinePrint = sprintf('%s%% [%-50s] %s',
						$intPercent, str_repeat('=', floor($intPercent / 2)) . '>', $intIndex);
					print $strLinePrint;
				}
		
				$strData .= fgetc($objFile);
			}
			fclose($objFile);

			print (str_repeat(chr(8) . ' ' . chr(8), strlen($strLinePrint)));
			print "Done.\r\n\r\n";

			return $strData;
		}

		/**
		 * Given FileXml from a QPM package definition, this will return a valid QFileInManifest object for that XML element
		 * @param SimpleXMLElement $objFileXml
		 * @param QDirectoryToken[] $objDirectoryTokenArray
		 * @return QFileInManifest
		 */
		public static function LoadFromQpmXml(SimpleXMLElement $objFileXml, $objDirectoryTokenArray) {
			$objFile = new QFileInManifest();
			$objFile->DirectoryToken = (string) $objFileXml['directoryToken'];
			$objFile->Path = (string) $objFileXml['path'];
			$objFile->Md5 = (string) $objFileXml['md5'];

			if (array_key_exists($objFile->DirectoryToken, $objDirectoryTokenArray)) {
				$objFile->DirectoryTokenObject = $objDirectoryTokenArray[$objFile->DirectoryToken];
			} else {
				return null;
			}

			if (is_file($objFile->GetFullPath()))
				$objFile->RealPath = realpath($objFile->GetFullPath());

			$objFile->Base64Data = (string) $objFileXml;
			return $objFile;
		}

		/**
		 * This will save the contents of the base64_decoded data to the filesystem.
		 * @param string $strAlternateToken
		 * @return void
		 */
		public function SaveFileFromQpm($strAlternateToken = null) {
			$strDecodedData = base64_decode($this->Base64Data);
			if (md5($strDecodedData) != $this->Md5) print "WARNING: Invalid MD5 Match for " . $this->Path . "\r\n";
			QApplication::MakeDirectory(dirname($this->GetFullPath()));
			if ($strAlternateToken)
				file_put_contents($this->GetFullPathWithAlternateToken($strAlternateToken), $strDecodedData);
			else
				file_put_contents($this->GetFullPath(), $strDecodedData);
		}

		public function GetFullPathWithAlternateToken($strAlternateToken) {
			$strActualFilePath = $this->GetFullPath();
			$strBaseName = basename($strActualFilePath);
			$intPosition = strpos($strBaseName, '.');
			if ($intPosition)
				return sprintf('%s/%s (%s).%s',
					dirname($strActualFilePath), substr($strBaseName, 0, $intPosition),
					$strAlternateToken,
					substr($strBaseName, $intPosition + 1));
			else
				return sprintf('%s/%s (%s)',
					dirname($strActualFilePath), $strBaseName, $strAlternateToken);
		}
		
		public function IsMd5MatchWithFilesystem() {
			return (md5_file($this->GetFullPath()) == $this->Md5);
		}

		public function GetTextForQpmReport() {
			return sprintf("  %-16s  %s\r\n", $this->DirectoryToken, $this->Path);
		}

		public function GetTextForQpmReportWithAlternateToken($strAlternateToken) {
			$intPosition = strpos($this->Path, '.');

			if ($intPosition)
				$strPath = sprintf('%s (%s).%s',
					substr($this->Path, 0, $intPosition),
					$strAlternateToken,
					substr($this->Path, $intPosition + 1));
			else
				$strPath = sprintf('%s (%s)', $this->Path, $strAlternateToken);

			return sprintf("  %-16s  %s\r\n", $this->DirectoryToken, $strPath);
		}
	}

	/* The following functions are used as QUpdateUtility error handlers for OS-level errors while trying
	 * to perform updates/deletes/overwrites/saves/socket connections, etc.
	 * (e.g. cannot connect, or permission denied, file locked, etc.)
	 */
	function QUpdateUtilityErrorHandler($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine) {
		QUpdateUtility::Error('Could not connect to Qcodo Update webservice at ' . QUpdateUtility::ServiceUrl . ' (' . $strErrorString . ')');
	}

	function QUpdateUtilityFileSystemErrorHandler($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine) {
		QUpdateUtility::$PrimaryInstance->strAlertArray[count(QUpdateUtility::$PrimaryInstance->strAlertArray)] =
			sprintf('%s while trying to download and save %s', $strErrorString, QUpdateUtility::$CurrentFilePath);
	}

	function QUpdateUtilityFileSystemErrorHandlerForDelete($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine) {
		QUpdateUtility::$PrimaryInstance->strAlertArray[count(QUpdateUtility::$PrimaryInstance->strAlertArray)] =
			sprintf('%s while trying to delete %s', $strErrorString, QUpdateUtility::$CurrentFilePath);
	}

	function QUpdateUtilityFileSystemErrorHandlerForRename($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine) {
		QUpdateUtility::$PrimaryInstance->strAlertArray[count(QUpdateUtility::$PrimaryInstance->strAlertArray)] =
			sprintf('%s while trying to rename %s', $strErrorString, QUpdateUtility::$CurrentFilePath);
	}
?>