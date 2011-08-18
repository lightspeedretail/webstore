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

require(__DATAGEN_CLASSES__ . '/StateGen.class.php');

/**
 * The State class defined here contains any customized code for the State
 * class in the Object Relational Model.  It represents the "xlsws_state"
 * table in the database.
 */
class State extends StateGen {
	// Define the Object Manager
	public static $Manager;

	// String representation of the object
	public function __toString() {
		return sprintf('State Object %s',  $this->code);
	}

	// Initialize the Object Manager on the class
	public static function InitializeManager() {
		if (!State::$Manager)
			State::$Manager =
				XLSObjectManager::Singleton('XLSStateManager','code');
	}

	public static function Load($intRowid, $forceload = false) {
		if (!$forceload && State::$Manager) {
			$obj = State::$Manager->GetByUniqueProperty(
				'Rowid', $intRowid);

			if ($obj)
				return $obj;
		}

		return parent::Load($intRowid);
	}

	public static function LoadByCode($strCode, $forceload = false) {
		return State::QuerySingle(
			QQ::Equal(QQN::State()->Code, $strCode)
		);
	}

	/**
	 * Return the default sorting order clause
	 * @param boolean $sql :: Return SQL query part or QQ::Clause
	 * @return mix
	 */
	public static function GetDefaultOrdering($sql = false) {
		if ($sql)
			return 'order by `sort_order`,`state`';
		else
			return QQ::Clause(
				QQ::OrderBy(
					QQN::State()->SortOrder,
					QQN::State()->State
				)
			);
	}
}
