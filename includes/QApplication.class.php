<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

    /**
     * The Application class is an abstract class that statically provides
     * information and global utilities for the entire web application.
     */
    abstract class QApplication extends QApplicationBase {
        // Ordered includes path array
        public static $ClassPath;

        // Timezone
        public static $Timezone;

        // Session
        public static $SessionName;
        public static $SessionObject;
        public static $SessionEvents = array();

        /*
         * Redefine the function InitializeAutoload configured as the 
         * PHP autoloader. This provides us with a configurable listing of 
         * paths to use when loading classes.
         *
         * @param string $strClassName :: The __CLASS__ name to load
         * @return boolean :: Have we loaded the strClassName or not
         */
        public static function Autoload($strClassName) {
            $strClassKey = strtolower($strClassName);

            if (array_key_exists($strClassKey, QApplication::$ClassFile)) {
                require(QApplication::$ClassFile[$strClassKey]);
                return true;
            }
            else {
                foreach (QApplication::$ClassPath as $strClassPath) {
                    if (file_exists($strFilePath = sprintf(
                        '%s/%s.class.php', $strClassPath, $strClassName))) {
                            require($strFilePath);
                            return true;
                   }
                }
            }

            return false;
        }

        /**
         * Get Database convenience function
         *
         * @return object :: The database instance
         */
        public static function GetDatabase() {
            return QApplication::$Database[1];
        }

        /**
         * Overwrite ServerSignature from Qcodo for LightSpeed Web Store
         *
         * @return void
         */
        protected static function InitializeServerSignature() {
            header(sprintf(
                'X-Powered-By: LightSpeed Web Store /%s; PHP/%s',
                XLSWS_VERSION, PHP_VERSION));
        }

        /**
         * Initialize I18N internationalization / translation code
         *
         * @return void
         */
        public static function InitializeI18N() {
            if (isset($_SESSION)) {
                if (array_key_exists('country_code', $_SESSION))
                    QApplication::$CountryCode = $_SESSION['country_code'];
                if (array_key_exists('language_code', $_SESSION))
                    QApplication::$LanguageCode = $_SESSION['language_code'];
            }

            if (empty(QApplication::$CountryCode))
                QApplication::$CountryCode = 
                    Configuration::$Manager->GetValue(
                        'DEFAULT_COUNTRY', 'us');

            if (empty(QApplication::$LanguageCode))
                QApplication::$LanguageCode = 
                    Configuration::$Manager->GetValue(
                        'LANGUAGE_DEFAULT', 'en');

            QI18N::Initialize();
        }

        /**
         * Initialize Locale settings
         *
         * @param const :: LC_ locale constant to set
         * @return void
         */
        public static function InitializeLocale($category = LC_MONETARY) {
            $arrCodesets = array('', '.UTF8', '.utf8', '.UTF-8', '.utf-8');
            $strLocale = _xls_get_conf('LOCALE', 'en_US');

            foreach ($arrCodesets as $strCodeset)
                if (setlocale($category, $strLocale . $strCodeset))
                    return;

            QApplication::Log(E_ERROR, 'Invalid locale ' . $strLocale);
        }

        /**
         * Initialize TimeZone settings
         *
         * @return void
         */
        public static function InitializeTimezone() {
            if ((function_exists('date_default_timezone_set')) &&
                ($zone = 
                Configuration::$Manager->GetValue('TIMEZONE', false))) {
                    try {
                        date_default_timezone_set($zone);
                    }
                    catch (Exception $e) {
                        QApplication::Log(E_WARNING, 'init', 
                            'Could not set timezone to ' . $zone);
                    }
            }
        }

        /**
         * Initialize XLSObjectManagers for semi-persistent object storage
         *
         * @return void
         */
        public static function InitializeContentManagers() {
            Configuration::InitializeManager();
            Category::InitializeManager();
            Product::InitializeManager();
            CartItem::InitializeManager();
            Customer::InitializeManager();
            #Country::InitializeManager();
            #State::InitializeManager();
        }

        /**
         * Initialize PHP Session
         *
         * @return void
         */
        public static function InitializeSession() {
            if (!QApplication::$EnableSession)
                return;

            if (empty(QApplication::$SessionObject))
                QApplication::$SessionObject = new XLSSessionHandler();

            if (empty(QApplication::$SessionName))
                QApplication::$SessionName = $SessionName = 
                    XLSSessionHandler::GetSessionName();

            session_name($SessionName);
            session_start();
        }

        /**
         * Custom logging function
         *
         * @return void
         */
        public static function Log($errno, $errtype, $errstr, $errfunc = null) {
            global $visitor;
            $error = $errtype;
            $iserror = false;

            switch ($errno) {
                case E_NOTICE:
                case E_USER_NOTICE:
                    $error .= '.notice';
                    break;
                case E_WARNING:
                case E_USER_WARNING:
                    $error .= '.warning';
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                    $error .= '.error';
                    $iserror = true;
                    break;
                default:
                    $error .= '.unknown';
                    break;
            }

            // We don't want to be verbose
            if (!ini_get('log_errors') && !$iserror)
                return;

            if (!is_string($errstr))
                $errstr = print_r($errstr, true);

            if (($errno & error_reporting()) == $errno) {
                // Custom depecration warning
                if ($errtype == 'legacy') {
                    error_log($error .
                        ' : Use of legacy function ' . $errstr);
                    return;
                }

                $error .= ' : ' . $errstr;

                // Only severe errors are logged to php error log.
                if ($iserror)
                    error_log($error);

                // Create user viewable error messages
                $log = new Log();
                if ($visitor)
                    $log->VisitorId = $visitor->RowId;
                $log->Created = new QDateTime(QDateTime::Now);
                $log->Log = $error;
                $log->Save(true);
            }
        }

    }

    /**
     * Disable Session Auto Loading 
     */
    QApplication::$EnableSession = false;

    /**
     * Populate the initial ClassPath folders
     * - These Paths are just those that we overwrote in redefining the method
     */
    QApplication::$ClassPath[] = __INCLUDES__;
    QApplication::$ClassPath[] = __QCODO__ . '/qform';

?>
