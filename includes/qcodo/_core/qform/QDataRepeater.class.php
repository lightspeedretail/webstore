<?php
	class QDataRepeater extends QPaginatedControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strTemplate = null;
		protected $intCurrentItemIndex = null;

		protected $strTagName = 'div';

		//////////
		// Methods
		//////////
		public function GetJavaScriptAction() {}
		public function ParsePostData() {}

		protected function GetControlHtml() {
			$this->DataBind();

			// Setup Style
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			// Iterate through everything
			$this->intCurrentItemIndex = 0;
			$strEvalledItems = '';
			$strToReturn = '';
			if (($this->strTemplate) && ($this->objDataSource)) {
				global $_FORM;
				global $_CONTROL;
				global $_ITEM;
				$_FORM = $this->objForm;
				$objCurrentControl = $_CONTROL;
				$_CONTROL = $this;

				foreach ($this->objDataSource as $objObject) {
					$_ITEM = $objObject;
					$strEvalledItems .= $this->objForm->EvaluateTemplate($this->strTemplate);
					$this->intCurrentItemIndex++;
				}

				$strToReturn = sprintf('<%s id="%s" %s%s>%s</%s>',
					$this->strTagName,
					$this->strControlId,
					$this->GetAttributes(),
					$strStyle,
					$strEvalledItems,
					$this->strTagName);

				$_CONTROL = $objCurrentControl;
			}

			$this->objDataSource = null;
			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Template": return $this->strTemplate;
				case "CurrentItemIndex": return $this->intCurrentItemIndex;
				case "TagName": return $this->strTagName;
				
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
				case "Template":
					try {
						if (file_exists($mixValue))
							$this->strTemplate = QType::Cast($mixValue, QType::String);
						else
							throw new QCallerException('Template file does not exist: ' . $mixValue);
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