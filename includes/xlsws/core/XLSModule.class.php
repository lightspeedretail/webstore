<?php

    class XLSModule extends QBaseClass {
        protected $objModule = null;
        protected $strModuleType = null;

        protected function LoadModule() {
            $objModule = Modules::LoadByFileType(get_class($this) . '.php', 
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
?>
