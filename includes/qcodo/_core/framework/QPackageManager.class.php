<?php
	require(dirname(__FILE__) . "/_manifest_helpers.inc.php");

	abstract class QPackageManager extends QBaseClass {
		protected $strPackageName;
		protected $strUsername;
		protected $blnLive;
		protected $blnForce;

		protected $objDirectoryArray;
		protected $objFileArrayByRealPath;
		protected $objManifestXml;

		protected $strManifestVersion;
		protected $strManifestVersionType;

		const QpmServiceEndpoint = 'http://qpm.qcodo.com/1_0.php';

		/**
		 * In addition to any files OR folders that start with a period ("."), QPM will ignore any
		 * folders that are named in the following IgnoreFolderArray
		 * @var boolean[]
		 */
		protected $blnIgnoreFolderArray = array(
			'cvs' => true,
			'svn' => true
		);

		/**
		 * In addition to any files OR folders that start with a period ("."), QPM will ignore any
		 * files that are named in the following IgnoreFileArray
		 * @var string[]
		 */
		protected $blnIgnoreFileArray = array(
			'manifest/manifest.xml' => '__QCODO_CORE__',
			'configuration.inc.php' => '__INCLUDES__',
			'QApplication.class.php' => '__INCLUDES__',
			'settings/codegen.xml' => '__DEVTOOLS_CLI__',
			'settings/qpm.xml' => '__DEVTOOLS_CLI__'
		);

		protected function SetupManifestXml() {
			$this->objManifestXml = new SimpleXMLElement(file_get_contents(__QCODO_CORE__ . '/manifest/manifest.xml'));
		}

		protected function SetupDirectoryArray() {
			$this->objDirectoryArray = array();
			foreach ($this->objManifestXml->directories->directory as $objDirectoryXml) {
				$objToken = new QDirectoryToken();
				$objToken->Token = (string) $objDirectoryXml['token'];
				$objToken->CoreFlag = (string) $objDirectoryXml['coreFlag'];
				$objToken->RelativeFlag = (string) $objDirectoryXml['relativeFlag'];
				$this->objDirectoryArray[$objToken->Token] = $objToken;
			}
		}

		protected function SetupFileArray() {
			$this->objFileArrayByRealPath = array();
			foreach ($this->objManifestXml->files->file as $objFileXml) {
				$objFileInManifest = new QFileInManifest();
				$objFileInManifest->DirectoryToken = (string) $objFileXml['directoryToken'];
				$objFileInManifest->Path = (string) $objFileXml['path'];
				$objFileInManifest->Md5 = (string) $objFileXml['md5'];

				$objFileInManifest->DirectoryTokenObject = $this->objDirectoryArray[$objFileInManifest->DirectoryToken];

				// Make sure this is valid and in-use DirectoryToken and that this file exists
				if (constant($objFileInManifest->DirectoryTokenObject->Token) && file_exists($objFileInManifest->GetFullPath())) {
					$objFileInManifest->RealPath = realpath($objFileInManifest->GetFullPath());
					if ($objFileInManifest->RealPath)
						$this->objFileArrayByRealPath[$objFileInManifest->RealPath] = $objFileInManifest;
				}
			}
		}

		protected function SetupManifestVersion() {
			$this->strManifestVersion = (string) $this->objManifestXml->version;
			$this->strManifestVersionType = (string) $this->objManifestXml->type;
		}
	}
?>