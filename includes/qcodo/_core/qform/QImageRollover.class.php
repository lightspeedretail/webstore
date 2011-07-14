<?php
	class QImageRollover extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// BEHAVIOR
		protected $mixImageStandard;
		protected $mixImageHover;
		protected $strLinkUrl;
		protected $strCustomLinkStyleArray = array();
		protected $strAltText;

		protected $strJavaScripts = '_core/control_rollover.js';

		//////////
		// Methods
		//////////
		public function ParsePostData() {}

		public function SetCustomLinkStyle($strName, $strValue) {
			$this->blnModified = true;
			if (!is_null($strValue))
				$this->strCustomLinkStyleArray[$strName] = $strValue;
			else {
				$this->strCustomLinkStyleArray[$strName] = null;
				unset($this->strCustomLinkStyleArray[$strName]);
			}
		}
		
		public function GetCustomLinkStyle($strName) {
			if ((is_array($this->strCustomLinkStyleArray)) && (array_key_exists($strName, $this->strCustomLinkStyleArray)))
				return $this->strCustomLinkStyleArray[$strName];
			else
				throw new QCallerException(sprintf("Custom Link Style does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		public function RemoveCustomLinkStyle($strName) {
			$this->blnModified = true;
			if ((is_array($this->strCustomLinkStyleArray)) && (array_key_exists($strName, $this->strCustomLinkStyleArray))) {
				$this->strCustomLinkStyleArray[$strName] = null;
				unset($this->strCustomLinkStyleArray[$strName]);
			} else
				throw new QCallerException(sprintf("Custom Link Style does not exist in Control '%s': %s", $this->strControlId, $strName));
		}
		
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			if ($this->strLinkUrl) {
				$strLinkStyle = '';
				if ($this->strCustomLinkStyleArray) foreach ($this->strCustomLinkStyleArray as $strKey => $strValue)
					$strLinkStyle .= sprintf('%s:%s;', $strKey, $strValue);
				if ($strLinkStyle)
					$strLinkStyle = ' style="' . $strLinkStyle . '"';

				$strToolTip = ($this->strToolTip) ? ' tooltip ="' . QApplication::HtmlEntities($this->strToolTip) . '"' : null;

				$strToReturn = '<a href="' . $this->strLinkUrl . '" id="' . $this->strControlId . '" name="' . $this->strControlId . '"' . $strToolTip . $strLinkStyle . '>';
			} else
				$strToReturn = '';

			if ($this->strAltText)
				$strAltText = ' alt="' . QApplication::HtmlEntities($this->strAltText) . '"';
			else if ($this->strToolTip)
				$strAltText = ' alt="' . QApplication::HtmlEntities($this->strToolTip) . '"';
			else
				$strAltText = '';

			$strControlId = ($this->strLinkUrl) ? $this->strControlId . '_img' : $this->strControlId;
			$strToReturn .= sprintf('<img id="%s" name="%s" src="%s" %s%s%s/>',
				$strControlId,
				$strControlId,
				($this->mixImageStandard instanceof QImageBase) ? $this->mixImageStandard->RenderAsImgSrc(false) : $this->mixImageStandard,
				$this->GetAttributes(),
				$strStyle,
				$strAltText);

			if ($this->strLinkUrl)
				$strToReturn .= '</a>';

			return $strToReturn;
		}

		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			if ($this->blnVisible && $this->mixImageHover) {
				$strToReturn .= sprintf('qc.regIR("%s", "%s", "%s", %s); ',
					$this->strControlId,
					($this->mixImageStandard instanceof QImageBase) ? $this->mixImageStandard->RenderAsImgSrc(false) : $this->mixImageStandard,
					($this->mixImageHover instanceof QImageBase) ? $this->mixImageHover->RenderAsImgSrc(false) : $this->mixImageHover,
					($this->strLinkUrl) ? 'true' : 'false');
			}
			return $strToReturn;
		}

		public function Validate() {return true;}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "ImageStandard": return $this->mixImageStandard;
				case "ImageHover": return $this->mixImageHover;
				case "AltText": return $this->strAltText;
				case "LinkUrl": return $this->strLinkUrl;

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
				case "ImageStandard":
					try {
						if ($mixValue instanceof QImageBase)
							$this->mixImageStandard = $mixValue;
						else
							$this->mixImageStandard = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageHover":
					try {
						if ($mixValue instanceof QImageBase)
							$this->mixImageHover = $mixValue;
						else
							$this->mixImageHover = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "AltText":
					try {
						$this->strAltText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "LinkUrl":
					try {
						$this->strLinkUrl = QType::Cast($mixValue, QType::String);
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
