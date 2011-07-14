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

	require(__DATAGEN_CLASSES__ . '/CountryGen.class.php');

	/**
     * The Country class defined here contains any customized code for the 
     * Country class in the Object Relational Model.  It represents the 
     * "xlsws_country" table in the database.
	 */
    class Country extends CountryGen {
        // Define the Object Manager
        public static $Manager;

        // String representation of the object
		public function __toString() {
			return sprintf('Country Object %s',  $this->strCode);
		}

        // Initialize the Object Manager on the class
        public static function InitializeManager() {
            if (!Country::$Manager)
                Country::$Manager = 
                    XLSObjectManager::Singleton('XLSCountryManager','code');
        }

        public static function Load($intRowid) {
            if (Country::$Manager) {
                $obj = Country::$Manager->GetByUniqueProperty(
                    'Rowid', $intRowid);

                if ($obj)
                    return $obj;
            }   

            return parent::Load($intRowid);
        }   

        public static function LoadByCode($strCode) {
            if (Country::$Manager) {
                $obj = Country::$Manager->GetByUniqueProperty(
                    'Code', $strValue);

                if ($obj)
                    return $obj;
            }   

            return parent::LoadByCode($strCode);
        }   

        /**
         * Return the default sorting order clause
         * @param boolean $sql :: Return SQL query part or QQ::Clause
         * @return mix
         */
        public static function GetDefaultOrdering($sql = false) {
            if ($sql)
                return 'order by `sort_order`,`country`';
            else
                return QQ::Clause(
                    QQ::OrderBy(
                        QQN::Country()->SortOrder,
                        QQN::Country()->Country
                    )
                );
        }
	}
?>
