<?php
	class QCliParameterProcessor extends QBaseClass {
		protected $chrShortIdentifierArray = array();
		protected $strLongIdentifierArray = array();

		protected $mixValueArray = array();
		protected $strHelpTextArray = array();
		protected $intParameterTypeArray = array();
		protected $blnFlagArray = array();

		protected $chrShortIdentifierByIndex = array();
		protected $strLongIdentifierByIndex = array();

		protected $strDefaultIdentifierArray = array();
		protected $mixDefaultValueArray = array();
		protected $intDefaultParameterTypeArray = array();
		protected $strDefaultHelpTextArray = array();

		protected $strQcodoCliCommand;
		protected $strHelpTextHeadline;



		/**
		 * @param string $strQcodoCliCommand
		 * @param string $strHelpTextHeadline
		 * @return QCliParameterProcessor
		 */
		public function __construct($strQcodoCliCommand, $strHelpTextHeadline) {
			$this->strQcodoCliCommand = trim($strQcodoCliCommand);
			$this->strHelpTextHeadline = trim($strHelpTextHeadline);
		}


		/**
		 * Parse the given array of arguments
		 * If nothing is passed in, we'll just assume to use argv, MINUS the "qcodo" call and the script name.
		 * If asking for help, it will print out help and exit with success.
		 * If error parsing the argument array, it will print out the error and exit with failure.
		 * Otherwise, it will return void and not exit at all.
		 * @param string[] $strArgumentArray
		 * @return void
		 */
		public function Run($strArgumentArray = null) {
			if (is_null($strArgumentArray)) {
				$strArgumentArray = $_SERVER['argv'];
				array_shift($strArgumentArray);
				array_shift($strArgumentArray);
			}

			// Asking for help?
			if ((count($strArgumentArray) == 1) && (
					(strtolower($strArgumentArray[0]) == '-h') ||
					(strtolower($strArgumentArray[0]) == '-?') ||
					(strtolower($strArgumentArray[0]) == '--help')
				)) {
				print $this->GetHelpText();
				exit(0);
			}

			if ($strError = $this->ParseArguments($strArgumentArray)) {
				printf("%s: %s\r\n", $this->strQcodoCliCommand, $strError);
				printf("See \"qcodo %s --help\" for more information\r\n", $this->strQcodoCliCommand);
				exit(1);
			}
		}

		/**
		 * This will actually handle the argument parsing from the array.
		 * 
		 * @param string[] $strArgumentArray
		 * @return string null if cleanly and completely parsed or string with an error message
		 */
		public function ParseArguments($strArgumentArray) {
			$intCurrentDefaultParameterIndex = 0;

			$intCurrentValueIndex = null;
			foreach ($strArgumentArray as $strArgument) {
				// a named parameter or flag?
				if ((substr($strArgument, 0, 1) == '-') || (substr($strArgument, 0, 2) == '--')) {
					// We are specifying a named parameter or flag -- if we are in middle of evaluating a ValueIndex, we need to
					// set the value of that ValueIndex to null
					if (!is_null($intCurrentValueIndex)) {
						$this->mixValueArray[$intCurrentValueIndex] = null;
						$intCurrentValueIndex = null;
					}

					// Figure out the ValueIndex we're dealing with
					$strValue = null;
					if ((substr($strArgument, 0, 2) == '--')) {
						// Parse out the LongIdentifier and the possible linked value
						if ($strError = $this->ParseLongIdentifier($strArgument, $intCurrentValueIndex)) return $strError;
					} else {
						// Parse out the ShortIdentifier and the possible linked value
						if ($strError = $this->ParseShortIdentifier($strArgument, $intCurrentValueIndex)) return $strError;
					}

				// Just a value -- we need to see who it belongs to
				} else {
					$strValue = $strArgument;

					// Are we currently trying to parse a valueindex
					if (!is_null($intCurrentValueIndex)) {
						try {
							$this->mixValueArray[$intCurrentValueIndex] = QCliParameterProcessor::CleanValue($strValue, $this->intParameterTypeArray[$intCurrentValueIndex]);
							$intCurrentValueIndex = null;
						} catch (QCallerException $objExc) { return $objExc->GetMessage(); }

					// or if not, then this is a default parameter
					} else {
						if (array_key_exists($intCurrentDefaultParameterIndex, $this->mixDefaultValueArray)) {
							try {
								$this->mixDefaultValueArray[$intCurrentDefaultParameterIndex] = QCliParameterProcessor::CleanValue($strValue, $this->intDefaultParameterTypeArray[$intCurrentDefaultParameterIndex]);
								$intCurrentDefaultParameterIndex++;
							} catch (QCallerException $objExc) { return $objExc->GetMessage(); }
						} else {
							return 'invalid argument "' . $strValue . '"';
						}
					}
				}
			}

			// Done iterating through the arguments
			// Make sure all the default arguments are accounted for
			if (array_key_exists($intCurrentDefaultParameterIndex, $this->mixDefaultValueArray)) {
				return 'missing value for "' . $this->strDefaultIdentifierArray[$intCurrentDefaultParameterIndex] . '"';
			}

			// Otherwise, Success - no error
			return null;
		}

		/**
		 * Given a "--"-based argument, this will parse out the information, pulling out and validating the LongIdentifier.
		 * If it is a FlagParameter, it will set the flag to true.
		 * If it is a NamedParameter, then if a value is specified using "=", then the value will be applied to the LongIdentifier, otherwise, it will set $intCurrentValueIndex to the ValueIndex of the LongIdentifier.
		 * @param string $strArgument the full "--"-based argument
		 * @param integer $intCurrentValueIndex the new current ValueIndex that should be evaluated (if applicable)
		 * @return string any error message (if any)
		 */
		protected function ParseLongIdentifier($strArgument, &$intCurrentValueIndex) {
			// Parse out the leading "--"
			$strArgument = substr($strArgument, 2);
			$mixValue = null;

			// Get out any "value" after "=" (if applicable)
			if (($intPosition = strpos($strArgument, '=')) !== false) {
				$mixValue = substr($strArgument, $intPosition+1);
				$strArgument = substr($strArgument, 0, $intPosition);
			}

			// Clean Out and Verify the LongIdentifier
			try {
				$strLongIdentifier = QCliParameterProcessor::CleanLongIdentifier($strArgument);
			} catch (QCallerException $objExc) {
				return 'invalid argument "' . $strArgument . '"';
			}

			// Get the ValueIndex
			if (array_key_exists($strLongIdentifier, $this->strLongIdentifierArray))
				$intValueIndex = $this->strLongIdentifierArray[$strLongIdentifier];
			else
				return 'invalid argument "' . $strArgument . '"';

			// See if this is a Flag- or a Named-Parameter
			if (array_key_exists($intValueIndex, $this->blnFlagArray)) {
				// Flag -- Set it to True!
				$this->mixValueArray[$intValueIndex] = true;
			} else {
				// NamedParameter -- Do we Have a Value?
				if (!is_null($mixValue)) {
					// Yes -- Set it
					try {
						$this->mixValueArray[$intValueIndex] = QCliParameterProcessor::CleanValue($mixValue, $this->intParameterTypeArray[$intValueIndex]);
					} catch (QCallerException $objExc) { return $objExc->GetMessage(); }
				} else {
					// No -- so let's update the Currently-processing ValueIndex
					$intCurrentValueIndex = $intValueIndex;
				}
			}

			// Success - no errors
			return null;
		}

		/**
		 * Given a "-"-based argument, this will parse out the information, pulling out and validating any/all ShortIdentifiers.
		 * If it is a single or clustered FlagParameter, it will set the flag(s) to true.
		 * If it is a NamedParameter, then if a value is specified using "=", then the value will be applied to the ShortIdentifier, otherwise, it will set $intCurrentValueIndex to the ValueIndex of the ShortIdentifier.
		 * @param string $strArgument the full "-"-based argument
		 * @param integer $intCurrentValueIndex the new current ValueIndex that should be evaluated (if applicable)
		 * @return string any error message (if any)
		 */
		protected function ParseShortIdentifier($strArgument, &$intCurrentValueIndex) {
			// Parse out the leading "-"
			$strArgument = substr($strArgument, 1);

			// Clean Out and Verify the ShortIdentifier
			$chrShortIdentifier = substr($strArgument, 0, 1);
			try {
				$chrShortIdentifier = QCliParameterProcessor::CleanShortIdentifier($chrShortIdentifier);
			} catch (QCallerException $objExc) {
				return 'invalid argument "' . $chrShortIdentifier . '"';
			}

			// Get the ValueIndex
			if (array_key_exists($chrShortIdentifier, $this->chrShortIdentifierArray))
				$intValueIndex = $this->chrShortIdentifierArray[$chrShortIdentifier];
			else
				return 'invalid argument "' . $chrShortIdentifier . '"';

			// See if this is a Flag- or a Named-Parameter
			if (array_key_exists($intValueIndex, $this->blnFlagArray)) {
				// Flag!  This also may be clustered, so go through all of the letters in the argument and set the flag value to true
				return $this->ParseShortIdentifierCluster($strArgument);
			} else {
				// NamedParameter -- Do we Have a Value?
				$strArgument = substr($strArgument, 1);
				if (strlen($strArgument)) {
					// Yes -- Set it

					// Take out any leading "="
					if (QString::FirstCharacter($strArgument) == '=') $strArgument = substr($strArgument, 1);

					// Set the Value
					try {
						$this->mixValueArray[$intValueIndex] = QCliParameterProcessor::CleanValue($strArgument, $this->intParameterTypeArray[$intValueIndex]);
					} catch (QCallerException $objExc) { return $objExc->GetMessage(); }
				} else {
					// No -- so let's update the Currently-processing ValueIndex
					$intCurrentValueIndex = $intValueIndex;
				}
			}

			// Success - no errors
			return null;
		}

		/**
		 * Assuming that a cluster of flags is passed it, it will parse out, validate each letter in the cluster as a flag
		 * and set its value to true.  It will return null if successful or return an error message if not.
		 * @param string $strClusterOfFlags
		 * @return string any error message (if any)
		 */
		protected function ParseShortIdentifierCluster($strClusterOfFlags) {
			for ($intCharacter = 0; $intCharacter < strlen($strClusterOfFlags); $intCharacter++) {
				// Parse out and validate the shortidentifier
				$chrShortIdentifier = substr($strClusterOfFlags, $intCharacter, 1);
				try {
					$chrShortIdentifier = QCliParameterProcessor::CleanShortIdentifier($chrShortIdentifier);
				} catch (QCallerException $objExc) {
					return 'invalid argument "' . $strClusterOfFlags . '"';
				}

				// Get the ValueIndex
				if (array_key_exists($chrShortIdentifier, $this->chrShortIdentifierArray))
					$intValueIndex = $this->chrShortIdentifierArray[$chrShortIdentifier];
				else
					return 'invalid argument "' . $strClusterOfFlags . '"';

				// Ensure it's a flag
				if (!array_key_exists($intValueIndex, $this->blnFlagArray))
					return 'invalid argument "' . $strClusterOfFlags . '"';

				// Set the Value to TRUE
				$this->mixValueArray[$intValueIndex] = true;
			}

			// Success - no errors
			return null;
		}



		/**
		 * Gets the value of a NamedParameter or a FlagParameter, based on the short or long identifier being passed in.
		 * Throws an exception if the identifier passed in doesn't exist.
		 * @param string $strIdentifier
		 * @return mixed
		 */
		public function GetValue($strIdentifier) {
			$strIdentifier = trim($strIdentifier);
			if (!strlen($strIdentifier)) throw new QCallerException('Invalid Identifier: ' . $strIdentifier);
			
			try {
				if (strlen($strIdentifier) == 1) {
					$strIdentifier = QCliParameterProcessor::CleanShortIdentifier($strIdentifier);
					if (array_key_exists($strIdentifier, $this->chrShortIdentifierArray))
						return ($this->mixValueArray[$this->chrShortIdentifierArray[$strIdentifier]]); 
				} else {
					$strIdentifier = QCliParameterProcessor::CleanLongIdentifier($strIdentifier); 
					if (array_key_exists($strIdentifier, $this->strLongIdentifierArray))
						return ($this->mixValueArray[$this->strLongIdentifierArray[$strIdentifier]]); 
				}
			} catch (QInvalidCastException $objExc) {}

			throw new QCallerException('Unknown Identifier: ' . $strIdentifier);
		}



		/**
		 * Gets the value of a DefaultParameter, based on the defaultIdentifier being passed in.
		 * Throws an exception if the defaultIdentifier passed in doesn't exist.
		 * @param string $strDefaultIdentifier
		 * @return mixed
		 */
		public function GetDefaultValue($strDefaultIdentifier) {
			$strDefaultIdentifier = trim($strDefaultIdentifier);
			if (!strlen($strDefaultIdentifier)) throw new QCallerException('Invalid DefaultIdentifier: ' . $strDefaultIdentifier);

			try {
				$strDefaultIdentifier = QCliParameterProcessor::CleanDefaultIdentifier($strDefaultIdentifier);
				foreach ($this->strDefaultIdentifierArray as $intValueIndex => $strIdentifier) {
					if ($strIdentifier == $strDefaultIdentifier)
						return $this->mixDefaultValueArray[$intValueIndex];
				}
			} catch (QInvalidCastException $objExc) {}

			throw new QCallerException('Unknown DefaultIdentifier: ' . $strDefaultIdentifier);
		}



		/**
		 * Given a value (usually parsed from the arguments), clean it up according to the type it is supposed to be
		 * or throw a QInvalidCastException
		 * @param mixed $mixValue
		 * @param QCliParameterType $intCliParameterType
		 * @return mixed
		 */
		public static function CleanValue($mixValue, $intCliParameterType) {
			try {
				switch ($intCliParameterType) {
					case QCliParameterType::Integer:
						return QType::Cast($mixValue, QType::Integer);

					case QCliParameterType::String:
						return QType::Cast($mixValue, QType::String);

					case QCliParameterType::Boolean:
						return QType::Cast($mixValue, QType::Boolean);

					case QCliParameterType::Path:
						$strPath = QType::Cast($mixValue, QType::String);
						// Windows
						if (substr(__FILE__, 1, 2) == ':\\') {
							if (substr($strPath, 1, 2) == ':\\')
								return $strPath;
							else
								return $_SERVER['PWD'] . '\\' . $strPath;
						} else {
							if (substr($strPath, 0, 1) == '/')
								return $strPath;
							else
								return $_SERVER['PWD'] . '/' . $strPath;
						}

					default:
						throw new Exception('Invalid QCliParameterType: ' . $intCliParameterType);
				}
			} catch (QCallerException $objExc) {
				throw new QCallerException('unable to parse ' . QCliParameterType::$NameArray[$intCliParameterType] . ' value "' . $mixValue . '"');
			}
		}
		/**
		 * Returns the "Help Text" based on the way this QCliParameterProcessor is set up.
		 * @return string
		 */
		public function GetHelpText() {
			$strToReturn = $this->strHelpTextHeadline . "\r\n";
			$strToReturn .= 'usage: qcodo ' . $this->strQcodoCliCommand . ' ';
			if (count($this->mixValueArray)) $strToReturn .= '[OPTIONS] ';
			if (count($this->strDefaultIdentifierArray)) $strToReturn .= implode(' ', $this->strDefaultIdentifierArray);
			$strToReturn .= "\r\n\r\n";


			// Default Identifier MaxLength and associated HelpText width and padding
			$intMaxIdentifierLength = 16;
			$strPadding = str_repeat(' ', $intMaxIdentifierLength+4);
			$intHelpTextWidth = 78-$intMaxIdentifierLength-4;


			// Printout any required parameters
			if (count($this->strDefaultIdentifierArray)) {
				$strToReturn .= "required parameters:\r\n";

				// Update MaxIdLength calculation (if applicable)
				foreach ($this->strDefaultIdentifierArray as $strDefaultIdentifier) {
					if (strlen($strDefaultIdentifier) > $intMaxIdentifierLength) $intMaxIdentifierLength = strlen($strDefaultIdentifier);
				}
				$strPadding = str_repeat(' ', $intMaxIdentifierLength+4);
				$intHelpTextWidth = 78-$intMaxIdentifierLength-4;

				// Render the Required Parameters
				foreach ($this->strDefaultIdentifierArray as $intIndex => $strDefaultIdentifier) {
					$strToReturn .= sprintf("  %-" . $intMaxIdentifierLength . "s  %s\r\n",
						$strDefaultIdentifier, QCliParameterProcessor::RenderHelpText($this->strDefaultHelpTextArray[$intIndex], $intHelpTextWidth, $strPadding));
				}
				
				$strToReturn .= "\r\n";
			}


			// Printout any optional parameters
			if (count($this->mixValueArray)) {
				$strToReturn .= "optional parameters:\r\n";

				foreach ($this->mixValueArray as $intIndex => $mixValue) {
					// First, figure out the formal label for the "identifier"
					$strIdentifier = '';
					if (array_key_exists($intIndex, $this->chrShortIdentifierByIndex))
						$strIdentifier .= '-' . $this->chrShortIdentifierByIndex[$intIndex];
					if (array_key_exists($intIndex, $this->strLongIdentifierByIndex)) {
						if ($strIdentifier) $strIdentifier .= ', ';
						$strIdentifier .= '--' . $this->strLongIdentifierByIndex[$intIndex];
					}

					// For non-flags (actual named parameters) output the parameter type we are expecting
					if (array_key_exists($intIndex, $this->intParameterTypeArray))
						$strIdentifier .= '=' . QCliParameterType::$NameArray[$this->intParameterTypeArray[$intIndex]];

					// Print it out by itself, or include the help text (if applicable)
					if (!($strHelpText = $this->strHelpTextArray[$intIndex])) {
						$strToReturn .= '  ' . $strIdentifier . "\r\n";
					} else {
						$strHelpText = QCliParameterProcessor::RenderHelpText($strHelpText, $intHelpTextWidth, $strPadding);
						if (strlen($strIdentifier) > $intMaxIdentifierLength)
							$strToReturn .= sprintf("  %s\r\n%s%s\r\n", $strIdentifier, $strPadding, $strHelpText);
						else
							$strToReturn .= sprintf("  %-" . $intMaxIdentifierLength . "s  %s\r\n", $strIdentifier, $strHelpText);
					}
				}

				$strToReturn .= "\r\n";
			}

			// Return the rendered Help Text
			return $strToReturn;
		}

		/**
		 * Given a help text, the max width for that help text, and the amount of left-side padding any subsequent line gets,
		 * it will returned the rendered help text with the spacing and linebreaks.
		 * @param string $strHelpText
		 * @param integer $intMaxWidth
		 * @param string $strPadding
		 * @return string
		 */
		public static function RenderHelpText($strHelpText, $intMaxWidth, $strPadding) {
			$strHelpText = wordwrap(trim($strHelpText), $intMaxWidth, "\r\n", true);
			$strHelpText = str_replace("\r\n", "\r\n" . $strPadding, $strHelpText);
			return $strHelpText;
		}

		/**
		 * Adds a CLI Flag parameter to process.  Values are false by default, but can be set to true if the flag is set in the argv.
		 * Examples include things like "--verbose" or "-v", etc.
		 * @param string $chrShortIdentifier
		 * @param string $strLongIdentifier
		 * @param string $strHelpText
		 * @return void
		 */
		public function AddFlagParameter($chrShortIdentifier, $strLongIdentifier, $strHelpText) {
			// Cleanup the Identifiers, and throw in invalid
			try {
				$chrShortIdentifier = QCliParameterProcessor::CleanShortIdentifier($chrShortIdentifier);
				$strLongIdentifier = QCliParameterProcessor::CleanLongIdentifier($strLongIdentifier);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Ensure at least one identifier is requested
			if (!$chrShortIdentifier && !$strLongIdentifier)
				throw new QCallerException('No identifiers were specified');

			// Ensure Identifiers are not already in use
			if ($chrShortIdentifier && array_key_exists($chrShortIdentifier, $this->chrShortIdentifierArray))
				throw new QCallerException('Short Identifier already in use: ' . $chrShortIdentifier);
			if ($strLongIdentifier && array_key_exists($strLongIdentifier, $this->strLongIdentifierArray))
				throw new QCallerException('Long Identifier already in use: ' . $strLongIdentifier);

			// Get the ValueIndex for this flag, and set the value to false
			$intIndex = count($this->mixValueArray);
			$this->mixValueArray[$intIndex] = false;
			$this->blnFlagArray[$intIndex] = true;
			$this->strHelpTextArray[$intIndex] = $strHelpText;

			// Set the Identifiers to this ValueIndex
			if ($chrShortIdentifier) {
				$this->chrShortIdentifierArray[$chrShortIdentifier] = $intIndex;
				$this->chrShortIdentifierByIndex[$intIndex] = $chrShortIdentifier;
			}
			if ($strLongIdentifier) {
				$this->strLongIdentifierArray[$strLongIdentifier] = $intIndex;
				$this->strLongIdentifierByIndex[$intIndex] = $strLongIdentifier;
			}
		}

		/**
		 * Adds a CLI Named parameter to process.  Default values can be specified.
		 * Named parameters in CLI calls MUST have values associated with them.  CLI calls can be typically:
		 * 	-i foobar
		 * 	-i=foobar
		 *  -ifoobar
		 *  --identifier foobar
		 *  --identifier=foobar
		 * @param string $chrShortIdentifier
		 * @param string $strLongIdentifier
		 * @param QCliParameterType $intCliParameterType
		 * @param mixed $mixDefaultValue
		 * @param string $strHelpText
		 * @return void
		 */
		public function AddNamedParameter($chrShortIdentifier, $strLongIdentifier, $intCliParameterType, $mixDefaultValue, $strHelpText) {
			// Cleanup the Identifiers, and throw in invalid
			try {
				$chrShortIdentifier = QCliParameterProcessor::CleanShortIdentifier($chrShortIdentifier);
				$strLongIdentifier = QCliParameterProcessor::CleanLongIdentifier($strLongIdentifier);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Ensure at least one identifier is requested
			if (!$chrShortIdentifier && !$strLongIdentifier)
				throw new QCallerException('No identifiers were specified');

			// Ensure Identifiers are not already in use
			if ($chrShortIdentifier && array_key_exists($chrShortIdentifier, $this->chrShortIdentifierArray))
				throw new QCallerException('Short Identifier already in use: ' . $chrShortIdentifier);
			if ($strLongIdentifier && array_key_exists($strLongIdentifier, $this->strLongIdentifierArray))
				throw new QCallerException('Long Identifier already in use: ' . $strLongIdentifier);

			// Get the ValueIndex for this flag, and set the value to false
			$intIndex = count($this->mixValueArray);
			$this->mixValueArray[$intIndex] = $mixDefaultValue;
			$this->intParameterTypeArray[$intIndex] = $intCliParameterType;
			$this->strHelpTextArray[$intIndex] = $strHelpText;

			// Set the Identifiers to this ValueIndex
			if ($chrShortIdentifier) {
				$this->chrShortIdentifierArray[$chrShortIdentifier] = $intIndex;
				$this->chrShortIdentifierByIndex[$intIndex] = $chrShortIdentifier;
			}
			if ($strLongIdentifier) {
				$this->strLongIdentifierArray[$strLongIdentifier] = $intIndex;
				$this->strLongIdentifierByIndex[$intIndex] = $strLongIdentifier;
			}
		}

		/**
		 * Adds a default parameter for this CLI call.  DefaultIdentifier will be alphanumeric with underscores in all caps.
		 * Because default parameters are required, there is no default value to specify.
		 * Note that since defualt parameters MUST be passed in, there is no short or long (-x or --xxx) identifiers associated with them.
		 * The identifier specified is simply for internal use.  Processing of default identifiers are done in the order they are added
		 * to the class.  So for example, if default identifiers are added in the following way:
		 * 	$this->AddDefaultParameter('USERNAME', QCliParameterType::String, 'Your Username');
		 * 	$this->AddDefaultParameter('PASSWORD', QCliParameterType::String, 'Your Possword');
		 * 	$this->AddDefaultParameter('PATH_TO_FILE', QCliParameterType::Path, 'Path to the given file');
		 * then the call to the CLI must follow with USERNAME PASSWORD PATH_TO_FILE.
		 * @param string $strDefaultIdentifier
		 * @param QCliParameterType $intCliParameterType
		 * @param string $strHelpText
		 * @return void
		 */		
		public function AddDefaultParameter($strDefaultIdentifier, $intCliParameterType, $strHelpText) {
			// Cleanup the Identifier, and throw in invalid
			try {
				$strDefaultIdentifier = QCliParameterProcessor::CleanDefaultIdentifier($strDefaultIdentifier);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			
			// Ensure DefaultIdentifier is not already in use
			if ($strDefaultIdentifier && array_key_exists($strDefaultIdentifier, $this->strDefaultIdentifierArray))
				throw new QCallerException('DefaultIdentifier already in use: ' . $strDefaultIdentifier);

			// Get the ValueIndex for this flag, and set the value to false
			$intIndex = count($this->mixDefaultValueArray);
			$this->mixDefaultValueArray[$intIndex] = null;
			$this->intDefaultParameterTypeArray[$intIndex] = $intCliParameterType;
			$this->strDefaultHelpTextArray[$intIndex] = $strHelpText;
			$this->strDefaultIdentifierArray[$intIndex] = $strDefaultIdentifier;
		}


		/**
		 * If this is a valid ShortIdentifier character (single letter), it will return it.
		 * If this was null, it will return null.
		 * If it is invalid, it will throw a QInvalidCastException 
		 * @param string $chrShortIdentifier
		 * @return string
		 */
		public static function CleanShortIdentifier($chrShortIdentifier) {
			if (is_null($chrShortIdentifier)) return null;
			if (strlen($chrShortIdentifier) != 1) throw new QInvalidCastException('Invalid Short Identifier: ' . $chrShortIdentifier);
			$intOrd = ord($chrShortIdentifier);
			if (($intOrd >= ord('a')) && ($intOrd <= ord('z')) ||
				($intOrd >= ord('A')) && ($intOrd <= ord('Z')))
				return $chrShortIdentifier;
			throw new QInvalidCastException('Invalid Short Identifier: ' . $chrShortIdentifier);
		}
				
		/**
		 * If this is a valid LongIdentifier string (alphanumeric or hyphen, all lowercase, begins with letter, at least 2 characters long), it will return it.
		 * If this was null, it will return null.
		 * If it is invalid, it will throw a QInvalidCastException 
		 * @param string $strLongIdentifier
		 * @return string
		 */
		public static function CleanLongIdentifier($strLongIdentifier) {
			if (is_null($strLongIdentifier)) return null;
			preg_match('/[A-Za-z][A-Za-z0-9\\-]+/', $strLongIdentifier, $arrMatches);
			if (count($arrMatches) != 1) throw new QInvalidCastException('Invalid Long Identifier: ' . $strLongIdentifier);
			if ($arrMatches[0] != $strLongIdentifier) throw new QInvalidCastException('Invalid Long Identifier: ' . $strLongIdentifier);
			return strtolower($strLongIdentifier);
		}

		/**
		 * If this is a valid DefaultIdentifier string (alphanumeric or underscore, all uppercase, begins with letter), it will return it.
		 * If this was null or invalid, it will throw a QInvalidCastException
		 * @param string $strDefaultIdentifier
		 * @return string
		 */
		public static function CleanDefaultIdentifier($strDefaultIdentifier) {
			if (!strlen($strDefaultIdentifier)) throw new QInvalidCastException('Default Identifier cannot be null');
			preg_match('/[A-Za-z][A-Za-z0-9_\\/]+/', $strDefaultIdentifier, $arrMatches);
			if (count($arrMatches) != 1) throw new QInvalidCastException('Invalid Default Identifier: ' . $strDefaultIdentifier);
			if ($arrMatches[0] != $strDefaultIdentifier) throw new QInvalidCastException('Invalid Default Identifier: ' . $strDefaultIdentifier);
			return strtoupper($strDefaultIdentifier);
		}
	}

	abstract class QCliParameterType extends QBaseClass {
		const String = 1;
		const Integer = 2;
		const Boolean = 3;
		const Path = 4;

		public static $NameArray = array(
			1 => 'string',
			2 => 'integer',
			3 => 'boolean',
			4 => 'path'
		);
	}
?>