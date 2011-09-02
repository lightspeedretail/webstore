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

abstract class XLSSessionHandlerBase extends QBaseClass {
	// Garbage collection variables
	public static $CollectionDefaultProbability = 1;
	public static $CollectionDefaultDivisor = 100;
	public static $CollectionDefaultLifetime = 3600;
	public static $CollectionOverridePhp = false;

	// Event handlers array
	protected static $Events = array();
	protected $uxtLifetime;

	public static function RegisterEvent($strEventName, $mixEvent) {
	  self::$Events[$strEventName][] = $mixEvent;
	}

	public static function TriggerEvent($strEventName, $arrParameters) {
		if (!array_key_exists($strEventName, self::$Events))
			return;

		foreach (self::$Events[$strEventName] as $mixEvent)
			if (is_callable($mixEvent))
				call_user_func($mixEvent, $arrParameters);
	}

	// Return a string to use as the session name
	public static function GetSessionName() {
		return 'XLSWS_' . substr(md5(__INCLUDES__), 0, 7);
	}

	// Return the maximum time a session may exist, in seconds
	public static function GetSessionLifetime() {
		$intLifetime = ini_get('session.gc_maxlifetime');

		if (self::$CollectionOverridePhp || $intLifetime == 0)
			$intLifetime = self::$CollectionDefaultLifetime;

		return $intLifetime;
	}

	public static function GetGarbageCollection() {
		$intProbability = ini_get('session.gc_probability');
		$intDivisor = ini_get('session.gc_divisor');

		if (self::$CollectionOverridePhp || $intProbability == 0) {
			$intProbability = self::$CollectionDefaultProbability;
			$intDivisor = self::$CollectionDefaultDivisor;
		}

		if (!(rand(0, $intDivisor) < $intProbability)) return false;
		return true;
	}

	public function __construct() {
		// Ensure that session gets saved prior to PHP closing.
		register_shutdown_function('session_write_close');
	}
}
