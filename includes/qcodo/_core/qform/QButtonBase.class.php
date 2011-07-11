<?php
	// This class will render an HTML Button.
	// * "Text" is used to display the button's text.
	// * "PrimaryButton" is a boolean to specify whether or not the button is
	//   'primary' (e.g. makes this button a "Submit" form element rather than a "Button" form element)

	abstract class QButtonBase extends QActionControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strText = null;
		protected $blnHtmlEntities = true;

		// BEHAVIOR
		protected $blnPrimaryButton = false;

		// SETTINGS
		protected $blnActionsMustTerminate = true;

		//////////
		// Methods
		//////////
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			if ($this->blnPrimaryButton)
				$strCommand = "submit";
			else
				$strCommand = "button";

			$strToReturn = sprintf('<input type="%s" name="%s" id="%s" value="%s" %s%s />',
				$strCommand,
				$this->strControlId,
				$this->strControlId,
				($this->blnHtmlEntities) ?
					QApplication::HtmlEntities($this->strText) :
					$this->strText,
				$this->GetAttributes(),
				$strStyle);

			return $strToReturn;
		}



		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "HtmlEntities": return $this->blnHtmlEntities;

				// BEHAVIOR
				case "PrimaryButton": return $this->blnPrimaryButton;

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

				case "HtmlEntities":
					try {
						$this->blnHtmlEntities = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "PrimaryButton":
					try {
						$this->blnPrimaryButton = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>