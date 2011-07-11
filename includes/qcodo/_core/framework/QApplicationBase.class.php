<?php
	/**
	 * This abstract class should never be instantiated.  It contains static methods,
	 * variables and constants to be used throughout the application.
	 *
	 * The static method "Initialize" should be called at the begin of the script by
	 * prepend.inc.
	 */
	abstract class QApplicationBase extends QBaseClass {
		//////////////////////////
		// Public Static Variables
		//////////////////////////

		/**
		 * Internal bitmask signifying which BrowserType the user is using
		 * Use the QApplication::IsBrowser() method to do browser checking
		 *
		 * @var integer BrowserType
		 */
		protected static $BrowserType = QBrowserType::Unsupported;

		/**
		 * Definition of CacheControl for the HTTP header.  In general, it is
		 * recommended to keep this as "private".  But this can/should be overriden
		 * for file/scripts that have special caching requirements (e.g. dynamically
		 * created images like QImageLabel).
		 *
		 * @var string CacheControl
		 */
		public static $CacheControl = 'private';

		/**
		 * Path of the "web root" or "document root" of the web server
		 * Like "/home/www/htdocs" on Linux/Unix or "c:\inetpub\wwwroot" on Windows
		 *
		 * @var string DocumentRoot
		 */
		public static $DocumentRoot;

		/**
		 * Whether or not we are currently trying to Process the Output of the page.
		 * Used by the OutputPage PHP output_buffering handler.  As of PHP 5.2,
		 * this gets called whenever ob_get_contents() is called.  Because some
		 * classes like QFormBase utilizes ob_get_contents() to perform template
		 * evaluation without wanting to actually perform OutputPage, this flag
		 * can be set/modified by QFormBase::EvaluateTemplate accordingly to
		 * prevent OutputPage from executing.
		 *
		 * @var boolean ProcessOutput
		 */
		public static $ProcessOutput = true;

		/**
		 * Full path of the actual PHP script being run
		 * Like "/home/www/htdocs/folder/script.php" on Linux/Unix
		 * or "c:\inetpub\wwwroot" on Windows
		 *
		 * @var string ScriptFilename
		 */
		public static $ScriptFilename;

		/**
		 * Web-relative path of the actual PHP script being run
		 * So for "http://www.domain.com/folder/script.php",
		 * QApplication::$ScriptName would be "/folder/script.php"
		 *
		 * @var string ScriptName
		 */
		public static $ScriptName;

		/**
		 * Extended Path Information after the script URL (if applicable)
		 * So for "http://www.domain.com/folder/script.php/15/225"
		 * QApplication::$PathInfo would be "/15/255"
		 *
		 * @var string PathInfo
		 */
		public static $PathInfo;

		/**
		 * Query String after the script URL (if applicable)
		 * So for "http://www.domain.com/folder/script.php?item=15&value=22"
		 * QApplication::$QueryString would be "item=15&value=22"
		 *
		 * @var string QueryString
		 */
		public static $QueryString;

		/**
		 * The full Request URI that was requested
		 * So for "http://www.domain.com/folder/script.php/15/25/?item=15&value=22"
		 * QApplication::$RequestUri would be "/folder/script.php/15/25/?item=15&value=22"
		 *
		 * @var string RequestUri
		 */
		public static $RequestUri;

		/**
		 * The IP address of the server running the script/PHP application
		 * This is either the LOCAL_ADDR or the SERVER_ADDR server constant, depending
		 * on the server type, OS and configuration.
		 *
		 * @var string ServerAddress
		 */
		public static $ServerAddress;

		/**
		 * The encoding type for the application (e.g. UTF-8, ISO-8859-1, etc.)
		 *
		 * @var string EncodingType
		 */
		public static $EncodingType = 'UTF-8';

		/**
		 * Specify whether or not PHP Session should be enabled
		 * @var boolean EnableSession
		 */
		public static $EnableSession = true;

		/**
		 * An array of Database objects, as initialized by QApplication::InitializeDatabaseConnections()
		 *
		 * @var DatabaseBase[] Database
		 */
		public static $Database;

		/**
		 * A flag to indicate whether or not this script is run as a CLI (Command Line Interface)
		 *
		 * @var boolean CliMode
		 */
		public static $CliMode;

		/**
		 * A flag to indicate whether or not this script is running in a Windows environment
		 *
		 * @var boolean Windows
		 */
		public static $Windows;

		/**
		 * Class File Array - used by QApplication::AutoLoad to more quickly load
		 * core class objects without making a file_exists call.
		 *
		 * @var array ClassFile
		 */
		public static $ClassFile;

		/**
		 * Preloaded Class File Array - used by QApplication::Initialize to load
		 * any core class objects during Initailize()
		 *
		 * @var array ClassFile
		 */
		public static $PreloadedClassFile;

		/**
		 * The QRequestMode enumerated value for the current request mode
		 *
		 * @var string RequestMode
		 */
		public static $RequestMode;

		/**
		 * 2-letter country code to set for internationalization and localization
		 * (e.g. us, uk, jp)
		 *
		 * @var string CountryCode
		 */
		public static $CountryCode;

		/**
		 * 2-letter language code to set for internationalization and localization
		 * (e.g. en, jp, etc.)
		 *
		 * @var string LanguageCode
		 */
		public static $LanguageCode;

		/**
		 * The instance of the active QI18n object (which contains translation strings), if any.
		 *
		 * @var QI18n $LanguageObject
		 */
		public static $LanguageObject;

		////////////////////////
		// Public Overrides
		////////////////////////
		/**
		 * This faux constructor method throws a caller exception.
		 * The Application object should never be instantiated, and this constructor
		 * override simply guarantees it.
		 *
		 * @return void
		 */
		public final function __construct() {
			throw new QCallerException('Application should never be instantiated.  All methods and variables are publically statically accessible.');
		}


		////////////////////////
		// Public Static Methods
		////////////////////////

		/**
		 * Called by QApplication::Initialize() to setup error and exception handling
		 * to use the Qcodo Error/Exception handler.  Only called for non-CLI calls.
		 * @return void
		 */
		protected static function InitializeErrorHandling() {
			set_error_handler('__qcodo_handle_error', error_reporting());
			set_exception_handler('__qcodo_handle_exception');
		}

		/**
		 * Called by QApplication::Initialize() to initialize the QApplication::$CliMode and
		 * QApplication::$Windows settings.
		 * @return void
		 */
		protected static function InitializeEnvironment() {
			if (PHP_SAPI == 'cli')
				QApplication::$CliMode = true;
			else
				QApplication::$CliMode = false;

			if (array_key_exists('windir', $_SERVER))
				QApplication::$Windows = true;
			else if (array_key_exists('WINDIR', $_SERVER))
				QApplication::$Windows = true;
			else
				QApplication::$Windows = false;
		}

		/**
		 * Called by QApplication::Initialize() to initialize the QApplication::$ServerAddress setting.
		 * @return void
		 */
		protected static function InitializeServerAddress() {
			if (array_key_exists('LOCAL_ADDR', $_SERVER))
				QApplication::$ServerAddress = $_SERVER['LOCAL_ADDR'];
			else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
				QApplication::$ServerAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if (array_key_exists('SERVER_ADDR', $_SERVER))
				QApplication::$ServerAddress = $_SERVER['SERVER_ADDR'];
		}
		
		/**
		 * Called by QApplication::Initialize() to initialize the various
		 * QApplication settings on ScriptName, DocumentRoot, etc.
		 * @return void
		 */
		protected static function InitializeScriptInfo() {
			// Setup ScriptFilename and ScriptName
			QApplication::$ScriptFilename = $_SERVER['SCRIPT_FILENAME'];
			QApplication::$ScriptName = $_SERVER['SCRIPT_NAME'];
			
			// Ensure both are set, or we'll have to abort
			if (!QApplication::$ScriptFilename) {
				throw new Exception('Error on QApplication::Initialize() - ScriptFilename or ScriptName was not set');
			}

			// Setup PathInfo and QueryString (if applicable)
			QApplication::$PathInfo = array_key_exists('PATH_INFO', $_SERVER) ? trim($_SERVER['PATH_INFO']) : null;
			QApplication::$QueryString = array_key_exists('QUERY_STRING', $_SERVER) ? $_SERVER['QUERY_STRING'] : null;
			
			// Setup DocumentRoot
			QApplication::$DocumentRoot = trim(__DOCROOT__);
		}
		
		/**
		 * Called by QApplication::Initialize() to initialize the QApplication::$RequestUri setting.
		 * @return void
		 */
		protected static function InitializeRequestUri() {
			if (defined('__URL_REWRITE__')) {
				switch (strtolower(__URL_REWRITE__)) {
					case 'apache':
						QApplication::$RequestUri = $_SERVER['REQUEST_URI'];
						break;

					case 'none':
						QApplication::$RequestUri = sprintf('%s%s%s',
							QApplication::$ScriptName, QApplication::$PathInfo,
							(QApplication::$QueryString) ? sprintf('?%s', QApplication::$QueryString) : null);
						break;

					default:
						throw new Exception('Invalid URL Rewrite type: ' . __URL_REWRITE__);
				}
			} else {
				QApplication::$RequestUri = sprintf('%s%s%s',
					QApplication::$ScriptName, QApplication::$PathInfo,
					(QApplication::$QueryString) ? sprintf('?%s', QApplication::$QueryString) : null);
			}
		}
		
		/**
		 * Called by QApplication::Initialize() to initialize the QApplication::$BrowserType setting.
		 * @return void
		 */
		protected static function InitializeBrowserType() {
			if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
				$strUserAgent = trim(strtolower($_SERVER['HTTP_USER_AGENT']));

				// INTERNET EXPLORER (supporting versions 6.0, 7.0 and 8.0)
				if (strpos($strUserAgent, 'msie') !== false) {
					QApplication::$BrowserType = QBrowserType::InternetExplorer;

					if (strpos($strUserAgent, 'msie 6.0') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::InternetExplorer_6_0;
					else if (strpos($strUserAgent, 'msie 7.0') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::InternetExplorer_7_0;
					else if (strpos($strUserAgent, 'msie 8.0') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::InternetExplorer_8_0;
					else
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Unsupported;

				// FIREFOX (supporting versions 1.0, 1.5, 2.0, 3.0 and 3.5)
				} else if ((strpos($strUserAgent, 'firefox') !== false) || (strpos($strUserAgent, 'iceweasel') !== false)) {
					QApplication::$BrowserType = QBrowserType::Firefox;
					$strUserAgent = str_replace('iceweasel/', 'firefox/', $strUserAgent);

					if (strpos($strUserAgent, 'firefox/1.0') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Firefox_1_0;
					else if (strpos($strUserAgent, 'firefox/1.5') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Firefox_1_5;
					else if (strpos($strUserAgent, 'firefox/2.0') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Firefox_2_0;
					else if (strpos($strUserAgent, 'firefox/3.0') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Firefox_3_0;
					else if (strpos($strUserAgent, 'firefox/3.5') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Firefox_3_5;
					else
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Unsupported;

				// CHROME (not yet supported)
				} else if (strpos($strUserAgent, 'chrome') !== false) {
					QApplication::$BrowserType = QBrowserType::Chrome;
					QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Unsupported;

					if (strpos($strUserAgent, 'chrome/2.') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Chrome_2_0;
					else if (strpos($strUserAgent, 'chrome/3.') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Chrome_3_0;
					else if (strpos($strUserAgent, 'chrome/4.') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Chrome_4_0;
					else
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Unsupported;

				// SAFARI (supporting version 2.0, 3.0 and 4.0)
				} else if (strpos($strUserAgent, 'safari') !== false) {
					QApplication::$BrowserType = QBrowserType::Safari;

					if (strpos($strUserAgent, 'safari/41') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Safari_2_0;
					else if (strpos($strUserAgent, 'version/3.') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Safari_3_0;
					else if (strpos($strUserAgent, 'version/4.') !== false)
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Safari_4_0;
					else
						QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Unsupported;

				// COMPLETELY UNSUPPORTED
				} else
					QApplication::$BrowserType = QBrowserType::Unsupported;

				// MACINTOSH?
				if (strpos($strUserAgent, 'macintosh') !== false)
					QApplication::$BrowserType = QApplication::$BrowserType | QBrowserType::Macintosh;
			}
		}

		/**
		 * This should be the first call to initialize all the static variables
		 * The application object also has static methods that are miscellaneous web
		 * development utilities, etc.
		 * 
		 * It also will make a call to InitializeDatabaseConnections()
		 *
		 * @return void
		 */
		public static function Initialize() {
			// Basic Initailization Routines
			QApplication::InitializeEnvironment();
			QApplication::InitializeScriptInfo();

			// Perform Initialization for CLI
			if (QApplication::$CliMode) {
				QApplication::InitializeForCli();

			// *OR* Perform Initializations for WebApp
			} else {
				QApplication::InitializeErrorHandling();
				QApplication::InitializeOutputBuffering();
				QApplication::InitializeServerAddress();
				QApplication::InitializeRequestUri();
				QApplication::InitializeBrowserType();
				QApplication::InitializeServerSignature();
				QApplication::InitializePhpSession();
			}

			// Next, Initialize PHP AutoLoad Functionality
			QApplication::InitializeAutoload();

			// Next, Initialize the Database Connections
			QApplication::InitializeDatabaseConnections();

			// Then Preload all required "Prepload" Class Files
			foreach (QApplication::$PreloadedClassFile as $strClassFile) require($strClassFile);

			// Finally, go through any other auto_includes that this application requires
			QApplication::InitializeAutoIncludes();
		}

		protected static function InitializeAutoload() {
			spl_autoload_register(array('QApplication', 'Autoload'));
		}

		protected static function InitializeAutoIncludes() {
			$objDirectory = opendir(__INCLUDES__ . '/auto_includes');
			$strFileArray = array();
			while ($strFile = readdir($objDirectory)) {
				if (strtolower(substr($strFile, strlen($strFile) - 8)) == '.inc.php')
					$strFileArray[] = __INCLUDES__ . '/auto_includes/' . $strFile;
			}

			asort($strFileArray);
			foreach ($strFileArray as $strFile) require($strFile);
		}

		protected static function InitializeServerSignature() {
			header(sprintf('X-Powered-By: PHP/%s; Qcodo/%s', PHP_VERSION, QCODO_VERSION));
		}

		protected static function InitializeOutputBuffering() {
			ob_start('__qcodo_ob_callback');
		}
		
		protected static function InitializePhpSession() {
			// Go ahead and start the PHP session if we have set EnableSession to true
			if (QApplication::$EnableSession) session_start();
		}

		protected static function InitializeForCli() {
			// We should only run through this logic if we are specifically running
			// a CLI script through the Qcodo CLI Runner Wrapper (e.g. "qcodo" or "qcodo.bat")
			if (!array_key_exists('QCODO_CLI_RUNNER', $_SERVER) || !$_SERVER['QCODO_CLI_RUNNER'])
				return;

			// Did we ask for a script to be run?
			if (!array_key_exists(1, $_SERVER['argv']) ||
				(substr($_SERVER['argv'][1], 0, 1) == '-')) {
				$strDefaultPath = __DEVTOOLS_CLI__;
				$strDefaultPath = str_replace('/html/../', '/', $strDefaultPath);
				$strDefaultPath = str_replace('/docroot/../', '/', $strDefaultPath);
				$strDefaultPath = str_replace('/wwwroot/../', '/', $strDefaultPath);
				$strDefaultPath = str_replace('/www/../', '/', $strDefaultPath);

				print "Qcodo CLI Runner v" . QCODO_VERSION . "\r\n";
				print "usage: qcodo SCRIPT [SCRIPT-SPECIFIC ARGS]\r\n";
				print "\r\n";
				print "required parameters:\r\n";
				print "  SCRIPT         the .cli.php script name inside the cli/scripts directory\r\n";
				print "                 that you wish to run\r\n";
				print "\r\n";
				print "the following SCRIPTs are included with the Qcodo distribution:\r\n";
				print "  codegen        Code generates your ORM-layer\r\n";
				print "  qcodo-updater  Updates your installed Qcodo framework to a new version\r\n";
				print "  qpm-download   Download and installs an external QPM package\r\n";
				print "  qpm-upload     Packages custom code you wrote into a QPM package\r\n";
				print "\r\n";
				print "Other custom scripts can be created as well.\r\n";
				print "See \"" . $strDefaultPath . "/scripts/_README.txt\" for more info";
				print "\r\n";
				exit(1);
			}

			// Find Script
			if (strpos($_SERVER['argv'][1], '.cli.php') === false)
				$strScriptFilename = $_SERVER['argv'][1] . '.cli.php';
			else
				$strScriptFilename = $_SERVER['argv'][1];

			if (file_exists($strPath = __DEVTOOLS_CLI__ . '/scripts/' . $strScriptFilename)) {
				QApplication::$ScriptFilename = $strPath;
				QApplication::$ScriptName = $strScriptFilename;
			} else if (file_exists($strPath = __DEVTOOLS_CLI__ . '/scripts/_core/' . $strScriptFilename)) {
				QApplication::$ScriptFilename = $strPath;
				QApplication::$ScriptName = $strScriptFilename;
			} else {
				print "error: the script '" . $_SERVER['argv'][1] . "' does not exist.\r\n";
				exit(1);
			}
		}

		public static function IsBrowser($intBrowserType) {
			return ($intBrowserType & QApplication::$BrowserType);
		}

		/**
		 * This call will initialize the database connection(s) as defined by
		 * the constants DB_CONNECTION_X, where "X" is the index number of a
		 * particular database connection.
		 *
		 * @return void
		 */
		public static function InitializeDatabaseConnections() {
			for ($intIndex = 0; $intIndex <= 9; $intIndex++) {
				$strConstantName = sprintf('DB_CONNECTION_%s', $intIndex);

				if (defined($strConstantName)) {
					// Expected Keys to be Set
					$strExpectedKeys = array(
						'adapter', 'server', 'port', 'database',
						'username', 'password', 'profiling'
					);

					// Lookup the Serialized Array from the DB_CONFIG constants and unserialize it
					$strSerialArray = constant($strConstantName);
					$objConfigArray = unserialize($strSerialArray);

					// Set All Expected Keys
					foreach ($strExpectedKeys as $strExpectedKey)
						if (!array_key_exists($strExpectedKey, $objConfigArray))
							$objConfigArray[$strExpectedKey] = null;

					if (!$objConfigArray['adapter'])
						throw new Exception('No Adapter Defined for ' . $strConstantName . ': ' . var_export($objConfigArray, true));

					if (!$objConfigArray['server'])
						throw new Exception('No Server Defined for ' . $strConstantName . ': ' . constant($strConstantName));

					$strDatabaseType = 'Q' . $objConfigArray['adapter'] . 'Database';
					if (!class_exists($strDatabaseType)) {
						$strDatabaseAdapter = sprintf('%s/database/%s.class.php', __QCODO_CORE__, $strDatabaseType);
						if (!file_exists($strDatabaseAdapter))
							throw new Exception('Database Type is not valid: ' . $objConfigArray['adapter']);
						require($strDatabaseAdapter);
					}

					QApplication::$Database[$intIndex] = new $strDatabaseType($intIndex, $objConfigArray);
				}
			}
		}

		/**
		 * This is called by the PHP5 Autoloader.  This static method can be overridden.
		 *
		 * @return boolean whether or not a class was found / included
		 */


                public static function Autoload($strClassName) {
                        if (array_key_exists(strtolower($strClassName), QApplication::$ClassFile)) {
                                require(QApplication::$ClassFile[strtolower($strClassName)]);
                                return true;
                        } else if (file_exists($strFilePath = sprintf('%s/%s.class.php', __INCLUDES__, $strClassName))) {
                                require($strFilePath);
                                return true;
                        } else if (file_exists($strFilePath = sprintf('%s/qform/%s.class.php', __QCODO__, $strClassName))) {
                                require($strFilePath);
                                return true;
                        }

                        return false;
                }

		/**
		 * Temprorarily overrides the default error handling mechanism.  Remember to call
		 * RestoreErrorHandler to restore the error handler back to the default.
		 *
		 * @param string $strName the name of the new error handler function, or NULL if none
		 * @param integer $intLevel if a error handler function is defined, then the new error reporting level (if any)
		 */
		public static function SetErrorHandler($strName, $intLevel = null) {
			if (!is_null(QApplicationBase::$intStoredErrorLevel))
				throw new QCallerException('Error handler is already currently overridden.  Cannot override twice.  Call RestoreErrorHandler before calling SetErrorHandler again.');
			if (!$strName) {
				// No Error Handling is wanted -- simulate a "On Error, Resume" type of functionality
				set_error_handler('__qcodo_handle_error', 0);
				QApplicationBase::$intStoredErrorLevel = error_reporting(0);
			} else {
				set_error_handler($strName, $intLevel);
				QApplicationBase::$intStoredErrorLevel = -1;
			}
		}

		/**
		 * Restores the temporarily overridden default error handling mechanism back to the default.
		 */
		public static function RestoreErrorHandler() {
			if (is_null(QApplicationBase::$intStoredErrorLevel))
				throw new QCallerException('Error handler is not currently overridden.  Cannot reset something that was never overridden.');
			if (QApplicationBase::$intStoredErrorLevel != -1)
				error_reporting(QApplicationBase::$intStoredErrorLevel);
			restore_error_handler();
			QApplicationBase::$intStoredErrorLevel = null;
		}
		private static $intStoredErrorLevel = null;

		/**
		 * Same as mkdir but correctly implements directory recursion.
		 * At its core, it will use the php MKDIR function.
		 * 
		 * This method does no special error handling.  If you want to use special error handlers,
		 * be sure to set that up BEFORE calling MakeDirectory.
		 *
		 * @param string $strPath actual path of the directoy you want created
		 * @param integer $intMode optional mode
		 * @return boolean the return flag from mkdir
		 */
		public static function MakeDirectory($strPath, $intMode = null) {
			if (is_dir($strPath))
				// Directory Already Exists
				return true;

			// Check to make sure the parent(s) exist, or create if not
			if (!QApplicationBase::MakeDirectory(dirname($strPath), $intMode))
				return false;

			// Create the current node/directory, and return its result
			$blnReturn = mkdir($strPath);

			if ($blnReturn && !is_null($intMode)) {
				// Manually CHMOD to $intMode (if applicable)
				// mkdir doesn't do it for mac, and this will error on windows
				// Therefore, ignore any errors that creep up
				QApplication::SetErrorHandler(null);
				chmod($strPath, $intMode);
				QApplication::RestoreErrorHandler();
			}

			return $blnReturn;
		}


		/**
		 * This will redirect the user to a new web location.  This can be a relative or absolute web path, or it
		 * can be an entire URL.
		 *
		 * @return void
		 */
		public static function Redirect($strLocation) {
			// Clear the output buffer (if any)
			ob_clean();

			if ((QApplication::$RequestMode == QRequestMode::Ajax) ||
				(array_key_exists('Qform__FormCallType', $_POST) &&
				($_POST['Qform__FormCallType'] == QCallType::Ajax))) {
				// AJAX-based Response

				// Response is in XML Format
				header('Content-Type: text/xml');

				// Output it and update render state
				$strLocation = 'document.location="' . $strLocation . '"';
				$strLocation = QString::XmlEscape($strLocation);
				print('<?xml version="1.0"?><response><controls/><commands><command>' . $strLocation . '</command></commands></response>');

			} else {
				// Was "DOCUMENT_ROOT" set?
				if (array_key_exists('DOCUMENT_ROOT', $_SERVER) && ($_SERVER['DOCUMENT_ROOT'])) {
					// If so, we're likley using PHP as a Plugin/Module
					// Use 'header' to redirect
					header(sprintf('Location: %s', $strLocation));
				} else {
					// We're likely using this as a CGI
					// Use JavaScript to redirect
					printf('<script type="text/javascript">document.location = "%s";</script>', $strLocation);
				}
			}

			// End the Response Script
			exit();
		}


		/**
		 * This will close the window.  It will immediately end processing of the rest of the script.
		 *
		 * @return void
		 */
		public static function CloseWindow() {
			// Clear the output buffer (if any)
			ob_clean();

			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				// AJAX-based Response

				// Response is in XML Format
				header('Content-Type: text/xml');

				// OUtput it and update render state
				_p('<?xml version="1.0"?><response><controls/><commands><command>window.close();</command></commands></response>', false);

			} else {
				// Use JavaScript to close
				_p('<script type="text/javascript">window.close();</script>', false);
			}

			// End the Response Script
			exit();
		}

		/**
		 * Gets the value of the QueryString item $strItem.  Will return NULL if it doesn't exist.
		 *
		 * @return string
		 */
		public static function QueryString($strItem) {
			if (array_key_exists($strItem, $_GET))
				return $_GET[$strItem];
			else
				return null;
		}

		/**
		 * Generates a valid URL Query String based on values in the global $_GET
		 * @return string
		 */
		public static function GenerateQueryString() {
			if (count($_GET)) {
				$strToReturn = '';
				foreach ($_GET as $strKey => $strValue)
					$strToReturn .= '&' . urlencode($strKey) . '=' . urlencode($strValue);
				return '?' . substr($strToReturn, 1);
			} else
				return '';
		}

		/**
		 * By default, this is used by the codegen and form drafts to do a quick check
		 * on the ALLOW_REMOTE_ADMIN constant (as defined in configuration.inc.php).  If enabled,
		 * then anyone can access the page.  If disabled, only "localhost" can access the page.
		 * 
		 * If you want to run a script that should be accessible regardless of
		 * ALLOW_REMOTE_ADMIN, simply remove the CheckRemoteAdmin() method call from that script.
		 *
		 * @param string $strFile script filename doing the check
		 * @param integer $intLine line number of the check call
		 */
		public static function CheckRemoteAdmin() {
			// Allow Remote?
			if (ALLOW_REMOTE_ADMIN === true)
				return;

			// Are we localhost?
			if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
				return;

			// Are we the correct IP?
			if (is_string(ALLOW_REMOTE_ADMIN))
				foreach (explode(',', ALLOW_REMOTE_ADMIN) as $strIpAddress)
					if ($_SERVER['REMOTE_ADDR'] == trim($strIpAddress) ||
						(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && ($_SERVER['HTTP_X_FORWARDED_FOR'] == trim($strIpAddress))))
						return;

			// If we're here -- then we're not allowed to access.  Present the Error/Issue.
			header($_SERVER['SERVER_PROTOCOL'] . ' 401 Access Denied');
			header('Status: 401 Access Denied', true);

			throw new QRemoteAdminDeniedException();
		}

		/**
		 * Gets the value of the PathInfo item at index $intIndex.  Will return NULL if it doesn't exist.
		 *
		 * The way PathInfo index is determined is, for example, given a URL '/folder/page.php/id/15/blue',
		 * QApplication::PathInfo(0) will return 'id'
		 * QApplication::PathInfo(1) will return '15'
		 * QApplication::PathInfo(2) will return 'blue'
		 *
		 * @return void
		 */
		public static function PathInfo($intIndex) {
			// TODO: Cache PathInfo
			$strPathInfo = QApplication::$PathInfo;
			
			// Remove Trailing '/'
			if (QString::FirstCharacter($strPathInfo) == '/')			
				$strPathInfo = substr($strPathInfo, 1);
			
			$strPathInfoArray = explode('/', $strPathInfo);

			if (array_key_exists($intIndex, $strPathInfoArray))
				return $strPathInfoArray[$intIndex];
			else
				return null;
		}
		
		public static $AlertMessageArray = array();
		public static $JavaScriptArray = array();
		public static $JavaScriptArrayHighPriority = array();

		public static $ErrorFlag = false;

		public static function DisplayAlert($strMessage) {
			array_push(QApplication::$AlertMessageArray, $strMessage);
		}

		public static function UpdatePageTitle($strPageTitle) {
			$strPageTitle = QApplication::HtmlEntities($strPageTitle);
			$strJavaScript = sprintf('document.title = "%s";', $strPageTitle);
			QApplication::ExecuteJavaScript($strJavaScript);
		}

		public static function ExecuteJavaScript($strJavaScript, $blnHighPriority = false) {
			if ($blnHighPriority)
				array_push(QApplication::$JavaScriptArrayHighPriority, $strJavaScript);
			else
				array_push(QApplication::$JavaScriptArray, $strJavaScript);
		}

		public static function OutputPage($strBuffer) {
			// If the ProcessOutput flag is set to false, simply return the buffer
			// without processing anything.
			if (!QApplication::$ProcessOutput)
				return $strBuffer;

			if (QApplication::$ErrorFlag) {
				return $strBuffer;
			} else {
				if (QApplication::$RequestMode == QRequestMode::Ajax) {
					return trim($strBuffer);
				} else {
					// Update Cache-Control setting
					header('Cache-Control: ' . QApplication::$CacheControl);

					$strScript = QApplicationBase::RenderJavaScript(false);

					if ($strScript)
						return sprintf('%s<script type="text/javascript">%s</script>', $strBuffer, $strScript);

					return $strBuffer;
				}
			}
		}

		public static function RenderJavaScript($blnOutput = true) {
			$strScript = '';
			foreach (QApplication::$AlertMessageArray as $strAlert) {
				$strAlert = addslashes($strAlert);
				$strScript .= sprintf('alert("%s"); ', $strAlert);
			}
			foreach (QApplication::$JavaScriptArrayHighPriority as $strJavaScript) {
				$strJavaScript = trim($strJavaScript);
				if (QString::LastCharacter($strJavaScript) != ';')
					$strScript .= sprintf('%s; ', $strJavaScript);
				else
					$strScript .= sprintf('%s ', $strJavaScript);
			}
			foreach (QApplication::$JavaScriptArray as $strJavaScript) {
				$strJavaScript = trim($strJavaScript);
				if (QString::LastCharacter($strJavaScript) != ';')
					$strScript .= sprintf('%s; ', $strJavaScript);
				else
					$strScript .= sprintf('%s ', $strJavaScript);
			}

			QApplication::$AlertMessageArray = array();
			QApplication::$JavaScriptArrayHighPriority = array();
			QApplication::$JavaScriptArray = array();

			if ($strScript) {
				if ($blnOutput)
					_p($strScript, false);
				else
					return $strScript;
			} else
				return null;
		}

  		/**
		 * If LanguageCode is specified and QI18n::Initialize() has been called, then this
		 * will perform a translation of the given token for the specified Language Code and optional
		 * Country code.
		 *
		 * Otherwise, this will simply return the token as is.
		 * This method is also used by the global print-translated "_t" function.
		 *
		 * @param string $strToken
		 * @return string the Translated token (if applicable)
		 */
		public static function Translate($strToken) {
			if (QApplication::$LanguageObject)
				return QApplication::$LanguageObject->TranslateToken($strToken);
			else
				return $strToken;
		}

		/**
		 * Global/Central HtmlEntities command to perform the PHP equivalent of htmlentities.
		 * Feel free to override to specify encoding/quoting specific preferences (e.g. ENT_QUOTES/ENT_NOQUOTES, etc.)
		 * 
		 * This method is also used by the global print "_p" function.
		 *
		 * @param string $strText text string to perform html escaping
		 * @return string the html escaped string
		 */
		public static function HtmlEntities($strText) {
			return htmlentities($strText, ENT_COMPAT, QApplication::$EncodingType);
		}

		/**
		 * For development purposes, this static method outputs the QcodoInfo page
		 * @return void
		 */
		public static function QcodoInfo() {
			require(__QCODO_CORE__ . '/assets/qcodo_info.inc.php');
		}

  		/**
		 * For development purposes, this static method outputs all the Application static variables
		 *
		 * @return void
		 */
		public static function VarDump() {
			_p('<div style="background-color: #cccccc; padding: 5px;"><b>Qcodo Settings</b><ul>', false);
			if (ini_get('magic_quotes_gpc') || ini_get('magic_quotes_runtime'))
				printf('<li><font color="red"><b>WARNING:</b> magic_quotes_gpc and magic_quotes_runtime need to be disabled</font>');

			printf('<li>QCODO_VERSION = "%s"</li>', QCODO_VERSION);
			printf('<li>__SUBDIRECTORY__ = "%s"</li>', __SUBDIRECTORY__);
			printf('<li>__VIRTUAL_DIRECTORY__ = "%s"</li>', __VIRTUAL_DIRECTORY__);
			printf('<li>__INCLUDES__ = "%s"</li>', __INCLUDES__);
			printf('<li>__QCODO_CORE__ = "%s"</li>', __QCODO_CORE__);
			printf('<li>PHP Include Path = "%s"</li>', get_include_path());
			printf('<li>QApplication::$DocumentRoot = "%s"</li>', QApplication::$DocumentRoot);
			printf('<li>QApplication::$EncodingType = "%s"</li>', QApplication::$EncodingType);
			printf('<li>QApplication::$PathInfo = "%s"</li>', QApplication::$PathInfo);
			printf('<li>QApplication::$QueryString = "%s"</li>', QApplication::$QueryString);
			printf('<li>QApplication::$RequestUri = "%s"</li>', QApplication::$RequestUri);
			printf('<li>QApplication::$ScriptFilename = "%s"</li>', QApplication::$ScriptFilename);
			printf('<li>QApplication::$ScriptName = "%s"</li>', QApplication::$ScriptName);
			printf('<li>QApplication::$ServerAddress = "%s"</li>', QApplication::$ServerAddress);

			if (QApplication::$Database) foreach (QApplication::$Database as $intKey => $objObject) {
				printf('<li>QApplication::$Database[%s] = %s</li>', 
					$intKey,
					var_export(unserialize(constant('DB_CONNECTION_' . $intKey)), true));
			}
			_p('</ul></div>', false);
		}
	}

	class QRequestMode {
		const Standard = 'Standard';
		const Ajax = 'Ajax';
	}

	class QBrowserType {
		const InternetExplorer = 1;
		const InternetExplorer_6_0 = 2;
		const InternetExplorer_7_0 = 4;
		const InternetExplorer_8_0 = 8;
		
		const Firefox = 16;
		const Firefox_1_0 = 32;
		const Firefox_1_5 = 64;
		const Firefox_2_0 = 128;
		const Firefox_3_0 = 256;
		const Firefox_3_5 = 512;
		
		const Safari = 1024;
		const Safari_2_0 = 2048;
		const Safari_3_0 = 4096;
		const Safari_4_0 = 8192;
		
		const Chrome = 16384;
		const Chrome_2_0 = 32768;
		const Chrome_3_0 = 65536;
		const Chrome_4_0 = 131072;

		const Macintosh = 262144;

		const Unsupported = 524288;
	}
?>
