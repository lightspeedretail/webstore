<?php
	class QCalendar extends QControl {
		protected $dtxLinkedControl;
		protected $strCalendarImageSource;

		protected $strJavaScripts = '_core/calendar.js';
		protected $strCssClass = 'calendar';

		public function ParsePostData() {}
		public function Validate() {return true;}
		public function GetControlHtml() {
			// Pull any Attributes
			$strAttributes = $this->GetAttributes();

			// Pull any styles
			if ($strStyle = $this->GetStyleAttributes())
				$strStyle = 'style="' . $strStyle . '"';

			$strImageStyle = '';
			if (file_exists(__DOCROOT__ . $this->strCalendarImageSource)) {
				$strSizeInfo = getimagesize(__DOCROOT__ . $this->strCalendarImageSource);
				$strImageStyle = 'style="width: ' . $strSizeInfo[0] . 'px; height: ' . $strSizeInfo[1] . 'px;"';
			}

			$strToReturn = sprintf('<img id="%s" src="%s" %s/><div id="%s_cal" %s%s></div>',
				$this->strControlId,
				$this->strCalendarImageSource,
				$strImageStyle,
				$this->strControlId,
				$strAttributes,
				$strStyle);

			return $strToReturn;
		}
		public function AddAction($objEvent, $objAction) {
			throw new QCallerException('QCalendar does not support custom events');
		}
		public function __construct($objParentObject, QDateTimeTextBox $dtxLinkedControl, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }

			// Setup Linked DateTimeTextBox control
			$this->dtxLinkedControl = $dtxLinkedControl;

			// Other Setup
			$this->strCalendarImageSource = __IMAGE_ASSETS__ . '/calendar.png';
			
			$this->dtxLinkedControl->RemoveAllActions(QClickEvent::EventName);
			$this->dtxLinkedControl->AddAction(new QClickEvent(), new QJavaScriptAction("qc.getC('" . $this->strControlId . "').showCalendar(); "));
			$this->dtxLinkedControl->AddAction(new QClickEvent(), new QBlurControlAction($this->dtxLinkedControl));
			$this->dtxLinkedControl->AddAction(new QClickEvent(), new QTerminateAction());
		}
		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			$strToReturn .= 'qc.regCAL("' . $this->strControlId . '","' . $this->dtxLinkedControl->ControlId . '"); ';
			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case 'CalendarImageSource': return $this->strCalendarImageSource;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {

				case 'CalendarImageSource': 
					try {
						return ($this->strCalendarImageSource = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}
	}
?>