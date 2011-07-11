<?php
	// NOTATIONS: http://www.cob.sjsu.edu/johnson_f/notation.htm
	abstract class QConvertNotationBase {
		public static function PrefixFromType($strType) {
			switch ($strType) {
				case QType::ArrayType:
					return "obj";
				case QType::Boolean:
					return "bln";
				case QType::DateTime:
					return "dtt";
				case QType::Float:
					return "flt";
				case QType::Integer:
					return "int";
				case QType::Object:
					return "obj";
				case QType::String:
					return "str";
			}
		}

		public static function WordsFromUnderscore($strName) {
			$strToReturn = trim(str_replace('_', ' ', $strName));
			if (strtolower($strToReturn) == $strToReturn)
				return ucwords($strToReturn);
			return $strToReturn;
		}

		public static function CamelCaseFromUnderscore($strName) {
			$strToReturn = '';

			// If entire underscore string is all uppercase, force to all lowercase
			// (mixed case and all lowercase can remain as is)
			if ($strName == strtoupper($strName))
				$strName = strtolower($strName);

			while (($intPosition = strpos($strName, "_")) !== false) {
				// Use 'ucfirst' to create camelcasing
				$strName = ucfirst($strName);
				if ($intPosition == 0) {
					$strName = substr($strName, 1);
				} else {
					$strToReturn .= substr($strName, 0, $intPosition);
					$strName = substr($strName, $intPosition + 1);
				}
			}

			$strToReturn .= ucfirst($strName);
			return $strToReturn;
		}

		public static function WordsFromCamelCase($strName) {
			if (strlen($strName) == 0)
				return '';

			$strToReturn = QString::FirstCharacter($strName);

			for ($intIndex = 1; $intIndex < strlen($strName); $intIndex++) {
				// Get the current character we're examining
				$strChar = substr($strName, $intIndex, 1);

				// Get the character previous to this
				$strPrevChar = substr($strName, $intIndex - 1, 1);

				// If an upper case letter
				if ((ord($strChar) >= ord('A')) &&
					(ord($strChar) <= ord('Z')))
					// Add a Space
					$strToReturn .= ' ' . $strChar;

				// If a digit, and the previous character is NOT a digit
				else if ((ord($strChar) >= ord('0')) &&
						 (ord($strChar) <= ord('9')) &&
						 ((ord($strPrevChar) < ord('0')) ||
						 (ord($strPrevChar) > ord('9'))))
					// Add a space
					$strToReturn .= ' ' . $strChar;

				// If a letter, and the previous character is a digit
				else if ((ord(strtolower($strChar)) >= ord('a')) &&
						 (ord(strtolower($strChar)) <= ord('z')) &&
						 (ord($strPrevChar) >= ord('0')) &&
						 (ord($strPrevChar) <= ord('9')))
					// Add a space
					$strToReturn .= ' ' . $strChar;

				// Otherwise
				else
					// Don't add a space					
					$strToReturn .= $strChar;
			}

			return $strToReturn;
		}



		public static function UnderscoreFromCamelCase($strName) {
			if (strlen($strName) == 0)
				return '';

			$strToReturn = QString::FirstCharacter($strName);

			for ($intIndex = 1; $intIndex < strlen($strName); $intIndex++) {
				$strChar = substr($strName, $intIndex, 1);
				if (strtoupper($strChar) == $strChar)
					$strToReturn .= '_' . $strChar;
				else
					$strToReturn .= $strChar;
			}
			
			return strtolower($strToReturn);
		}

		public static function JavaCaseFromUnderscore($strName) {
			$strToReturn = QConvertNotation::CamelCaseFromUnderscore($strName);
			return strtolower(substr($strToReturn, 0, 1)) . substr($strToReturn, 1);
		}
	}
?>