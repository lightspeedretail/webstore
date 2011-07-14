<?php
	class QDateTimeTextBox extends QTextBox {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// MISC
		protected $dttMinimum = null;
		protected $dttMaximum = null;
		
		protected $strLabelForInvalid = 'For example, "Mar 20, 4:30pm" or "Mar 20"';
		protected $calLinkedControl;

		//////////
		// Methods
		//////////
		
		public static function ParseForDateTimeValue($strText) {
			// Trim and Clean
			$strText = strtolower(trim($strText));
			while(strpos($strText, '  ') !== false)
				$strText = str_replace('  ', ' ', $strText);
			$strText = str_replace('.', '', $strText);
			$strText = str_replace('@', ' ', $strText);

			// Are we ATTEMPTING to parse a Time value?
			if ((strpos($strText, ':') === false) &&
				(strpos($strText, 'am') === false) &&
				(strpos($strText, 'pm') === false)) {
				// There is NO TIME VALUE
				$dttToReturn = new QDateTime($strText);
				if ($dttToReturn->IsDateNull())
					return null;
				else
					return $dttToReturn;
			}

			// Add ':00' if it doesn't exist AND if 'am' or 'pm' exists
			if ((strpos($strText, 'pm') !== false) &&
				(strpos($strText, ':') === false)) {
				$strText = str_replace(' pm', ':00 pm', $strText, $intCount);
				if (!$intCount)
					$strText = str_replace('pm', ':00 pm', $strText, $intCount);
			} else if ((strpos($strText, 'am') !== false) &&
				(strpos($strText, ':') === false)) {
				$strText = str_replace(' am', ':00 am', $strText, $intCount);
				if (!$intCount)
					$strText = str_replace('am', ':00 am', $strText, $intCount);
			}

			$dttToReturn = new QDateTime($strText);
			if ($dttToReturn->IsDateNull())
				return null;
			else
				return $dttToReturn;
		}

		public function Validate() {
			if (parent::Validate()) {
				if ($this->strText != "") {
					$dttTest = QDateTimeTextBox::ParseForDateTimeValue($this->strText);

					if (!$dttTest) {
						$this->strValidationError = $this->strLabelForInvalid;
						return false;
					}

					if (!is_null($this->dttMinimum)) {
						if ($this->dttMinimum == QDateTime::Now) {
							$dttToCompare = new QDateTime(QDateTime::Now);
							$strError = 'in the past';
						} else {
							$dttToCompare = $this->dttMinimum;
							$strError = 'before ' . $this->dttMinimum->__toString();
						}

						if ($dttTest->IsEarlierThan($dttToCompare)) {
							$this->strValidationError = 'Date cannot be ' . $strError;
							return false;
						}
					}
					
					if (!is_null($this->dttMaximum)) {
						if ($this->dttMaximum == QDateTime::Now) {
							$dttToCompare = new QDateTime(QDateTime::Now);
							$strError = 'in the future';
						} else {
							$dttToCompare = $this->dttMaximum;
							$strError = 'after ' . $this->dttMaximum->__toString();
						}

						if ($dttTest->IsLaterThan($dttToCompare)) {
							$this->strValidationError = 'Date cannot be ' . $strError;
							return false;
						}
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
				case "Maximum": return $this->dttMaximum;
				case "Minimum": return $this->dttMinimum;
				case 'DateTime': return QDateTimeTextBox::ParseForDateTimeValue($this->strText);
				case 'LabelForInvalid': return $this->strLabelForInvalid;

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
						if ($mixValue == QDateTime::Now)
							$this->dttMaximum = QDateTime::Now;
						else
							$this->dttMaximum = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Minimum':
					try {
						if ($mixValue == QDateTime::Now)
							$this->dttMinimum = QDateTime::Now;
						else
							$this->dttMinimum = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForInvalid':
					try {
						return ($this->strLabelForInvalid = QType::Cast($mixValue, QType::String));
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