<?php
	/**
	 * Qcodo Error Handler
	 * 
	 * If we are in this class, we must assume that the application is in an unstable state.
	 * 
	 * Thus, we cannot depend on any other qcodo or application-based classes or objects
	 * to help with the error processing.
	 *
	 * Therefore, all classes and functionality for error handling must be defined in this class
	 * in order to minimize any dependency on the rest of the framework.
	 */
	class QErrorHandler {
		// Static Properties that should always be set on any error
		public static $Type;
		public static $Message;
		public static $ObjectType;
		public static $Filename;
		public static $LineNumber;
		public static $StackTrace;

		// Properties that are calculated based on the error information above
		public static $FileLinesArray;
		public static $MessageBody;

		// Static Properties that can be optionally set
		public static $RenderedPage;
		public static $ErrorAttributeArray = array();

		// Other Properties
		public static $DateTimeOfError;
		public static $FileNameOfError;
		public static $IsoDateTimeOfError;
		
		protected static function Run() {
			// Get the RenderedPage (if applicable)
			if (ob_get_length()) {
				QErrorHandler::$RenderedPage = ob_get_contents();
				ob_clean();
			}

			// Setup the FileLinesArray
			QErrorHandler::$FileLinesArray = file(QErrorHandler::$Filename);

			// Set up the MessageBody
			QErrorHandler::$MessageBody = htmlentities(QErrorHandler::$Message);
			QErrorHandler::$MessageBody = str_replace(" ", "&nbsp;", str_replace("\n", "<br/>\n", QErrorHandler::$MessageBody));
			QErrorHandler::$MessageBody = str_replace(":&nbsp;", ": ", QErrorHandler::$MessageBody);

			// Figure Out DateTime (and if we are logging, the filename of the error log)
			$strMicrotime = microtime();
			$strParts = explode(' ', $strMicrotime);
			$strMicrotime = substr($strParts[0], 2);
			$intTimestamp = $strParts[1];
			QErrorHandler::$DateTimeOfError = date('l, F j Y, g:i:s.' . $strMicrotime . ' A T', $intTimestamp);
			QErrorHandler::$IsoDateTimeOfError = date('Y-m-d H:i:s T', $intTimestamp);
			if (defined('ERROR_LOG_PATH') && ERROR_LOG_PATH && defined('ERROR_LOG_FLAG') && ERROR_LOG_FLAG)
				QErrorHandler::$FileNameOfError = sprintf('qcodo_error_%s_%s.html', date('Y-m-d_His', $intTimestamp), $strMicrotime);

			// Generate the Error Dump
			if (!ob_get_level()) ob_start();
			require(__QCODO_CORE__ . '/assets/error_dump.inc.php');

			// Do We Log???
			if (defined('ERROR_LOG_PATH') && ERROR_LOG_PATH && defined('ERROR_LOG_FLAG') && ERROR_LOG_FLAG) {
				// Log to File in ERROR_LOG_PATH
				$strContents = ob_get_contents();

				QApplication::MakeDirectory(ERROR_LOG_PATH, 0777);
				$strFileName = sprintf('%s/%s', ERROR_LOG_PATH, QErrorHandler::$FileNameOfError);
				file_put_contents($strFileName, $strContents);
				@chmod($strFileName, 0666);
			}

			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				if (defined('ERROR_FRIENDLY_AJAX_MESSAGE') && ERROR_FRIENDLY_AJAX_MESSAGE) {
					// Reset the Buffer
					while(ob_get_level()) ob_end_clean();
		
					// Setup the Friendly Response
					header('Content-Type: text/xml');
					$strToReturn = '<controls/><commands><command>alert("' . str_replace('"', '\\"', ERROR_FRIENDLY_AJAX_MESSAGE) . '");</command></commands>';
					if (QApplication::$EncodingType)
						printf("<?xml version=\"1.0\" encoding=\"%s\"?><response>%s</response>\r\n", QApplication::$EncodingType, $strToReturn);
					else
						printf("<?xml version=\"1.0\"?><response>%s</response>\r\n", $strToReturn);
					return false;
				}
			} else {
				if (defined('ERROR_FRIENDLY_PAGE_PATH') && ERROR_FRIENDLY_PAGE_PATH) {
					// Reset the Buffer
					while(ob_get_level()) ob_end_clean();
					header("HTTP/1.1 500 Internal Server Error");
					require(ERROR_FRIENDLY_PAGE_PATH);		
				}
			}

			exit();
		}



		public static function PrepDataForScript($strData) {
			$strData = str_replace("\\", "\\\\", $strData);
			$strData = str_replace("\n", "\\n", $strData);
			$strData = str_replace("\r", "\\r", $strData);
			$strData = str_replace("\"", "&quot;", $strData);
			$strData = str_replace("</script>", "&lt/script&gt", $strData);
			$strData = str_replace("</Script>", "&lt/script&gt", $strData);
			$strData = str_replace("</SCRIPT>", "&lt/script&gt", $strData);
			return $strData;
		}



		public static function HandleException(Exception $objException) {
			// If we still have access to QApplicationBase, set the error flag on the Application
			if (class_exists('QApplicationBase'))
				QApplicationBase::$ErrorFlag = true;
	
			// If we are currently dealing with reporting an error, don't go on
			if (QErrorHandler::$Type)
				return;
	
			// Setup the QErrorHandler Object
			QErrorHandler::$Type = 'Exception';
			$objReflection = new ReflectionObject($objException);
			QErrorHandler::$Message = $objException->getMessage();
			QErrorHandler::$ObjectType = $objReflection->getName();
			QErrorHandler::$Filename = $objException->getFile();
			QErrorHandler::$LineNumber = $objException->getLine();
			QErrorHandler::$StackTrace = trim($objException->getTraceAsString());
	
			// Special Setup for Database Exceptions
			if ($objException instanceof QDatabaseExceptionBase) {
				QErrorHandler::$ErrorAttributeArray[] = new QErrorAttribute('Database Error Number', $objException->ErrorNumber, false);
	
				if ($objException->Query) {
					QErrorHandler::$ErrorAttributeArray[] = new QErrorAttribute('Query', $objException->Query, true);
				}
			}
	
			// Sepcial Setup for DataBind Exceptions
			if ($objException instanceof QDataBindException) {
				if ($objException->Query) {
					QErrorHandler::$ErrorAttributeArray[] = new QErrorAttribute('Query', $objException->Query, true);
				}
			}

			QErrorHandler::Run();
		}



		public static function HandleError($intErrorNumber, $strErrorString, $strErrorFile, $intErrorLine) {
			// If a command is called with "@", then we should return
			if (error_reporting() == 0)
				return;
	
			// If we still have access to QApplicationBase, set the error flag on the Application
			if (class_exists('QApplicationBase'))
				QApplicationBase::$ErrorFlag = true;
	
			// If we are currently dealing with reporting an error, don't go on
			if (QErrorHandler::$Type)
				return;
	
			// Setup the QErrorHandler Object
			QErrorHandler::$Type = 'Exception';
			QErrorHandler::$Message = $strErrorString;
			QErrorHandler::$Filename = $strErrorFile;
			QErrorHandler::$LineNumber = $intErrorLine;
			
			switch ($intErrorNumber) {
				case E_ERROR:
					QErrorHandler::$ObjectType = 'E_ERROR';
					break;
				case E_WARNING:
					return;
					QErrorHandler::$ObjectType = 'E_WARNING';
					break;
				case E_PARSE:
					QErrorHandler::$ObjectType = 'E_PARSE';
					break;
				case E_NOTICE:
					return;
					QErrorHandler::$ObjectType = 'E_NOTICE';
					break;
				case E_STRICT:
					QErrorHandler::$ObjectType = 'E_STRICT';
					break;
				case E_CORE_ERROR:
					QErrorHandler::$ObjectType = 'E_CORE_ERROR';
					break;
				case E_CORE_WARNING:
					QErrorHandler::$ObjectType = 'E_CORE_WARNING';
					break;
				case E_COMPILE_ERROR:
					QErrorHandler::$ObjectType = 'E_COMPILE_ERROR';
					break;
				case E_COMPILE_WARNING:
					QErrorHandler::$ObjectType = 'E_COMPILE_WARNING';
					break;
				case E_USER_ERROR:
					QErrorHandler::$ObjectType = 'E_USER_ERROR';
					break;
				case E_USER_WARNING:
					QErrorHandler::$ObjectType = 'E_USER_WARNING';
					break;
				case E_USER_NOTICE:
					QErrorHandler::$ObjectType = 'E_USER_NOTICE';
					break;
				default:
					QErrorHandler::$ObjectType = 'Unknown';
					break;
			}
	
			// Setup the Stack Trace
			QErrorHandler::$StackTrace = "";
			$objBackTrace = debug_backtrace();
			for ($intIndex = 0; $intIndex < count($objBackTrace); $intIndex++) {
				$objItem = $objBackTrace[$intIndex];
				
				$strKeyFile = (array_key_exists('file', $objItem)) ? $objItem['file'] : '';
				$strKeyLine = (array_key_exists('line', $objItem)) ? $objItem['line'] : '';
				$strKeyClass = (array_key_exists('class', $objItem)) ? $objItem['class'] : '';
				$strKeyType = (array_key_exists('type', $objItem)) ? $objItem['type'] : '';
				$strKeyFunction = (array_key_exists('function', $objItem)) ? $objItem['function'] : '';
				
				QErrorHandler::$StackTrace .= sprintf("#%s %s(%s): %s%s%s()\n",
					$intIndex,
					$strKeyFile,
					$strKeyLine,
					$strKeyClass,
					$strKeyType,
					$strKeyFunction);
			}

			QErrorHandler::Run();
		}
	}

	class QErrorAttribute {
		public $Label;
		public $Contents;
		public $MultiLine;

		public function __construct($strLabel, $strContents, $blnMultiLine) {
			$this->Label = $strLabel;
			$this->Contents = $strContents;
			$this->MultiLine = $blnMultiLine;
		}
	}
?>