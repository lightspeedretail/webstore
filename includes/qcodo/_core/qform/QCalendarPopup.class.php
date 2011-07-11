<?php
	// This class is meant to be a date-picker.  It will essentially render an uneditable HTML textbox
	// as well as a calendar icon.  The idea is that if you click on the icon or the textbox,
	// it will pop up a calendar in a new small window.
	// * "DateTime" is a Date object for the specified date.

	class QCalendarPopup extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// MISC
		protected $intTimestamp = null;
		protected $strCalendarType = QCalendarType::DateOnly;

		// SETTINGS
		protected $strJavaScripts = '_core/calendar_popup.js';

		//////////
		// Methods
		//////////
		public function ParsePostData() {
			$blnChanged = false;
			$dttNewDateTime = new QDateTime();

			// Update Date Component
			switch ($this->strCalendarType) {
				case QCalendarType::DateOnly:
				case QCalendarType::DateTime:
				case QCalendarType::DateTimeSeconds:
				$strKey = $this->strControlId . "_intTimestamp";
				if (array_key_exists($strKey, $_POST)) {
					// If no date was set, set to null and return
					$intTimestamp = $_POST[$strKey];
					if (!$intTimestamp) {
						$this->intTimestamp = null;
						return;
					}

					// Otherwise, set up a new date object, and update dttNewDateTime accordingly
					$blnChanged = true;
					$dttSelectedDate = QDateTime::FromTimestamp($_POST[$strKey]);

					$dttNewDateTime->SetDate($dttSelectedDate->Year, $dttSelectedDate->Month, $dttSelectedDate->Day);					
				}
			}

			// Update Time Component
			switch ($this->strCalendarType) {
				case QCalendarType::TimeOnly:
				case QCalendarType::TimeSecondsOnly:
				case QCalendarType::DateTime:
				case QCalendarType::DateTimeSeconds:
					// Hour
					$strKey = $this->strControlId . "_intHour";
					if (array_key_exists($strKey, $_POST)) {
						$blnChanged = true;
						$dttNewDateTime->SetTime($_POST[$strKey], $dttNewDateTime->Minute, $dttNewDateTime->Second);
					}

					// Minute
					$strKey = $this->strControlId . "_intMinute";
					if (array_key_exists($strKey, $_POST)) {
						$blnChanged = true;
						$dttNewDateTime->SetTime($dttNewDateTime->Hour, $_POST[$strKey], $dttNewDateTime->Second);
					}

					// Second
					$strKey = $this->strControlId . "_intSecond";
					if (array_key_exists($strKey, $_POST)) {
						$blnChanged = true;
						$dttNewDateTime->SetTime($dttNewDateTime->Hour, $dttNewDateTime->Minute, $_POST[$strKey]);
					}
			}

			// Update local intTimestamp
			$this->intTimestamp = $dttNewDateTime->Timestamp;
		}

		public function GetJavaScriptAction() {
			return "onchange";
		}

		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			if ($this->intTimestamp) {
				$intTimestamp = $this->intTimestamp;
				$strDate = $this->DateTime->__toString(QDateTime::FormatDisplayDate);
			} else {
				$intTimestamp = "";
				$strDate = "";
			}
			
			$strToReturn = "";

			switch ($this->strCalendarType) {
				case QCalendarType::DateOnly:
				case QCalendarType::DateTime:
				case QCalendarType::DateTimeSeconds:
					$strToReturn .= sprintf('<input type="hidden" name="%s_intTimestamp" id="%s_intTimestamp" value="%s" />',
						$this->strControlId,
						$this->strControlId,
						$intTimestamp);

					$strToReturn .= sprintf('<input type="text" onFocus="this.blur()" onClick="__calendar(%s, %s)" name="%s" id="%s" value="%s" %s%s>',
						"'" . $this->Form->FormId . "'",
						"'" . $this->strControlId . "'",
						$this->strControlId,
						$this->strControlId,
						$strDate,
						$this->GetAttributes(),
						$strStyle);
		
					if ($this->blnEnabled) {
						$strToReturn .= sprintf(' <a href="javascript:__calendar(%s, %s)"><img src="%s/calendar.png" border="0"></a>',
							"'" . $this->Form->FormId . "'",
							"'" . $this->strControlId . "'",
							__VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__);

						if (!$this->blnRequired) {
							$strToReturn .= sprintf(' <a href="javascript:__resetCalendar(%s, %s)" style="font-family: verdana, arial, helvetica; font-size: 8pt; text-decoration: none;">%s</a>',
								"'" . $this->Form->FormId . "'",
								"'" . $this->strControlId . "'",
								QApplication::Translate('Reset'));
						}
					}
			}
			
			switch ($this->strCalendarType) {
				case QCalendarType::DateTime:
				case QCalendarType::DateTimeSeconds:
					$strToReturn .= ' &nbsp; ';
			}

			switch ($this->strCalendarType) {
				case QCalendarType::DateTime:
				case QCalendarType::DateTimeSeconds:
				case QCalendarType::TimeOnly:
				case QCalendarType::TimeSecondsOnly:
					if ($intTimestamp)
						$dttDate = QDateTime::FromTimestamp($intTimestamp);
					else
						$dttDate = new QDateTime();

					$strToReturn .= sprintf('<select name="%s_intHour" id="%s_intHour">', $this->strControlId, $this->strControlId);
					for ($intHour = 0; $intHour <= 23; $intHour++) {
						$strToReturn .= sprintf('<option value="%s" %s>%s</option>', $intHour, ($intHour == $dttDate->Hour) ? 'selected="selected"' : '', date('g A', mktime($intHour, 0, 0, 1, 1, 2000)));
					}
					$strToReturn .= '</select> : ';

					$strToReturn .= sprintf('<select name="%s_intMinute" id="%s_intMinute">', $this->strControlId, $this->strControlId);
					for ($intMinute = 0; $intMinute <= 59; $intMinute++) {
						$strToReturn .= sprintf('<option value="%s" %s>%02d</option>', $intMinute, ($intMinute == $dttDate->Minute) ? 'selected="selected"' : '', $intMinute);
					}
					$strToReturn .= '</select>';
					
					if (($this->strCalendarType == QCalendarType::DateTimeSeconds) || ($this->strCalendarType == QCalendarType::TimeSecondsOnly)) {
						$strToReturn .= sprintf(' : <select name="%s_intSecond" id="%s_intSecond">', $this->strControlId, $this->strControlId);
						for ($intSecond = 0; $intSecond <= 59; $intSecond++) {
							$strToReturn .= sprintf('<option value="%s" %s>%02d</option>', $intSecond, ($intSecond == $dttDate->Second) ? 'selected="selected"' : '', $intSecond);
						}
						$strToReturn .= '</select>';
					}
			}

			return $strToReturn;
		}

		public function Validate() {
			if ($this->blnRequired)
				if (!$this->intTimestamp) {
					$this->strValidationError = sprintf(QApplication::Translate("%s is required"), $this->strName);
					return false;
				}
			
			$this->strValidationError = "";
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "DateTime":
					if ($this->intTimestamp) {
						$dttToReturn = QDateTime::FromTimestamp($this->intTimestamp);
						$dttToReturn->SetTime(null, null, null);
						return $dttToReturn;
					} else {
						return null;
					}
				
				case "CalendarType":
					return $this->strCalendarType;

//				case "MinimumDate": return $this->dttMinimumDate;
//				case "MaximumDate": return $this->dttMaximumDate;
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
				case "DateTime":
					try {
						$dttDate = QType::Cast($mixValue, QType::DateTime);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					if (!is_null($dttDate))
						$this->intTimestamp = $dttDate->Timestamp;
					else
						$this->intTimestamp = null;

					break;

				case "CalendarType":
					try {
						$this->strCalendarType = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

//				case "MinimumDate": $this->dttMinimumDate = QType::Cast($mixValue, QType::Date); break;
//				case "MaximumDate": $this->dttMaximumDate = QType::Cast($mixValue, QType::Date); break;
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