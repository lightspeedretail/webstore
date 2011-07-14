<?php
	class QWaitIcon extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strText = null;
		protected $strPadding = null;
		protected $strTagName = 'span';
		protected $blnDisplay = false;

		// LAYOUT
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		protected $strVerticalAlign = QVerticalAlign::NotSet;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strText = sprintf('<img src="%s/spinner_14.gif" width="14" height="14" alt="Please Wait..."/>', __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__);
		}

		public function GetStyleAttributes() {
			$strStyle = parent::GetStyleAttributes();

			if ($this->strPadding)
				$strStyle .= sprintf('padding:%s;', $this->strPadding);

			if (($this->strHorizontalAlign) && ($this->strHorizontalAlign != QHorizontalAlign::NotSet))
				$strStyle .= sprintf('text-align:%s;', $this->strHorizontalAlign);

			if (($this->strVerticalAlign) && ($this->strVerticalAlign != QVerticalAlign::NotSet))
				$strStyle .= sprintf('vertical-align:%s;', $this->strVerticalAlign);

			return $strStyle;
		}

		//////////
		// Methods
		//////////
		public function ParsePostData() {}
		public function Validate() {return true;}
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();

			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			$strToReturn = sprintf('<%s id="%s" %s%s>%s</%s>',
				$this->strTagName,
				$this->strControlId,
				$this->GetAttributes(true, false),
				$strStyle,
				$this->strText,
				$this->strTagName);

			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "TagName": return $this->strTagName;
				case "Padding": return $this->strPadding;

				// LAYOUT
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;

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

				case "TagName":
					try {
						$this->strTagName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Padding":
					try {
						$this->strPadding = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HorizontalAlign":
					try {
						$this->strHorizontalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "VerticalAlign":
					try {
						$this->strVerticalAlign = QType::Cast($mixValue, QType::String);
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