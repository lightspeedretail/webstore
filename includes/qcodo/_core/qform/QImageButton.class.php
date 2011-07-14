<?php
	// This class will render an HTML ImageButton <input type="image">.
	// * "AlternateText" is rendered as the HTML "alt" tag.
	// * "ImageUrl" is the url of the image to be used.

	class QImageButton extends QActionControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strAlternateText = null;
		protected $strImageUrl = null;

		// BEHAVIOR
		protected $blnPrimaryButton = false;
		protected $intClickX;
		protected $intClickY;

		// SETTINGS
		protected $blnActionsMustTerminate = true;
		

		//////////
		// Methods
		//////////
		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = parent::GetAttributes($blnIncludeCustom, $blnIncludeAction);

			if ($this->strAlternateText)
				$strToReturn .= sprintf('alt="%s" ', $this->strAlternateText);
			if ($this->strImageUrl)
				$strToReturn .= sprintf('src="%s" ', $this->strImageUrl);

			return $strToReturn;
		}
		
		public function ParsePostData() {
			$strKeyX = sprintf('%s_x', $this->strControlId);
			$strKeyY = sprintf('%s_y', $this->strControlId);
			if (array_key_exists($strKeyX, $_POST)) {
				$this->intClickX = $_POST[$strKeyX];
				$this->intClickY = $_POST[$strKeyY];
			} else {
				$this->intClickX = null;
				$this->intClickY = null;
			}
		}

		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			if ($this->blnPrimaryButton) {
				$strToReturn = sprintf('<input type="image" name="%s" id="%s" %s%s />',
					$this->strControlId,
					$this->strControlId,
					$this->GetAttributes(),
					$strStyle);
			} else {
				$strToReturn = sprintf('<img name="%s" id="%s" %s%s />',
					$this->strControlId,
					$this->strControlId,
					$this->GetAttributes(),
					$strStyle);
			}
			
			$strToReturn .= sprintf('<input type="hidden" name="%s_x" id="%s_x" value=""/><input type="hidden" name="%s_y" id="%s_y" value=""/>',
				$this->strControlId,
				$this->strControlId,
				$this->strControlId,
				$this->strControlId);

			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "AlternateText": return $this->strAlternateText;
				case "ImageUrl": return $this->strImageUrl;

				// BEHAVIOR
				case "PrimaryButton": return $this->blnPrimaryButton;
				case "ClickX": return $this->intClickX;
				case "ClickY": return $this->intClickY;

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
				case "AlternateText":
					try {
						$this->strAlternateText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageUrl":
					try {
						$this->strImageUrl = QType::Cast($mixValue, QType::String);
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
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>