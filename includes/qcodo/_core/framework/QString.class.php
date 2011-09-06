<?php
	/**
	 * An abstract utility class to handle string manipulation.  All methods
	 * are statically available.
	 */
	abstract class QString {
		/**
		 * This faux constructor method throws a caller exception.
		 * The String object should never be instantiated, and this constructor
		 * override simply guarantees it.
		 *
		 * @return void
		 */
		public final function __construct() {
			throw new CallerException('QString class should never be instantiated.  All methods and variables are publically statically accessible.');
		}

		/**
		 * Returns the first character of a given string, or null if the given
		 * string is null.
		 * @param string $strString 
		 * @return string the first character, or null
		 */
		public final static function FirstCharacter($strString) {
			if (strlen($strString) > 0)
				return substr($strString, 0 , 1);
			else
				return null;
		}

		/**
		 * Returns the last character of a given string, or null if the given
		 * string is null.
		 * @param string $strString 
		 * @return string the last character, or null
		 */
		public final static function LastCharacter($strString) {
			$intLength = strlen($strString);
			if ($intLength > 0)
				return substr($strString, $intLength - 1);
			else
				return null;
		}

		/**
		 * Returns whether or not the given string starts with another string
		 * @param $strString
		 * @param $strStartsWith
		 * @return boolean
		 */
		public final static function IsStartsWith($strString, $strStartsWith) {
			if (substr($strString, 0, strlen($strStartsWith)) == $strStartsWith)
				return true;
			else
				return false;
		}

		/**
		 * Truncates the string to a given length, adding elipses (if needed).
		 * @param string $strString string to truncate
		 * @param integer $intMaxLength the maximum possible length of the string to return (including length of the elipse)
		 * @param bololean $blnHtmlEntities whether or not to escape the text with htmlentities first
		 * @return string the full string or the truncated string with eplise
		 */
		public final static function Truncate($strText, $intMaxLength, $blnHtmlEntities = true) {
			if (strlen($strText) > $intMaxLength) {
				$strText = substr($strText, 0, $intMaxLength - 1);
				if ($blnHtmlEntities) $strText = QApplication::HtmlEntities($strText);
				$strText .= '&hellip;';
				return $strText;
			} else {
				if ($blnHtmlEntities)
					return QApplication::HtmlEntities($strText);
				else
					return $strText;
			}
		}

		/**
		 * Escapes the string so that it can be safely used in as an Xml Node (basically, adding CDATA if needed)
		 * @param string $strString string to escape
		 * @return string the XML Node-safe String
		 */
		public final static function XmlEscape($strString) {
			if ((strpos($strString, '<') !== false) ||
				(strpos($strString, '&') !== false)) {
				$strString = str_replace(']]>', ']]]]><![CDATA[>', $strString);
				$strString = sprintf('<![CDATA[%s]]>', $strString);
			}

			return $strString;
		}

		/**
		 * Obfuscates an email so that it can be outputted as HTML to the page.
		 * @param string $strEmail the email address to obfuscate
		 * @return string the HTML of the obfuscated Email address
		 */
		public static function ObfuscateEmail($strEmail) {
			$strEmail = QApplication::HtmlEntities($strEmail);
			$strEmail = str_replace('@', '<strong style="display: none;">' . md5(microtime()) . '</strong>&#064;<strong style="display: none;">' . md5(microtime()) . '</strong>', $strEmail);
			$strEmail = str_replace('.', '<strong style="display: none;">' . md5(microtime()) . '</strong>&#046;<strong style="display: none;">' . md5(microtime()) . '</strong>', $strEmail);
			return $strEmail;
		}

		/**
		 * Given an integer that represents a byte size, this will return a string
		 * displaying the value in bytes, KB, MB, GB, TB or PB
		 * @param integer $intBytes
		 * @return string
		 */
		public static function GetByteSize($intBytes, $intNumberOfTenths = 1) {
			if (is_null($intBytes)) return QApplication::Translate('n/a');
			if ($intBytes == 0) return '0 bytes';

			$strToReturn = '';
			if ($intBytes < 0) {
				$intBytes = $intBytes * -1;
				$strToReturn .= '-';
			}

			if ($intBytes == 1)
				$strToReturn ='1 byte';
			else if ($intBytes < 1024)
				$strToReturn .= $intBytes . ' bytes';
			else if ($intBytes < (1024 * 1024))
				$strToReturn .= sprintf('%.' . $intNumberOfTenths . 'f KB', $intBytes / (1024));
			else if ($intBytes < (1024 * 1024 * 1024))
				$strToReturn .= sprintf('%.' . $intNumberOfTenths . 'f MB', $intBytes / (1024*1024));
			else if ($intBytes < (1024 * 1024 * 1024 * 1024))
				$strToReturn .= sprintf('%.' . $intNumberOfTenths . 'f GB', $intBytes / (1024*1024*1024));
			else if ($intBytes < (1024 * 1024 * 1024 * 1024 * 1024))
				$strToReturn .= sprintf('%.' . $intNumberOfTenths . 'f TB', $intBytes / (1024*1024*1024*1024));
			else
				$strToReturn .= sprintf('%.' . $intNumberOfTenths . 'f PB', $intBytes / (1024*1024*1024*1024*1024));

			return $strToReturn;
		}


		/**
		 * Similar to strpos(haystack, needle, [offset]) except "needle" can be a regular expression as well.
		 * Will only work if both the first and last character of "needle" is "/", signifying a regexp-based search.
		 *
		 * NOTE: If a regexp was used, needle WILL be modified to reflect the actual string literal found/used in the search.
		 * 
		 * @param string $strHaystack the contents to search through
		 * @param string $strNeedle the search term itself (either a literal string OR a regexp value)
		 * @param integer $intOffset optional offset value
		 * @return mixed the position number OR false if not found
		 */
		public static function StringPosition($strHaystack, &$strNeedle, $intOffset = null) {
			if ((strlen($strNeedle) >= 3) &&
				(QString::FirstCharacter($strNeedle) == '/') &&
				(QString::LastCharacter($strNeedle) == '/')) {
				$arrMatches = array();
				preg_match_all($strNeedle, $strHaystack, $arrMatches, null, $intOffset);
				if (is_array($arrMatches) && array_key_exists(0, $arrMatches)) {
					$arrMatches = $arrMatches[0];
				} else
					return false;
				if (array_key_exists(0, $arrMatches)) {
					$strNeedle = $arrMatches[0];
				} else
					return false;
			}

			if (is_null($intOffset)) {
				return strpos($strHaystack, $strNeedle);
			} else {
				return strpos($strHaystack, $strNeedle, $intOffset);
			}
		}

		/**
		 * A better version of strrpos which also allows for the use of RegExp-based matching
		 * @param string $strHaystack the text content to search through
		 * @param string $strNeedle either a plain-text item or a regexp pattern item to search for - if regexp used, this will update as the actual string of the content found
		 * @param integer $intOffset optional position offset
		 * @return mixed the position number OR false if not found
		 */
		public static function StringReversePosition($strHaystack, &$strNeedle, $intOffset = null) {
			if ((strlen($strNeedle) >= 3) &&
				(QString::FirstCharacter($strNeedle) == '/') &&
				(QString::LastCharacter($strNeedle) == '/')) {
				$arrMatches = array();
				preg_match_all($strNeedle, $strHaystack, $arrMatches);
				$arrMatches = $arrMatches[0];
				if (count($arrMatches)) {
					$strNeedle = $arrMatches[count($arrMatches) - 1];
				} else
					return false;
			}
			
			if (is_null($intOffset)) {
				return strrpos($strHaystack, $strNeedle);
			} else {
				return strrpos($strHaystack, $strNeedle, $intOffset);
			}
		}

		/**
		 * Checks if text length is between given bounds
		 * @param string $strString Text to be checked
		 * @param integer $intMinimumLength Minimum acceptable length
		 * @param integer $intMaximumLength Maximum acceptable length
		 * @return boolean
		 */
		public static function IsLengthBeetween($strString, $intMinimumLength, $intMaximumLength) {
			$intStringLength = strlen($strString);
			if (($intStringLength < $intMinimumLength) || ($intStringLength > $intMaximumLength))
				return false;
			else
				return true;
		}

		/**
		 * Given an underscore_separated_string, this will convert the string
		 * to CamelCaseNotation.  Note that this will ignore any casing in the
		 * underscore separated string.
		 * 
		 * @param string $strString
		 * @return string
		 */
		public static function ConvertToCamelCase($strString) {
			return str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($strString))));
		}

		/**
		 * Encodes a given 8-bit string into a quoted-printable string,
		 * @param string $strString the string to encode
		 * @return string the encoded string
		 */
		public static function QuotedPrintableEncode($strString) {
			if (function_exists('quoted_printable_encode')) {
				$strText = quoted_printable_encode($strString);
			} else {
				$strText = preg_replace( '/[^\x21-\x3C\x3E-\x7E\x09\x20]/e', 'sprintf( "=%02X", ord ( "$0" ) ) ;', $strString );
				preg_match_all( '/.{1,73}([^=]{0,2})?/', $strText, $arrMatch );
				$strText = implode( '=' . "\r\n", $arrMatch[0] );
			}

			return $strText;
		}

		/**
		 * Returns whether or not the given string contains any UTF-8 encoded characters.
		 * Uses regexp pattern as originally defined from http://w3.org/International/questions/qa-forms-utf-8.html
		 * and modified by chris@w3style.co.uk for efficiency.
		 * @param string $strString
		 * @return boolean whether or not the string contains any UTF-8 characters
		 */
		public static function IsContainsUtf8($strString) {
			return preg_match('%(?:
				[\xC2-\xDF][\x80-\xBF]				# non-overlong 2-byte
				|\xE0[\xA0-\xBF][\x80-\xBF]			# excluding overlongs
				|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}	# straight 3-byte
				|\xED[\x80-\x9F][\x80-\xBF]			# excluding surrogates
				|\xF0[\x90-\xBF][\x80-\xBF]{2}		# planes 1-3
				|[\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
				|\xF4[\x80-\x8F][\x80-\xBF]{2}		# plane 16
				)+%xs', $strString);
		}
	}
?>