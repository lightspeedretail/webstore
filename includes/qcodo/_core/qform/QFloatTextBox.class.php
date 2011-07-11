<?php
	// A subclass of TextBox with its validate method overridden -- Validate will also ensure
	// that the Text is a valid integer and (if applicable) is in the range of Minimum <= x <= Maximum
	// * "Maximum" (optional) is the maximum value the integer can be
	// * "Minimum" (optional) is the minimum value the integer can be

	class QFloatTextBox extends QTextBox {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// MISC
		protected $fltMaximum = null;
		protected $fltMinimum = null;

		protected $strLabelForInvalid;
		protected $strLabelForLess;
		protected $strLabelForGreater;

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strLabelForInvalid = QApplication::Translate('Invalid Float');
			$this->strLabelForLess = QApplication::Translate('Value must be less than %s');
			$this->strLabelForGreater = QApplication::Translate('Value must be greater than %s');
		}

		public function Validate() {
			if (parent::Validate()) {
				if ($this->strText != '') {
					try {
						$this->strText = QType::Cast($this->strText, QType::Float);
					} catch (QInvalidCastException $objExc) {
						$this->strValidationError = $this->strLabelForInvalid;
						return false;
					}
					
					if ((!is_null($this->fltMinimum)) && ($this->strText < $this->fltMinimum)) {
						$this->strValidationError = sprintf($this->strLabelForGreater, $this->fltMinimum);
						return false;
					}

					if ((!is_null($this->fltMaximum)) && ($this->strText > $this->fltMaximum)) {
						$this->strValidationError = sprintf($this->strLabelForLess, $this->fltMaximum);
						return false;
					}
				}
			} else
				return false;

			$this->strValidationError = '';
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case 'Maximum': return $this->fltMaximum;
				case 'Minimum': return $this->fltMinimum;
				case 'LabelForInvalid': return $this->strLabelForInvalid;
				case 'LabelForGreater': return $this->strLabelForGreater;
				case 'LabelForLess': return $this->strLabelForLess;

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
				// MISC
				case 'Maximum':
					try {
						$this->fltMaximum = QType::Cast($mixValue, QType::Float);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case 'Minimum':
					try {
						$this->fltMinimum = QType::Cast($mixValue, QType::Float);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForInvalid':
					try {
						$this->strLabelForInvalid = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForGreater':
					try {
						$this->strLabelForGreater = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForLess':
					try {
						$this->strLabelForLess = QType::Cast($mixValue, QType::String);
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