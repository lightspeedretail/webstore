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

class XLSSessionHandler extends XLSSessionHandlerBase {
	public static $SessionHandler = 'DB';

	public function __construct() {
		parent::__construct();

		session_set_save_handler(
			array(&$this,"Open"),
			array(&$this,"Close"),
			array(&$this,"Read"),
			array(&$this,"Write"),
			array(&$this,"Destroy"),
			array(&$this,"GarbageCollect")
		);
	}

	public function Open($strSavePath, $strName) {
		$this->TriggerEvent('Open', array($strSavePath, $strName));

		return true;
	}

	public function Close() {
		$this->TriggerEvent('Close', array());

		if (self::GetGarbageCollection())
			$this->GarbageCollect();

		return true;
	}

	public function Read($strName) {
		$session = Sessions::LoadByVchName($strName);

		if ($session) return $session->TxtData;
		else return '';
	}

	public function Write($strName, $unkData) {
		$session = Sessions::LoadByVchName($strName);

		if (!$session) {
			$session = new Sessions();
			$session->VchName = $strName;
		}
		$session->UxtExpires = time() + self::GetSessionLifetime();
		$session->TxtData = $unkData;
		$session->Save();

		return true;
	}

	function Destroy($strName) {
		$this->TriggerEvent('Destroy', array($strName));

		$db = Sessions::GetDatabase();
		$db->NonQuery("DELETE FROM xlsws_sessions" .
			" WHERE vchName = '" . $strName . "'");
	}

	function GarbageCollect($intMaxLifetime = 0) {
		if ($intMaxLifetime = 0)
			$intMaxLifetime = self::GetSessionLifetime();

		$intExpiry = time() - $intMaxLifetime;

		$this->TriggerEvent('GarbageCollect', array($intExpiry));

		$db = Sessions::GetDatabase();
		$db->NonQuery("DELETE FROM xlsws_sessions" .
			" WHERE uxtExpires < '" . $intExpiry . "'");
	}
}
?>
