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
    protected $strName;
    protected $strAdminName;
    protected $strModuleName;

	protected $strModuleType = null;

    protected $objModule = null;
    protected $objConfig = null;

    protected $arrAdminFields;
    protected $arrClientFields;

    protected $blnDebug = false;
    protected $strDebugOption = 'DEBUG_MODULE';

    public function __construct() {
        if (!$this->strModuleName)
            $this->strModuleName = get_class($this);

        if (_xls_get_conf($this->strDebugOption, 0) == 1)
            $this->blnDebug = true;
    }

	protected function LoadModule() {
        $objModule = Modules::LoadByFileType(
            $this->strModuleName
            $this->strModuleType
        );

        if (!$objModule) {
            $objModule = Modules::LoadByFileType(
                $this->strModuleName
                $this->strModuleType
            );
        }

		if ($objModule) $this->objModule = $objModule;
		else $this->objModule = false;

		return $this->objModule;
	}

	protected function GetModule() {
		if (is_null($this->objModule))
			$this->LoadModule();

		return $this->objModule;
	}

    protected function GetConfig() {
        if (!$this->objConfig) { 
            $objModule = $this->GetModule();
            $this->objConfig = $objModule->GetConfigValues();
        }

        return $this->objConfig;
	}

    // TODO remove
    public function GetConfigurationValues() {
        error_log(__FUNCTION__ . ' legacy');
        return $this->GetConfig();
    }

    protected function GetName() {
        $strName = $this->strModuleName;

        if (defined('XLSWS_ADMIN_MODULE')) {
            if ($this->strAdminName) 
                $strName = $this->strAdminName;
        }
        else {
            if ($this->strName)
                $strName = $this->strName;
            else if ($this->Config['label'])
                $strName = $this->Config['label'];
        }

        return _sp($strName);
    }

    protected function GetFields($ParentCtrl, $blnReset = false) {
        if (defined('XLSWS_ADMIN_MODULE'))
            return $this->GetAdminFields($blnReset);
        else
            return $this->GetClientFields($blnReset);
    }

    protected function GetAdminFields($ParentCtrl, $blnReset = false) {
        if ($blnReset)
            $this->arrAdminFields = array();

        return $this->arrAdminFields;
    }

    protected function GetClientFields($ParentCtrl, $blnReset = false) {
        if ($blnReset)
            $this->arrClientFields = array();

        return $this->arrClientFields;
    }

	public function __get($strName) {
		switch ($strName) {
			case 'Config':
                return $this->GetConfig();

			case 'Module':
                return $this->GetModule();

            case 'Name':
                return $this->GetName();

            case 'Fields':
                return $this->GetFields();

            case 'NewFields':
                return $this->GetFields(true);

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
