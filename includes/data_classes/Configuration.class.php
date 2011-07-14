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
    require(__DATAGEN_CLASSES__ . '/ConfigurationGen.class.php');

    /**
     * The Configuration class defined here contains any
     * customized code for the Configuration table in the
     * Object Relational Model.  It represents the "xlsws_configuration" table 
     * in the database, and extends from the code generated abstract ConfigurationGen
     * class, which contains all the basic CRUD-type functionality as well as
     * basic methods to handle relationships and index-based loading.
     * 
     * @package My Application
     * @subpackage DataObjects
     * 
     */

    class Configuration extends ConfigurationGen {
        // Define the Object Manager for semi-persistent storage
        public static $Manager;

        // String representation of this Object
        public function __toString() {
            return sprintf('Configuration Object %s',  $this->strName);
        }

        // Initialize the Object Manager on the class
        public static function InitializeManager() {
            if (!Configuration::$Manager)
                Configuration::$Manager =
                    XLSConfigurationManager::Singleton('XLSConfigurationManager');
        }

        public function __get($strName) {
            switch ($strName) {
                case 'ConfigType':
                    return $this->ConfigurationTypeId;

                case 'Value':
                    return trim($this->strValue);

                default:
                    try {
                        return parent::__get($strName);
                    } catch (QCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            }
        }

        public function __set($strName, $mixValue) {
            switch ($strName) {
                case 'ConfigType':
                    try {
                        return ($this->ConfigurationTypeId = 
                            QType::Cast($mixValue, QType::Integer));
                    } catch (QInvalidCastException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }

                default:
                    try {
                        return (parent::__set($strName, $mixValue));
                    } catch (QCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            }
        }
    }
?>
