<?php
	class QDialogBox extends QPanel {
		protected $strPosition = QPosition::Absolute;
		protected $strJavaScripts = '_core/control_dialog.js';

		// APPEARANCE
		protected $strMatteColor = '#000000';
		protected $intMatteOpacity = 50;
		protected $strCssClass = 'dialogbox';

		// BEHAVIOR
		protected $blnMatteClickable = true;
		protected $blnAnyKeyCloses = false;

		protected function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();
			return $strToReturn;
		}

		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			if ($this->blnVisible) {
				$strToReturn .= sprintf('qc.regDB("%s", "%s", %s, %s, %s); ',
					$this->strControlId, $this->strMatteColor, $this->intMatteOpacity,
					($this->blnMatteClickable) ? 'true' : 'false',
					($this->blnMatteClickable && $this->blnAnyKeyCloses) ? 'true' : 'false'
				);
			}
			return $strToReturn;
		}

		public function ShowDialogBox() {
			if (!$this->blnVisible)
				$this->Visible = true;
			if (!$this->blnDisplay)
				$this->Display = true;
			QApplication::ExecuteJavaScript("qc.getWrapper('" . $this->strControlId . "').showDialogBox()");
			$this->blnWrapperModified = false;
		}

		public function HideDialogBox() {
			if ($this->blnDisplay)
				$this->Display = false;
			QApplication::ExecuteJavaScript("qc.getWrapper('" . $this->strControlId . "').hideDialogBox()");
			$this->blnWrapperModified = false;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "MatteColor": return $this->strMatteColor;
				case "MatteOpacity": return $this->intMatteOpacity;

				// BEHAVIOR
				case "MatteClickable": return $this->blnMatteClickable;
				case "AnyKeyCloses": return $this->blnAnyKeyCloses;

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
			$this->blnModified = true;

			switch ($strName) {
				case "MatteColor":
					try {
						$this->strMatteColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "MatteOpacity":
					try {
						$this->intMatteOpacity = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "MatteClickable":
					try {
						$this->blnMatteClickable = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "AnyKeyCloses":
					try {
						$this->blnAnyKeyCloses = QType::Cast($mixValue, QType::Boolean);
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