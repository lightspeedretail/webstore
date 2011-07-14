<?php
	class QControlLabel extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strText = null;

		// BEHAVIOR
		protected $strForControlId;

		//////////
		// Methods
		//////////
		public function ParsePostData() {}
		public function GetJavaScriptAction() {}
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			$strToReturn = sprintf('<label id="%s" for="%s" %s%s>%s</label>',
				$this->strControlId,
				$this->strForControlId,
				$this->GetAttributes(),
				$strStyle,
				$this->strText);

			return $strToReturn;
		}
		public function Validate() {return true;}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "ForControlId": return $this->strForControlId;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "Text":
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ForControlId":
					try {
						$this->strForControlId = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				case "ForControl":
					try {
						$objControl = QType::Cast($mixValue, 'QControl');
						$this->strForControlId = QType::Cast($objControl->ControlId, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>