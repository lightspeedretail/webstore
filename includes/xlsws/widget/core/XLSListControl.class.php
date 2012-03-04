<?php

class XLSListControl extends QListBox {
    protected $strPlaceholder = '-- select one --';
    protected $strLabelForRequired = '%s is required';
    protected $strLabelForRequiredUnnamed = 'Required';

    public function __construct($objParentObject, $strControlId = null) {
        parent::__construct($objParentObject, $strControlId);

        $this->strLabelForRequired = 
            QApplication::Translate($this->strLabelForRequired);

        $this->strLabelForRequiredUnnamed = 
            QApplication::Translate($this->strLabelForRequiredUnnamed);
    }   

    public function __toString() {
        return $this->Value;
    }

    public function AddPlaceholder() {
        $this->AddItemAt(0, new QListItem(_sp($this->strPlaceholder), null, true));
    }

    public function ValidateValue() {
        return true;
    }

    public function ValidateRequired() {
        if (!$this->Required) return true;

        $strSelectedValue = $this->SelectedValue;

        if (empty($strSelectedValue) || is_null($strSelectedValue)) {
            if ($this->strName)
                $this->ValidationError = sprintf(
                    $this->strLabelForRequired, 
                    $this->strName
                );
            else
                $this->ValidationError = 
                    $this->strLabelForRequiredUnnamed;

            return false;
        }

        return true;
    }

    public function Validate() {
        if (!$this->ValidateValue())
            return false;
        
        if (!$this->ValidateRequired())
            return false;

        $this->ValidationError = false;
        return true;
    }

    public function __get($strName) {
        switch ($strName) {
            case 'Value': return $this->SelectedValue;
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

    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'Value': return ($this->SelectedValue = $mixValue);
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
}
