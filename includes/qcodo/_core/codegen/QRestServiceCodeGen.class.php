<?php
	class QRestServiceCodeGen extends QCodeGen {
		// REST Service-specific Attributes
		protected $strServiceUrl;

		public function __construct($objSettingsXml) {
			// Lookup Instance-Specific Configuration from the SettingsXml Node
			$this->strServiceUrl = QCodeGen::LookupSetting($objSettingsXml, null, 'serviceUrl');
		}

		public function GetTitle() {
			return sprintf('REST Service (%s)', $this->strServiceUrl);
		}

		public function GetConfigXml() {
			$strCrLf = "\r\n";
			$strToReturn = sprintf('		<restService url="%s">%s', $this->strServiceUrl, $strCrLf);
			$strToReturn .= sprintf('		</restService>%s', $strCrLf);
			return $strToReturn;
		}

		public function GetReportLabel() {
			return 'There were 2 REST Services available to attempt code generation:';
		}

		public function GenerateAll() {
			$strReport = '';
			$strReport .= "Successfully generated REST Service Class:   TestBlahservice\r\n";
			return $strReport;
		}


		////////////////////
		// Public Overriders
		////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'ServiceUrl':
					return $this->strServiceUrl;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch($strName) {
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
			}
		}
	}
?>