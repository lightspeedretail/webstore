<?php

	/*require('includes/prepend.inc.php');*/		/* if you DO NOT have "includes/" in your include_path */

	// wrapper class for JsCalendar from http://files7.atomiclearning.com/extras/calendar/
	// 
	// NOTE: all the AddAction calls are forwarded to the hiddenInput. This is mainly for proper handling of the QChangeEvent
	// if this behavior is not what you need, customize the AddAction method below (or extend the class).
	// 
	// properties:
	// "JsCalendarCssName": the name of the style to use with JsCalendar. The JsCalendar distribution includes several css files that can be used
	//                      This property allows to choose one of them. The name should not include the 'calendar-' prefix or '.css' extnesion.
	// "JsCalendarLang": Locale for the jscalendar
	// "DisplayFormat": the date format in JsCalendar sepcs
	// "QcDisplayFormat": same as above but with QDateTime sepcs
	// "ButtonPosition": the position of the button: left or right of the label (see QJsCalendarButtonPosition below)
	// "PopupVerticalAlign": vertical alignment of the calendar popup (see QJsCalendarVerticalAlignType below)
	// "PopupHorizontalAlign": vertical alignment of the calendar popup (see QJsCalendarHorizontalAlignType below)
	// "CalendarType": show or not the time (see QJsCalendarType below)
	// "MinimumYear": the minimum year to include in the year dropdown
	// "MaximumYear": the maximum year to include in the year dropdown
	// "ShowOtherMonths": show or not the greyed dates of months other than the currently selected
	// "FirstDayInWeek": which day is considered the first in a week (0-Sunday, etc)
	// "DateTime": date as QDateTime object
	// also, all the properties of a QTextBox apply
	//
	// see http://files7.atomiclearning.com/extras/calendar/doc/html/reference.html#node_sec_2.3
	// for many other properties that can esily be added here
	class QJsCalendar extends QTextBox {
		public static $JsCalendarCssName = 'win2k-cold-1';
		public static $JsCalendarLang = 'en';
		
		protected $hiddenInput;
		protected $strDisplayFormat = '%Y-%m-%d';
		protected $strQcDisplayFormat = 'YYYY-MM-DD';
		protected $strButtonPosition = QJsCalendarButtonPosition::Right;
		protected $strPopupVerticalAlign = QJsCalendarVerticalAlignType::Below;
		protected $strPopupHorizontalAlign = QJsCalendarHorizontalAlignType::Left;
		protected $strCalendarType = QJsCalendarType::Date;
		protected $intMinimumYear = 1900;
		protected $intMaximumYear = 2999;
		protected $blnShowOtherMonths = true;
		protected $intFirstDayInWeek = 0;
		//protected $strWidth = '200px';
		
		// this maps the JsCalendar format specs to QCodo QDateTime format specs. Not all of them are added (only the ones I needed :-))
		// TODO: add all the specs
		//qcodo	jscalendar	php
		//MMMM	%B			F
		//MMM	%b			M
		//MM	%m			m
		//M					n			
		//DDDD	%A			l
		//DDD	%a			D
		//DD	%d			d
		//D		%e			j
		//YYYY	%Y			Y
		//YY	%y			y
		//hhhh	%H			H
		//hhh	%k			G
		//hh	%I			h
		//h		%l 			g
		//mm	%M			i
		//ss	%S			s
		//zzzz				
		//zzz					
		//zz	%p			A
		//z		%P			a
		//ttt				T
		private $mapDateFormat = array(
		 '%B' => 'MMMM',
		 '%b' => 'MMM',
		 '%m' => 'MM',
		 '%A' => 'DDDD',
		 '%a' => 'DDD',
		 '%d' => 'DD',
		 '%e' => 'D',
		 '%Y' => 'YYYY',
		 '%y' => 'YY',
		 '%H' => 'hhhh',
		 '%k' => 'hhh',
		 '%I' => 'hh',
		 '%l' => 'h',
		 '%M' => 'mm',
		 '%S' => 'ss',
		 '%P' => 'zz',
		 '%p' => 'z',
		 );
		private function qcFrmt($jscFrmt) {
			$qcFrmt = $jscFrmt;
			foreach ($this->mapDateFormat as $key => $val) {
				$qcFrmt = str_replace($key, $val, $qcFrmt);
			}
			return $qcFrmt;
		} 
		
		private function jscFrmt($qcFrmt) {
			$jscFrmt = $qcFrmt;
			foreach ($this->mapDateFormat as $key => $val) {
				$jscFrmt = str_replace($val, $key, $jscFrmt);
			}
			return $jscFrmt;
		} 
		
		public function __construct($objParentObject, $strControlId = null) {
			// First, call the parent to do most of the basic setup
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			
			$this->hiddenInput = new QHiddenInput($this);
			$this->hiddenInput->Visible = true;
			$this->strJavaScripts = 'jscalendar/calendar.js,jscalendar/lang/calendar-'.QJsCalendar::$JsCalendarLang.'.js,jscalendar/calendar-setup.js';
			$this->strStyleSheets = 'jscalendar/calendar-'.QJsCalendar::$JsCalendarCssName.'.css';

			parent::AddAction(new QChangeEvent(), new QAjaxControlAction($this, 'date_Change'));
		}		
		
		// this may not always be what you want (see note above). Customize if necessary.
		public function AddAction($objEvent, $objAction) {
			return $this->hiddenInput->AddAction($objEvent, $objAction);
		}

		public function AddLabelAction($objEvent, $objAction) {
			return parent::AddAction($objEvent, $objAction);
		}

		public function date_Change($strFormId, $strControlId, $strParameter) {
			$this->DateTime = $this->GetDateFromLabel($this->DateTime);
		}
		
		public function GetDateFromLabel($dttPrevDate = null) {
			$strDate = trim($this->Text);
			if ($strDate) {
				$dttDate = new QDateTime($strDate);
				if ($dttDate->IsNull()) {
					return $dttPrevDate; // reset when bad date is entered
				}
				return $dttDate;
			} else {
				return null;
			}
		}
		
		protected function GetControlHtml() {
			$strText = '';
			$strText .= $this->hiddenInput->Render(false);
			if (!$this->Visible) {
				return $strText;
			}
			$strTriggerId = 'ftriggerc'.$this->ControlId;
			$strText .= '<img alt="cal" src="'. __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__.'/jscalendar/img.gif" class="calendar_img" id="'.$strTriggerId.'" style="cursor: pointer; border: 1px solid red;" title="Date selector" onmouseover="this.style.background=\'red\';" onmouseout="this.style.background=\'\'" />';
			if ($this->strButtonPosition === QJsCalendarButtonPosition::Right) {
				$strText = parent::GetControlHtml().$strText;
			} else {
				$strText .= parent::GetControlHtml();
			}
			
			$strJavaScript = '';
			$strJavaScript .= 'Calendar.setup({';
			switch ($this->strCalendarType) {
				case QJsCalendarType::DateTime:
					$strJavaScript .= 'showsTime     :    true,';
					break;
			}
			if ($this->blnShowOtherMonths) {
				$strJavaScript .= 'showOthers     :    true,';
			}
			$strJavaScript .= 'firstDay       :    '.$this->intFirstDayInWeek.',';
			$strJavaScript .= 'range          :    new Array('.$this->intMinimumYear.','.$this->intMaximumYear.'),';
	        $strJavaScript .= 'inputField     :    "'.$this->hiddenInput->ControlId.'",';
	        $strJavaScript .= 'ifFormat       :    "'.$this->jscFrmt(QDateTime::FormatIso).'",';
	        $strJavaScript .= 'timeFormat     :    ' . (strpos ($this->strQcDisplayFormat, "z") === false ? 24 : 12) . ',';
	        $strJavaScript .= 'displayArea    :    "'.$this->ControlId.'",';
	        $strJavaScript .= 'daFormat       :    "'.$this->strDisplayFormat.'",';
	        $strJavaScript .= 'button         :    "'.$strTriggerId.'",';
	        $strJavaScript .= 'align          :    "'.$this->strPopupVerticalAlign.$this->strPopupHorizontalAlign.'",';
	        $strJavaScript .= 'step           :    1,';
	        $strJavaScript .= 'singleClick    :    true';
	    	$strJavaScript .= '});';
	    	QApplication::ExecuteJavaScript($strJavaScript);

			return $strText;		
		}
	
	
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "DateTime":
					if ($this->hiddenInput->Text) {
						$objToReturn = new QDateTime($this->hiddenInput->Text);
						switch ($this->strCalendarType) {
							case QJsCalendarType::DayAndMonth:
								$objToReturn->setDate(2000, $objToReturn->Month, $objToReturn->Day);							
								$objToReturn->setTime(0, 0, 0);
								break;						
						}
						return $objToReturn;
					} else {
						return $this->GetDateFromLabel();
					}
				
				case "CalendarType": return $this->strCalendarType;
				case "ButtonPosition": return $this->strButtonPosition;
				case "PopupVerticalAlign": return $this->strPopupVerticalAlign;
				case "PopupHorizontalAlign": return $this->strPopupHorizontalAlign;
				case "FirstDayInWeek": return $this->intFirstDayInWeek;
				case "MinimumYear": return $this->intMinimumYear;
				case "MaximumYear": return $this->intMaximumYear;
				case "ShowOtherMonths": return $this->blnShowOtherMonths;

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
				case "ButtonPosition":
					try {
						$this->strButtonPosition = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					break;
				case "PopupVerticalAlign":
					try {
						$this->strPopupVerticalAlign = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					break;
				case "PopupHorizontalAlign":
					try {
						$this->strPopupHorizontalAlign = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					break;
				case "DisplayFormat":
					try {
						$this->strDisplayFormat = QType::Cast($mixValue, QType::String);
						$this->strQcDisplayFormat = $this->qcFrmt($this->strDisplayFormat);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					break;
				case "QcDisplayFormat":
					try {
						$this->strQcDisplayFormat = QType::Cast($mixValue, QType::String);
						$this->strDisplayFormat = $this->jscFrmt($this->strQcDisplayFormat);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
					break;
				case "DateTime":
					try {
						$dttDate = QType::Cast($mixValue, QType::DateTime);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					if (!is_null($dttDate)) {
						$this->hiddenInput->Text = $dttDate->__toString(QDateTime::FormatIso);
						$this->Text = $dttDate->__toString($this->strQcDisplayFormat);
					} else {
						$this->hiddenInput->Text = null;
						$this->Text = null;
					}

					break;

				case "CalendarType":
					try {
						$this->strCalendarType = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case "FirstDayInWeek": $this->intFirstDayInWeek = QType::Cast($mixValue, QType::Integer); break;
				case "MinimumYear": $this->intMinimumYear = QType::Cast($mixValue, QType::Integer); break;
				case "MaximumYear": $this->intMaximumYear = QType::Cast($mixValue, QType::Integer); break;
				case "ShowOtherMonths": $this->blnShowOtherMonths = Qtype::Cast($mixValue, QType::Boolean); break;
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

	// I couldn't find any other way to have a user specified hidden input field. So here is a trival extension of QTextBox
	class QHiddenInput extends QTextBox {
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			$strToReturn = sprintf('<input type="hidden" name="%s" id="%s" value="%s" %s%s />',
				$this->strControlId,
				$this->strControlId,
				QApplication::HtmlEntities($this->strText),
				$this->GetAttributes(),
				$strStyle);

			return $strToReturn;
		}
	}

	abstract class QJsCalendarType {
		const DayAndMonth = 'DayAndMonth';
		const Date = 'Date';
		const DateTime = 'DateTime';
	}

	abstract class QJsCalendarVerticalAlignType {
		const Above = 'T';
		const AboveOverlap = 't';
		const Center = 'c';
		const BelowOverlap = 'b';
		const Below = 'B';
	}

	abstract class QJsCalendarHorizontalAlignType {
		const Left = 'L';
		const LeftOverlap = 'l';
		const Center = 'c';
		const RightOverlap = 'r';
		const Right = 'R';
	}

	abstract class QJsCalendarButtonPosition {
		const Left = 'Left';
		const Right = 'Right';
	}
?>