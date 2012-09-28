<?php

class XLSCompositeControl extends QPanel {
    protected $arrRegisteredChildren = array();

    public function GetChildName($strName) {
        return $this->strControlId . $strName;
    }

    public function GetChildByName($strName) {
        return $this->GetChildControl($this->GetChildName($strName));
    }

    public function IsRegistered($strName) {
        return in_array($strName, $this->arrRegisteredChildren);
    }

    public function IsBuilt($strName) {
        if ($this->GetChildByName($strName)) return true;
        return false;
    }

    public function BuildChild($strName) {
        $objControl = $this->GetChildByName($strName);
        if ($objControl)
           return $objControl; 

        $strMethod = 'Build' . $strName . 'Control';

        if (method_exists($this, $strMethod))
            $objControl = $this->$strMethod();

        return $objControl;
    }

    public function BuildChildren() {
        foreach ($this->arrRegisteredChildren as $strName) {
            $this->BuildChild($strName, true);
        }
    }

    public function UpdateChild($strName) {
        $objControl = $this->GetChildByName($strName);
        if (!$objControl)
            return $objControl;

        $strMethod = 'Update' . $strName . 'Control';

        if ($objControl && method_exists($this, $strMethod))
            $this->$strMethod();

        return $objControl;
    }

    public function UpdateChildren() {
        foreach ($this->arrRegisteredChildren as $strName)
            $this->UpdateChild($strName);
    }

    public function Update() {
        $this->UpdateChildren();
        $this->UpdateControl();
    }

    protected function BuildControl() {
        $this->BuildChildren();
    }

    protected function UpdateControl() {}
    protected function BindControl() {}

    public function RenderAsFieldset($blnDisplayOutput = true) {
        $this->RenderHelper(func_get_args(), __FUNCTION__);

        $this->blnIsBlockElement = true;

        $strToReturn = '';
        $strClass = '';

        if ($this->blnRequired)
            $strClass .= ' required';

        if (!$this->blnEnabled)
            $strClass .= ' enabled';

        if ($this->strWarning)
            $strClass .= ' warning';

        if ($this->strValidationError)
            $strClass .= ' error';

        if ($strClass) $strToReturn = 
            sprintf('<fieldset class="%s">', $strClass);
        else $strToReturn = '<fieldset>';

        if ($this->strName) {
            $strRequired = '';
            if ($this->blnRequired)
                $strRequired = '<span class="red">*</span>';

            $strInstructions = '';
            if ($this->strInstructions)
                $strInstructions = sprintf('<br><span class="%s">%s</span>',
                    'instructions',
                    $this->strInstructions
                );

            $strToReturn .= 
                sprintf('<legend><label for="%s">%s%s</label>%s</legend>',
                $this->strControlId,
                $strRequired,
                $this->strName,
                $strInstructions
            );
        }

        try {
            $strToReturn .= $this->GetControlHtml();
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        if ($this->strValidationError)
            $strToReturn .= sprintf('<br><span class="%s">%s</span>',
                'error',
                $this->strValidationError
            );

        $strToReturn .= '</fieldset>';

        return $this->RenderOutput($strToReturn, $blnDisplayOutput);
    }

    public function ValidationReset($blnRecurse = false) {
        if ($blnRecurse)
            foreach ($this->RegisteredChildren as $strName) {
                $objControl = $this->GetChildByName($strName);
                $objControl->ValidationReset($blnRecurse);
            }
        parent::ValidationReset();
    }

    public function __construct($objParentControl, $strControlId) {
        try { 
            parent::__construct($objParentControl, $strControlId);

            $this->BuildControl();
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'RegisteredChildren': 
                return $this->arrRegisteredChildren = $mixValue;

            default:
                try { 
                    return parent::__set($strName, $mixValue);
                }
                catch (QCallerException $objExc) { 
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    public function __get($strName) {
        if (in_array($strName, $this->arrRegisteredChildren))
            return $this->GetChildByName($strName);

        switch ($strName) {
            case 'RegisteredChildren': 
                return $this->arrRegisteredChildren;

            default:
                try { 
                    return parent::__get($strName);
                }
                catch (QCallerException $objExc) { 
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

/* vim: set ft=php ts=4 sw=4 tw=80 et: */
