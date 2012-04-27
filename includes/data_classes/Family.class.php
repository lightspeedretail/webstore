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

require(__DATAGEN_CLASSES__ . '/FamilyGen.class.php');

/**
 * The Family class defined here contains any customized code for the
 * Family class in the Object Relational Model. It represents the
 * "xlsws_family" table in the database.
 */
class Family extends FamilyGen {
	// Define the Category Object Manager
	public static $Manager;

	// String representation of the object
	public function __toString() {
		return sprintf('Family Object %s',  $this->family);
	}

	// Initialize the Object Manager on the class
	public static function InitializeManager() {
		if (!Family::$Manager)
			Family::$Manager =
				XLSObjectManager::Singleton('XLSFamilyManager','code');
	}

	public static function LoadByRequestUrl($strName) {
		return Family::QuerySingle(
			QQ::Equal(QQN::Family()->RequestUrl, $strName)
			);
	}

	public function GetLink() {
		return $this->strRequestUrl."/f/";
	}

	/*// Override LoadAll to pull from the Products table for performance
	public static function LoadAll($objOptionalClauses = null) {error_log(__class__.' '.__function__);
		try {
			$query = 'SELECT DISTINCT family FROM xlsws_product';
			$query .= ' WHERE web=1 AND fk_product_master_id=0';
			$query .= " AND family <> ''";
			$query .= ' ORDER BY family asc';

			$db = QApplication::$Database[1];
			$matches = $db->Query($query);

			$families = Product::InstantiateDbResult($matches);
			return $families;
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}
	}
	*/
	
	public static function ConvertSEO() {
	
		$arrFamilies = Family::QueryArray();
		foreach ($arrFamilies as $objFamily) {
			$strName = str_replace("&","and",$objFamily->Family);
			$objFamily->RequestUrl = _xls_seo_url($strName); 
			$objFamily->Save();
		}
	
	}
	
	public function __get($strName) {
		switch ($strName) {
			case 'Link': 
				return $this->GetLink();
			case 'RequestUrl': 
				return $this->strRequestUrl;

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
	
}
