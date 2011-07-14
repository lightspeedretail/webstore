<?php
	// These Aid with the PHP 5.2 DateTime error handling
	class QDateTimeNullException extends QCallerException {}
	function QDateTimeErrorHandler() {}

	/**
	 * QDateTime (Standard)
	 * REQUIRES: PHP >= 5.2.0
	 * 
	 * This DateTime class manages datetimes for the entire system.  It basically
	 * provides a nice wrapper around the PHP DateTime class, which is included with
	 * all versions of PHP >= 5.2.0.
	 * 
	 * For legacy PHP users (PHP < 5.2.0), please refer to QDateTime.legacy
	 */
	class QDateTime extends DateTime {
		const Now = 'now';
		const FormatIso = 'YYYY-MM-DD hhhh:mm:ss';
		const FormatIsoCompressed = 'YYYYMMDDhhhhmmss';
		const FormatDisplayDate = 'MMM DD YYYY';
		const FormatDisplayDateFull = 'DDD, MMMM D, YYYY';
		const FormatDisplayDateTime = 'MMM DD YYYY hh:mm zz';
		const FormatDisplayDateTimeFull = 'DDDD, MMMM D, YYYY, h:mm:ss zz';
		const FormatDisplayTime = 'hh:mm:ss zz';
		const FormatRfc822 = 'DDD, DD MMM YYYY hhhh:mm:ss ttt';

		const FormatSoap = 'YYYY-MM-DDThhhh:mm:ss';

		public static $DefaultFormat = QDateTime::FormatDisplayDate;
		
		/**
		 * Returns a new QDateTime object that's set to "Now"
		 * Set blnTimeValue to true (default) for a DateTime, and set blnTimeValue to false for just a Date
		 *
		 * @param boolean $blnTimeValue whether or not to include the time value
		 * @return QDateTime the current date and/or time
		 */
		public static function Now($blnTimeValue = true) {
			$dttToReturn = new QDateTime(QDateTime::Now);
			if (!$blnTimeValue) {
				$dttToReturn->blnTimeNull = true;
				$dttToReturn->ReinforceNullProperties();
			}
			return $dttToReturn;
		}

		protected $blnDateNull = true;
		protected $blnTimeNull = true;

		public static function NowToString($strFormat = null) {
			$dttNow = new QDateTime(QDateTime::Now);
			return $dttNow->__toString($strFormat);
		}
		public function IsDateNull() {
			return $this->blnDateNull;
		}
		public function IsNull() {
			return ($this->blnDateNull && $this->blnTimeNull);
		}
		public function IsTimeNull() {
			return $this->blnTimeNull;
		}
		public function PhpDate($strFormat) {
			// This just makes a call to format
			return parent::format($strFormat);
		}
		public function GetSoapDateTimeArray($dttArray) {
			if (!$dttArray)
				return null;

			$strArrayToReturn = array();
			foreach ($dttArray as $dttItem)
				array_push($strArrayToReturn, $dttItem->__toString(QDateTime::FormatSoap));
			return $strArrayToReturn;
		}

		/**
		 * @param integer $intTimestamp
		 * @param DateTimeZone $objTimeZone
		 * @return QDateTime
		 */
		public static function FromTimestamp($intTimestamp, DateTimeZone $objTimeZone = null) {
			return new QDateTime(date('Y-m-d H:i:s', $intTimestamp), $objTimeZone);
		}

		public function __construct($mixValue = null, DateTimeZone $objTimeZone = null) {

			// Cloning from another QDateTime object
			if ($mixValue instanceof QDateTime) {
				if ($objTimeZone)
					throw new QCallerException('QDateTime cloning cannot take in a DateTimeZone parameter');
				if ($mixValue->GetTimeZone()->GetName() == date_default_timezone_get())
					parent::__construct($mixValue->format('Y-m-d H:i:s'));
				else
					parent::__construct($mixValue->format(DateTime::ISO8601));
				$this->blnDateNull = $mixValue->IsDateNull();
				$this->blnTimeNull = $mixValue->IsTimeNull();

			// Subclassing from a PHP DateTime object
			} else if ($mixValue instanceof DateTime) {
				if ($objTimeZone)
					throw new QCallerException('QDateTime subclassing of a DateTime object cannot take in a DateTimeZone parameter');
				parent::__construct($mixValue->format(DateTime::ISO8601));

				// By definition, a DateTime object doesn't have anything nulled
				$this->blnDateNull = false;
				$this->blnTimeNull = false;

			// Using "Now" constant
			} else if (strtolower($mixValue) == QDateTime::Now) {
				if ($objTimeZone)
					parent::__construct('now', $objTimeZone);
				else
					parent::__construct('now');
				$this->blnDateNull = false;
				$this->blnTimeNull = false;

			// Null or No Value
			} else if (!$mixValue) {
				// Set to "null date"
				// And Do Nothing Else -- Default Values are already set to Nulled out
				if ($objTimeZone)
					parent::__construct('2000-01-01 00:00:00', $objTimeZone);
				else
					parent::__construct('2000-01-01 00:00:00');

			// Parse the Value string
			} else {
				$intTimestamp = null;
				$blnValid = false;
				QApplication::SetErrorHandler('QDateTimeErrorHandler');
				try {
					if ($objTimeZone)
						$blnValid = parent::__construct($mixValue, $objTimeZone);
					else
						$blnValid = parent::__construct($mixValue);
				} catch (Exception $objExc) {}
				if ($blnValid !== false)
					$intTimestamp = parent::format('U');
				QApplication::RestoreErrorHandler();

				// Valid Value String
				if ($intTimestamp) {
					// To deal with "Tues" and date skipping bug in PHP 5.2
					parent::__construct(date('Y-m-d H:i:s', parent::format('U')));

					// We MUST assume that Date isn't null
					$this->blnDateNull = false;

					// Update Time Null Value if Time was Specified
					if (strpos($mixValue, ':') !== false)
						$this->blnTimeNull = false;

				// Timestamp-based Value string
				} else if (is_numeric($mixValue)) {
					if ($objTimeZone)
						parent::__construct(date('Y-m-d H:i:s', $mixValue), $objTimeZone);
					else
						parent::__construct(date('Y-m-d H:i:s', $mixValue));

					$this->blnTimeNull = false;
					$this->blnDateNull = false;

				// Null Date
				} else {
					// Set to "null date"
					// And Do Nothing Else -- Default Values are already set to Nulled out
					if ($objTimeZone)
						parent::__construct('2000-01-01 00:00:00', $objTimeZone);
					else
						parent::__construct('2000-01-01 00:00:00');
				}
			}
		}

		/* The Following Methods are in place because of a bug in PHP 5.2.0 */
		protected $strSerializedData;
		public function __sleep() {
			$this->strSerializedData = parent::format(DateTime::ISO8601);
			return array('blnDateNull', 'blnTimeNull', 'strSerializedData');
		}
		public function __wakeup() {
			parent::__construct($this->strSerializedData);
		}

		/**
		 * Outputs the date as a string given the format strFormat.  By default,
		 * it will return as QDateTime::FormatDisplayDate "MMM DD YYYY", e.g. Mar 20 1977.
		 *
		 * Properties of strFormat are (using Sunday, March 2, 1977 at 1:15:35 pm
		 * in the following examples):
		 *
		 *	M - Month as an integer (e.g., 3)
		 *	MM - Month as an integer with leading zero (e.g., 03)
		 *	MMM - Month as three-letters (e.g., Mar)
		 *	MMMM - Month as full name (e.g., March)
		 *
		 *	D - Day as an integer (e.g., 2)
		 *	DD - Day as an integer with leading zero (e.g., 02)
		 *	DDD - Day of week as three-letters (e.g., Wed)
		 *	DDDD - Day of week as full name (e.g., Wednesday)
		 *
		 *	YY - Year as a two-digit integer (e.g., 77)
		 *	YYYY - Year as a four-digit integer (e.g., 1977)
		 *
		 *	h - Hour as an integer in 12-hour format (e.g., 1)
		 *	hh - Hour as an integer in 12-hour format with leading zero (e.g., 01)
		 *	hhh - Hour as an integer in 24-hour format (e.g., 13)
		 *	hhhh - Hour as an integer in 24-hour format with leading zero (e.g., 13)
		 *
		 *	mm - Minute as a two-digit integer
		 *
		 *	ss - Second as a two-digit integer
		 *
		 *	z - "pm" or "am"
		 *	zz - "PM" or "AM"
		 *	zzz - "p.m." or "a.m."
		 *	zzzz - "P.M." or "A.M."
		 *
		 *  ttt - Timezone Abbreviation as a three-letter code (e.g. PDT, GMT)
		 *  tttt - Timezone Identifier (e.g. America/Los_Angeles)
		 *
		 * @param string $strFormat the format of the date
		 * @return string the formatted date as a string
		 */
		public function __toString() {
			// For PHP 5.3 Compatability
			$strArgumentArray = func_get_args();

			if (count($strArgumentArray) >= 1)
				$strFormat = $strArgumentArray[0];
			else
				$strFormat = null;

			$this->ReinforceNullProperties();
			if (is_null($strFormat))
				$strFormat = QDateTime::$DefaultFormat;

			preg_match_all('/(?(?=D)([D]+)|(?(?=M)([M]+)|(?(?=Y)([Y]+)|(?(?=h)([h]+)|(?(?=m)([m]+)|(?(?=s)([s]+)|(?(?=z)([z]+)|(?(?=t)([t]+)|))))))))/', $strFormat, $strArray);
			$strArray = $strArray[0];
			$strToReturn = '';

			$intStartPosition = 0;
			for ($intIndex = 0; $intIndex < count($strArray); $intIndex++) {
				$strToken = trim($strArray[$intIndex]);
				if ($strToken) {
					$intEndPosition = strpos($strFormat, $strArray[$intIndex], $intStartPosition);
					$strToReturn .= substr($strFormat, $intStartPosition, $intEndPosition - $intStartPosition);
					$intStartPosition = $intEndPosition + strlen($strArray[$intIndex]);

					switch ($strArray[$intIndex]) {
						case 'M':
							$strToReturn .= parent::format('n');
							break;
						case 'MM':
							$strToReturn .= parent::format('m');
							break;
						case 'MMM':
							$strToReturn .= parent::format('M');
							break;
						case 'MMMM':
							$strToReturn .= parent::format('F');
							break;
			
						case 'D':
							$strToReturn .= parent::format('j');
							break;
						case 'DD':
							$strToReturn .= parent::format('d');
							break;
						case 'DDD':
							$strToReturn .= parent::format('D');
							break;
						case 'DDDD':
							$strToReturn .= parent::format('l');
							break;
			
						case 'YY':
							$strToReturn .= parent::format('y');
							break;
						case 'YYYY':
							$strToReturn .= parent::format('Y');
							break;
			
						case 'h':
							$strToReturn .= parent::format('g');
							break;
						case 'hh':
							$strToReturn .= parent::format('h');
							break;
						case 'hhh':
							$strToReturn .= parent::format('G');
							break;
						case 'hhhh':
							$strToReturn .= parent::format('H');
							break;

						case 'mm':
							$strToReturn .= parent::format('i');
							break;
			
						case 'ss':
							$strToReturn .= parent::format('s');
							break;
			
						case 'z':
							$strToReturn .= parent::format('a');
							break;
						case 'zz':
							$strToReturn .= parent::format('A');
							break;
						case 'zzz':
							$strToReturn .= sprintf('%s.m.', substr(parent::format('a'), 0, 1));
							break;
						case 'zzzz':
							$strToReturn .= sprintf('%s.M.', substr(parent::format('A'), 0, 1));
							break;

						case 'ttt':
							$strToReturn .= parent::format('T');
							break;
						case 'tttt':
							$strToReturn .= parent::format('e');
							break;

						default:
							$strToReturn .= $strArray[$intIndex];
					}
				}
			}

			if ($intStartPosition < strlen($strFormat))
				$strToReturn .= substr($strFormat, $intStartPosition);

			return $strToReturn;
		}

		public function format($strFormat) {
			$this->ReinforceNullProperties();
			return parent::format($strFormat);
		}

		public function setTime($intHour, $intMinute, $intSecond = null) {
			// For compatibility with PHP 5.3
			if (is_null($intSecond)) $intSecond = 0;

			// If HOUR or MINUTE is NULL...
			if (is_null($intHour) || is_null($intMinute)) {
				parent::setTime($intHour, $intMinute, $intSecond);
				$this->blnTimeNull = true;
				return $this;
			}

			$intHour = QType::Cast($intHour, QType::Integer);
			$intMinute = QType::Cast($intMinute, QType::Integer);
			$intSecond = QType::Cast($intSecond, QType::Integer);
			$this->blnTimeNull = false;
			parent::setTime($intHour, $intMinute, $intSecond);
			return $this;
		}

		public function setDate($intYear, $intMonth, $intDay) {
			$intYear = QType::Cast($intYear, QType::Integer);
			$intMonth = QType::Cast($intMonth, QType::Integer);
			$intDay = QType::Cast($intDay, QType::Integer);
			$this->blnDateNull = false;
			parent::setDate($intYear, $intMonth, $intDay);
			return $this;
		}

		protected function ReinforceNullProperties() {
			if ($this->blnDateNull)
				parent::setDate(2000, 1, 1);
			if ($this->blnTimeNull)
				parent::setTime(0, 0, 0);
		}
		
		/**
		 * Converts the current QDateTime object to a different TimeZone.
		 * 
		 * TimeZone should be passed in as a string-based identifier.
		 * 
		 * Note that this is different than the built-in DateTime::SetTimezone() method which expicitly
		 * takes in a DateTimeZone object.  QDateTime::ConvertToTimezone allows you to specify any
		 * string-based Timezone identifier.  If none is specified and/or if the specified timezone
		 * is not a valid identifier, it will simply remain unchanged as opposed to throwing an exeception
		 * or error.
		 * 
		 * @param string $strTimezoneIdentifier a string-based parameter specifying a timezone identifier (e.g. America/Los_Angeles)
		 * @return void
		 */
		public function ConvertToTimezone($strTimezoneIdentifier) {
			try {
				$dtzNewTimezone = new DateTimeZone($strTimezoneIdentifier);
				$this->SetTimezone($dtzNewTimezone);
			} catch (Exception $objExc) {}
		}

		public function IsEqualTo(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp == $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp == $dttCompare->Timestamp);
			}
		}

		public function IsEarlierThan(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp < $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp < $dttCompare->Timestamp);
			}
		}

		public function IsEarlierOrEqualTo(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp <= $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp <= $dttCompare->Timestamp);
			}
		}

		public function IsLaterThan(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp > $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp > $dttCompare->Timestamp);
			}
		}

		public function IsLaterOrEqualTo(QDateTime $dttCompare) {
			// All comparison operations MUST have operands with matching Date Nullstates
			if ($this->blnDateNull != $dttCompare->blnDateNull)
				return false;

			// If mismatched Time nullstates, then only compare the Date portions
			if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
				// Let's "Null Out" the Time
				$dttThis = new QDateTime($this);
				$dttThat = new QDateTime($dttCompare);
				$dttThis->Hour = null;
				$dttThat->Hour = null;

				// Return the Result
				return ($dttThis->Timestamp >= $dttThat->Timestamp);
			} else {
				// Return the Result for the both Date and Time components
				return ($this->Timestamp >= $dttCompare->Timestamp);
			}
		}

		public function Difference(QDateTime $dttDateTime) {
			$intDifference = $this->Timestamp - $dttDateTime->Timestamp;
			return new QDateTimeSpan($intDifference);
		}

		public function Add($dtsSpan){
			if ($dtsSpan instanceof QDateTimeSpan) {
				// Get this DateTime timestamp
				$intTimestamp = $this->Timestamp;

				// And add the Span Second count to it
				$this->Timestamp = $this->Timestamp + $dtsSpan->Seconds;
				return $this;
			} else if ($dtsSpan instanceof DateInterval) {
				return parent::add($dtsSpan);
			}
		}

		public function AddSeconds($intSeconds){
			$this->Second += $intSeconds;
			return $this;
		}

		public function AddMinutes($intMinutes){
			$this->Minute += $intMinutes;
			return $this;
		}

		public function AddHours($intHours){
			$this->Hour += $intHours;
			return $this;
		}

		public function AddDays($intDays){
			$this->Day += $intDays;
			return $this;
		}

		public function AddMonths($intMonths){
			$this->Month += $intMonths;
			return $this;
		}

		public function AddYears($intYears){
			$this->Year += $intYears;
			return $this;
		}
		
		public function Modify($mixValue) {
			parent::modify($mixValue);
			return $this;
		}

		public function __get($strName) {
			$this->ReinforceNullProperties();

			switch ($strName) {
				case 'Month':
					if ($this->blnDateNull)
						return null;
					else
						return (int) parent::format('m');

				case 'Day':
					if ($this->blnDateNull)
						return null;
					else
						return (int) parent::format('d');

				case 'Year':
					if ($this->blnDateNull)
						return null;
					else
						return (int) parent::format('Y');

				case 'Hour':
					if ($this->blnTimeNull)
						return null;
					else
						return (int) parent::format('H');

				case 'Minute':
					if ($this->blnTimeNull)
						return null;
					else
						return (int) parent::format('i');

				case 'Second':
					if ($this->blnTimeNull)
						return null;
					else
						return (int) parent::format('s');

				case 'Timestamp':
					// Until PHP fixes a bug where lowest int is int(-2147483648) but lowest float/double is (-2147529600)
					// We return as a "double"
					return (double) parent::format('U');

				case 'Age':
					// Figure out the Difference from "Now"
					$dtsFromCurrent = $this->Difference(QDateTime::Now());
					
					// It's in the future ('about 2 hours from now')
					if ($dtsFromCurrent->IsPositive())
						return $dtsFromCurrent->SimpleDisplay() . ' from now';

					// It's in the past ('about 5 hours ago')
					else if ($dtsFromCurrent->IsNegative()) {
						$dtsFromCurrent->Seconds = abs($dtsFromCurrent->Seconds);
						return $dtsFromCurrent->SimpleDisplay() . ' ago';

					// It's current
					} else
						return 'right now';

				default:
					throw new QUndefinedPropertyException('GET', 'QDateTime', $strName);
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Month':
						if ($this->blnDateNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Month property on a null date.  Use SetDate().');
						if (is_null($mixValue)) {
							$this->blnDateNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setDate(parent::format('Y'), $mixValue, parent::format('d'));
						return $mixValue;

					case 'Day':
						if ($this->blnDateNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Day property on a null date.  Use SetDate().');
						if (is_null($mixValue)) {
							$this->blnDateNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setDate(parent::format('Y'), parent::format('m'), $mixValue);
						return $mixValue;

					case 'Year':
						if ($this->blnDateNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Year property on a null date.  Use SetDate().');
						if (is_null($mixValue)) {
							$this->blnDateNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setDate($mixValue, parent::format('m'), parent::format('d'));
						return $mixValue;

					case 'Hour':
						if ($this->blnTimeNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Hour property on a null time.  Use SetTime().');
						if (is_null($mixValue)) {
							$this->blnTimeNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setTime($mixValue, parent::format('i'), parent::format('s'));
						return $mixValue;

					case 'Minute':
						if ($this->blnTimeNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Minute property on a null time.  Use SetTime().');
						if (is_null($mixValue)) {
							$this->blnTimeNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setTime(parent::format('H'), $mixValue, parent::format('s'));
						return $mixValue;

					case 'Second':
						if ($this->blnTimeNull && (!is_null($mixValue)))
							throw new QDateTimeNullException('Cannot set the Second property on a null time.  Use SetTime().');
						if (is_null($mixValue)) {
							$this->blnTimeNull = true;
							$this->ReinforceNullProperties();
							return null;
						}
						$mixValue = QType::Cast($mixValue, QType::Integer);
						parent::setTime(parent::format('H'), parent::format('i'), $mixValue);
						return $mixValue;

					case 'Timestamp':
						$mixValue = QType::Cast($mixValue, QType::Integer);
						$this->blnDateNull = false;
						$this->blnTimeNull = false;

						$this->SetDate(date('Y', $mixValue), date('m', $mixValue), date('d', $mixValue));
						$this->SetTime(date('H', $mixValue), date('i', $mixValue), date('s', $mixValue));
						return $mixValue;

					default:
						throw new QUndefinedPropertyException('SET', 'QDateTime', $strName);
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}

/*
	This is a reference to the documentation for hte PHP DateTime classes (as of PHP 5.2)

      DateTime::ATOM
      DateTime::COOKIE
      DateTime::ISO8601
      DateTime::RFC822
      DateTime::RFC850
      DateTime::RFC1036
      DateTime::RFC1123
      DateTime::RFC2822
      DateTime::RFC3339
      DateTime::RSS
      DateTime::W3C

      DateTime::__construct([string time[, DateTimeZone object]])
      - Returns new DateTime object
      
      string DateTime::format(string format)
      - Returns date formatted according to given format
      
      long DateTime::getOffset()
      - Returns the DST offset
      
      DateTimeZone DateTime::getTimezone()
      - Return new DateTimeZone object relative to give DateTime
      
      void DateTime::modify(string modify)
      - Alters the timestamp
      
      array DateTime::parse(string date)
      - Returns associative array with detailed info about given date
      
      void DateTime::setDate(long year, long month, long day)
      - Sets the date
      
      void DateTime::setISODate(long year, long week[, long day])
      - Sets the ISO date
      
      void DateTime::setTime(long hour, long minute[, long second])
      - Sets the time
      
      void DateTime::setTimezone(DateTimeZone object)
      - Sets the timezone for the DateTime object
*/

/* Some quick and dirty test harnesses
	$dtt1 = new QDateTime();
	$dtt2 = new QDateTime();
	printTable($dtt1, $dtt2);
	$dtt2->setDate(2000, 1, 1);
	$dtt1->setTime(0,0,3);
	$dtt2->setTime(0,0,2);
//	$dtt2->Month++;
	printTable($dtt1, $dtt2);

	function printTable($dtt1, $dtt2) {
		print('<table border="1" cellpadding="2"><tr><td>');
		printDate($dtt1);
		print('</td><td>');
		printDate($dtt2);
		print ('</td></tr>');
		
		print ('<tr><td colspan="2" align="center">IsEqualTo: <b>' . (($dtt1->IsEqualTo($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsEarlierThan: <b>' . (($dtt1->IsEarlierThan($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsLaterThan: <b>' . (($dtt1->IsLaterThan($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsEarlierOrEqualTo: <b>' . (($dtt1->IsEarlierOrEqualTo($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print ('<tr><td colspan="2" align="center">IsLaterOrEqualTo: <b>' . (($dtt1->IsLaterOrEqualTo($dtt2)) ? 'Yes' : 'No') . '</b></td></tr>');
		print('</table>');
	}
	
	function printDate($dtt) {
		print ('Time Null: ' . (($dtt->IsTimeNull()) ? 'Yes' : 'No'));
		print ('<br/>');
		print ('Date Null: ' . (($dtt->IsDateNull()) ? 'Yes' : 'No'));
		print ('<br/>');
		print ('Date: ' . $dtt->__toString(QDateTime::FormatDisplayDateTimeFull));
		print ('<br/>');
		print ('Month: ' . $dtt->Month . '<br/>');
		print ('Day: ' . $dtt->Day . '<br/>');
		print ('Year: ' . $dtt->Year . '<br/>');
		print ('Hour: ' . $dtt->Hour . '<br/>');
		print ('Minute: ' . $dtt->Minute . '<br/>');
		print ('Second: ' . $dtt->Second . '<br/>');
	}*/
?>