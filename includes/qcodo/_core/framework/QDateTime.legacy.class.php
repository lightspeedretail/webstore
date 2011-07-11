<?php
	/**
	 * QDateTime.legacy
	 * 
	 * This DateTime class manages datetimes for the entire system.  It basically
	 * provides a nice wrapper around the built-in date-related functionality in PHP.
	 *
	 * This is used by Qcodo users using PHP < 5.2.0
	 * As of Qcodo 0.3.14, no additional functionality will be added to QDateTime.legacy
	 * 
	 * All new QDateTime functionality will be added to the QDateTime (standard) which uses PHP DateTime,
	 * which is included in all versions of >= PHP 5.2.0
	 */
	class QDateTime extends QBaseClass {
		/**
		 * The Month value.  Should be accessed through the Month property.
		 * @var int $intMonth
		 */
		protected $intMonth;
		/**
		 * The Day value.  Should be accessed through the Day property.
		 * @var int $intDay
		 */
		protected $intDay;
		/**
		 * The Year value.  Should be accessed through the Year property.
		 * @var int $intYear
		 */
		protected $intYear;
		/**
		 * The Hour value.  Should be accessed through the Hour property.
		 * @var int $intHour
		 */
		protected $intHour;
		/**
		 * The Minute value.  Should be accessed through the Minute property.
		 * @var int $intMinute
		 */
		protected $intMinute;
		/**
		 * The Second value.  Should be accessed through the Second property.
		 * @var int $intSecond
		 */
		protected $intSecond;

		/**
		 * The "Default" Display Format
		 * @var string $DefaultFormat
		 */
		public static $DefaultFormat = QDateTime::FormatDisplayDate;

		/**
		 * Pass this to __construct in order to set DateTime to current date/time.
		 * e.g. $dttDate = new QDateTime(QDateTime::Now);
		 */
		const Now = 'Now';

		/**
		 * Constant for __toString to display Date/Time as ISO Standard
		 * 1977-03-20 15:35:15
		 */
		const FormatIso = 'YYYY-MM-DD hhhh:mm:ss';
		/**
		 * Constant for __toString to display Date/Time as 14-digit
		 * 19770320153515
		 */
		const FormatIsoCompressed = 'YYYYMMDDhhhhmmss';
		/**
		 * Constant for __toString to display Date as simple
		 * Mar 20 1977
		 */
		const FormatDisplayDate = 'MMM DD YYYY';
		/**
		 * Constant for __toString to display Date as extended
		 * Sunday, March 20, 1977
		 */
		const FormatDisplayDateFull = 'DDD, MMMM D, YYYY';
		/**
		 * Constant for __toString to display Date/Time as simple
		 * Mar 20 1977 03:35 PM
		 */
		const FormatDisplayDateTime = 'MMM DD YYYY hh:mm zz';
		/**
		 * Constant for __toString to display Date/Time as extended
		 * Sunday, March 20, 1977, 3:35:15 PM
		 */
		const FormatDisplayDateTimeFull = 'DDDD, MMMM D, YYYY, h:mm:ss zz';
		/**
		 * Constant for __toString to display Time as
		 * 03:35:15 PM
		 */
		const FormatDisplayTime = 'hh:mm:ss zz';
		/**
		 * Constant for __toString to display DateTime as RFC 822 Format
		 * Sun, 20 Mar 1977 15:35:15 GMT
		 */
		const FormatRfc822 = 'DDD, DD MMM YYYY hhhh:mm:ss ttt';
		/**
		 * Constant for __toString to display Date/Time as SOAP Standard
		 * 1977-03-20T15:35:15
		 */
		const FormatSoap = 'YYYY-MM-DDThhhh:mm:ss';

		/**
		 * Constructor function to create a new datetime.  Takes in an optional $mixValue, which can be one of the following:
		 *  ISO Standard format (mostly used in databases) 1977-03-20 15:35:15
		 *  ISO-compressed format (the pure digits of the ISO format) 19770320153515
		 *  another DateTime object (to clone it)
		 *  the QDateTime::Now constant (to return current date/time)
		 *  a unix timestamp
		 *  null/none (to return a blank date, which by default is set to 0000-01-01 00:00:00)
		 * DateTime can be constructed with either the date, the time, or both portions of any of the above formats.
		 * For any part that's missing, the date would be set to 0000-01-01, or the time would be set to 00:00:00
		 * @param $mixValue the date/time to set (see documentation for more information)
		 * @return DateTime the new DateTime object
		 */
		public function __construct($mixValue = null) {
			if ($mixValue instanceof QDateTime) {
				$this->intMonth = $mixValue->Month;
				$this->intDay = $mixValue->Day;
				$this->intYear = $mixValue->Year;
				$this->intHour = $mixValue->Hour;
				$this->intMinute = $mixValue->Minute;
				$this->intSecond = $mixValue->Second;
			} else if (preg_match('/^(\d{4})-?(\d{2})-?(\d{2})([T\s]?(\d{2}):?(\d{2}):?(\d{2})(\.\d+)?(Z|[\+\-]\d{2}:?\d{2})?)?$/i', $mixValue, $regs)) {
				// The above line of RegEx code is borrowed from the Date PEAR library.
	            // This regex is very loose and accepts almost any butchered format you could
	            // throw at it.  e.g. 2003-10-07 19:45:15 and 2003-10071945:15
	            // are the same thing in the eyes of this regex, even though the
	            // latter is not a valid ISO 8601 date.
	            // TO DO: Replace
	            
	            $this->intYear		= $regs[1];
	            $this->intMonth		= $regs[2];
	            $this->intDay		= $regs[3];
	            $this->intHour		= isset($regs[5])?$regs[5]:null;
	            $this->intMinute	= isset($regs[6])?$regs[6]:0;
	            $this->intSecond	= isset($regs[7])?$regs[7]:0;

	            if ($this->intMonth == 0)
	            	$this->intMonth = 1;

	            if ($this->intDay == 0)
	            	$this->intDay = 1;
			} else if (is_numeric($mixValue)) {
				$this->intYear		= date('Y', $mixValue);
				$this->intMonth		= date('m', $mixValue);
				$this->intDay		= date('d', $mixValue);
				$this->intHour		= date('H', $mixValue);
				$this->intMinute	= date('i', $mixValue);
				$this->intSecond	= date('s', $mixValue);
			} else if ($mixValue == QDateTime::Now) {
				$intTimestamp = time();
				$this->intYear		= date('Y', $intTimestamp);
				$this->intMonth		= date('m', $intTimestamp);
				$this->intDay		= date('d', $intTimestamp);
				$this->intHour		= date('H', $intTimestamp);
				$this->intMinute	= date('i', $intTimestamp);
				$this->intSecond	= date('s', $intTimestamp);
			} else if (preg_match('/^([T\s]?(\d{2}):?(\d{2}):?(\d{2})(\.\d+)?(Z|[\+\-]\d{2}:?\d{2})?)/i', $mixValue, $regs)) {
				// Time Only
				$this->intYear = null;
				$this->intMonth = 1;
				$this->intDay = 1;
				$this->intHour = $regs[2];
				$this->intMinute = $regs[3];
				$this->intSecond = $regs[4];
			} else {
				// Blank Date
				$this->intMonth = 1;
				$this->intDay = 1;
			}
		}

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
				$dttToReturn->intHour = null;
				$dttToReturn->intMinute = null;
				$dttToReturn->intSecond = null;
			}
			return $dttToReturn;
		}

		/**
		 * @param integer $intTimestamp
		 * @return QDateTime
		 */
		public static function FromTimestamp($intTimestamp) {
			return new QDateTime(date('Y-m-d H:i:s', $intTimestamp));
		}

		public function IsLaterThan(QDateTime $dttDate){
			if($this->Timestamp > $dttDate->Timestamp) 
				return true;
			else 
				return false;
		}
		
		public function IsEarlierThan(QDateTime $dttDate) {
			if($this->Timestamp < $dttDate->Timestamp) 
				return true;
			else 
				return false;
		}
		public function IsEqualTo(QDateTime $dttDate) {
			if($this->Timestamp == $dttDate->Timestamp) 
				return true;
			else 
				return false;
		}

		public function Difference(QDateTime $dttDateTime){
			
			$intDifference = $this->Timestamp - $dttDateTime->Timestamp;
			$dtsDateSpan = new QDateTimeSpan();
			$dtsDateSpan->AddSeconds($intDifference);
			return $dtsDateSpan;
		}
		
		public function Add(QDateTimeSpan $dtsSpan){
			// Get this DateTime timestamp
			$intTimestamp = $this->Timestamp;
			$intTimestamp = $intTimestamp + $dtsSpan->Seconds;
			$this->intYear		= date('Y', $intTimestamp);
			$this->intMonth		= date('m', $intTimestamp);
			$this->intDay		= date('d', $intTimestamp);
			$this->intHour		= date('H', $intTimestamp);
			$this->intMinute	= date('i', $intTimestamp);
			$this->intSecond	= date('s', $intTimestamp);			
		}
		
		public function Subtract(QDateTimeSpan $dtsSpan){
			// Get this DateTime timestamp
			$intTimestamp = $this->Timestamp;
			$intTimestamp = $intTimestamp - $dtsSpan->Seconds;
			$this->intYear		= date('Y', $intTimestamp);
			$this->intMonth		= date('m', $intTimestamp);
			$this->intDay		= date('d', $intTimestamp);
			$this->intHour		= date('H', $intTimestamp);
			$this->intMinute	= date('i', $intTimestamp);
			$this->intSecond	= date('s', $intTimestamp);			
		}

		/**
		 * Shortcut to output the current datetime in any defined format.  Follows the same
		 * formatting mechanism as __toString.  This is basically a shortcut to doing:
		 *    $dttNow = new QDateTime(QDateTime::Now);
		 *    $dttNow->__toString($strFormat);
		 *
		 * @param string $strFormat the format of the datetime
		 * @return string the formatted current datetime as a string
		 */
		public static function NowToString($strFormat = null) {
			if (is_null($strFormat))
				$strFormat = QDateTime::$DefaultFormat;

			$dttNow = new QDateTime(QDateTime::Now);
			return $dttNow->__toString($strFormat);
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
		 *  ttt - Timezone as a three-letter code (e.g. GMT)
		 *
		 * @param string $strFormat the format of the date
		 * @return string the formatted date as a string
		 */
		public function __toString($strFormat = null) {
			if (is_null($strFormat))
				$strFormat = QDateTime::$DefaultFormat;

			$strArray = preg_split('/([^D^M^Y^h^m^s^z^t])+/', $strFormat);
			$strToReturn = '';

			$intTimestamp = $this->Timestamp;
			
			$intStartPosition = 0;
			for ($intIndex = 0; $intIndex < count($strArray); $intIndex++) {
				$strToken = trim($strArray[$intIndex]);
				if ($strToken) {
					$intEndPosition = strpos($strFormat, $strArray[$intIndex], $intStartPosition);
					$strToReturn .= substr($strFormat, $intStartPosition, $intEndPosition - $intStartPosition);
					$intStartPosition = $intEndPosition + strlen($strArray[$intIndex]);

					switch ($strArray[$intIndex]) {
						case 'M':
							$strToReturn .= date('n', $intTimestamp);
							break;
						case 'MM':
							$strToReturn .= date('m', $intTimestamp);
							break;
						case 'MMM':
							$strToReturn .= date('M', $intTimestamp);
							break;
						case 'MMMM':
							$strToReturn .= date('F', $intTimestamp);
							break;
			
						case 'D':
							$strToReturn .= date('j', $intTimestamp);
							break;
						case 'DD':
							$strToReturn .= date('d', $intTimestamp);
							break;
						case 'DDD':
							$strToReturn .= date('D', $intTimestamp);
							break;
						case 'DDDD':
							$strToReturn .= date('l', $intTimestamp);
							break;
			
						case 'YY':
							$strToReturn .= date('y', $intTimestamp);
							break;
						case 'YYYY':
							$strToReturn .= date('Y', $intTimestamp);
							break;
			
						case 'h':
							$strToReturn .= date('g', $intTimestamp);
							break;
						case 'hh':
							$strToReturn .= date('h', $intTimestamp);
							break;
						case 'hhh':
							$strToReturn .= date('G', $intTimestamp);
							break;
						case 'hhhh':
							$strToReturn .= date('H', $intTimestamp);
							break;

						case 'mm':
							$strToReturn .= date('i', $intTimestamp);
							break;
			
						case 'ss':
							$strToReturn .= date('s', $intTimestamp);
							break;
			
						case 'z':
							$strToReturn .= date('a', $intTimestamp);
							break;
						case 'zz':
							$strToReturn .= date('A', $intTimestamp);
							break;
						case 'zzz':
							$strToReturn .= sprintf('%s.m.', substr(date('a', $intTimestamp), 0, 1));
							break;
						case 'zzzz':
							$strToReturn .= sprintf('%s.M.', substr(date('A', $intTimestamp), 0, 1));
							break;

						case 'ttt':
							$strToReturn .= date('T', $intTimestamp);
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

		/**
		 * This makes a call to the PHP date() function, where you
		 * can pass in a PHP date()-compatible format to display
		 * this date/time object.
		 *
		 * @param string $strFormat a PHP date()-compatible string format for datetime
		 * @return string the output from the PHP date() call
		 */
		public function PHPDate($strFormat) {
			return date($strFormat, $this->Timestamp);
		}

		/**
		 * This returns a boolean specifying whether or not this object DateTime is null
		 *
		 * @return boolean whether or not the datetime object is null
		 */
		public function IsNull() {
			return (is_null($this->intYear) && is_null($this->intHour));
		}

		/**
		 * This returns a boolean specifying whether or not the Date portion is null
		 *
		 * @return boolean whether or not the date is null
		 */
		public function IsDateNull() {
			return (is_null($this->intYear));
		}

		/**
		 * This returns a boolean specifying whether or not the Date portion is null
		 *
		 * @return boolean whether or not the time is null
		 */
		public function IsTimeNull() {
			return (is_null($this->intHour));
		}
		
		/**
		 * This returns an array of strings with ISO-formatted datetime values, given an array of QDateTime objects
		 *
		 * @return string[] array of ISO-formatted datetime strings
		 */
		public function GetSoapDateTimeArray($dttArray) {
			if (!$dttArray)
				return null;

			$strArrayToReturn = array();
			foreach ($dttArray as $dttItem)
				array_push($strArrayToReturn, $dttItem->__toString(QDateTime::FormatSoap));
			return $strArrayToReturn;
		}

		/**
		 * @param integer $intYear
		 * @param integer $intMonth
		 * @param integer $intDay
		 */
		public function setDate($intYear, $intMonth, $intDay) {
			try {
				$this->intYear = QType::Cast($intYear, QType::Integer);
				$this->intMonth = QType::Cast($intMonth, QType::Integer);
				$this->intDay = QType::Cast($intDay, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * @param integer $intHour
		 * @param integer $intMinute
		 * @param integer $intSecond
		 */
		public function setTime($intHour, $intMinute, $intSecond) {
			try {
				$this->intHour = QType::Cast($intHour, QType::Integer);
				$this->intMinute = QType::Cast($intMinute, QType::Integer);
				$this->intSecond = QType::Cast($intSecond, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed the returned property
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Month': return $this->intMonth;
				case 'Day': return $this->intDay;
				case 'Year': return $this->intYear;
				case 'Hour': return $this->intHour;
				case 'Minute': return $this->intMinute;
				case 'Second': return $this->intSecond;
				case 'Timestamp': return mktime($this->intHour, $this->intMinute, $this->intSecond, $this->intMonth, $this->intDay, $this->intYear);

				case 'Age':
					// Figure out the Difference from "Now"
					$dtsFromCurrent = $this->Difference(new QDateTime(QDateTime::Now));

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
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed the property that was set
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Month':
						return ($this->intMonth = QType::Cast($mixValue, QType::Integer));

					case 'Day':
						return ($this->intDay = QType::Cast($mixValue, QType::Integer));

					case 'Year':
						return ($this->intYear= QType::Cast($mixValue, QType::Integer));

					case 'Hour':
						return ($this->intHour= QType::Cast($mixValue, QType::Integer));

					case 'Minute':
						return ($this->intMinute= QType::Cast($mixValue, QType::Integer));

					case 'Second':
						return ($this->intSecond = QType::Cast($mixValue, QType::Integer));

					default:
						return (parent::__set($strName, $mixValue));
				}				
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>