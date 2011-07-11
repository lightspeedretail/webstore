<?php
	class QErrorLogViewer extends QBaseClass {
		protected $strFileArray = array();
		protected $strErrorLogPath;
		protected $strHash;
		protected $objLogXml;

		public static function GetFileArray($strErrorLogPath) {
			$strFileArray = array();

			$objDirectory = opendir($strErrorLogPath);
			$strPattern = '/qcodo_error_[0-9\-_]+\.html/';
			while ($strFile = readdir($objDirectory))
				if (preg_match($strPattern, $strFile)) {
					$strFileArray[] = $strFile;
				}

			return $strFileArray;
		}

		public function GetLogXmlPath() {
			return $this->strErrorLogPath . '/log.xml';
		}

		/**
		 * To view the logged errors in a given path, specify the folder path of the folder containing
		 * the logged errors.  It is assumed that the filenames of the logged errors are in the format
		 * of "qcodo_error_YYYY-MM-DD_hhhhmmss_X.html", where "X" is the microseconds
		 * 
		 * Because this will cache a log.xml file in this directory, make sure this folder is writable
		 * by the user of the process running this class.
		 * @param string $strErrorLogPath absolute path to the folder containing error log files
		 * @return QErrorLogViewer
		 */
		public function __construct($strErrorLogPath) {
			$this->strErrorLogPath = $strErrorLogPath;
			$this->strFileArray = QErrorLogViewer::GetFileArray($strErrorLogPath);
			sort($this->strFileArray);

			$this->strHash = md5(serialize($this->strFileArray));
			if (!file_exists($this->GetLogXmlPath())) {
				$this->GenerateLogXml();
				$this->objLogXml = new SimpleXMLElement(file_get_contents($this->GetLogXmlPath()));
			} else {
				$this->objLogXml = new SimpleXMLElement(file_get_contents($this->GetLogXmlPath()));
				if ((string) $this->objLogXml->hash != $this->strHash) {
					$this->GenerateLogXml();
					$this->objLogXml = new SimpleXMLElement(file_get_contents($this->GetLogXmlPath()));
				}
			}
		}

		/**
		 * If you wish to view the logged error files in this path, you can do so
		 * in a QDataGrid by using this method as the DataSource for the datagrid.
		 * 
		 * Note that pagination is not allowed for this -- you must view all items.
		 * 
		 * Also note that the return is an array of SimpleXMLElements which can
		 * be used in the QDataGridColumn to render out the contents of the metadata of
		 * a given error.
		 * @return SimpleXMLElement[]
		 */
		public function GetAsDataSource() {
			$objXmlArrayToReturn = array();
			foreach ($this->objLogXml->errors->error as $objErrorXml)
				$objXmlArrayToReturn[] = $objErrorXml;
			return $objXmlArrayToReturn;
		}

		protected function GenerateLogXml() {
			$strContent = '<errorLog><hash>' . $this->strHash . '</hash><count>' . count($this->strFileArray) . '</count>';
			$strContent .= '<errors>';
			foreach ($this->strFileArray as $strFile) {
				$strFileContent = file_get_contents($this->strErrorLogPath . '/' . $strFile);
				if ($intPos = strpos($strFileContent, '<!--qcodo--')) {
					$strFileContent = substr($strFileContent, $intPos + strlen('<!--qcodo--'));
					$strFileContent = substr($strFileContent, 0, strlen($strFileContent) - 3);
					$strContent .= $strFileContent;
				} else {
					// $strFile = qcodo_error_2009-10-20_114539_69546400.html
					$strDate = substr($strFile, 12, 10) . ' ' . substr($strFile, 23, 2) . ':' . substr($strFile, 25, 2) . ':' . substr($strFile, 27, 2);
					$strContent .= '<error valid="false"><filename>' . $strFile . '</filename><isoDateTime>' . $strDate . '</isoDateTime></error>';
				}
			}
			$strContent .= '</errors></errorLog>';

			file_put_contents($this->GetLogXmlPath(), $strContent);
		}
	}
?>