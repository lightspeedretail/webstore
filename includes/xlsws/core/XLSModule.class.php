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

class XLSModule extends QBaseClass {
	protected $objModule = null;
	protected $strModuleType = null;

	protected function LoadModule() {
		$objModule = Modules::LoadByFileType(get_class($this),
			$this->strModuleType);

		if ($objModule) $this->objModule = $objModule;
		else $this->objModule = false;

		return $this->objModule;
	}

	protected function GetModule() {
		if (is_null($this->objModule))
			$this->LoadModule();

		return $this->objModule;
	}

	protected function GetConfigurationValues() {
		$module = $this->GetModule();

		if (!$module)
			return array();

		return $module->GetConfigValues();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Config':
				return $this->GetConfigurationValues();
			case 'Module':
				return $this->GetModule();
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
